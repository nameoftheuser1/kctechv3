<x-admin-layout>
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Financial Dashboard</h1>
            <p class="text-gray-600 mt-2">Overview of your business performance</p>
            <div class="flex justify-end mb-6">
                <a href="{{ route('settings.edit') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm transition-colors duration-200 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Adjust Years
                </a>
            </div>
        </div>

        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Revenue Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                        <h3 class="text-2xl font-bold text-green-600 mt-1">₱{{ number_format($combinedRevenue, 2) }}
                        </h3>
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
                        <p class="text-sm font-medium text-gray-600">Total Loss</p>
                        <h3 class="text-2xl font-bold text-red-600 mt-1">₱{{ number_format($totalLoss, 2) }}</h3>
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

            <!-- Gross Income Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Gross Income</p>
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
                <!-- Note about the color coding -->
                <p class="mt-4 text-sm text-gray-500">
                    <span class="text-red-600 font-bold">Red</span> indicates a negative value or loss, while
                    <span class="text-green-600 font-bold">Green</span> indicates a positive value or gain.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
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
                </div>
                <canvas id="reservationsCountChart" class="w-full h-64"></canvas>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation"></script>
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
            const combinedSales = @json($combinedSales);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: combinedSales.map(sale => sale.month),
                    datasets: [{
                        label: 'Combined Sales',
                        data: combinedSales.map(sale => sale.revenue),
                        borderColor: (context) => {
                            const chart = context.chart;
                            const {
                                ctx
                            } = chart;

                            // Create gradient for distinguishing actual and predicted sales
                            const gradient = ctx.createLinearGradient(0, 0, ctx.canvas.width,
                                0);
                            const totalDataPoints = combinedSales.length;
                            const actualSalesCount = totalDataPoints -
                                3; // Last 3 months are for predicted sales

                            // Blue for actual sales, transitioning to red for predicted sales
                            gradient.addColorStop(0,
                                'rgba(34, 197, 94, 1)'); // Actual sales color
                            gradient.addColorStop(actualSalesCount / totalDataPoints,
                                'rgba(34, 197, 94, 1)');
                            gradient.addColorStop(actualSalesCount / totalDataPoints,
                                'rgba(255, 99, 132, 1)'); // Predicted sales color
                            gradient.addColorStop(1, 'rgba(255, 99, 132, 1)');

                            return gradient;
                        },
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
                                    const sale = combinedSales[context.dataIndex];
                                    const isPredicted = context.dataIndex >= (combinedSales.length -
                                        3); // Last 3 months are predicted
                                    const type = isPredicted ? 'Predicted Sales' : 'Actual Sales';
                                    return `${type}: ₱${context.raw.toLocaleString()}`;
                                }
                            }
                        },
                        annotation: {
                            annotations: {
                                predictionStart: {
                                    type: 'line',
                                    scaleID: 'x',
                                    value: combinedSales.length - 3, // The index where prediction starts
                                    borderColor: 'rgba(255, 99, 132, 0.8)',
                                    borderWidth: 2,
                                    label: {
                                        enabled: true,
                                        content: 'Prediction Starts',
                                        position: 'start',
                                        backgroundColor: 'rgba(255, 99, 132, 0.8)',
                                        color: '#fff',
                                        padding: 6
                                    }
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
            const totalCommissions = @json($totalCommissions); // Add total commissions

            new Chart(gaugeCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Gross Income', 'Expenses', 'Salaries',
                        'Commissions'
                    ], // Add 'Commissions' label
                    datasets: [{
                        data: [totalRevenue, totalExpenses, totalSalaries,
                            totalCommissions
                        ], // Add totalCommissions to data
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.8)', // Color for 'Gross Income'
                            'rgba(239, 68, 68, 0.8)', // Color for 'Expenses'
                            'rgba(59, 130, 246, 0.8)', // Color for 'Salaries'
                            'rgba(255, 159, 64, 0.8)' // Color for 'Commissions'
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

            // Group data by year
            const years = [...new Set(reservationCounts.map(item => item.year))]; // Extract unique years
            const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                'October', 'November', 'December'
            ];

            // Organize data for each year and month
            const datasets = years.map(year => {
                return {
                    label: `Reservations in ${year}`,
                    data: months.map(month => {
                        const monthData = reservationCounts.find(item => item.year === year && item
                            .month === month);
                        return monthData ? monthData.count :
                            0; // Return count if exists, otherwise 0
                    }),
                    backgroundColor: year === 2023 ? 'rgba(54, 162, 235, 0.8)' :
                    'rgba(249, 115, 22, 0.8)', // Different colors for each year
                    borderColor: year === 2023 ? 'rgb(54, 162, 235)' : 'rgb(249, 115, 22)',
                    borderWidth: 1,
                    borderRadius: 6,
                    barThickness: 20
                };
            });

            // Create the chart
            new Chart(reservationsCtx, {
                type: 'bar',
                data: {
                    labels: months, // Use months as labels
                    datasets: datasets // Add datasets for both years
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true // Show legend to differentiate years
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
                            },
                            stacked: false // Bars are grouped side by side, not stacked
                        }
                    }
                }
            });

        });
    </script>
</x-admin-layout>
