<div wire:poll.300s>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Bar -->
            <div
                class="bg-white p-4 rounded-xl shadow-lg border border-gray-100 flex flex-wrap items-center justify-between gap-4 hover:scale-[1.01] transition-transform duration-300">
                <div class="flex items-center gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">
                            {{ $isAdmin ? 'Organizational Overview' : 'My Workspace' }}
                        </h2>
                        <p class="text-xs text-gray-500">Based on Correspondence Registration Date</p>
                    </div>
                    <flux:badge variant="neutral" size="sm" class="animate-pulse">Live Updates every 5m</flux:badge>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <flux:label class="text-xs font-semibold uppercase text-gray-500">From</flux:label>
                        <flux:input type="date" wire:model.live="fromDate" size="sm" />
                    </div>
                    <div class="flex items-center gap-2">
                        <flux:label class="text-xs font-semibold uppercase text-gray-500">To</flux:label>
                        <flux:input type="date" wire:model.live="toDate" size="sm" />
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div
                    class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-l-blue-600 border border-gray-100 hover:scale-105 transition-transform duration-300">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Volume</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['total']) }}</div>
                    <p class="mt-1 text-xs text-gray-400">All registered items</p>
                </div>
                <div
                    class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-l-green-600 border border-gray-100 hover:scale-105 transition-transform duration-300">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Receipts</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['receipts']) }}</div>
                    <p class="mt-1 text-xs text-gray-400">Incoming letters</p>
                </div>
                <div
                    class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-l-purple-600 border border-gray-100 hover:scale-105 transition-transform duration-300">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Dispatches</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['dispatches']) }}</div>
                    <p class="mt-1 text-xs text-gray-400">Outgoing letters</p>
                </div>
                <div
                    class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-l-orange-500 border border-gray-100 text-orange-700 hover:scale-105 transition-transform duration-300">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Active/Pending</div>
                    <div class="mt-2 text-3xl font-bold">{{ number_format($stats['pending']) }}</div>
                    <p class="mt-1 text-xs text-orange-400">In-progress items</p>
                </div>
            </div>

            <!-- New Stats Grid 2 -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div
                    class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-l-red-600 border border-gray-100 text-red-700 hover:scale-105 transition-transform duration-300">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Overdue Issues</div>
                    <div class="mt-2 text-3xl font-bold">{{ number_format($stats['overdue']) }}</div>
                    <p class="mt-1 text-xs text-red-400">Past due date & unclosed</p>
                </div>
                <div
                    class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-l-yellow-600 border border-gray-100 text-yellow-700 hover:scale-105 transition-transform duration-300">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Urgent Letters</div>
                    <div class="mt-2 text-3xl font-bold">{{ number_format($stats['urgent']) }}</div>
                    <p class="mt-1 text-xs text-yellow-400">High/Critical priority</p>
                </div>
                <div
                    class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-l-indigo-600 border border-gray-100 text-indigo-700 hover:scale-105 transition-transform duration-300">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Replied Rate</div>
                    <div class="mt-2 text-3xl font-bold">{{ number_format($stats['replied']) }}</div>
                    <p class="mt-1 text-xs text-indigo-400">Official replies sent</p>
                </div>
                <div
                    class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-l-teal-600 border border-gray-100 text-teal-700 hover:scale-105 transition-transform duration-300">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Finalized</div>
                    <div class="mt-2 text-3xl font-bold">{{ number_format($stats['closed']) }}</div>
                    <p class="mt-1 text-xs text-teal-400">Closed/Archived records</p>
                </div>
            </div>

            <!-- Charts Row 1 -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Trend Chart -->
                <div
                    class="lg:col-span-2 bg-white p-6 rounded-xl shadow-lg border border-gray-100 hover:scale-[1.01] transition-transform duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <flux:heading size="lg">Correspondence Volume Trend</flux:heading>
                            <flux:subheading>Timeline of daily incoming and outgoing activity</flux:subheading>
                        </div>
                    </div>
                    <div id="volumeTrendChart" style="min-height: 350px;"></div>
                </div>

                <!-- Status Pie -->
                <div
                    class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 hover:scale-[1.01] transition-transform duration-300">
                    <div class="mb-4">
                        <flux:heading size="lg">Status Distribution</flux:heading>
                        <flux:subheading>Current state of all correspondences in the selected range</flux:subheading>
                    </div>
                    <div id="statusBreakdownChart" style="min-height: 350px;"></div>
                </div>
            </div>

            <!-- Charts Row 2: Priority & Confidentiality -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div
                    class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 hover:scale-[1.01] transition-transform duration-300">
                    <div class="mb-4">
                        <flux:heading size="lg">Priority Breakdown</flux:heading>
                        <flux:subheading>Distribution by priority levels (e.g., Urgent vs. Normal)</flux:subheading>
                    </div>
                    <div id="priorityChart" style="min-height: 300px;"></div>
                </div>

                <div
                    class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 hover:scale-[1.01] transition-transform duration-300">
                    <div class="mb-4">
                        <flux:heading size="lg">Confidentiality Breakdown</flux:heading>
                        <flux:subheading>Breakdown by document secrecy levels</flux:subheading>
                    </div>
                    <div id="confidentialityChart" style="min-height: 300px;"></div>
                </div>
            </div>

            <!-- Charts Row 3 -->
            @if ($isAdmin)
                <div class="grid grid-cols-1 gap-6">
                    <div
                        class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 hover:scale-[1.01] transition-transform duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <flux:heading size="lg">Top User Workload</flux:heading>
                                <flux:subheading>Efficiency metrics: showing active correspondences assigned to specific
                                    users</flux:subheading>
                            </div>
                        </div>
                        <div id="workloadChart" style="min-height: 350px;"></div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('livewire:init', () => {
                let trendChart, statusChart, workloadChart, priorityChart, confidentialityChart;

                const initCharts = () => {
                    const data = @json($chartData);

                    // 1. Volume Trend
                    const trendOptions = {
                        series: [{
                                name: 'Receipts',
                                data: data.trend.receipts
                            },
                            {
                                name: 'Dispatches',
                                data: data.trend.dispatches
                            }
                        ],
                        chart: {
                            type: 'area',
                            height: 350,
                            toolbar: {
                                show: false
                            },
                            zoom: {
                                enabled: false
                            },
                            background: 'transparent'
                        },
                        theme: {
                            mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                        },
                        colors: ['#16a34a', '#9333ea'],
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 2
                        },
                        xaxis: {
                            categories: data.trend.dates,
                            type: 'datetime'
                        },
                        tooltip: {
                            x: {
                                format: 'dd MMM yyyy'
                            }
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.45,
                                opacityTo: 0.05
                            }
                        }
                    };
                    if (trendChart) trendChart.destroy();
                    trendChart = new ApexCharts(document.querySelector("#volumeTrendChart"), trendOptions);
                    trendChart.render();

                    // 2. Status Distribution
                    const statusOptions = {
                        series: data.status.values,
                        chart: {
                            type: 'donut',
                            height: 350,
                            background: 'transparent'
                        },
                        theme: {
                            mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                        },
                        labels: data.status.labels,
                        colors: data.status.colors,
                        legend: {
                            position: 'bottom'
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '65%'
                                }
                            }
                        },
                        states: {
                            hover: {
                                filter: {
                                    type: 'none'
                                }
                            },
                            active: {
                                filter: {
                                    type: 'none'
                                }
                            }
                        }
                    };
                    if (statusChart) statusChart.destroy();
                    statusChart = new ApexCharts(document.querySelector("#statusBreakdownChart"), statusOptions);
                    statusChart.render();

                    // 3. Priority Breakdown
                    const priorityOptions = {
                        series: data.priority.values,
                        chart: {
                            type: 'pie',
                            height: 300,
                            background: 'transparent'
                        },
                        labels: data.priority.labels,
                        colors: data.priority.colors,
                        theme: {
                            mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                        },
                        legend: {
                            position: 'bottom'
                        }
                    };
                    if (priorityChart) priorityChart.destroy();
                    priorityChart = new ApexCharts(document.querySelector("#priorityChart"), priorityOptions);
                    priorityChart.render();

                    // 4. Confidentiality Breakdown
                    const confidentialityOptions = {
                        series: [{
                            name: 'Count',
                            data: data.confidentiality.values
                        }],
                        chart: {
                            type: 'bar',
                            height: 300,
                            toolbar: {
                                show: false
                            },
                            background: 'transparent'
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                horizontal: true,
                                distributed: true
                            }
                        },
                        theme: {
                            mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                        },
                        xaxis: {
                            categories: data.confidentiality.labels
                        },
                        colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
                        legend: {
                            show: false
                        }
                    };
                    if (confidentialityChart) confidentialityChart.destroy();
                    confidentialityChart = new ApexCharts(document.querySelector("#confidentialityChart"), confidentialityOptions);
                    confidentialityChart.render();

                    // 5. Workload (Admin only)
                    if (data.workload && data.workload.values && data.workload.values.length > 0) {
                        const workloadOptions = {
                            series: [{
                                name: 'Letters',
                                data: data.workload.values
                            }],
                            chart: {
                                type: 'bar',
                                height: 350,
                                toolbar: {
                                    show: false
                                },
                                background: 'transparent'
                            },
                            theme: {
                                mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                            },
                            plotOptions: {
                                bar: {
                                    borderRadius: 4,
                                    horizontal: true
                                }
                            },
                            colors: ['#2563eb'],
                            dataLabels: {
                                enabled: true
                            },
                            xaxis: {
                                categories: data.workload.labels
                            }
                        };
                        if (workloadChart) workloadChart.destroy();
                        workloadChart = new ApexCharts(document.querySelector("#workloadChart"), workloadOptions);
                        workloadChart.render();
                    }
                };

                initCharts();

                Livewire.on('refresh-charts', () => {
                    setTimeout(initCharts, 100);
                });
            });
        </script>
    @endpush
</div>