<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Expense;
use App\Models\Reservation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Phpml\Regression\LeastSquares;

class DashboardController extends Controller
{

    public function index()
    {
        // Fetch the total_revenue_date and total_expenses_date from the settings table
        $totalRevenueYear = DB::table('settings')->where('key', 'total_revenue_year')->value('value');
        $totalExpensesYear = DB::table('settings')->where('key', 'total_expenses_year')->value('value');
        $totalCommissionsYear = DB::table('settings')->where('key', 'total_commissions_year')->value('value') ?? 2024;
        $totalSalariesYear = DB::table('settings')->where('key', 'total_salaries_year')->value('value');
        $predictSalesMonth = DB::table('settings')->where('key', 'predict_sales_month')->value('value');
        $predictReservationsMonth = DB::table('settings')->where('key', 'predict_reservations_month')->value('value');

        $currentYear = date('Y');

        // Total revenue for the specified year (show 0 if null)
        $totalRevenue = Reservation::whereYear('created_at', $totalRevenueYear)
            ->where('status', 'check_out')
            ->sum('total_amount') ?? 0;

        // Total expenses for the specified year (show 0 if null)
        $totalExpenses = Expense::whereYear('date_time', $totalExpensesYear)
            ->sum('amount') ?? 0;

        // Total salaries for the specified year (show 0 if null)
        $totalSalaries = Employee::whereYear('payout_date', $totalSalariesYear)
            ->sum('salary') ?? 0;

        $totalCommissions = Reservation::whereYear('check_in', $totalCommissionsYear)
            ->sum('commission_amount') ?? 0;

        // Predict sales for the next 6 months
        $historicalData = $this->getHistoricalSalesData(5); // Historical sales for 5 months
        $predictedSales = $this->predictSales($predictSalesMonth); // Predicted sales for next N months

        // Combine both arrays
        $combinedSales = array_merge($historicalData, $predictedSales);

        // Get reservation counts for the past 12 months
        $reservationCounts = $this->getReservationCountsForPastMonths(12);

        // Predict reservation counts for the next 6 months
        $predictedReservations = $this->predictReservations($predictReservationsMonth);

        // Calculate the loss vs income
        $overallLossVsIncome = $totalRevenue - ($totalExpenses + $totalSalaries);

        return view('dashboard.index', [
            'totalRevenueYear' => $totalRevenueYear,
            'totalExpensesYear' => $totalExpensesYear,
            'totalSalariesYear' => $totalSalariesYear,
            'currentYear' => $currentYear,
            'totalRevenue' => $totalRevenue,
            'totalExpenses' => $totalExpenses,
            'totalSalaries' => $totalSalaries,
            'totalCommissions' =>  $totalCommissions,
            'combinedSales' => $combinedSales,
            'overallLossVsIncome' => $overallLossVsIncome,
            'reservationCounts' => $reservationCounts,
            'predictedReservations' => $predictedReservations,
        ]);
    }

    /**
     * Predict sales for the next specified number of months using PHP-ML.
     *
     * @param int $monthsToPredict
     * @return array
     */
    private function predictSales($monthsToPredict)
    {
        // Fetch historical data for the past 12 months
        $historicalData = $this->getHistoricalSalesData(12);

        // Prepare data for PHP-ML
        $samples = [];
        $targets = [];
        foreach ($historicalData as $index => $data) {
            $samples[] = [$index];
            $targets[] = $data['revenue'];
        }

        // Train the model
        $regression = new LeastSquares();
        $regression->train($samples, $targets);

        // Make predictions for the next months
        $predictions = [];
        $lastIndex = count($historicalData);
        for ($i = 1; $i <= $monthsToPredict; $i++) {
            $predictedRevenue = $regression->predict([$lastIndex + $i]);
            $predictedMonth = Carbon::now()->addMonths($i);
            $predictions[] = [
                'month' => $predictedMonth->format('F Y'),
                'revenue' => max(0, round($predictedRevenue, 2)), // Ensure non-negative prediction
            ];
        }

        return $predictions;
    }

    /**
     * Predict reservation counts for the next specified number of months.
     *
     * @param int $monthsToPredict
     * @return array
     */
    private function predictReservations($monthsToPredict)
    {
        // Fetch historical reservation data for the past 12 months
        $historicalData = $this->getReservationCountsForPastMonths(12);

        // Prepare data for PHP-ML
        $samples = [];
        $targets = [];
        foreach ($historicalData as $index => $data) {
            $samples[] = [$index];
            $targets[] = $data['count'];
        }

        // Train the model
        $regression = new LeastSquares();
        $regression->train($samples, $targets);

        // Make predictions for the next months
        $predictions = [];
        $lastIndex = count($historicalData);
        for ($i = 1; $i <= $monthsToPredict; $i++) {
            $predictedCount = $regression->predict([$lastIndex + $i]);
            $predictedMonth = Carbon::now()->addMonths($i);
            $predictions[] = [
                'month' => $predictedMonth->format('F Y'),
                'count' => max(0, round($predictedCount)), // Ensure non-negative prediction
            ];
        }

        return $predictions;
    }

    /**
     * Get historical sales data for the specified number of past months.
     *
     * @param int $months
     * @return array
     */
    private function getHistoricalSalesData($months)
    {
        $data = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenue = Reservation::whereMonth('check_in', $date->month)
                ->whereYear('check_in', $date->year)
                ->sum('total_amount') ?? 0;

            $data[] = [
                'month' => $date->format('F Y'),
                'revenue' => $revenue,
            ];
        }
        return $data;
    }

    /**
     * Get reservation counts for the specified number of past months.
     *
     * @param int $months
     * @return array
     */
    private function getReservationCountsForPastMonths($months)
    {
        $counts = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Reservation::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();

            $counts[] = [
                'month' => $date->format('F Y'),
                'count' => $count,
            ];
        }
        return $counts;
    }
}
