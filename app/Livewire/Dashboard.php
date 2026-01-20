<?php

namespace App\Livewire;

use App\Models\Correspondence;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;

class Dashboard extends Component
{
    #[Url]
    public $fromDate;

    #[Url]
    public $toDate;

    public $isAdmin = false;

    /**
     * Map Tailwind color names to vibrant hex codes for charts.
     */
    private const TAILWIND_COLORS = [
        'red' => '#ef4444',
        'orange' => '#f97316',
        'amber' => '#f59e0b',
        'yellow' => '#eab308',
        'lime' => '#84cc16',
        'green' => '#22c55e',
        'emerald' => '#10b981',
        'teal' => '#14b8a6',
        'cyan' => '#06b6d4',
        'sky' => '#0ea5e9',
        'blue' => '#3b82f6',
        'indigo' => '#6366f1',
        'violet' => '#8b5cf6',
        'purple' => '#a855f7',
        'fuchsia' => '#d946ef',
        'pink' => '#ec4899',
        'rose' => '#f43f5e',
        'slate' => '#64748b',
        'gray' => '#6b7280',
        'zinc' => '#71717a',
        'neutral' => '#737373',
        'stone' => '#78716c',
    ];

    /**
     * Convert a Tailwind color name to hex code.
     */
    private function toHexColor(?string $color): string
    {
        if ($color === null) {
            return '#6b7280'; // gray-500 default
        }

        // If it's already a hex color, return it
        if (str_starts_with($color, '#')) {
            return $color;
        }

        return self::TAILWIND_COLORS[strtolower($color)] ?? '#6b7280';
    }

    public function mount(): void
    {
        if (auth()->check()) {
            $this->isAdmin = auth()->user()->hasRole(['super-admin', 'admin']);
        }
        $this->fromDate = $this->fromDate ?? now()->startOfMonth()->format('Y-m-d');
        $this->toDate = $this->toDate ?? now()->format('Y-m-d');
    }

    public function updated($propertyName): void
    {
        if (in_array($propertyName, ['fromDate', 'toDate'])) {
            $this->dispatch('refresh-charts');
        }
    }

    public function getStatsProperty(): array
    {
        if (! auth()->check()) {
            return [
                'total' => 0,
                'receipts' => 0,
                'dispatches' => 0,
                'pending' => 0,
                'closed' => 0,
                'overdue' => 0,
                'urgent' => 0,
                'replied' => 0,
            ];
        }

        $user = auth()->user();
        $query = Correspondence::query()
            ->whereBetween('created_at', [$this->fromDate.' 00:00:00', $this->toDate.' 23:59:59']);

        if (! $this->isAdmin) {
            $query->where(function ($q) use ($user) {
                $q->where('current_holder_id', $user->id)
                    ->orWhere('addressed_to_user_id', $user->id)
                    ->orWhere('marked_to_user_id', $user->id);
            });
        }

        return [
            'total' => (clone $query)->count(),
            'receipts' => (clone $query)->where('type', 'Receipt')->count(),
            'dispatches' => (clone $query)->where('type', 'Dispatch')->count(),
            'pending' => (clone $query)->whereHas('status', fn ($q) => $q->where('is_final', false))->count(),
            'closed' => (clone $query)->whereHas('status', fn ($q) => $q->where('is_final', true))->count(),
            'overdue' => (clone $query)->whereNotNull('due_date')->where('due_date', '<', now())->whereHas('status', fn ($q) => $q->where('is_final', false))->count(),
            'urgent' => (clone $query)->whereHas('priority', fn ($q) => $q->whereIn('name', ['Urgent', 'High', 'Critical']))->count(),
            'replied' => (clone $query)->where('is_replied', true)->count(),
        ];
    }

    public function getChartDataProperty(): array
    {
        if (! auth()->check()) {
            return $this->getEmptyChartData();
        }

        $user = auth()->user();
        $start = Carbon::parse($this->fromDate)->startOfDay();
        $end = Carbon::parse($this->toDate)->endOfDay();

        // 1. Volume Trend (Receipts vs Dispatches)
        $trendData = Correspondence::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw("SUM(CASE WHEN type = 'Receipt' THEN 1 ELSE 0 END) as receipts"),
            DB::raw("SUM(CASE WHEN type = 'Dispatch' THEN 1 ELSE 0 END) as dispatches")
        )
            ->whereBetween('created_at', [$start, $end])
            ->when(! $this->isAdmin, function ($q) use ($user) {
                $q->where(function ($inner) use ($user) {
                    $inner->where('current_holder_id', $user->id)
                        ->orWhere('addressed_to_user_id', $user->id)
                        ->orWhere('marked_to_user_id', $user->id);
                });
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill missing dates with 0 for a better trend visualization
        $trendResults = [];
        $current = $start->copy();
        while ($current <= $end) {
            $dateStr = $current->format('Y-m-d');
            $row = $trendData->firstWhere('date', $dateStr);

            $trendResults['dates'][] = $dateStr;
            $trendResults['receipts'][] = (int) ($row->receipts ?? 0);
            $trendResults['dispatches'][] = (int) ($row->dispatches ?? 0);

            $current->addDay();
        }

        // 2. Status Breakdown
        $statusBreakdown = Correspondence::select('status_id', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$start, $end])
            ->when(! $this->isAdmin, function ($q) use ($user) {
                $q->where('current_holder_id', $user->id);
            })
            ->groupBy('status_id')
            ->with('status')
            ->get()
            ->map(fn ($item) => [
                'label' => $item->status->name ?? 'Unknown',
                'value' => $item->total,
                'color' => $this->toHexColor($item->status->color ?? null),
            ]);

        // 3. Division/Workload Breakdown (Admin Only)
        $workloadData = [];
        if ($this->isAdmin) {
            $workloadData = Correspondence::select('current_holder_id', DB::raw('count(*) as total'))
                ->whereBetween('created_at', [$start, $end])
                ->whereHas('status', fn ($q) => $q->where('is_final', false))
                ->groupBy('current_holder_id')
                ->with('currentHolder')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get()
                ->map(fn ($item) => [
                    'label' => $item->currentHolder?->name ?? 'System',
                    'value' => $item->total,
                ]);
        }

        // 4. Priority Breakdown
        $priorityBreakdown = Correspondence::select('priority_id', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$start, $end])
            ->when(! $this->isAdmin, function ($q) use ($user) {
                $q->where('current_holder_id', $user->id);
            })
            ->groupBy('priority_id')
            ->with('priority')
            ->get()
            ->map(fn ($item) => [
                'label' => $item->priority->name ?? 'Normal',
                'value' => $item->total,
                'color' => $this->toHexColor($item->priority->color ?? null),
            ]);

        // 5. Confidentiality Breakdown
        $confidentialityBreakdown = Correspondence::select('confidentiality', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$start, $end])
            ->when(! $this->isAdmin, function ($q) use ($user) {
                $q->where('current_holder_id', $user->id);
            })
            ->groupBy('confidentiality')
            ->get()
            ->map(fn ($item) => [
                'label' => $item->confidentiality ?: 'Normal',
                'value' => $item->total,
            ]);

        return [
            'trend' => [
                'dates' => $trendResults['dates'],
                'receipts' => $trendResults['receipts'],
                'dispatches' => $trendResults['dispatches'],
            ],
            'status' => [
                'labels' => $statusBreakdown->pluck('label')->toArray(),
                'values' => $statusBreakdown->pluck('value')->toArray(),
                'colors' => $statusBreakdown->pluck('color')->toArray(),
            ],
            'workload' => [
                'labels' => collect($workloadData)->pluck('label')->toArray(),
                'values' => collect($workloadData)->pluck('value')->toArray(),
            ],
            'priority' => [
                'labels' => $priorityBreakdown->pluck('label')->toArray(),
                'values' => $priorityBreakdown->pluck('value')->toArray(),
                'colors' => $priorityBreakdown->pluck('color')->toArray(),
            ],
            'confidentiality' => [
                'labels' => $confidentialityBreakdown->pluck('label')->toArray(),
                'values' => $confidentialityBreakdown->pluck('value')->toArray(),
            ],
        ];
    }

    private function getEmptyChartData(): array
    {
        return [
            'trend' => [
                'dates' => [],
                'receipts' => [],
                'dispatches' => [],
            ],
            'status' => [
                'labels' => [],
                'values' => [],
                'colors' => [],
            ],
            'workload' => [
                'labels' => [],
                'values' => [],
            ],
            'priority' => [
                'labels' => [],
                'values' => [],
                'colors' => [],
            ],
            'confidentiality' => [
                'labels' => [],
                'values' => [],
            ],
        ];
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'stats' => $this->stats,
            'chartData' => $this->chartData,
        ])->layout('layouts.app');
    }
}
