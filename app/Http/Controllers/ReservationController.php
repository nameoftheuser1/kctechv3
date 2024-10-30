<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::query();

        // Search by 'contact', 'name', or 'address'
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('contact', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%')
                    ->orWhereHas('rooms', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Filter by month
        if ($request->has('month') && $request->month != '') {
            $month = $request->month;
            $query->whereMonth('check_in', $month);
        }

        // Filter by day
        if ($request->has('day') && $request->day != '') {
            $day = $request->day;
            $query->whereDay('check_in', $day);
        }

        // Filter by room id
        if ($request->has('room_id') && $request->room_id != '') {
            $roomId = $request->room_id;
            $query->whereHas('rooms', function ($q) use ($roomId) {
                $q->where('id', $roomId);
            });
        }

        // Fetch paginated reservations with associated rooms
        $reservations = $query->with('rooms')->paginate(10);

        return view('reservations.index', compact('reservations'));
    }


    public function checkDate()
    {
        $currentDateTime = now()->format('Y-m-d\TH:i');
        return view('reservations.check-date', compact('currentDateTime'));
    }


    public function create(Request $request)
    {
        $rooms = Room::all();

        if ($request->has(['check_in', 'check_out'])) {
            $checkIn = $request->input('check_in');
            $checkOut = $request->input('check_out');

            $reservedRoomIds = Reservation::where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out', [$checkIn, $checkOut])
                    ->orWhere(function ($query) use ($checkIn, $checkOut) {
                        $query->where('check_in', '<=', $checkIn)
                            ->where('check_out', '>=', $checkOut);
                    });
            })->with('rooms')->get()->pluck('rooms.*')->flatten()->pluck('id')->toArray();

            $rooms = $rooms->whereNotIn('id', $reservedRoomIds);
        }

        return view('reservations.create', compact('rooms'));
    }

    public function show(Reservation $reservation)
    {
        return view('reservations.show', compact('reservation'));
    }


    public function store(Request $request)
    {
        // Log the incoming request data
        Log::info('Creating a new reservation', [
            'request_data' => $request->all(),
        ]);

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'pax' => 'required|integer|min:1',
            'contact' => 'required|string|max:50',
            'car_unit_plate_number' => 'nullable|string|max:255',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'rooms' => 'required|array',
            'rooms.*' => 'exists:rooms,id',
        ]);

        try {
            $totalAmount = 0;
            $rooms = Room::whereIn('id', $request->rooms)->get();
            foreach ($rooms as $room) {
                $totalAmount += $room->price;
            }

            $reservation = Reservation::create([
                'name' => $request->name,
                'address' => $request->address,
                'pax' => $request->pax,
                'contact' => $request->contact,
                'car_unit_plate_number' => $request->car_unit_plate_number,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'total_amount' => $totalAmount,
            ]);

            $reservation->rooms()->attach($request->rooms);

            // Log the successful creation of the reservation
            Log::info('Reservation created successfully', [
                'reservation_id' => $reservation->id,
                'rooms' => $request->rooms,
                'total_amount' => $totalAmount,
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating reservation', [
                'error_message' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);

            return redirect()->route('reservations.index')->with('error', 'Failed to create reservation.');
        }

        // Redirect to the receipt page with the reservation ID
        return redirect()->route('receipt', ['id' => $reservation->id])->with('success', 'Reservation created successfully.');
    }

    public function update(Reservation $reservation, Request $request)
    {
        // Validate the request data
        $request->validate([
            'status' => 'required|string|in:check out',
        ]);

        // Update the reservation status
        $reservation->status = $request->input('status');
        $reservation->save();

        return redirect()->route('reservations.index')->with('success', 'Reservation status updated successfully.');
    }

    public function showReceipt($id)
    {
        $reservation = Reservation::with('rooms')->findOrFail($id);

        return view('reservations.receipt', compact('reservation'));
    }
}
