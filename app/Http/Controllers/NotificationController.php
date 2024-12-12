<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function unreadReservationCount()
    {
        $now = now();
        $threeHoursAgo = $now->clone()->subHours(3);

        $count = Reservation::where('status', 'pending')
            ->whereBetween('created_at', [$threeHoursAgo, $now])
            ->count();

        return response()->json(['new_reservations_count' => $count]);
    }
}
