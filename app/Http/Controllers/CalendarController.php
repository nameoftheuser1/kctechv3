<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('rooms')->get();
        return view('calendar.index', compact('reservations'));
    }
}
