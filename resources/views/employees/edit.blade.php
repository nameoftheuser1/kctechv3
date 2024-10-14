<x-admin-layout>
    <div class="container mx-auto px-4 py-8 w-full sm:w-1/2 border rounded-lg bg-white mt-0 sm:mt-10">
        <a href="{{ route('employees.index') }}" class="text-blue-500 text-sm underline">&larr; back to employee list</a>
        <h1 class="text-3xl font-bold text-slate-700 mt-4">Edit Employee</h1>
        <p class="text-sm text-slate-500 mb-6">Update employee details</p>
        <form action="{{ route('employees.update', $employee->id) }}" method="POST" class="flex flex-col w-full justify-center">
            @csrf
            @method('PUT') <!-- Include this line for PUT requests -->

            <div class="mb-4 w-full">
                <label for="name" class="block text-gray-700 font-bold mb-2 text-sm">Employee Name</label>
                <input type="text" name="name" id="name"
                    class="w-full text-gray-600 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                    value="{{ old('name', $employee->name) }}" required>
            </div>

            @error('name')
                <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
            @enderror

            <div class="mb-4">
                <label for="salary" class="block text-gray-700 font-bold mb-2 text-sm">Salary</label>
                <input type="number" name="salary" id="salary" step="0.01"
                    class="w-full text-gray-600 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                    value="{{ old('salary', $employee->salary) }}" required>
            </div>

            @error('salary')
                <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
            @enderror

            <div class="mb-4">
                <label for="payout_date" class="block text-gray-700 font-bold mb-2 text-sm">Payout Date</label>
                <input type="date" name="payout_date" id="payout_date"
                    class="w-full text-gray-600 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                    value="{{ old('payout_date', $employee->payout_date) }}" required>
            </div>

            @error('payout_date')
                <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
            @enderror

            <div>
                <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Update Employee
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
