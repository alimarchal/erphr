<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight inline-block">
                {{ __('Correspondence Reports') }}
            </h2>
            <div class="flex justify-center items-center float-right">
                <a href="{{ route('correspondence.index') }}"
                    class="inline-flex items-center ml-2 px-4 py-2 bg-blue-950 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-800 focus:bg-green-800 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-12 gap-6">

                <!-- Receipt Register Report Card -->
                @can('view receipt report')
                    <a href="{{ route('correspondence.reports.receipts') }}"
                        class="transform hover:scale-110 transition duration-300 shadow-xl rounded-lg col-span-12 sm:col-span-6 md:col-span-3 lg:col-span-3 intro-y bg-white block border-l-4 border-l-green-600">
                        <div class="p-5 flex justify-between">
                            <div>
                                <div class="text-3xl font-bold leading-8">{{ $stats['total_receipts'] }}</div>
                                <div class="mt-1 text-base font-extrabold text-black">Receipt Register</div>
                            </div>
                            <svg class="h-16 w-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </a>
                @endcan

                <!-- Dispatch Register Report Card -->
                @can('view dispatch report')
                    <a href="{{ route('correspondence.reports.dispatches') }}"
                        class="transform hover:scale-110 transition duration-300 shadow-xl rounded-lg col-span-12 sm:col-span-6 md:col-span-3 lg:col-span-3 intro-y bg-white block border-l-4 border-l-purple-600">
                        <div class="p-5 flex justify-between">
                            <div>
                                <div class="text-3xl font-bold leading-8">{{ $stats['total_dispatches'] ?? 0 }}</div>
                                <div class="mt-1 text-base font-extrabold text-black">Dispatch Register</div>
                            </div>
                            <svg class="h-16 w-16 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </div>
                    </a>
                @endcan

                <!-- Overdue Receipts Card -->
                @can('view receipt report')
                    <a href="{{ route('correspondence.reports.receipts', ['filter[overdue]' => 1]) }}"
                        class="transform hover:scale-110 transition duration-300 shadow-xl rounded-lg col-span-12 sm:col-span-6 md:col-span-3 lg:col-span-3 intro-y bg-white block border-l-4 border-l-red-600">
                        <div class="p-5 flex justify-between">
                            <div>
                                <div class="text-3xl font-bold leading-8 text-red-600">{{ $stats['overdue_receipts'] ?? 0 }}
                                </div>
                                <div class="mt-1 text-base font-extrabold text-black">Aging > 3 Days</div>
                            </div>
                            <svg class="h-16 w-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </a>
                @endcan

                <!-- Pending Receipts Card -->
                @can('view receipt report')
                    <a href="{{ route('correspondence.reports.receipts', ['filter[pending]' => 1]) }}"
                        class="transform hover:scale-110 transition duration-300 shadow-xl rounded-lg col-span-12 sm:col-span-6 md:col-span-3 lg:col-span-3 intro-y bg-white block border-l-4 border-l-orange-500">
                        <div class="p-5 flex justify-between">
                            <div>
                                <div class="text-3xl font-bold leading-8 text-orange-500">
                                    {{ $stats['pending_receipts'] ?? 0 }}</div>
                                <div class="mt-1 text-base font-extrabold text-black">Pending Receipts</div>
                            </div>
                            <svg class="h-16 w-16 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                    </a>
                @endcan

                <!-- User-wise Summary Card -->
                @can('view user summary report')
                    <a href="{{ route('correspondence.reports.user-wise') }}"
                        class="transform hover:scale-110 transition duration-300 shadow-xl rounded-lg col-span-12 sm:col-span-6 md:col-span-3 lg:col-span-3 intro-y bg-white block border-l-4 border-l-blue-600">
                        <div class="p-5 flex justify-between">
                            <div>
                                <div class="text-3xl font-bold leading-8 text-blue-600">User Summary</div>
                                <div class="mt-1 text-base font-extrabold text-black">Status & Workload</div>
                            </div>
                            <svg class="h-16 w-16 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </a>
                @endcan

                <!-- Monthly Handled Summary Card -->
                @can('view monthly summary report')
                    <a href="{{ route('correspondence.reports.monthly-summary') }}"
                        class="transform hover:scale-110 transition duration-300 shadow-xl rounded-lg col-span-12 sm:col-span-6 md:col-span-3 lg:col-span-3 intro-y bg-white block border-l-4 border-l-indigo-600">
                        <div class="p-5 flex justify-between">
                            <div>
                                <div class="text-3xl font-bold leading-8 text-indigo-600">Monthly Stats</div>
                                <div class="mt-1 text-base font-extrabold text-black">Total Handled</div>
                            </div>
                            <svg class="h-16 w-16 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </a>
                @endcan

                <!-- Addressed to User Card (Shortcut) -->
                @can('view receipt report')
                    <a href="{{ route('correspondence.reports.receipts', ['filter[addressed_to_user_id]' => auth()->id()]) }}"
                        class="transform hover:scale-110 transition duration-300 shadow-xl rounded-lg col-span-12 sm:col-span-6 md:col-span-3 lg:col-span-3 intro-y bg-white block border-l-4 border-l-pink-600">
                        <div class="p-5 flex justify-between">
                            <div>
                                <div class="text-3xl font-bold leading-8 text-pink-600">My Addressed</div>
                                <div class="mt-1 text-base font-extrabold text-black">Letters for Me</div>
                            </div>
                            <svg class="h-16 w-16 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </a>
                @endcan

                <!-- Marked to User Card (Shortcut) -->
                @can('view receipt report')
                    <a href="{{ route('correspondence.reports.receipts', ['filter[marked_to_user_id]' => auth()->id()]) }}"
                        class="transform hover:scale-110 transition duration-300 shadow-xl rounded-lg col-span-12 sm:col-span-6 md:col-span-3 lg:col-span-3 intro-y bg-white block border-l-4 border-l-teal-600">
                        <div class="p-5 flex justify-between">
                            <div>
                                <div class="text-3xl font-bold leading-8 text-teal-600">My Marked</div>
                                <div class="mt-1 text-base font-extrabold text-black">Marked to Me</div>
                            </div>
                            <svg class="h-16 w-16 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                    </a>
                @endcan

            </div>
        </div>
    </div>
</x-app-layout>