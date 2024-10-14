<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $employees = Employee::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('salary', 'like', "%{$search}%")
                    ->orWhere('payout_date', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('employees.index', ['employees' => $employees]);
    }

    public function create()
    {
        return view('employees.create');
    }



    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'salary' => ['required', 'numeric'],
            'payout_date' => ['required', 'date'],
        ]);

        Employee::create($fields);

        return redirect()->route('employees.index')->with('success', 'The employee has been added successfully.');
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $fields = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'salary' => ['required', 'numeric', 'between:0,999999.99'],
            'payout_date' => ['required', 'date'],
        ]);

        $employee->update($fields);

        return redirect()->route('employees.index')->with('success', 'The employee has been updated');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('deleted', 'The employee has been deleted');
    }
}
