<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dispatch Report') }}
            </h2>
            <div class="flex items-center space-x-3">
                <button onclick="window.print()"
                    class="no-print inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </button>
                <button onclick="downloadPDF('Dispatch Correspondence Report')"
                    class="no-print inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download
                </button>
                <button id="toggle"
                    class="no-print inline-flex items-center px-4 py-2 bg-blue-950 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-950 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Refresh Filters
                </button>
                <a href="{{ route('correspondence.reports.index') }}"
                    class="no-print inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <style>
            @media print {
                @page {
                    size: A4 portrait;
                    margin: 1cm;
                }

                .no-print {
                    display: none !important;
                }

                .print-only {
                    display: block !important;
                }

                body {
                    background-color: white !important;
                    color: black !important;
                    margin: 0 !important;
                    padding: 0 !important;
                }

                .max-w-7xl,
                .max-w-2xl {
                    max-width: 100% !important;
                    width: 100% !important;
                    margin: 0 !important;
                    padding: 0 !important;
                }

                .bg-white,
                .sm\:rounded-lg,
                .shadow-xl,
                .shadow {
                    box-shadow: none !important;
                    border: none !important;
                    background: transparent !important;
                }

                .py-6,
                .py-12,
                .p-6,
                .p-8,
                .px-4,
                .px-6,
                .px-8,
                .sm\:px-6,
                .lg\:px-8 {
                    padding: 0 !important;
                    margin: 0 !important;
                }
            }
        </style>

        <div class="no-print">
            <x-filter-section :action="route('correspondence.reports.dispatches')" :isExpanded="false">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <x-label for="filter_status_id" value="Status" />
                        <select id="filter_status_id" name="filter[status_id]"
                            class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ request('filter.status_id') == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label for="filter_to_division_id" value="To Division" />
                        <select id="filter_to_division_id" name="filter[to_division_id]"
                            class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">All Divisions</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}" {{ request('filter.to_division_id') == $division->id ? 'selected' : '' }}>
                                    {{ $division->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label for="filter_column_category_id" value="Category" />
                        <select id="filter_column_category_id" name="filter[category_id]"
                            class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('filter.category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label for="filter_dispatch_from" value="Date From" />
                        <x-input id="filter_dispatch_from" name="filter[dispatch_from]" type="date"
                            class="mt-1 block w-full" :value="request('filter.dispatch_from')" />
                    </div>

                    <div>
                        <x-label for="filter_dispatch_to" value="Date To" />
                        <x-input id="filter_dispatch_to" name="filter[dispatch_to]" type="date"
                            class="mt-1 block w-full" :value="request('filter.dispatch_to')" />
                    </div>
                </div>
            </x-filter-section>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @php
                    $headers = ['Disp. No', 'Date', 'Register No', 'Subject', 'Destination', 'Status'];
                    $data = $correspondences->map(function ($item) {
                        return [
                            'disp_no' => $item->dispatch_no,
                            'date' => $item->dispatch_date?->format('d-m-Y'),
                            'reg_no' => $item->register_number,
                            'subject' => Str::limit($item->subject, 60),
                            'destination' => $item->toDivision?->name ?? $item->external_recipient ?? 'N/A',
                            'status' => $item->status?->name ?? 'N/A',
                        ];
                    })->toArray();
                @endphp

                <x-printable-table :data="$data" :headers="$headers" title="Dispatch Correspondence Register"
                    description="This report provides a detailed record of all outgoing correspondences dispatched within the selected date range, including destination and current status."
                    organization="{{ config('app.name') }}" :showActions="false" />
            </div>
        </div>
    </div>
</x-app-layout>