<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Total reservations for the current month (show 0 if null)
        $reservations = Reservation::whereMonth('check_in', $currentMonth)
            ->whereYear('check_in', $currentYear)
            ->count() ?? 0;

        // Total expenses for the current month (show 0 if null)
        $totalExpenses = Expense::whereMonth('date_time', $currentMonth)
            ->whereYear('date_time', $currentYear)
            ->sum('amount') ?? 0;

        // Total revenue for the current month (show 0 if null)
        $totalRevenue = Reservation::whereMonth('check_in', $currentMonth)
            ->whereYear('check_in', $currentYear)
            ->sum('total_amount') ?? 0;

        // Calculate profit or loss for the current month
        $profitLoss = $totalRevenue - $totalExpenses;

        // Revenue forecasting for the next month based on simple average
        $forecastedReservations = $this->predictFutureReservations();
        $forecastedRevenue = $this->forecastRevenue($forecastedReservations);

        return view('dashboard.index', [
            'reservations' => $reservations,
            'totalRevenue' => $totalRevenue, // Add this to pass to the view
            'totalExpenses' =>  $totalExpenses,
            'profitLoss' => $profitLoss, // Add profit/loss
            'forecastedReservations' => $forecastedReservations,
            'forecastedRevenue' => $forecastedRevenue,
        ]);
    }



    /**
     * Predict future reservation counts based on the average of the last 3 months.
     */
    private function predictFutureReservations()
    {
        $previousMonths = [Carbon::now()->subMonth(1), Carbon::now()->subMonth(2), Carbon::now()->subMonth(3)];

        $totalReservations = 0;
        foreach ($previousMonths as $month) {
            $reservations = Reservation::whereMonth('check_in', $month->month)
                ->whereYear('check_in', $month->year)
                ->count() ?? 0;
            $totalReservations += $reservations;
        }

        // Simple average of the last 3 months (avoid division by zero)
        return $totalReservations > 0 ? $totalReservations / count($previousMonths) : 0;
    }

    /**
     * Forecast revenue based on predicted reservation counts and average revenue per reservation.
     */
    private function forecastRevenue($predictedReservations)
    {
        // Calculate the average revenue per reservation for the last 3 months
        $previousMonths = [Carbon::now()->subMonth(1), Carbon::now()->subMonth(2), Carbon::now()->subMonth(3)];

        $totalRevenue = 0;
        $totalReservations = 0;
        foreach ($previousMonths as $month) {
            $revenue = Reservation::whereMonth('check_in', $month->month)
                ->whereYear('check_in', $month->year)
                ->sum('total_amount') ?? 0;
            $reservations = Reservation::whereMonth('check_in', $month->month)
                ->whereYear('check_in', $month->year)
                ->count() ?? 0;

            $totalRevenue += $revenue;
            $totalReservations += $reservations;
        }

        // Avoid division by zero if there are no reservations in the past months
        if ($totalReservations == 0) {
            return 0;
        }

        $averageRevenuePerReservation = $totalRevenue / $totalReservations;

        // Forecasted revenue for the next month
        return $predictedReservations * $averageRevenuePerReservation;
    }
}
