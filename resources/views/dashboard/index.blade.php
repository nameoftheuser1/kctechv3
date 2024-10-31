<x-admin-layout>
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Financial Dashboard</h1>
            <p class="text-gray-600 mt-2">Overview of your business performance</p>
        </div>

        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Revenue Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                        <h3 class="text-2xl font-bold text-green-600 mt-1">₱{{ number_format($totalRevenue, 2) }}</h3>
                        <p class="text-sm font-medium text-gray-600">
                            This data covers the period {{ $totalRevenueYear }}.
                        </p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Expenses Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Expenses</p>
                        <h3 class="text-2xl font-bold text-red-600 mt-1">₱{{ number_format($totalExpenses, 2) }}</h3>
                        <p class="text-sm font-medium text-gray-600">
                            This data covers the period {{ $totalExpensesYear }}.
                        </p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Overall Loss vs Income Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Overall Loss vs Income</p>
                        <h3
                            class="text-2xl font-bold {{ $overallLossVsIncome < 0 ? 'text-red-600' : 'text-green-600' }} mt-1">
                            ₱{{ number_format(abs($overallLossVsIncome), 2) }}
                        </h3>
                    </div>
                    <div class="p-3 rounded-full {{ $overallLossVsIncome < 0 ? 'bg-red-100' : 'bg-green-100' }}">
                        <svg class="w-6 h-6 {{ $overallLossVsIncome < 0 ? 'text-red-600' : 'text-green-600' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if ($overallLossVsIncome < 0)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            @endif
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 ">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Sales Predictions</h2>
                <div class="flex items-center space-x-2">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <span class="mr-1">●</span> Trending Up
                    </span>
                </div>
            </div>
            <canvas id="salesPredictionChart" class="w-full h-64"></canvas>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 mt-4">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Income vs Expenses</h2>
                    <div class="flex items-center space-x-2">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <span class="mr-1">●</span> Monthly
                        </span>
                    </div>
                </div>
                <canvas id="lossIncomeGauge" class="w-full h-64"></canvas>
            </div>
            <!-- Reservations Chart -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 md:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Reservation Trends</h2>
                    <div class="flex items-center space-x-2">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                            <span class="mr-1">●</span> Past Months
                        </span>
                    </div>
                </div>
                <canvas id="reservationsCountChart" class="w-full h-64"></canvas>
            </div>
        </div>

        <!-- Predicted Reservations Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 md:col-span-2 mt-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Predicted Reservations</h2>
                <div class="flex items-center space-x-2">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                        <span class="mr-1">●</span> Future Predictions
                    </span>
                </div>
            </div>
            <canvas id="predictedReservationsChart" class="w-full h-64"></canvas>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enhanced Chart.js defaults for better visuals
            Chart.defaults.font.family = "'Inter', 'system-ui', '-apple-system', 'sans-serif'";
            Chart.defaults.font.size = 12;
            Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(0, 0, 0, 0.8)';
            Chart.defaults.plugins.tooltip.padding = 12;
            Chart.defaults.plugins.tooltip.cornerRadius = 8;

            // Sales Prediction Chart
            const ctx = document.getElementById('salesPredictionChart').getContext('2d');
            const predictions = @json($predictedSales);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: predictions.map(pred => pred.month),
                    datasets: [{
                        label: 'Predicted Sales',
                        data: predictions.map(pred => pred.revenue),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgb(34, 197, 94)',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Revenue: ₱${context.raw.toLocaleString()}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Enhanced Loss vs Income Gauge
            const gaugeCtx = document.getElementById('lossIncomeGauge').getContext('2d');
            const totalRevenue = @json($totalRevenue);
            const totalExpenses = @json($totalExpenses);
            const totalSalaries = @json($totalSalaries);

            new Chart(gaugeCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Revenue', 'Expenses', 'Salaries'],
                    datasets: [{
                        data: [totalRevenue, totalExpenses, totalSalaries],
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(59, 130, 246, 0.8)'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label;
                                    const value = context.raw;
                                    return `${label}: ₱${value.toLocaleString()}`;
                                }
                            }
                        }
                    }
                }
            });

            // Enhanced Reservations Chart
            const reservationsCtx = document.getElementById('reservationsCountChart').getContext('2d');
            const reservationCounts = @json($reservationCounts);

            new Chart(reservationsCtx, {
                type: 'bar',
                data: {
                    labels: reservationCounts.map(item => item.month),
                    datasets: [{
                        label: 'Reservations',
                        data: reservationCounts.map(item => item.count),
                        backgroundColor: 'rgba(249, 115, 22, 0.8)',
                        borderColor: 'rgb(249, 115, 22)',
                        borderWidth: 1,
                        borderRadius: 6,
                        barThickness: 20
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return Math.floor(value); // Only show whole numbers
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Predicted Reservations Chart
            const predictedReservationsCtx = document.getElementById('predictedReservationsChart').getContext('2d');
            const predictedReservations = @json($predictedReservations);

            new Chart(predictedReservationsCtx, {
                type: 'line',
                data: {
                    labels: predictedReservations.map(item => item.month),
                    datasets: [{
                        label: 'Predicted Reservations',
                        data: predictedReservations.map(item => item.count),
                        borderColor: 'rgb(147, 51, 234)',
                        backgroundColor: 'rgba(147, 51, 234, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgb(147, 51, 234)',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Predicted Reservations: ${Math.round(context.raw)}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return Math.floor(value);
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-admin-layout>
