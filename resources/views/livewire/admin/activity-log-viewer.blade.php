<?php

use Livewire\Volt\Component;
use Spatie\Activitylog\Models\Activity;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;

new class extends Component {
    use WithPagination;

    public $search = '';
    public $causer_id = '';
    public $subject_type = '';
    public $event = '';
    public $date_from = '';
    public $date_to = '';

    public function mount()
    {
        if (Gate::denies('view-activity-logs')) {
            // This will be checked in the routes too, but extra safety
            // Actually, I'll rely on route middleware mostly, but good to have
        }
    }

    public function updating($property)
    {
        if (in_array($property, ['search', 'causer_id', 'subject_type', 'event', 'date_from', 'date_to'])) {
            $this->resetPage();
        }
    }

    public function rendering($view)
    {
        return $view->layout('layouts.app');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'causer_id', 'subject_type', 'event', 'date_from', 'date_to']);
    }

    public function getActivities()
    {
        return Activity::with(['causer', 'subject'])
            ->when($this->search, function ($query) {
                $query->where('description', 'like', '%' . $this->search . '%')
                    ->orWhere('properties', 'like', '%' . $this->search . '%');
            })
            ->when($this->causer_id, fn($q) => $q->where('causer_id', $this->causer_id))
            ->when($this->subject_type, fn($q) => $q->where('subject_type', $this->subject_type))
            ->when($this->event, fn($q) => $q->where('event', $this->event))
            ->when($this->date_from, fn($q) => $q->whereDate('created_at', '>=', $this->date_from))
            ->when($this->date_to, fn($q) => $q->whereDate('created_at', '<=', $this->date_to))
            ->latest()
            ->paginate(50);
    }

    public function getUsersProperty()
    {
        return \App\Models\User::orderBy('name')->get(['id', 'name']);
    }

    public function getSubjectTypesProperty()
    {
        return Activity::groupBy('subject_type')
            ->whereNotNull('subject_type')
            ->pluck('subject_type');
    }

    public function getEventsProperty()
    {
        return Activity::groupBy('event')
            ->whereNotNull('event')
            ->pluck('event');
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight inline-block">
            Settings Audit-Module Logs
        </h2>
        <div class="flex justify-center items-center float-right">
            <button id="toggle"
                class="inline-flex items-center ml-2 px-4 py-2 bg-blue-950 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-950 focus:bg-green-800 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Search
            </button>

            <button wire:click="resetFilters"
                class="inline-flex items-center ml-2 px-4 py-2 bg-blue-950 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-950 focus:bg-green-800 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-4 mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Reset
            </button>

            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center ml-2 px-4 py-2 bg-blue-950 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-800 focus:bg-green-800 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg" id="filters"
            style="display: none">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <x-label for="search" value="Description / Keyword" />
                        <input type="text" wire:model.live.debounce.300ms="search" id="search"
                            placeholder="Search description..."
                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                    </div>

                    <div>
                        <x-label for="causer_id" value="User" />
                        <select wire:model.live="causer_id" id="causer_id"
                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                            <option value="">All Users</option>
                            @foreach ($this->users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label for="subject_type" value="Module" />
                        <select wire:model.live="subject_type" id="subject_type"
                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                            <option value="">All Modules</option>
                            @foreach ($this->subjectTypes as $type)
                                <option value="{{ $type }}">{{ class_basename($type) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label for="event" value="Action Type" />
                        <select wire:model.live="event" id="event"
                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                            <option value="">All Actions</option>
                            @foreach ($this->events as $ev)
                                <option value="{{ $ev }}">{{ ucfirst($ev) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label for="date_from" value="From Date" />
                        <input type="date" wire:model.live="date_from" id="date_from"
                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" />
                    </div>

                    <div>
                        <x-label for="date_to" value="To Date" />
                        <input type="date" wire:model.live="date_to" id="date_to"
                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="relative overflow-x-auto rounded-lg">
                    <table class="min-w-max w-full table-auto text-sm">
                        <thead>
                            <tr class="bg-blue-800 text-white uppercase text-sm">
                                <th class="py-2 px-4 text-center">Time</th>
                                <th class="py-2 px-4 text-center">User</th>
                                <th class="py-2 px-4 text-center">Action</th>
                                <th class="py-2 px-4 text-center">Module</th>
                                <th class="py-2 px-4 text-center">Details</th>
                            </tr>
                        </thead>
                        <tbody class="text-black text-md leading-normal font-extrabold dark:text-gray-200">
                            @forelse($this->getActivities() as $activity)
                                <tr class="border-b border-gray-200 hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-700">
                                    <td class="py-2 px-4 text-center whitespace-nowrap">
                                        {{ $activity->created_at->format('Y-m-d H:i') }}
                                        <span class="block text-[10px] text-gray-500 font-normal">{{ $activity->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td class="py-2 px-4 text-center">
                                        {{ $activity->causer?->name ?? 'System' }}
                                        @if ($activity->properties->has('ip'))
                                            <span class="block text-[10px] text-indigo-500 font-mono font-normal">{{ $activity->properties['ip'] }}</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-4 text-center">
                                        <span class="px-2 py-1 rounded-full text-[10px] uppercase font-bold
                                            {{ $activity->event === 'created' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $activity->event === 'updated' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ in_array($activity->event, ['deleted', 'destroyed']) ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $activity->event === 'viewed' ? 'bg-gray-100 text-gray-800' : '' }}
                                            {{ $activity->event === 'login' ? 'bg-teal-100 text-teal-800' : '' }}
                                            {{ !in_array($activity->event, ['created', 'updated', 'deleted', 'viewed', 'login', 'destroyed']) ? 'bg-purple-100 text-purple-800' : '' }}
                                        ">
                                            {{ $activity->event ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 text-center whitespace-nowrap">
                                        {{ class_basename($activity->subject_type) }}
                                        @if ($activity->subject_id)
                                            <span class="block text-[10px] text-gray-400 font-normal italic">ID: {{ substr($activity->subject_id, 0, 8) }}</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-4 text-left">
                                        <div class="text-sm font-normal text-gray-800 dark:text-gray-300">{{ $activity->description }}</div>
                                        @if ($activity->event === 'updated' && isset($activity->properties['attributes']))
                                            <details class="group mt-1">
                                                <summary class="cursor-pointer text-indigo-600 text-[10px] font-bold uppercase hover:underline">Changes</summary>
                                                <div class="mt-1 text-[10px] font-normal border rounded p-1 bg-gray-50 dark:bg-gray-900 dark:border-gray-700">
                                                    @foreach($activity->properties['attributes'] as $key => $value)
                                                        @php $old = $activity->properties['old'][$key] ?? null; @endphp
                                                        @if($old != $value)
                                                            <div class="mb-1">
                                                                <span class="font-bold">{{ $key }}:</span> 
                                                                <span class="text-red-500 strike-through">{{ is_array($old) ? json_encode($old) : $old }}</span> 
                                                                → 
                                                                <span class="text-green-500">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </details>
                                        @elseif($activity->properties && $activity->properties->count() > 0)
                                            <details class="group mt-1">
                                                <summary class="cursor-pointer text-indigo-600 text-[10px] font-bold uppercase hover:underline">Data</summary>
                                                <pre class="mt-1 p-1 bg-gray-50 dark:bg-gray-900 rounded text-[9px] font-normal overflow-auto max-h-20">{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
                                            </details>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-10 text-center text-gray-500 font-normal">
                                        No activity logs found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    {{ $this->getActivities()->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('modals')
        <script>
            document.addEventListener('livewire:init', () => {
                const targetDiv = document.getElementById("filters");
                const btn = document.getElementById("toggle");

                function showFilters() {
                    targetDiv.style.display = 'block';
                    targetDiv.style.opacity = '0';
                    targetDiv.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        targetDiv.style.opacity = '1';
                        targetDiv.style.transform = 'translateY(0)';
                    }, 10);
                }

                function hideFilters() {
                    targetDiv.style.opacity = '0';
                    targetDiv.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        targetDiv.style.display = 'none';
                    }, 300);
                }

                if (btn && targetDiv) {
                    btn.onclick = function(event) {
                        event.stopPropagation();
                        if (targetDiv.style.display === "none") {
                            showFilters();
                        } else {
                            hideFilters();
                        }
                    };

                    // Hide filters when clicking outside
                    document.addEventListener('click', function(event) {
                        if (targetDiv.style.display === 'block' && !targetDiv.contains(event.target) && event.target !== btn) {
                            hideFilters();
                        }
                    });

                    // Prevent clicks inside the filter from closing it
                    targetDiv.addEventListener('click', function(event) {
                        event.stopPropagation();
                    });
                }

                // Add CSS for smooth transitions
                if (!document.getElementById('filters-style')) {
                    const style = document.createElement('style');
                    style.id = 'filters-style';
                    style.textContent = `#filters {transition: opacity 0.3s ease, transform 0.3s ease;}`;
                    document.head.appendChild(style);
                }
            });
        </script>
    @endpush
</div>
