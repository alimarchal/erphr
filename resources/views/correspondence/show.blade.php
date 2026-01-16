<x-app-layout>
    <x-slot name="header">
        <x-page-header
            :title="$correspondence->register_number"
            :backUrl="route('correspondence.index', ['type' => $correspondence->type])"
            :showSearch="false"
            :editUrl="auth()->user()->can('edit correspondence') ? route('correspondence.edit', $correspondence) : null"
            showPrint
        />
    </x-slot>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            /* ===== HIDE BANNER, NAVIGATION, AND HEADER ===== */
            /* Hide Jetstream banner (first div under body with x-data containing icons) */
            body > div:first-child {
                display: none !important;
            }

            /* Hide navigation menu */
            nav,
            nav[wire\:id],
            [wire\:snapshot],
            .min-h-screen > nav {
                display: none !important;
            }

            /* Hide page header (with purple buttons) */
            header,
            .min-h-screen > header,
            header.bg-white {
                display: none !important;
            }

            /* Hide all SVGs in header/nav areas (icons) */
            body > div:first-child svg,
            nav svg,
            header svg {
                display: none !important;
            }

            /* Reset min-h-screen container */
            .min-h-screen {
                min-height: auto !important;
                background: white !important;
            }

            /* ===== BODY AND GENERAL STYLES ===== */
            body {
                background-color: white !important;
                color: black !important;
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
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

            .py-6, .py-12, .p-6, .p-8, .px-4, .px-6, .px-8, .sm\:px-6, .lg\:px-8, .p-2 {
                padding: 0 !important;
            }

            .mb-6, .mb-8, .mb-4 {
                margin-bottom: 0.5rem !important;
            }

            /* Remove all colors for laser printing */
            .bg-blue-50, .bg-yellow-50, .bg-green-50, .bg-gray-50, .bg-purple-50, .bg-orange-50, .bg-indigo-50, .bg-teal-50,
            .bg-red-50, .bg-blue-100, .bg-gray-100, .bg-red-600, .bg-purple-600, .bg-indigo-500, .bg-indigo-600 {
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

            /* Remove all borders from sections in print */
            .bg-white[class*="shadow"] {
                border: none !important;
                border-bottom: none !important;
            }

            /* Table print styles */
            .info-table {
                width: 100% !important;
                page-break-inside: auto;
                table-layout: fixed;
                font-size: 9pt !important;
            }

            .info-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .info-table th, .info-table td {
                border: 1px solid black !important;
                padding: 3px 4px !important;
                font-size: 9pt !important;
                word-wrap: break-word;
                overflow-wrap: break-word;
            }

            .info-table th {
                font-size: 8pt !important;
            }

            .section-header {
                font-size: 10pt !important;
                padding: 3px !important;
            }

            .animate-pulse {
                animation: none !important;
            }

            /* Scale content to fit */
            .info-table td[colspan="6"] {
                font-size: 9pt !important;
            }

            /* Print Activity Section */
            .print-section {
                display: block !important;
                page-break-before: auto;
                margin-top: 1rem !important;
            }

            .print-section-title {
                font-size: 11pt !important;
                font-weight: bold !important;
                border-bottom: 2px solid black !important;
                padding-bottom: 4px !important;
                margin-bottom: 8px !important;
            }

            .print-timeline-item {
                border: 1px solid #ccc !important;
                padding: 6px !important;
                margin-bottom: 6px !important;
                page-break-inside: avoid;
                font-size: 9pt !important;
            }

            .print-comment-item {
                border: 1px solid #ccc !important;
                padding: 6px !important;
                margin-bottom: 4px !important;
                font-size: 9pt !important;
            }

            .max-h-\[500px\], .max-h-\[400px\] {
                max-height: none !important;
                overflow: visible !important;
            }

            [x-show] {
                display: block !important;
            }

            /* Hide Update Status, Add Comment, and Mark tabs in print */
            [x-show="activeTab === 'status'"],
            [x-show="activeTab === 'addComment'"],
            [x-show="activeTab === 'mark'"] {
                display: none !important;
            }

            .screen-only {
                display: none !important;
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
            text-align: left;
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

        .content-cell {
            text-align: left !important;
        }

        @media print {
            .section-header {
                background-color: transparent !important;
                color: black !important;
            }

            /* ===== PRINT HEADER STYLES ===== */
            .print-header {
                text-align: center;
                margin-bottom: 0px;
                padding: 5px 0;
                page-break-after: avoid;
            }

            .print-header img {
                max-width: 100px;
                height: auto;
                margin-bottom: 5px;
            }

            .print-header-title {
                font-size: 16pt;
                font-weight: bold;
                margin-bottom: 4px;
            }

            .print-header-subtitle {
                font-size: 12pt;
                font-weight: 600;
                margin-bottom: 4px;
            }

            .print-header-meta {
                font-size: 9pt;
                color: #333;
                margin-top: 3px;
            }
        }

        /* Screen styles for header */
        .print-header {
            text-align: center;
            margin-bottom: 2px;
            padding: 5px 0;
        }

        .print-header img {
            max-width: 100px;
            height: auto;
            margin-bottom: 10px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .print-header-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 4px;
            color: #1f2937;
        }

        .print-header-subtitle {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 4px;
            color: #374151;
        }

        .print-header-meta {
            font-size: 12px;
            color: #6b7280;
            margin-top: 8px;
        }

        @media print {
            .print-header {
                text-align: center;
                margin-bottom: 5px;
                padding: 5px 0;
                page-break-after: avoid;
            }

            .print-header-title {
                font-size: 16pt;
            }

            .print-header-subtitle {
                font-size: 12pt;
            }

            .print-header-meta {
                font-size: 9pt;
                color: #333;
            }
        }

        /* Screen: Hide print-only elements */
        .print-only {
            display: block;
        }

        @media print {
            .print-only {
                display: block !important;
            }

            .print-header {
                display: block !important;
            }
        }
    </style>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <x-status-message class="mb-4" />

            <!-- Correspondence Information Section -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                            <!-- Print & Screen Header -->

            <div class="print-header">
                <div style="text-align: center;">
                    <img src="{{ asset('icons-images/logo.png') }}" alt="Logo" style="width:100px;height:auto;display:block;margin:0 auto 5px;">
                    <div>
                        <div class="print-header-title">The Bank of Azad Jammu & Kashmir</div>
                        <div class="print-header-subtitle">{{ $correspondence->toDivision?->name ?? $correspondence->fromDivision?->name ?? 'Division' }}</div>
                        <div class="print-header-meta hidden print:block">
                            UUID: {{ $correspondence->id }} | Printed: {{ now()->format('d-M-Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

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
                                <td>{{ $correspondence->fromDivision?->short_name ?? $correspondence->fromDivision?->name ?? 'N/A' }}</td>
                                <th>Region</th>
                                <td>{{ $correspondence->region?->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Branch</th>
                                <td>{{ $correspondence->branch?->name ?? 'N/A' }}</td>
                                <th>Marked To</th>
                                <td>{{ $correspondence->markedTo?->name ?? 'N/A' }}</td>
                                <th>Marked To Designation</th>
                                <td>{{ $correspondence->markedTo?->designation ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Addressed To</th>
                                <td>{{ $correspondence->addressedTo?->name ?? 'N/A' }}</td>
                                <th>Addressed To Designation</th>
                                <td>{{ $correspondence->addressedTo?->designation ?? 'N/A' }}</td>
                                <th>To Division (Internal)</th>
                                <td>{{ $correspondence->toDivision?->short_name ?? $correspondence->toDivision?->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Current Holder</th>
                                <td>{{ $correspondence->currentHolder?->name ?? 'Not assigned' }}</td>
                                <th>Since</th>
                                <td>{{ $correspondence->current_holder_since?->format('d-M-Y H:i') ?? 'N/A' }}</td>
                                <th>Due Date</th>
                                <td>{{ $correspondence->due_date?->format('d-M-Y') ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Days Open</th>
                                <td>{{ $correspondence->days_open ?? 0 }} days</td>
                                <th>Status</th>
                                <td>{{ $correspondence->status?->name ?? 'N/A' }}</td>
                                <th>Priority</th>
                                <td>{{ $correspondence->priority?->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Confidentiality</th>
                                <td colspan="5">{{ $correspondence->confidentiality ?? 'Normal' }}</td>
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
                                <td>{{ $correspondence->creator?->name ?? 'N/A' }}</td>
                                <th>Created At</th>
                                <td>{{ $correspondence->created_at?->format('d-M-Y H:i') ?? 'N/A' }}</td>
                                <th>Last Updated By</th>
                                <td>{{ $correspondence->updater?->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated At</th>
                                <td colspan="5">{{ $correspondence->updated_at?->format('d-M-Y H:i') ?? 'N/A' }}</td>
                            </tr>

                            {{-- Subject Section --}}
                            <tr>
                                <th colspan="6" class="section-header" style="text-align: left!important;">Subject</th>
                            </tr>
                            <tr>
                                <td colspan="6" class="content-cell" style="padding: 12px;"><strong>{{ $correspondence->subject ?? 'N/A' }}</strong></td>
                            </tr>

                            {{-- Description Section --}}
                            <tr>
                                <th colspan="6" class="section-header" style="text-align: left!important;">Description</th>
                            </tr>
                            <tr>
                                <td colspan="6" class="content-cell" style="padding: 12px; line-height: 1.6;">{{ $correspondence->description ?? 'N/A' }}</td>
                            </tr>

                            {{-- Remarks Section --}}
                            @if($correspondence->remarks)
                            <tr>
                                <th colspan="6" class="section-header" style="text-align: left!important;">Remarks</th>
                            </tr>
                            <tr>
                                <td colspan="6" class="content-cell" style="padding: 12px; font-style: italic;">{{ $correspondence->remarks }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
            </div>

            <!-- Activity & Actions Section -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6" x-data="{ activeTab: 'timeline' }">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-6 pb-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 print-section-title">Activity & Actions</h3>
                    
                    <div class="flex flex-wrap p-1 bg-gray-200 rounded-lg gap-1 no-print">
                        <button @click="activeTab = 'timeline'" 
                            :class="activeTab === 'timeline' ? 'bg-white shadow text-blue-800' : 'text-gray-600 hover:text-gray-900'"
                            class="px-3 py-1.5 text-xs font-bold rounded-md transition-all duration-200">
                            Timeline
                        </button>
                        <button @click="activeTab = 'attachments'" 
                            :class="activeTab === 'attachments' ? 'bg-white shadow text-blue-800' : 'text-gray-600 hover:text-gray-900'"
                            class="px-3 py-1.5 text-xs font-bold rounded-md transition-all duration-200 flex items-center">
                            Attachments
                            @php 
                                $mainAttCount = $correspondence->getMedia('attachments')->count();
                                $movAttCount = $correspondence->movements->sum(fn($m) => $m->getMedia('attachments')->count());
                                $totalAttCount = $mainAttCount + $movAttCount;
                            @endphp
                            @if($totalAttCount > 0)
                                <span class="ml-1.5 px-1.5 py-0.5 bg-blue-100 text-blue-800 rounded-full text-[10px]">{{ $totalAttCount }}</span>
                            @endif
                        </button>
                        <button @click="activeTab = 'comments'" 
                            :class="activeTab === 'comments' ? 'bg-white shadow text-blue-800' : 'text-gray-600 hover:text-gray-900'"
                            class="px-3 py-1.5 text-xs font-bold rounded-md transition-all duration-200 flex items-center">
                            Comments
                            @php $comCount = $correspondence->movements->sum(fn($m) => $m->comments->count()); @endphp
                            @if($comCount > 0)
                                <span class="ml-1.5 px-1.5 py-0.5 bg-gray-300 text-gray-800 rounded-full text-[10px]">{{ $comCount }}</span>
                            @endif
                        </button>
                        <button @click="activeTab = 'status'" 
                            :class="activeTab === 'status' ? 'bg-white shadow text-green-800' : 'text-gray-600 hover:text-gray-900'"
                            class="px-3 py-1.5 text-xs font-bold rounded-md transition-all duration-200">
                            Update Status
                        </button>
                        <button @click="activeTab = 'mark'" 
                            :class="activeTab === 'mark' ? 'bg-white shadow text-emerald-800' : 'text-gray-600 hover:text-gray-900'"
                            class="px-3 py-1.5 text-xs font-bold rounded-md transition-all duration-200">
                            Mark To
                        </button>
                        <button @click="activeTab = 'addComment'" 
                            :class="activeTab === 'addComment' ? 'bg-white shadow text-indigo-800' : 'text-gray-600 hover:text-gray-900'"
                            class="px-3 py-1.5 text-xs font-bold rounded-md transition-all duration-200">
                            Add Comment
                        </button>
                        @if($correspondence->parent || $correspondence->replies->count() > 0)
                        <button @click="activeTab = 'related'" 
                            :class="activeTab === 'related' ? 'bg-white shadow text-purple-800' : 'text-gray-600 hover:text-gray-900'"
                            class="px-3 py-1.5 text-xs font-bold rounded-md transition-all duration-200">
                            Related
                        </button>
                        @endif
                    </div>
                </div>

                <div class="p-6">
                    {{-- Timeline Tab --}}
                    <div x-show="activeTab === 'timeline'" class="space-y-0 print-section">
                        <h4 class="hidden print:block font-bold text-sm mb-2 border-b border-black pb-1">MOVEMENT TIMELINE</h4>
                        @if($correspondence->movements->count() > 0)
                            <div class="relative max-h-[500px] overflow-y-auto">
                                <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-gray-200 no-print"></div>
                                <div class="space-y-6 print:space-y-2">
                                    @foreach($correspondence->movements->sortByDesc('created_at') as $movement)
                                        <div class="relative pl-12 print:pl-0 print-timeline-item">
                                            <div class="absolute left-[10px] top-1 w-3 h-3 rounded-full border-2 border-white ring-2 ring-offset-2 no-print
                                                {{ $movement->status === 'Pending' ? 'bg-yellow-400 ring-yellow-400' : '' }}
                                                {{ $movement->status === 'Received' ? 'bg-blue-500 ring-blue-500' : '' }}
                                                {{ $movement->status === 'Reviewed' ? 'bg-indigo-500 ring-indigo-500' : '' }}
                                                {{ $movement->status === 'Actioned' ? 'bg-green-500 ring-green-500' : '' }}
                                                shadow-sm">
                                            </div>
                                            <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden print:shadow-none print:rounded-none">
                                                <div class="px-4 py-2 border-b border-gray-50 flex items-center justify-between
                                                    {{ $movement->status === 'Pending' ? 'bg-yellow-50/30' : '' }}
                                                    {{ $movement->status === 'Actioned' ? 'bg-green-50/30' : '' }}">
                                                    <div class="flex items-center gap-2">
                                                        <div class="hidden sm:flex w-7 h-7 rounded-full bg-blue-100 items-center justify-center text-blue-700 text-xs font-bold">
                                                            {{ strtoupper(substr($movement->fromUser?->name ?? 'S', 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                                                                {{ $movement->action }} | Seq #{{ $movement->sequence }}
                                                            </div>
                                                            <div class="text-sm font-bold text-gray-900 leading-tight">
                                                                {{ $movement->fromUser?->name ?? 'System' }}
                                                                <span class="text-gray-400 font-normal mx-1">→</span>
                                                                {{ $movement->toUser?->name ?? 'Everyone' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                                            {{ $movement->status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                            {{ $movement->status === 'Received' ? 'bg-blue-100 text-blue-800' : '' }}
                                                            {{ $movement->status === 'Reviewed' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                                            {{ $movement->status === 'Actioned' ? 'bg-green-100 text-green-800' : '' }}">
                                                            {{ $movement->status }}
                                                        </span>
                                                        <div class="text-[10px] text-gray-400 mt-0.5">{{ $movement->created_at->format('d-M-Y H:i') }}</div>
                                                    </div>
                                                </div>
                                                @if($movement->instructions)
                                                <div class="px-4 py-2 bg-blue-50/50 border-t border-blue-100 text-sm italic text-gray-700">
                                                    "{{ $movement->instructions }}"
                                                </div>
                                                @endif
                                                @if($movement->isPending() && $movement->to_user_id === auth()->id())
                                                <div class="px-4 py-2 border-t border-gray-50">
                                                    <form method="POST" action="{{ route('correspondence.movement.update', $correspondence) }}" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="movement_id" value="{{ $movement->id }}">
                                                        <input type="hidden" name="action" value="receive">
                                                        <button type="submit" class="text-[10px] font-bold uppercase px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                                            Mark Received
                                                        </button>
                                                    </form>
                                                </div>
                                                @elseif($movement->status === 'Received' && $movement->to_user_id === auth()->id())
                                                <div class="px-4 py-2 border-t border-gray-50">
                                                    <form method="POST" action="{{ route('correspondence.movement.update', $correspondence) }}" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="movement_id" value="{{ $movement->id }}">
                                                        <input type="hidden" name="action" value="review">
                                                        <button type="submit" class="text-[10px] font-bold uppercase px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                                            Mark Reviewed
                                                        </button>
                                                    </form>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-400 text-sm italic">No movements recorded yet.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Attachments Tab (Combined Main + Movement) --}}
                    <div x-show="activeTab === 'attachments'" style="display: none;" class="print-section">
                        <h4 class="hidden print:block font-bold text-sm mb-2 border-b border-black pb-1">ATTACHMENTS</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 max-h-[400px] overflow-y-auto print:max-h-none print:overflow-visible print:grid-cols-2">
                            @if($correspondence->getMedia('attachments')->count() > 0)
                                @foreach($correspondence->getMedia('attachments') as $media)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100 hover:bg-gray-100 transition print:p-1 print:bg-white print:rounded-none print:border-gray-300">
                                        <div class="flex items-center min-w-0">
                                            <div class="p-2 bg-blue-100 rounded mr-3 no-print">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div class="truncate">
                                                <p class="text-xs font-bold text-gray-900 truncate print:text-[9pt]">{{ $media->file_name }}</p>
                                                <p class="text-[10px] text-blue-600 font-medium uppercase print:text-[8pt] print:text-black">Main Attachment</p>
                                            </div>
                                        </div>
                                        <a href="{{ $media->getUrl() }}" target="_blank" class="ml-2 p-1.5 text-blue-600 hover:bg-blue-200 rounded-full transition no-print">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                            @foreach($correspondence->movements as $movement)
                                @foreach($movement->getMedia('attachments') as $media)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100 hover:bg-gray-100 transition print:p-1 print:bg-white print:rounded-none print:border-gray-300">
                                        <div class="flex items-center min-w-0">
                                            <div class="p-2 bg-green-100 rounded mr-3 no-print">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                </svg>
                                            </div>
                                            <div class="truncate">
                                                <p class="text-xs font-bold text-gray-900 truncate print:text-[9pt]">{{ $media->file_name }}</p>
                                                <p class="text-[10px] text-green-600 font-medium uppercase print:text-[8pt] print:text-black">Movement #{{ $movement->sequence }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ $media->getUrl() }}" target="_blank" class="ml-2 p-1.5 text-blue-600 hover:bg-blue-200 rounded-full transition no-print">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                        @if($totalAttCount === 0)
                            <div class="text-center py-8">
                                <p class="text-gray-400 text-sm italic">No attachments found.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Comments Tab --}}
                    <div x-show="activeTab === 'comments'" style="display: none;" class="print-section">
                        <h4 class="hidden print:block font-bold text-sm mb-2 border-b border-black pb-1">COMMENTS</h4>
                        <div class="space-y-3 max-h-[400px] overflow-y-auto print:max-h-none print:overflow-visible print:space-y-1">
                            @php $hasComments = false; @endphp
                            @foreach($correspondence->movements as $movement)
                                @foreach($movement->comments as $comment)
                                    @php $hasComments = true; @endphp
                                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 print:p-1 print:bg-white print:rounded-none print:border-gray-300 print-comment-item">
                                        <div class="flex justify-between items-start mb-1">
                                            <div class="flex items-center">
                                                <div class="w-6 h-6 rounded-full bg-blue-800 flex items-center justify-center text-[10px] text-white font-bold mr-2 no-print">
                                                    {{ strtoupper(substr($comment->user?->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <span class="text-xs font-bold text-gray-900 print:text-[9pt]">{{ $comment->user?->name ?? 'Unknown' }}</span>
                                            </div>
                                            <span class="text-[10px] text-gray-400 print:text-[8pt] print:text-black">{{ $comment->created_at->format('d-M-Y H:i') }}</span>
                                        </div>
                                        <p class="text-sm text-gray-700 ml-8 print:ml-0 print:text-[9pt] print:text-black">{{ $comment->comment }}</p>
                                        <div class="mt-1 ml-8 print:ml-0 text-[10px] text-blue-600 font-bold uppercase print:text-[8pt] print:text-black">Movement #{{ $movement->sequence }}</div>
                                    </div>
                                @endforeach
                            @endforeach
                            @if(!$hasComments)
                                <div class="text-center py-8">
                                    <p class="text-gray-400 text-sm italic">No comments found.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Mark To Tab (inline form, previously modal) --}}
                    <div x-show="activeTab === 'mark'" style="display: none;" class="no-print">
                        <form method="POST" action="{{ route('correspondence.mark', $correspondence) }}" enctype="multipart/form-data">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <x-label for="to_user_id" value="Mark To" :required="true" />
                                    <select id="to_user_id" name="to_user_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">Select Person</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}{{ $user->designation ? " ({$user->designation})" : '' }}</option>
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

                            <div class="mb-4">
                                <x-label for="expected_response_date" value="Expected Response Date" />
                                <x-input id="expected_response_date" type="date" name="expected_response_date" class="mt-1 block w-full" />
                            </div>

                            <div class="mb-4">
                                <x-label for="instructions" value="Instructions" />
                                <textarea id="instructions" name="instructions" rows="3"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    placeholder="Any specific instructions..."></textarea>
                            </div>

                            <div class="mb-4">
                                <x-label for="mark_attachments" value="Attach Files (Optional)" />
                                <input type="file" id="mark_attachments" name="attachments[]" multiple
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                                <p class="text-xs text-gray-500 mt-1">You can upload multiple files. Max 15MB per file.</p>
                            </div>

                            <div class="mb-6">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_urgent" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    <span class="ml-2 text-sm font-medium text-gray-700">Mark as Urgent</span>
                                </label>
                            </div>

                            <div class="flex justify-end space-x-3 pt-4 border-t">
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

                    {{-- Update Status Tab --}}
                    <div x-show="activeTab === 'status'" style="display: none;" class="no-print">
                        <form action="{{ route('correspondence.status.update', $correspondence) }}" method="POST">
                            @csrf
                            <div class="flex flex-wrap items-end gap-3">
                                <div class="flex-grow min-w-[200px]">
                                    <x-label for="quick_status_id" value="Select Status" class="text-xs font-semibold text-gray-500 mb-1" />
                                    <select id="quick_status_id" name="status_id" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <option value="">Choose status...</option>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}" {{ $correspondence->status_id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex-grow min-w-[200px]">
                                    <x-label for="status_remarks" value="Note (optional)" class="text-xs font-semibold text-gray-500 mb-1" />
                                    <input type="text" id="status_remarks" name="remarks" placeholder="Reason for status change..." class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                </div>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs font-bold uppercase tracking-widest rounded-md hover:bg-blue-700 transition shadow-sm h-[38px]">
                                    Update Status
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Add Comment Tab --}}
                    <div x-show="activeTab === 'addComment'" style="display: none;" class="no-print">
                        <form action="{{ route('correspondence.comment.add', $correspondence) }}" method="POST" class="flex gap-3">
                            @csrf
                            <div class="flex-grow">
                                <input type="text" name="comment" required placeholder="Type your comment here..." class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            </div>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold uppercase tracking-widest rounded-md hover:bg-indigo-700 transition shadow-sm">
                                Post Comment
                            </button>
                        </form>
                    </div>

                    {{-- Related Tab --}}
                    @if($correspondence->parent || $correspondence->replies->count() > 0)
                    <div x-show="activeTab === 'related'" style="display: none;" class="print-section">
                        <h4 class="hidden print:block font-bold text-sm mb-2 border-b border-black pb-1">RELATED DOCUMENTS</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 print:grid-cols-2 print:gap-2">
                            @if($correspondence->parent)
                                <div>
                                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block mb-2 print:text-[8pt] print:text-black">Parent Document:</span>
                                    <a href="{{ route('correspondence.show', $correspondence->parent) }}" 
                                       class="flex items-center p-3 bg-blue-50 border border-blue-100 rounded-lg text-sm text-blue-700 font-bold hover:bg-blue-100 transition print:p-1 print:bg-white print:border-gray-300 print:rounded-none print:text-[9pt] print:text-black">
                                        <svg class="w-4 h-4 mr-2 no-print" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                        </svg>
                                        {{ $correspondence->parent->register_number }}
                                    </a>
                                </div>
                            @endif
                            @if($correspondence->replies->count() > 0)
                                <div>
                                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block mb-2 print:text-[8pt] print:text-black">Replies / Follow-ups:</span>
                                    <div class="space-y-2 print:space-y-1">
                                        @foreach($correspondence->replies as $reply)
                                            <a href="{{ route('correspondence.show', $reply) }}" 
                                               class="flex items-center p-3 bg-green-50 border border-green-100 rounded-lg text-sm text-green-700 font-bold hover:bg-green-100 transition print:p-1 print:bg-white print:border-gray-300 print:rounded-none print:text-[9pt] print:text-black">
                                                <svg class="w-4 h-4 mr-2 no-print" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                                </svg>
                                                {{ $reply->register_number }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
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
                                <option value="{{ $user->id }}">{{ $user->name }}{{ $user->designation ? " ({$user->designation})" : '' }}</option>
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
