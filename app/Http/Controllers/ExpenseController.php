<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $expenses = Expense::query()
            ->when($search, function ($query, $search) {
                $query->where('expense_description', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%")
                    ->orWhere('date_time', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('expenses.index', ['expenses' => $expenses]);
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'expense_description' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'between:0,999999.99'],
            'date_time' => ['required', 'date'],
        ]);

        Expense::create($fields);

        return redirect()->route('expenses.index')->with('success', 'The expense has been added successfully.');
    }

    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $fields = $request->validate([
            'expense_description' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'between:0,999999.99'],
            'date_time' => ['required', 'date'],
        ]);

        $expense->update($fields);

        return redirect()->route('expenses.index')->with('success', 'The expense has been updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')->with('deleted', 'The expense has been deleted successfully.');
    }
}
