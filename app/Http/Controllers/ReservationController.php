<?php

namespace App\Http\Controllers;

use App\Mail\ReservationUpdated;
use App\Models\Expense;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::query();

        // Exclude reservations with status 'cancel'
        $query->where('status', '!=', 'cancel');

        // Search by 'contact', 'name', or 'address'
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        // Filter by month
        if ($request->has('month') && $request->month != '') {
            $query->whereMonth('created_at', $request->month);
        }

        // Filter by day
        if ($request->has('day') && $request->day != '') {
            $query->whereDay('created_at', $request->day);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Clone the query to get the count before pagination
        $reservationCount = $query->count();

        // Order by latest created_at
        $query->orderBy('check_in', 'desc');

        // Fetch paginated reservations with associated rooms
        $reservations = $query->paginate(10);

        if ($request->ajax()) {
            return view('reservations.partials.table', compact('reservations', 'reservationCount'))->render();
        }

        return view('reservations.index', compact('reservations', 'reservationCount'));
    }

    public function updateReservation(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'pax' => 'required|integer|min:1',
            'contact' => 'required|string|max:255',
            'car_unit_plate_number' => 'nullable|string|max:255',
            'check_in' => 'required|date|before_or_equal:check_out',
            'check_out' => 'required|date|after_or_equal:check_in',
            'stay_type' => 'required|in:day tour,overnight',
            'rooms' => 'required|array|min:1',
            'rooms.*' => 'exists:rooms,id',
        ]);

        // Update the rooms relationship first
        $reservation->rooms()->sync($validatedData['rooms']);

        // Calculate the duration of the stay
        $checkIn = \Carbon\Carbon::parse($validatedData['check_in']);
        $checkOut = \Carbon\Carbon::parse($validatedData['check_out']);
        $duration = $checkIn->diffInDays($checkOut) + 1; // Adding 1 to include the check-in day

        // Fetch the updated rooms
        $rooms = Room::whereIn('id', $validatedData['rooms'])->get();

        // Calculate the total price based on stay type and room price (no duration)
        $totalPrice = $rooms->sum(function ($room) use ($validatedData) {
            return $room->price; // No duration multiplier needed
        });

        // Update the reservation with the calculated total amount
        $reservation->update(['total_amount' => $totalPrice]);

        // Send the email to the reservation email
        Mail::to($reservation->email)->send(new ReservationUpdated($reservation));

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Reservation updated successfully.');
    }


    public function create(Request $request)
    {
        $rooms = Room::all();

        return view('reservations.create', compact('rooms'));
    }

    public function edit(Reservation $reservation)
    {
        $availableRooms = Room::all();
        $selectedRoomIds = $reservation->rooms->pluck('id')->toArray();

        return view('reservations.edit', compact('reservation', 'availableRooms', 'selectedRoomIds'));
    }

    public function show(Reservation $reservation)
    {
        return view('reservations.show', compact('reservation'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'pax' => 'required|integer|min:1',
            'contact' => 'required|string',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'status' => 'required|string|in:check in,reserved',
            'rooms' => 'required|array',
            'rooms.*' => 'exists:rooms,id',
        ]);

        $rooms = Room::whereIn('id', $validated['rooms'])->get();
        $totalAmount = $rooms->sum('price');

        Reservation::create([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'pax' => $validated['pax'],
            'contact' => $validated['contact'],
            'car_unit_plate_number' => $request->input('car_unit_plate_number'),
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'status' => $validated['status'],
            'total_amount' => $totalAmount,
            'down_payment' => round($totalAmount * 0.3),
        ]);

        return redirect()->route('reservations.index')->with('success', 'Reservation created successfully.');
    }


    public function update(Reservation $reservation, Request $request)
    {
        // Validate the request data
        $request->validate([
            'status' => 'required|string|in:check out',
        ]);

        // Check if the status is 'check out'
        if ($request->input('status') === 'check out') {
            $checkoutTime = now();
            $reservation->checkout_time = $checkoutTime; // Update the actual check-out time
            // Update the total amount in the reservation
        }

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

    /**
     * Apply commission to the reservation.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function applyCommission($id)
    {
        // Find the reservation
        $reservation = Reservation::findOrFail($id);

        // Check if it's already commissioned
        if ($reservation->is_commissioned) {
            return redirect()->back()->with('message', 'Commission already applied.');
        }

        // Get the commission percent from settings
        $commissionPercent = Setting::where('key', 'commission_percent')->value('value') ?? 10;

        // Calculate commission amount
        $commissionAmount = $reservation->total_amount * ($commissionPercent / 100);

        // Update the commission amount and total amount
        $reservation->commission_amount = $commissionAmount;
        $reservation->total_amount -= $commissionAmount;
        $reservation->is_commissioned = true;
        $reservation->save();

        // Add the commission amount as an expense
        Expense::create([
            'expense_description' => "Commission for Reservation ID: $reservation->id",
            'amount' => $commissionAmount,
            'date_time' => now(),
        ]);

        return redirect()->back()->with('message', 'Commission applied successfully.');
    }

    public function updateRooms(Request $request)
    {
        $checkIn = $request->input('check_in');
        $checkOut = $request->input('check_out');
        $stayType = $request->input('stay_type');

        $today = now()->toDateString();

        $request->validate([
            'check_in' => ['required', 'date', 'after_or_equal:' . $today],
            'check_out' => ['required', 'date', 'after:check_in'],
            'stay_type' => ['nullable', 'in:day tour,overnight'], // Add validation for stay type
        ]);

        // Get reserved rooms between check-in and check-out dates
        $reservedRoomIds = Reservation::where(function ($query) use ($checkIn, $checkOut) {
            $query->whereBetween('check_in', [$checkIn, $checkOut])
                ->orWhereBetween('check_out', [$checkIn, $checkOut])
                ->orWhere(function ($query) use ($checkIn, $checkOut) {
                    $query->where('check_in', '<=', $checkIn)
                        ->where('check_out', '>=', $checkOut);
                });
        })->with('rooms')->get()->pluck('rooms.*')->flatten()->pluck('id')->toArray();

        // Get rooms that are not reserved, optionally filter by stay type
        $query = Room::whereNotIn('id', $reservedRoomIds);

        if ($stayType) {
            $query->where('stay_type', $stayType);
        }

        $rooms = $query->get();

        // Return available rooms as JSON
        return response()->json(['rooms' => $rooms]);
    }
}
