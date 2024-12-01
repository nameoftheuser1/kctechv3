<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Mail\PaymentReceived;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Search functionality
        $search = $request->get('search');
        $payments = Payment::query()
            ->when($search, function ($query) use ($search) {
                $query->where('reference_number', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'reservation' => 'required|exists:reservations,id',
            'reference_number' => 'required|numeric|min:0',
            'amount' => 'required|numeric|min:0',
        ]);

        $reservation = Reservation::findOrFail($request->reservation);

        // Check if a payment already exists for this reservation
        if (Payment::where('reservation_id', $reservation->id)->exists()) {
            return back()->with('error', 'A payment has already been recorded for this reservation.');
        }

        // Validate the reference number and amount
        if ($reservation->down_payment != $request->amount) {
            return back()->with('error', 'The down payment amount does not match this booking. Please ensure that the reference number and amount match the contact number and down payment in your reservation.');
        }

        // Change the reservation status to 'reserved'
        $reservation->status = 'pending';
        $reservation->save();

        // Create the payment
        $payment = Payment::create([
            'reservation_id' => $reservation->id,
            'reference_number' => $fields['reference_number'],
            'amount' => $fields['amount'],
        ]);

        // Get the admin's email
        $adminEmail = User::where('role', 'admin')->value('email');

        // Send an email to the admin
        Mail::to($adminEmail)->send(new PaymentReceived($reservation, $payment));

        return redirect()->route('home.thankyou')->with('success', 'Thank you! Your payment has been received, and your booking is now reserved.');
    }


    public function thankYou()
    {
        return view('home.thankyou');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
