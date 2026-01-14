{{-- resources/views/components/printable-table.blade.php --}}
@props([
    'data' => [],
    'headers' => [],
    'title' => 'Data Report',
    'description' => null,
    'showActions' => true,
    'actionButtons' => null,
    'organization' => 'Organization Name'
])

<div class="printable-table-container">
    <!-- Header (Unified for Screen & Print) -->
    <div class="text-center mb-8 border-b-2 border-transparent print:border-black pb-4">
        <h2 class="text-xl font-bold text-gray-800 print:text-black">{{ $organization }}</h2>
        <h1 class="text-2xl font-bold uppercase underline mt-2 print:text-black print:no-underline print:text-xl">{{ $title }}</h1>
        @if($description)
            <p class="text-sm text-gray-600 mt-2 italic max-w-3xl mx-auto print:text-black print:mt-1">{{ $description }}</p>
        @endif
        <p class="text-xs text-gray-500 mt-4 print:text-black print:text-sm print:mt-2">
            Report Generated on: {{ now()->format('d-M-Y h:i A') }}
        </p>
    </div>

    @if($showActions)
    <div class="mb-4 print:hidden">
        <button onclick="downloadPDF('{{ $title }}')" 
                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Download PDF
        </button>
    </div>
    @endif

    <!-- Table -->
    <div class="data-table overflow-hidden">
        @if (count($data) > 0)
            <table class="w-full border-collapse border-2 border-black">
                <thead>
                    <tr class="bg-gray-100 print:bg-gray-200">
                        @foreach($headers as $header)
                            <th class="border border-black px-3 py-2 text-left text-xs font-bold text-black uppercase">
                                {{ $header }}
                            </th>
                        @endforeach
                        @if($showActions && $actionButtons)
                            <th class="border border-black px-3 py-2 text-center text-xs font-bold text-black uppercase print:hidden">
                                Actions
                            </th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $row)
                        <tr class="hover:bg-gray-50 print:hover:bg-transparent">
                            @foreach($row as $key => $value)
                                @if(!in_array($key, ['actions']))
                                    <td class="border border-black px-3 py-2 text-xs text-black break-words">
                                        @if(is_array($value))
                                            {{ implode(', ', $value) }}
                                        @elseif($value instanceof \Carbon\Carbon)
                                            {{ $value->format('d/m/Y') }}
                                        @elseif(is_bool($value))
                                            {{ $value ? 'Yes' : 'No' }}
                                        @else
                                            {{ Str::limit($value, 60) }}
                                        @endif
                                    </td>
                                @endif
                            @endforeach
                            
                            @if($showActions && $actionButtons)
                                <td class="border border-black px-3 py-2 text-center print:hidden">
                                    <div class="flex justify-center space-x-1">
                                        {!! $actionButtons($row, $index) !!}
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center py-12 border border-black">
                <p class="text-gray-600">No data found.</p>
            </div>
        @endif
    </div>
</div>

<!-- Print Styles -->
<style>
@media print {
    @page {
        size: A4 portrait;
        margin: 1cm;
    }
    
    body * {
        visibility: hidden;
    }
    
    .printable-table-container, .printable-table-container * {
        visibility: visible;
    }
    
    .printable-table-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .shadow, .shadow-xl, .shadow-md, .bg-white, .rounded-lg {
        box-shadow: none !important;
        background: transparent !important;
        border: none !important;
    }
    
    .print\:hidden {
        display: none !important;
    }

    .no-print {
        display: none !important;
    }

    .py-6, .py-12, .p-6, .px-4, .px-6, .sm\:px-6, .lg\:px-8 {
        padding: 0 !important;
        margin: 0 !important;
    }
}
    
    .print\:block {
        display: block !important;
    }
    
    .print\:bg-gray-200 {
        background-color: #e5e7eb !important;
    }
    
    .print\:border-black {
        border-color: #000 !important;
    }
    
    table {
        font-size: 10px !important;
        page-break-inside: auto;
    }
    
    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
    
    th, td {
        padding: 4px 2px !important;
        word-wrap: break-word;
        font-size: 8px !important;
    }
    
    thead {
        display: table-header-group;
    }
}
</style>

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
    
    pdf.setFontSize(10);
    pdf.setFont(undefined, 'normal');
    pdf.text('Generated: ' + new Date().toLocaleDateString(), 105, 30, { align: 'center' });
    
    // Get table
    const table = document.querySelector('.data-table table');
    const rows = table.querySelectorAll('tr');
    const headers = Array.from(table.querySelectorAll('th')).filter(th => !th.classList.contains('print:hidden'));
    
    let yPos = 45;
    const pageHeight = 280;
    const margin = 10;
    const pageWidth = 190;
    
    // Calculate column widths based on content
    const numCols = headers.length;
    const colWidth = (pageWidth - (margin * 2)) / numCols;
    
    // Table headers
    pdf.setFontSize(8);
    pdf.setFont(undefined, 'bold');
    
    let xPos = margin;
    headers.forEach((header, index) => {
        pdf.rect(xPos, yPos, colWidth, 8);
        const text = header.textContent.trim();
        pdf.text(text, xPos + 2, yPos + 6);
        xPos += colWidth;
    });
    
    yPos += 8;
    pdf.setFont(undefined, 'normal');
    
    // Table data
    for (let i = 1; i < rows.length; i++) {
        const cells = Array.from(rows[i].querySelectorAll('td')).filter(td => !td.classList.contains('print:hidden'));
        
        // Check for new page
        if (yPos > pageHeight - 20) {
            pdf.addPage();
            yPos = 20;
            
            // Re-add headers
            pdf.setFont(undefined, 'bold');
            xPos = margin;
            headers.forEach((header, index) => {
                pdf.rect(xPos, yPos, colWidth, 8);
                const text = header.textContent.trim();
                pdf.text(text, xPos + 2, yPos + 6);
                xPos += colWidth;
            });
            yPos += 8;
            pdf.setFont(undefined, 'normal');
        }
        
        xPos = margin;
        cells.forEach((cell, index) => {
            let text = cell.textContent.trim();
            
            // Truncate long text
            const maxLength = Math.floor(colWidth / 2);
            if (text.length > maxLength) {
                text = text.substring(0, maxLength - 3) + '...';
            }
            
            pdf.rect(xPos, yPos, colWidth, 8);
            pdf.text(text, xPos + 2, yPos + 6);
            xPos += colWidth;
        });
        
        yPos += 8;
    }
    
    // Save PDF
    const filename = title.toLowerCase().replace(/\s+/g, '-') + '-' + new Date().toISOString().split('T')[0] + '.pdf';
    pdf.save(filename);
}
</script>