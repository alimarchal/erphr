<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('User-wise Correspondence Summary') }}
            </h2>
            <div class="flex items-center space-x-3 no-print">
                <button onclick="window.print()"
                    class="flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </button>
                <button onclick="downloadPDF('User-wise Correspondence Summary')"
                    class="flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download
                </button>
                <a href="{{ route('correspondence.reports.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <style>
                .report-table {
                    width: 100%;
                    border-collapse: collapse;
                    border: 2px solid black;
                    font-size: 12px;
                }

                .report-table th,
                .report-table td {
                    border: 1px solid black;
                    padding: 8px;
                    text-align: center;
                }

                .report-table th {
                    background-color: #f3f4f6;
                    font-weight: bold;
                    text-transform: uppercase;
                }

                @media print {
                    @page {
                        size: A4 portrait;
                        margin: 1cm;
                    }

                    .no-print {
                        display: none !important;
                    }

                    .max-w-7xl,
                    .max-w-2xl {
                        max-width: 100% !important;
                        width: 100% !important;
                        margin: 0 !important;
                        padding: 0 !important;
                    }

                    body {
                        background-color: white !important;
                        margin: 0 !important;
                        padding: 0 !important;
                    }

                    .p-6,
                    .py-6,
                    .px-6,
                    .sm\:px-6,
                    .lg\:px-8 {
                        padding: 0 !important;
                        margin: 0 !important;
                    }

                    .report-table {
                        font-size: 8px !important;
                        width: 100% !important;
                    }

                    .report-table span {
                        font-size: 7px !important;
                    }

                    .shadow-xl,
                    .shadow,
                    .bg-white,
                    .sm\:rounded-lg {
                        box-shadow: none !important;
                        background: transparent !important;
                        border: none !important;
                    }
                }

                .report-table th,
                .report-table td {
                    padding: 4px !important;
                }
            </style>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="text-center mb-8 border-b-2 border-transparent print:border-black pb-4">
                    <h2 class="text-xl font-bold text-gray-800 print:text-black">{{ config('app.name') }}</h2>
                    <h1
                        class="text-2xl font-bold uppercase underline mt-2 print:text-black print:no-underline print:text-xl">
                        User-wise Status & Workload Report</h1>
                    <p class="text-sm text-gray-600 mt-2 italic max-w-2xl mx-auto print:text-black print:mt-1">
                        This report provides a comprehensive summary of correspondences addressed to, marked to, and
                        currently held by each user.
                        It includes a detailed breakdown of statuses for all correspondences currently in a user's
                        possession.
                    </p>
                    <p class="text-xs text-gray-500 mt-4 print:text-black print:text-sm print:mt-2">Report Generated on:
                        {{ now()->format('d-M-Y h:i A') }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th rowspan="2">Sr#</th>
                                <th rowspan="2" class="text-left">User Name / Designation</th>
                                <th rowspan="2">Addressed To</th>
                                <th rowspan="2">Marked To</th>
                                <th rowspan="2">Current Holder</th>
                                <th colspan="{{ $statuses->count() }}">Status Breakdown (Currently Held)</th>
                            </tr>
                            <tr>
                                @foreach($statuses as $status)
                                    <th>{{ $status->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="text-left font-bold">
                                        {{ $user->name }}<br>
                                        <span
                                            class="text-[10px] text-gray-500 font-normal italic">{{ $user->designation ?? 'No Designation' }}</span>
                                    </td>
                                    <td>{{ $addressedCounts[$user->id] ?? 0 }}</td>
                                    <td>{{ $markedCounts[$user->id] ?? 0 }}</td>
                                    <td class="bg-blue-50 font-bold">{{ $heldCounts[$user->id] ?? 0 }}</td>
                                    @foreach($statuses as $status)
                                        @php
                                            $count = $statusCounts->get($user->id)?->where('status_id', $status->id)->first()?->total ?? 0;
                                        @endphp
                                        <td>{{ $count }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-100 font-bold">
                            <tr>
                                <td colspan="2">TOTAL</td>
                                <td>{{ $addressedCounts->sum() }}</td>
                                <td>{{ $markedCounts->sum() }}</td>
                                <td>{{ $heldCounts->sum() }}</td>
                                @foreach($statuses as $status)
                                    <td>
                                        {{ $statusCounts->flatten(1)->where('status_id', $status->id)->sum('total') }}
                                    </td>
                                @endforeach
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- PDF Generation Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        function downloadPDF(title = 'Data Report') {
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF('p', 'mm', 'a4');

            // Header
            pdf.setFontSize(14);
            pdf.setFont(undefined, 'bold');
            pdf.text(title.toUpperCase(), 105, 20, { align: 'center' });

            pdf.setFontSize(9);
            pdf.setFont(undefined, 'italic');
            pdf.text('This report provides a summary of correspondences addressed to, marked to, and currently held by each user.', 105, 28, { align: 'center' });

            pdf.setFontSize(10);
            pdf.setFont(undefined, 'normal');
            pdf.text('Generated: ' + new Date().toLocaleDateString(), 105, 35, { align: 'center' });

            // Get table
            const table = document.querySelector('.report-table');
            const rows = table.querySelectorAll('tr');

            let yPos = 45;
            const pageHeight = 280;
            const margin = 10;
            const pageWidth = 190;

            // This is a simple table crawler for complex headers
            // For simpler logic, we just grab all rows and cells
            pdf.setFontSize(7);

            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].querySelectorAll('th, td');
                const colWidth = (pageWidth - (margin * 2)) / cells.length;

                if (yPos > pageHeight - 20) {
                    pdf.addPage();
                    yPos = 20;
                }

                let xPos = margin;
                cells.forEach((cell) => {
                    const text = cell.innerText.trim().split('\n')[0]; // Just take first line
                    pdf.rect(xPos, yPos, colWidth, 7);
                    pdf.text(text, xPos + 1, yPos + 5);
                    xPos += colWidth;
                });
                yPos += 7;
            }

            const filename = title.toLowerCase().replace(/\s+/g, '-') + '.pdf';
            pdf.save(filename);
        }
    </script>
</x-app-layout>