<x-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md p-8 bg-slate-50 rounded-lg shadow-md">
            <h2 class="text-3xl font-bold text-center text-slate-800 mb-6">Login</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-slate-700">Username</label>
                    <input type="text" name="username" id="username"
                        class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm p-2 focus:ring focus:ring-slate-500 @error('username') border-red-500 @enderror"
                        value="{{ old('username') }}" required >

                    @error('username')
                        <span class="text-red-500 text-sm mt-1" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                    <input type="password" name="password" id="password"
                        class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm p-2 focus:ring focus:ring-slate-500 @error('password') border-red-500 @enderror"
                        required>

                    @error('password')
                        <span class="text-red-500 text-sm mt-1" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="w-full bg-slate-600 text-white font-semibold py-2 rounded-md hover:bg-slate-700 transition duration-200">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
