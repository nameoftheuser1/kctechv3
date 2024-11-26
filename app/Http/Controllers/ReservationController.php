<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
                    ->orWhere('address', 'like', '%' . $search . '%');
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

        // Clone the query to get the count before pagination
        $reservationCount = $query->count();

        // Order by latest created_at
        $query->orderBy('created_at', 'desc');

        // Fetch paginated reservations with associated rooms
        $reservations = $query->paginate(10);

        if ($request->ajax()) {
            return view('reservations.partials.table', compact('reservations', 'reservationCount'))->render();
        }

        return view('reservations.index', compact('reservations', 'reservationCount'));
    }



    public function checkDate()
    {
        $currentDateTime = now()->format('Y-m-d\TH:i');
        return view('reservations.check-date', compact('currentDateTime'));
    }


    public function create(Request $request)
    {
        $rooms = Room::all();
        $today = now()->toDateString();

        $request->validate([
            'check_in' => ['required', 'date', 'after_or_equal:' . $today],
            'check_out' => ['required', 'date', 'after:check_in'],
        ]);

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

    public function edit(Reservation $reservation)
    {
        // Fetch all rooms
        $rooms = Room::all();

        // Get the check-in and check-out dates from the reservation
        $checkIn = $reservation->check_in->toDateString();
        $checkOut = $reservation->check_out->toDateString();

        // Check if there are any reservations that overlap with the current reservation's dates !! update to only the status that is not pending
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

        // Filter out reserved rooms
        $availableRooms = $rooms->whereNotIn('id', $reservedRoomIds);

        // Return the edit view with the reservation and available rooms data
        return view('reservations.edit', compact('reservation', 'availableRooms'));
    }


    public function show(Reservation $reservation)
    {
        return view('reservations.show', compact('reservation'));
    }


    public function store(Request $request)
    {
        // Validate the request
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
        ];

        // Add status validation for admin users
        if (Auth::user()->role && Auth::user()->role == 'admin') {
            $rules['status'] = 'required|in:reserved,check in';
        } else {
            $request->merge(['status' => 'reserved']);
        }

        // Add down_payment validation if status is "reserved"
        if ($request->status === 'reserved') {
            $rules['down_payment'] = 'nullable|numeric|min:0';
        }

        $request->validate($rules);

        try {
            $totalAmount = 0;
            $rooms = Room::whereIn('id', $request->rooms)->get();
            foreach ($rooms as $room) {
                $totalAmount += $room->price;
            }

            $status = Auth::user()->role && Auth::user()->role == 'admin' ? $request->status : 'reserved';

            $reservation = Reservation::create([
                'name' => $request->name,
                'address' => $request->address,
                'pax' => $request->pax,
                'contact' => $request->contact,
                'car_unit_plate_number' => $request->car_unit_plate_number,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'total_amount' => $totalAmount,
                'status' => $status,
                'down_payment' => $request->status === 'reserved' ? $request->down_payment : null,
            ]);

            $reservation->rooms()->attach($request->rooms);
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

        $additionalCharges = 0;

        // Check if the status is 'check out'
        if ($request->input('status') === 'check out') {
            $checkoutTime = now();
            $reservation->check_out = $checkoutTime; // Update the actual check-out time

            // Calculate late check-out fees
            $originalCheckout = $reservation->getOriginal('check_out'); // Get the scheduled check-out time
            if ($originalCheckout) {
                $lateHours = $checkoutTime->diffInHours($originalCheckout, false); // Calculate hours past check-out time

                if ($lateHours > 0) {
                    if ($reservation->pax >= 8 && $reservation->pax <= 15) {
                        $additionalCharges += $lateHours * 1000;
                    } elseif ($reservation->pax >= 2 && $reservation->pax <= 7) {
                        $additionalCharges += $lateHours * 500;
                    }
                }
            }

            // Calculate charges for open cottages or tables
            if (isset($reservation->cottage_type)) {
                if ($reservation->cottage_type === 'day tour') {
                    $additionalCharges += $reservation->pax * 200;
                } elseif ($reservation->cottage_type === 'overnight') {
                    $additionalCharges += $reservation->pax * 300;
                }
            }

            // Save the additional charges to the sales_reports table
            DB::table('sales_reports')->insert([
                'reservation_id' => $reservation->id,
                'amount' => $additionalCharges,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update the total amount in the reservation
            $reservation->total_amount += $additionalCharges;
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
