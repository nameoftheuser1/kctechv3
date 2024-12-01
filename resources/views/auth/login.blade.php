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
                        value="{{ old('username') }}" required>

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
                    <button type="submit"
                        class="w-full bg-slate-600 text-white font-semibold py-2 rounded-md hover:bg-slate-700 transition duration-200">
                        Login
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <button id="resetPasswordButton"
                    class="w-full bg-slate-500 text-white font-semibold py-2 rounded-md hover:bg-slate-600 transition duration-200"
                    onclick="sendResetLink()" disabled>
                    Reset Password (<span id="timer">60</span>s)
                </button>
            </div>
        </div>
    </div>

    <script>
        let timer = 60;
        const button = document.getElementById('resetPasswordButton');
        const timerElement = document.getElementById('timer');

        // Enable button after DOM loads
        document.addEventListener('DOMContentLoaded', () => {
            button.disabled = false;
            timerElement.textContent = '';
        });

        function sendResetLink() {
            button.disabled = true;
            timer = 60;
            updateTimer();

            // Call the Laravel controller endpoint
            fetch("{{ route('password.email') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    }
                })
                .then(response => {
                    if (response.ok) {
                        alert('Reset link sent to your email!');
                    } else {
                        alert('Failed to send reset link.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });

            // Start countdown
            const countdown = setInterval(() => {
                if (timer <= 0) {
                    clearInterval(countdown);
                    button.disabled = false;
                    timerElement.textContent = '';
                    return;
                }
                timer--;
                updateTimer();
            }, 1000);
        }

        function updateTimer() {
            timerElement.textContent = timer;
        }
    </script>
</x-layout>
