<?php

namespace App\Http\Controllers;

use App\Mail\ReservationConfirmed;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ReservationStatusController extends Controller
{
    /**
     * Update the reservation status to "check in".
     *
     * @param  int  $reservationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkIn($reservationId)
    {
        $reservation = Reservation::find($reservationId);

        if ($reservation) {
            $reservation->status = 'check in';
            $reservation->save();
        }

        return redirect()->route('reservations.index')->with('success', 'Reservation checked in.');
    }

    /**
     * Update the reservation status to "cancel".
     *
     * @param  int  $reservationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel($reservationId)
    {
        $reservation = Reservation::find($reservationId);

        if ($reservation) {
            // Detach all associated rooms
            $reservation->rooms()->detach();

            // Reset the total amount to 0
            $reservation->total_amount = 0;

            // Update the reservation status to 'cancel'
            $reservation->status = 'cancel';

            // Save the reservation
            $reservation->save();
        }

        return redirect()->route('reservations.index')->with('success', 'Reservation canceled and rooms removed.');
    }

    /**
     * Update the reservation status to "reserved".
     *
     * @param  int  $reservationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reserve($reservationId)
    {
        DB::transaction(function () use ($reservationId) {
            $reservation = Reservation::lockForUpdate()->find($reservationId);

            if (!$reservation) {
                throw new \Exception('Reservation not found.');
            }

            if ($reservation->status === 'reserved') {
                throw new \Exception('This reservation is already reserved.');
            }

            $reservation->status = 'reserved';
            $reservation->save();

            Mail::to($reservation->email)->send(new ReservationConfirmed($reservation));
        });

        return redirect()->route('reservations.index')->with('success', 'Reservation status set to reserved.');
    }
}
