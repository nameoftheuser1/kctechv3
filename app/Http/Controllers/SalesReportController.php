<?php

namespace App\Http\Controllers;

use App\Models\SalesReport;
use App\Http\Requests\StoreSalesReportRequest;
use App\Http\Requests\UpdateSalesReportRequest;
use App\Models\Reservation;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $salesReports = SalesReport::with('reservation')
            ->when($request->search, function ($query) use ($request) {
                $query->whereHas('reservation', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('sales-reports.index', compact('salesReports'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $reservations = Reservation::all(); // Assuming you have a Reservation model
        return view('sales-reports.create', compact('reservations'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'amount' => 'required|numeric|min:0',
        ]);

        SalesReport::create([
            'reservation_id' => $request->reservation_id,
            'amount' => $request->amount,
        ]);

        return redirect()->route('sales-reports.index')->with('success', 'Sales report created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesReport $salesReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesReport $salesReport)
    {
        // Fetch all reservations to populate the select dropdown
        $reservations = Reservation::all();

        // Pass the sales report and reservations to the view
        return view('sales-reports.edit', compact('salesReport', 'reservations'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesReport $salesReport)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'amount' => 'required|numeric|min:0',
        ]);

        // Update the sales report in the database
        $salesReport->update($validated);

        // Flash success message and redirect
        return redirect()->route('sales-reports.index')->with('success', 'Sales report updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesReport $salesReport)
    {
        // Delete the sales report
        $salesReport->delete();

        // Redirect back to the sales reports index with a success message
        return redirect()->route('sales-reports.index')->with('success', 'Sales report deleted successfully');
    }
}
