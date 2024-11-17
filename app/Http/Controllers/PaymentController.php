<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Reservation;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

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
            'gcash_number' => 'required|numeric|min:0',
            'amount' => 'required|numeric|min:0',
        ]);

        $reservation = Reservation::findOrFail($request->reservation);

        // Validate the GCash number and amount
        if ($reservation->contact != $request->gcash_number || $reservation->down_payment != $request->amount) {
            return back()->with('error', 'The GCash number or down payment amount does not match this booking. Please ensure that the GCash number and amount match the contact number and down payment in your reservation.');
        }

        // Change the reservation status to 'reserved'
        $reservation->status = 'reserved';
        $reservation->save();

        // Create the payment
        Payment::create([
            'reservation_id' => $reservation->id,
            'gcash_number' => $fields['gcash_number'],
            'amount' => $fields['amount'],
        ]);

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
