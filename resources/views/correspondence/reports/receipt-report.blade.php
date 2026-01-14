<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Receipt Report') }}
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
                <button onclick="downloadPDF('Receipt Correspondence Report')"
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
            <x-filter-section :action="route('correspondence.reports.receipts')" :isExpanded="false">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <x-label for="filter_status_id" value="Status Wise" />
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
                        <x-label for="filter_addressed_to_user_id" value="Addressed To" />
                        <select id="filter_addressed_to_user_id" name="filter[addressed_to_user_id]"
                            class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">All Official Addressees</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('filter.addressed_to_user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label for="filter_current_holder_id" value="Assigned To (User)" />
                        <select id="filter_current_holder_id" name="filter[current_holder_id]"
                            class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">All Current Holders</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('filter.current_holder_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label for="filter_marked_to_user_id" value="Marked To" />
                        <select id="filter_marked_to_user_id" name="filter[marked_to_user_id]"
                            class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">All Marked Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('filter.marked_to_user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label for="filter_category_id" value="Category" />
                        <select id="filter_category_id" name="filter[category_id]"
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
                        <x-label for="filter_received_from" value="Date From" />
                        <x-input id="filter_received_from" name="filter[received_from]" type="date"
                            class="mt-1 block w-full" :value="request('filter.received_from')" />
                    </div>

                    <div>
                        <x-label for="filter_received_to" value="Date To" />
                        <x-input id="filter_received_to" name="filter[received_to]" type="date"
                            class="mt-1 block w-full" :value="request('filter.received_to')" />
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="filter[overdue]" value="1"
                            class="rounded border-gray-300 text-red-600" {{ request('filter.overdue') ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-600 font-bold">Aging > 3 Days Only</span>
                    </label>
                </div>
            </x-filter-section>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @php
                    $headers = ['Rec. No', 'Date', 'Register No', 'Subject', 'Addressed To', 'Current Holder', 'Status', 'Aging'];
                    $data = $correspondences->map(function ($item) {
                        return [
                            'rec_no' => $item->receipt_no,
                            'date' => $item->received_date?->format('d-m-Y'),
                            'reg_no' => $item->register_number,
                            'subject' => Str::limit($item->subject, 50),
                            'addressed_to' => $item->addressedTo?->name ?? 'N/A',
                            'holder' => $item->currentHolder?->name ?? 'N/A',
                            'status' => $item->status?->name ?? 'N/A',
                            'aging' => $item->current_holder_since ? (int)now()->diffInDays($item->current_holder_since) . ' days' : '0 days',
                        ];
                    })->toArray();
                @endphp

                <x-printable-table :data="$data" :headers="$headers" title="Receipt Correspondence Register"
                    description="This report provides a detailed record of all incoming correspondences received within the selected date range, including current holder and aging information."
                    organization="{{ config('app.name') }}" :showActions="false" />
            </div>
        </div>
    </div>
</x-app-layout>