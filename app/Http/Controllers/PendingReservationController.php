<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class PendingReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::query();

        // Only fetch reservations with status 'pending'
        $query->where('status', 'pending');

        // Search by 'contact', 'name', or 'address'
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('contact', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%');
            });
        }

        // Filter by month
        if ($request->has('month') && $request->month != '') {
            $query->whereMonth('check_in', $request->month);
        }

        // Filter by day
        if ($request->has('day') && $request->day != '') {
            $query->whereDay('check_in', $request->day);
        }

        // Fetch paginated reservations with associated rooms
        $reservations = $query->paginate(10);

        if ($request->ajax()) {
            return view('reservations.partials.table', compact('reservations'))->render();
        }

        return view('pending-reservation.index', compact('reservations'));
    }
}
