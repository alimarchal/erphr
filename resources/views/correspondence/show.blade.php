<x-app-layout>
    <x-slot name="header">
        <x-page-header
            :title="$correspondence->register_number"
            :backUrl="route('correspondence.index', ['type' => $correspondence->type])"
            :showSearch="false"
        />
    </x-slot>

    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 1cm;
            }

            .no-print {
                display: none !important;
            }

            body {
                background-color: white !important;
                color: black !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .max-w-7xl {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .bg-white, .sm\:rounded-lg, .shadow-xl, .shadow, .rounded-lg, .rounded-md, .rounded-full {
                box-shadow: none !important;
                border-radius: 0 !important;
                background: white !important;
            }

            .py-6, .py-12, .p-6, .p-8, .px-4, .px-6, .px-8, .sm\:px-6, .lg\:px-8 {
                padding: 0 !important;
            }

            .mb-6, .mb-8, .mb-4 {
                margin-bottom: 0.5rem !important;
            }

            /* Remove all colors for laser printing */
            .bg-blue-50, .bg-yellow-50, .bg-green-50, .bg-gray-50, .bg-purple-50, .bg-orange-50, .bg-indigo-50, .bg-teal-50,
            .bg-red-50, .bg-blue-100, .bg-gray-100, .bg-red-600, .bg-purple-600 {
                background-color: white !important;
            }

            .text-blue-700, .text-yellow-700, .text-green-700, .text-gray-700, .text-purple-700, .text-orange-700,
            .text-indigo-700, .text-teal-700, .text-red-700, .text-blue-600, .text-white {
                color: black !important;
            }

            .border-blue-100, .border-yellow-100, .border-green-100, .border-gray-100, .border-purple-100,
            .border-orange-100, .border-indigo-100, .border-teal-100, .border-red-100 {
                border-color: black !important;
            }

            /* Table print styles */
            .info-table {
                width: 100% !important;
                page-break-inside: auto;
            }

            .info-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .info-table th, .info-table td {
                border: 1px solid black !important;
                padding: 4px 6px !important;
                font-size: 10pt !important;
            }

            .animate-pulse {
                animation: none !important;
            }
        }

        /* Screen styles for table */
        .info-table {
            border-collapse: collapse;
            width: 100%;
        }

        .info-table th {
            background-color: #f9fafb;
            font-weight: bold;
            text-align: left;
            white-space: nowrap;
            color: #000;
        }

        .info-table td {
            font-weight: normal;
            color: #000;
        }

        .info-table th, .info-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 13px;
        }

        .section-header {
            background-color: transparent !important;
            color: black !important;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.05em;
            text-align: center !important;
            padding: 4px 6px !important;
        }

        @media print {
            .section-header {
                background-color: transparent !important;
                color: black !important;
            }
        }
    </style>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-status-message class="mb-4" />

            {{-- Unified Main Card --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                {{-- Card Header with Badges & Actions --}}
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-3">
                        <h3 class="text-lg font-bold text-gray-900 mr-2">Correspondence Information</h3>
                        
                        @if($correspondence->status)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border
                                {{ $correspondence->status->color === 'blue' ? 'bg-blue-50 text-blue-700 border-blue-100' : '' }}
                                {{ $correspondence->status->color === 'yellow' ? 'bg-yellow-50 text-yellow-700 border-yellow-100' : '' }}
                                {{ $correspondence->status->color === 'green' ? 'bg-green-50 text-green-700 border-green-100' : '' }}
                                {{ $correspondence->status->color === 'gray' ? 'bg-gray-50 text-gray-700 border-gray-100' : '' }}
                                {{ $correspondence->status->color === 'purple' ? 'bg-purple-50 text-purple-700 border-purple-100' : '' }}
                                {{ $correspondence->status->color === 'orange' ? 'bg-orange-50 text-orange-700 border-orange-100' : '' }}
                                {{ $correspondence->status->color === 'indigo' ? 'bg-indigo-50 text-indigo-700 border-indigo-100' : '' }}
                                {{ $correspondence->status->color === 'teal' ? 'bg-teal-50 text-teal-700 border-teal-100' : '' }}">
                                {{ $correspondence->status->name }}
                            </span>
                        @endif

                        @if($correspondence->priority)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border
                                {{ $correspondence->priority->color === 'red' ? 'bg-red-50 text-red-700 border-red-100' : '' }}
                                {{ $correspondence->priority->color === 'orange' ? 'bg-orange-50 text-orange-700 border-orange-100' : '' }}
                                {{ $correspondence->priority->color === 'yellow' ? 'bg-yellow-50 text-yellow-700 border-yellow-100' : '' }}
                                {{ $correspondence->priority->color === 'green' ? 'bg-green-50 text-green-700 border-green-100' : '' }}">
                                {{ $correspondence->priority->name }}
                            </span>
                        @endif

                        @if($correspondence->isOverdue())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-600 text-white animate-pulse">
                                OVERDUE
                            </span>
                        @endif

                        @if($correspondence->confidentiality !== 'Normal')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-600 text-white">
                                {{ strtoupper($correspondence->confidentiality) }}
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center space-x-2">
                        <button type="button" onclick="window.print()"
                                class="no-print inline-flex items-center px-4 py-2 bg-gray-800 text-white text-xs font-bold uppercase tracking-widest rounded-md hover:bg-gray-900 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Print
                        </button>
                        @can('edit correspondence')
                        <a href="{{ route('correspondence.edit', $correspondence) }}"
                           class="no-print inline-flex items-center px-4 py-2 bg-blue-800 text-white text-xs font-bold uppercase tracking-widest rounded-md hover:bg-blue-900 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                        @endcan
                        @can('mark correspondence')
                        <button type="button" onclick="document.getElementById('mark-modal').classList.remove('hidden')"
                                class="no-print inline-flex items-center px-4 py-2 bg-green-600 text-white text-xs font-bold uppercase tracking-widest rounded-md hover:bg-green-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                            Mark To
                        </button>
                        @endcan
                    </div>
                </div>

                <div class="p-2">
                    <table class="info-table">
                        <tbody>
                            {{-- Basic Details Section --}}
                            <tr>
                                <th colspan="6" class="section-header">Basic Details</th>
                            </tr>
                            <tr>
                                <th style="width: 16.67%;">{{ $correspondence->isReceipt() ? 'Receipt No' : 'Dispatch No' }}</th>
                                <td style="width: 16.67%;">{{ $correspondence->isReceipt() ? ($correspondence->receipt_no ?? 'N/A') : ($correspondence->dispatch_no ?? 'N/A') }}</td>
                                <th style="width: 16.67%;">Register No</th>
                                <td style="width: 16.67%;">{{ $correspondence->register_number ?? 'N/A' }}</td>
                                <th style="width: 16.67%;">Type</th>
                                <td style="width: 16.67%;">{{ $correspondence->type ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Letter Type</th>
                                <td>{{ $correspondence->letterType?->name ?? 'N/A' }}</td>
                                <th>Category</th>
                                <td>{{ $correspondence->category?->name ?? 'N/A' }}</td>
                                <th>Letter Date</th>
                                <td>{{ $correspondence->letter_date?->format('d-M-Y') ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ $correspondence->isReceipt() ? 'Received Date' : 'Dispatch Date' }}</th>
                                <td>{{ $correspondence->isReceipt() ? ($correspondence->received_date?->format('d-M-Y') ?? 'N/A') : ($correspondence->dispatch_date?->format('d-M-Y') ?? 'N/A') }}</td>
                                <th>Reference Number</th>
                                <td colspan="3">{{ $correspondence->reference_number ?? 'N/A' }}</td>
                            </tr>

                            {{-- Source & Destination Section --}}
                            <tr>
                                <th colspan="6" class="section-header">Source & Destination</th>
                            </tr>
                            <tr>
                                <th>External Party (Sender/Recipient)</th>
                                <td>{{ $correspondence->sender_name ?? 'N/A' }}</td>
                                <th>From Division (Internal)</th>
                                <td>{{ $correspondence->fromDivision?->name ?? 'N/A' }}</td>
                                <th>Region</th>
                                <td>{{ $correspondence->region?->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Branch</th>
                                <td>{{ $correspondence->branch?->name ?? 'N/A' }}</td>
                                <th>Marked To</th>
                                <td>{{ $correspondence->markedTo?->name ?? 'N/A' }}</td>
                                <th>Addressed To</th>
                                <td>{{ $correspondence->addressedTo?->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Current Holder</th>
                                <td>
                                    {{ $correspondence->currentHolder?->name ?? 'Not assigned' }}
                                    @if($correspondence->current_holder_since)
                                        <br><small class="text-gray-500">(Since {{ $correspondence->current_holder_since->format('d-M-Y H:i') }})</small>
                                    @endif
                                </td>
                                <th>Due Date</th>
                                <td>{{ $correspondence->due_date?->format('d-M-Y') ?? 'N/A' }}</td>
                                <th>Days Open</th>
                                <td>{{ $correspondence->days_open ?? 0 }} days</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{{ $correspondence->status?->name ?? 'N/A' }}</td>
                                <th>Priority</th>
                                <td>{{ $correspondence->priority?->name ?? 'N/A' }}</td>
                                <th>Confidentiality</th>
                                <td>{{ $correspondence->confidentiality ?? 'Normal' }}</td>
                            </tr>

                            {{-- Delivery & Record Info Section --}}
                            <tr>
                                <th colspan="6" class="section-header">Delivery & Record Info</th>
                            </tr>
                            <tr>
                                <th>Delivery Mode</th>
                                <td>{{ $correspondence->delivery_mode ?? 'N/A' }}</td>
                                <th>Courier Name</th>
                                <td>{{ $correspondence->courier_name ?? 'N/A' }}</td>
                                <th>Tracking Number</th>
                                <td>{{ $correspondence->courier_tracking ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Created By</th>
                                <td>
                                    {{ $correspondence->creator?->name ?? 'N/A' }}
                                    @if($correspondence->created_at)
                                        <br><small class="text-gray-500">{{ $correspondence->created_at->format('d-M-Y H:i') }}</small>
                                    @endif
                                </td>
                                <th>Last Updated By</th>
                                <td colspan="3">
                                    {{ $correspondence->updater?->name ?? 'N/A' }}
                                    @if($correspondence->updated_at)
                                        <span class="text-gray-500"> - {{ $correspondence->updated_at->format('d-M-Y H:i') }}</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Subject Section --}}
                            <tr>
                                <th colspan="6" class="section-header">Subject</th>
                            </tr>
                            <tr>
                                <td colspan="6" style="padding: 12px;"><strong>{{ $correspondence->subject ?? 'N/A' }}</strong></td>
                            </tr>

                            {{-- Description Section --}}
                            @if($correspondence->description)
                            <tr>
                                <th colspan="6" class="section-header">Description</th>
                            </tr>
                            <tr>
                                <td colspan="6" style="padding: 12px; line-height: 1.6;">{{ $correspondence->description }}</td>
                            </tr>
                            @endif

                            {{-- Remarks Section --}}
                            @if($correspondence->remarks)
                            <tr>
                                <th colspan="6" class="section-header">Remarks</th>
                            </tr>
                            <tr>
                                <td colspan="6" style="padding: 12px; font-style: italic;">{{ $correspondence->remarks }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Quick Actions: Status & Comment --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 no-print">
                {{-- Status Update Card --}}
                <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden border border-blue-100 h-full">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Update Status
                        </h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('correspondence.status.update', $correspondence) }}" method="POST">
                            @csrf
                            <div class="flex items-end gap-3">
                                <div class="flex-grow">
                                    <x-label for="quick_status_id" value="Select Status" class="text-xs font-semibold text-gray-500 mb-1" />
                                    <select id="quick_status_id" name="status_id" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <option value="">Choose status...</option>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}" {{ $correspondence->status_id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs font-bold uppercase tracking-widest rounded-md hover:bg-blue-700 transition shadow-sm h-[38px]">
                                    Update
                                </button>
                            </div>
                            <div class="mt-3">
                                <x-label for="status_remarks" value="Add optional note to timeline" class="text-[10px] font-semibold text-gray-400 mb-1 uppercase tracking-wider" />
                                <input type="text" id="status_remarks" name="remarks" placeholder="Explain the reason for status change (optional)..." class="block w-full border-gray-200 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs">
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Quick Comment Card --}}
                <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden border border-gray-100 h-full">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest flex items-center">
                            <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            Add Quick Comment
                        </h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('correspondence.comment.add', $correspondence) }}" method="POST" class="flex gap-4">
                            @csrf
                            <div class="flex-grow">
                                <input type="text" name="comment" required placeholder="Type your comment here..." class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            </div>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold uppercase tracking-widest rounded-md hover:bg-indigo-700 transition shadow-sm">
                                Post
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 no-print">
                {{-- Movement Trail with Tabs --}}
                <div class="lg:col-span-2" x-data="{ activeTab: 'timeline' }">
                    <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-wrap items-center justify-between gap-4">
                            <h3 class="text-lg font-bold text-gray-900">Movement Trail</h3>
                            
                            <div class="flex p-1 bg-gray-200 rounded-lg">
                                <button @click="activeTab = 'timeline'" 
                                    :class="activeTab === 'timeline' ? 'bg-white shadow text-blue-800' : 'text-gray-600 hover:text-gray-900'"
                                    class="px-4 py-1.5 text-xs font-bold rounded-md transition-all duration-200">
                                    Timeline
                                </button>
                                <button @click="activeTab = 'attachments'" 
                                    :class="activeTab === 'attachments' ? 'bg-white shadow text-blue-800' : 'text-gray-600 hover:text-gray-900'"
                                    class="px-4 py-1.5 text-xs font-bold rounded-md transition-all duration-200 flex items-center">
                                    Attachments
                                    @php $attCount = $correspondence->movements->sum(fn($m) => $m->getMedia('attachments')->count()); @endphp
                                    @if($attCount > 0)
                                        <span class="ml-2 px-1.5 py-0.5 bg-blue-100 text-blue-800 rounded-full text-[10px]">{{ $attCount }}</span>
                                    @endif
                                </button>
                                <button @click="activeTab = 'comments'" 
                                    :class="activeTab === 'comments' ? 'bg-white shadow text-blue-800' : 'text-gray-600 hover:text-gray-900'"
                                    class="px-4 py-1.5 text-xs font-bold rounded-md transition-all duration-200 flex items-center">
                                    Comments
                                    @php $comCount = $correspondence->movements->sum(fn($m) => $m->comments->count()); @endphp
                                    @if($comCount > 0)
                                        <span class="ml-2 px-1.5 py-0.5 bg-gray-300 text-gray-800 rounded-full text-[10px]">{{ $comCount }}</span>
                                    @endif
                                </button>
                            </div>
                        </div>

                        <div class="p-6">
                            {{-- Timeline Tab --}}
                            <div x-show="activeTab === 'timeline'" class="space-y-0">
                                @if($correspondence->movements->count() > 0)
                                    <div class="relative">
                                        {{-- Vertical Connector Line --}}
                                        <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-gray-200"></div>

                                        <div class="space-y-8">
                                            @foreach($correspondence->movements->sortByDesc('created_at') as $movement)
                                                <div class="relative pl-12">
                                                    {{-- Timeline Pulse/Dot --}}
                                                    <div class="absolute left-[10px] top-1 w-3 h-3 rounded-full border-2 border-white ring-2 ring-offset-2 
                                                        {{ $movement->status === 'Pending' ? 'bg-yellow-400 ring-yellow-400' : '' }}
                                                        {{ $movement->status === 'Received' ? 'bg-blue-500 ring-blue-500' : '' }}
                                                        {{ $movement->status === 'Reviewed' ? 'bg-indigo-500 ring-indigo-500' : '' }}
                                                        {{ $movement->status === 'Actioned' ? 'bg-green-500 ring-green-500' : '' }}
                                                        shadow-sm">
                                                    </div>

                                                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden transition-all hover:shadow-md">
                                                        {{-- Header of Movement Card --}}
                                                        <div class="px-5 py-3 border-b border-gray-50 flex items-center justify-between
                                                            {{ $movement->status === 'Pending' ? 'bg-yellow-50/30' : '' }}
                                                            {{ $movement->status === 'Actioned' ? 'bg-green-50/30' : '' }}">
                                                            <div class="flex items-center gap-3">
                                                                <div class="hidden sm:flex w-8 h-8 rounded-full bg-blue-100 items-center justify-center text-blue-700 text-xs font-bold">
                                                                    {{ strtoupper(substr($movement->fromUser?->name ?? 'S', 0, 1)) }}
                                                                </div>
                                                                <div>
                                                                    <div class="text-xs font-bold text-gray-500 uppercase tracking-widest">
                                                                        {{ $movement->action }} <span class="mx-1 text-gray-300">|</span> Seq #{{ $movement->sequence }}
                                                                    </div>
                                                                    <div class="text-sm font-bold text-gray-900 leading-tight">
                                                                        {{ $movement->fromUser?->name ?? 'System' }}
                                                                        <span class="text-gray-400 font-normal mx-1">→</span>
                                                                        {{ $movement->toUser?->name ?? 'Everyone' }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="text-right">
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                                                    {{ $movement->status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                                    {{ $movement->status === 'Received' ? 'bg-blue-100 text-blue-800' : '' }}
                                                                    {{ $movement->status === 'Reviewed' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                                                    {{ $movement->status === 'Actioned' ? 'bg-green-100 text-green-800 border border-green-200' : '' }}">
                                                                    {{ $movement->status }}
                                                                </span>
                                                                <div class="text-[10px] text-gray-400 mt-1 font-medium">{{ $movement->created_at->format('d-M-Y H:i') }}</div>
                                                            </div>
                                                        </div>

                                                        {{-- Body of Movement Card --}}
                                                        <div class="p-5">
                                                            @if($movement->instructions)
                                                                <div class="flex gap-3 mb-4 last:mb-0">
                                                                    <div class="flex-shrink-0 mt-1">
                                                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                                        </svg>
                                                                    </div>
                                                                    <div class="bg-blue-50/50 p-3 rounded-lg border border-blue-100 text-sm italic text-gray-700 w-full">
                                                                        "{{ $movement->instructions }}"
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if($movement->comments->count() > 0)
                                                                <div class="space-y-3 mt-3">
                                                                    @foreach($movement->comments as $comment)
                                                                        <div class="flex gap-3">
                                                                            <div class="flex-shrink-0 mt-1">
                                                                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                                                                </svg>
                                                                            </div>
                                                                            <div class="text-sm bg-gray-50 p-3 rounded-lg border border-gray-100 w-full">
                                                                                <div class="flex items-center justify-between mb-1">
                                                                                    <span class="font-bold text-gray-900 text-xs">{{ $comment->user?->name }}</span>
                                                                                    <span class="text-[10px] text-gray-400">{{ $comment->created_at->format('d-M-Y H:i') }}</span>
                                                                                </div>
                                                                                <p class="text-gray-700 leading-relaxed">{{ $comment->comment }}</p>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif

                                                            {{-- Status specific footer actions --}}
                                                            @if($movement->isPending() && $movement->to_user_id === auth()->id())
                                                                <div class="mt-4 pt-4 border-t border-gray-50 flex gap-2">
                                                                    <form method="POST" action="{{ route('correspondence.movement.update', $correspondence) }}">
                                                                        @csrf
                                                                        <input type="hidden" name="movement_id" value="{{ $movement->id }}">
                                                                        <input type="hidden" name="action" value="receive">
                                                                        <button type="submit" class="text-[10px] font-bold uppercase tracking-widest px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition shadow-sm">
                                                                            Mark Received
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            @elseif($movement->status === 'Received' && $movement->to_user_id === auth()->id())
                                                                <div class="mt-4 pt-4 border-t border-gray-50 flex gap-2">
                                                                    <form method="POST" action="{{ route('correspondence.movement.update', $correspondence) }}">
                                                                        @csrf
                                                                        <input type="hidden" name="movement_id" value="{{ $movement->id }}">
                                                                        <input type="hidden" name="action" value="review">
                                                                        <button type="submit" class="text-[10px] font-bold uppercase tracking-widest px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition shadow-sm">
                                                                            Mark Reviewed
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-12">
                                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 font-medium">No movements recorded yet.</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Attachments Tab --}}
                            <div x-show="activeTab === 'attachments'" style="display: none;">
                                @php $hasMovementAttachments = false; @endphp
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($correspondence->movements as $movement)
                                        @if($movement->getMedia('attachments')->count() > 0)
                                            @php $hasMovementAttachments = true; @endphp
                                            @foreach($movement->getMedia('attachments') as $media)
                                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100 hover:bg-gray-100 transition">
                                                    <div class="flex items-center min-w-0">
                                                        <div class="p-2 bg-blue-100 rounded mr-3">
                                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                            </svg>
                                                        </div>
                                                        <div class="truncate">
                                                            <p class="text-xs font-bold text-gray-900 truncate">{{ $media->file_name }}</p>
                                                            <p class="text-[10px] text-gray-500 font-medium uppercase">From Movement #{{ $movement->sequence }}</p>
                                                        </div>
                                                    </div>
                                                    <a href="{{ $media->getUrl() }}" target="_blank" class="ml-2 p-1.5 text-blue-600 hover:bg-blue-200 rounded-full transition">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </div>
                                @if(!$hasMovementAttachments)
                                    <div class="text-center py-8">
                                        <p class="text-gray-400 text-sm italic">No attachments found in movements.</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Comments Tab --}}
                            <div x-show="activeTab === 'comments'" style="display: none;">
                                @php $hasMovementComments = false; @endphp
                                <div class="space-y-4">
                                    @foreach($correspondence->movements as $movement)
                                        @if($movement->comments->count() > 0)
                                            @php $hasMovementComments = true; @endphp
                                            @foreach($movement->comments as $comment)
                                                <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                                                    <div class="flex justify-between items-start mb-2">
                                                        <div class="flex items-center">
                                                            <div class="w-6 h-6 rounded-full bg-blue-800 flex items-center justify-center text-[10px] text-white font-bold mr-2">
                                                                {{ strtoupper(substr($comment->user?->name ?? 'U', 0, 1)) }}
                                                            </div>
                                                            <span class="text-xs font-bold text-gray-900">{{ $comment->user?->name ?? 'Unknown' }}</span>
                                                        </div>
                                                        <span class="text-[10px] text-gray-400">{{ $comment->created_at->format('d-M-Y H:i') }}</span>
                                                    </div>
                                                    <p class="text-sm text-gray-700 leading-relaxed">{{ $comment->comment }}</p>
                                                    <div class="mt-2 text-[10px] text-blue-600 font-bold uppercase tracking-widest">On Movement #{{ $movement->sequence }}</div>
                                                </div>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </div>
                                @if(!$hasMovementComments)
                                    <div class="text-center py-8">
                                        <p class="text-gray-400 text-sm italic">No comments found in movements.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar: Attachments & Related --}}
                <div class="space-y-6">
                    {{-- Attachments Card --}}
                    <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-bold text-gray-900">Main Attachments</h3>
                        </div>
                        <div class="p-6">
                            @if($correspondence->getMedia('attachments')->count() > 0)
                                <ul class="space-y-3">
                                    @foreach($correspondence->getMedia('attachments') as $media)
                                        <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100 hover:bg-gray-100 transition">
                                            <div class="flex items-center min-w-0">
                                                <div class="p-2 bg-blue-100 rounded mr-3">
                                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <div class="truncate">
                                                    <p class="text-sm font-bold text-gray-900 truncate">{{ $media->file_name }}</p>
                                                    <p class="text-[10px] text-gray-500 font-medium uppercase">{{ number_format($media->size / 1024, 1) }} KB</p>
                                                </div>
                                            </div>
                                            <a href="{{ $media->getUrl() }}" target="_blank"
                                               class="ml-4 p-2 text-blue-600 hover:bg-blue-200 rounded-full transition"
                                               title="Download">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-center py-6">
                                    <p class="text-gray-400 text-sm italic">No attachments uploaded.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Related Info Card --}}
                    @if($correspondence->parent || $correspondence->replies->count() > 0)
                        <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h3 class="text-lg font-bold text-gray-900">Related Correspondence</h3>
                            </div>
                            <div class="p-6">
                                @if($correspondence->parent)
                                    <div class="mb-4">
                                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block mb-2">Parent Document:</span>
                                        <a href="{{ route('correspondence.show', $correspondence->parent) }}" 
                                           class="flex items-center p-2 bg-blue-50 border border-blue-100 rounded text-sm text-blue-700 font-bold hover:bg-blue-100 transition">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                            </svg>
                                            {{ $correspondence->parent->register_number }}
                                        </a>
                                    </div>
                                @endif

                                @if($correspondence->replies->count() > 0)
                                    <div>
                                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block mb-2">Replies / Follow-ups:</span>
                                        <ul class="space-y-2">
                                            @foreach($correspondence->replies as $reply)
                                                <li>
                                                    <a href="{{ route('correspondence.show', $reply) }}" 
                                                       class="flex items-center p-2 bg-green-50 border border-green-100 rounded text-sm text-green-700 font-bold hover:bg-green-100 transition">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                                        </svg>
                                                        {{ $reply->register_number }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>

    {{-- Mark To Modal --}}
    <div id="mark-modal" class="no-print hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-6 border w-full max-w-3xl shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-900">Mark Correspondence</h3>
                <button type="button" onclick="document.getElementById('mark-modal').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('correspondence.mark', $correspondence) }}" enctype="multipart/form-data">
                @csrf

                {{-- Row 1: Mark To & Action --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <x-label for="to_user_id" value="Mark To" :required="true" />
                        <select id="to_user_id" name="to_user_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Select Person</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label for="action" value="Action" :required="true" />
                        <select id="action" name="action" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="Mark">Mark</option>
                            <option value="Forward">Forward</option>
                            <option value="ForInfo">For Information</option>
                            <option value="ForAction">For Action</option>
                            <option value="ForApproval">For Approval</option>
                            <option value="ForSignature">For Signature</option>
                            <option value="ForComments">For Comments</option>
                            <option value="ForReview">For Review</option>
                            <option value="ForReply">For Reply</option>
                            <option value="Return">Return</option>
                        </select>
                    </div>
                </div>

                {{-- Row 2: Expected Response Date --}}
                <div class="mb-4">
                    <x-label for="expected_response_date" value="Expected Response Date" />
                    <x-input id="expected_response_date" type="date" name="expected_response_date" class="mt-1 block w-full" />
                </div>

                {{-- Row 3: Instructions (Full Width) --}}
                <div class="mb-4">
                    <x-label for="instructions" value="Instructions" />
                    <textarea id="instructions" name="instructions" rows="3"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        placeholder="Any specific instructions..."></textarea>
                </div>

                {{-- Row 4: Attachments (Full Width) --}}
                <div class="mb-4">
                    <x-label for="mark_attachments" value="Attach Files (Optional)" />
                    <input type="file" id="mark_attachments" name="attachments[]" multiple
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    <p class="text-xs text-gray-500 mt-1">You can upload multiple files. Max 15MB per file.</p>
                </div>

                {{-- Row 5: Urgent Checkbox --}}
                <div class="mb-6">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_urgent" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <span class="ml-2 text-sm font-medium text-gray-700">Mark as Urgent</span>
                    </label>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <button type="button" onclick="document.getElementById('mark-modal').classList.add('hidden')"
                            class="px-5 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Submit
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
