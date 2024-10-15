<x-admin-layout>
    <div class="container mx-auto py-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold mb-4">Dashboard</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800">Total Reservations (Current Month)</h3>
                    <p class="text-2xl font-bold text-blue-500">{{ $reservations }}</p>
                </div>

                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800">Total Revenue (Current Month)</h3>
                    <p class="text-2xl font-bold text-green-500">${{ $totalRevenue }}</p>
                </div>

                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800">Total Expenses (Current Month)</h3>
                    <p class="text-2xl font-bold text-red-500">${{ $totalExpenses }}</p>
                </div>

                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800">Profit/Loss (Current Month)</h3>
                    <p class="text-2xl font-bold text-yellow-500">${{ $profitLoss }}</p>
                </div>

                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800">Predicted Reservations (Next Month)</h3>
                    <p class="text-2xl font-bold text-blue-500">{{ $forecastedReservations }}</p>
                </div>

                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800">Forecasted Revenue (Next Month)</h3>
                    <p class="text-2xl font-bold text-green-500">${{ $forecastedRevenue }}</p>
                </div>
            </div>
        </div>

        <!-- Chart.js Canvas -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Current & Forecasted Data</h3>
            <canvas id="myChart" width="400" height="200"></canvas>
        </div>

        <script>
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Total Reservations', 'Total Revenue', 'Total Expenses', 'Profit/Loss',
                        'Forecasted Reservations', 'Forecasted Revenue'
                    ],
                    datasets: [{
                        label: 'Current & Forecasted Data',
                        data: [
                            {{ $reservations }},
                            {{ $totalRevenue }},
                            {{ $totalExpenses }},
                            {{ $profitLoss }},
                            {{ $forecastedReservations }},
                            {{ $forecastedRevenue }}
                        ],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(231, 76, 60, 0.2)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(231, 76, 60, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    </div>
</x-admin-layout>
