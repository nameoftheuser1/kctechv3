<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserReservationController extends Controller
{
    public function create()
    {
        $checkIn = now();
        $checkOut = now()->addHours(12);

        return view("home.form", compact('checkIn', 'checkOut'));
    }


    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'pax' => 'required|integer|min:1',
            'contact' => 'required|string|max:50',
            'car_unit_plate_number' => 'nullable|string|max:255',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'rooms' => 'required|array',
            'rooms.*' => 'exists:rooms,id',
            'total_amount' => 'required|numeric|min:0',
            'down_payment' => 'required|numeric|min:0',
        ];

        $request->validate($rules);
        try {
            $expected_down_payment = (int)($request->total_amount * 0.20);

            if ($request->down_payment < $expected_down_payment) {
                return back()->with('error', 'Down payment is too low.');
            }

            $reservation = Reservation::create([
                'name' => $request->name,
                'address' => $request->address,
                'pax' => $request->pax,
                'contact' => $request->contact,
                'car_unit_plate_number' => $request->car_unit_plate_number,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'total_amount' => $request->total_amount,
                'status' => 'pending',
                'down_payment' => $request->down_payment
            ]);

            return redirect()->route('user-form.receipt', [
                'id' => $reservation->id,
                'down_payment' => $request->down_payment
            ])->with('success', 'Reservation created successfully.');
        } catch (Exception $e) {
            Log::error('Error creating reservation', [
                'error_message' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);

            return back()->with('error', 'Failed to create reservation.');
        }
    }

    public function checkAvailability(Request $request)
    {
        $checkIn = $request->input('check_in');
        $checkOut = $request->input('check_out');
        $stayType = $request->input('stay_type');

        // Query reservations that overlap with the requested dates and exclude rooms with 'pending' status
        $reservedRoomIds = Reservation::where(function ($query) use ($checkIn, $checkOut) {
            $query->whereBetween('check_in', [$checkIn, $checkOut])
                ->orWhereBetween('check_out', [$checkIn, $checkOut])
                ->orWhere(function ($query) use ($checkIn, $checkOut) {
                    $query->where('check_in', '<=', $checkIn)
                        ->where('check_out', '>=', $checkOut);
                });
        })
            ->with(['rooms' => function ($query) {
                $query->where('status', '!=', 'pending');
            }])
            ->get()
            ->pluck('rooms.*')
            ->flatten()
            ->pluck('id')
            ->toArray();

        // Fetch rooms that are not reserved and match the requested stay type
        $availableRooms = Room::whereNotIn('id', $reservedRoomIds)
            ->where('stay_type', $stayType)
            ->get(['id', 'room_number', 'room_type', 'pax', 'price']); // Select the necessary fields

        // Return the list of available rooms as JSON
        return response()->json(['rooms' => $availableRooms]);
    }

    public function receipt($id)
    {
        $reservation = Reservation::findOrFail($id);
        return view('home.receipt', compact('reservation'));
    }
}
