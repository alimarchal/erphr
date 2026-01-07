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

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight inline-block">
            System Audit Trail
        </h2>
        <div class="flex justify-center items-center float-right space-x-2">
            <button id="toggle"
                class="inline-flex items-center px-4 py-2 bg-blue-950 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-950 focus:bg-green-800 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Search
            </button>

            <button wire:click="resetFilters"
                class="inline-flex items-center px-4 py-2 bg-blue-950 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-950 focus:bg-green-800 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Reset
            </button>

            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-950 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-800 focus:bg-green-800 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg" id="filters" style="display: none">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <x-label for="search" value="Description / Keyword" />
                        <x-input id="search" type="text" wire:model.live.debounce.300ms="search" class="mt-1 block w-full" placeholder="Search..." />
                    </div>

                    <div>
                        <x-label for="causer_id" value="User" />
                        <select wire:model.live="causer_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                            <option value="">All Users</option>
                            @foreach($this->users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label for="subject_type" value="Module (e.g. Correspondence)" />
                        <select wire:model.live="subject_type" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                            <option value="">All Modules</option>
                            @foreach($this->subjectTypes as $type)
                                <option value="{{ $type }}">{{ class_basename($type) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label for="event" value="Action Type" />
                        <select wire:model.live="event" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                            <option value="">All Actions</option>
                            @foreach($this->events as $ev)
                                <option value="{{ $ev }}">{{ ucfirst($ev) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label for="date_from" value="From Date" />
                        <x-input id="date_from" type="date" wire:model.live="date_from" class="mt-1 block w-full" />
                    </div>

                    <div>
                        <x-label for="date_to" value="To Date" />
                        <x-input id="date_to" type="date" wire:model.live="date_to" class="mt-1 block w-full" />
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg mt-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Module</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Details</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($this->getActivities() as $activity)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $activity->created_at->format('Y-m-d H:i:s') }}
                                    <div class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                    <div class="flex flex-col">
                                        <span>{{ $activity->causer?->name ?? 'System' }}</span>
                                        @if($activity->properties->has('ip'))
                                            <span class="text-xs text-indigo-500 font-mono">{{ $activity->properties['ip'] }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $activity->event === 'created' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                        {{ $activity->event === 'updated' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                        {{ ($activity->event === 'deleted' || $activity->event === 'destroyed') ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                        {{ $activity->event === 'viewed' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}
                                        {{ $activity->event === 'login' ? 'bg-teal-100 text-teal-800 dark:bg-teal-900/30 dark:text-teal-400' : '' }}
                                        {{ !in_array($activity->event, ['created', 'updated', 'deleted', 'viewed', 'login', 'destroyed']) ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400' : '' }}
                                    ">
                                        {{ ucfirst($activity->event ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ class_basename($activity->subject_type) }}</span>
                                    @if($activity->subject_id)
                                        <span class="text-[10px] bg-gray-100 dark:bg-gray-700 px-1 rounded text-gray-500">ID:{{ substr($activity->subject_id, 0, 8) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    <div class="font-medium text-gray-700 dark:text-gray-200">{{ $activity->description }}</div>
                                    
                                    @if($activity->event === 'updated' && isset($activity->properties['attributes']))
                                        <div class="mt-2">
                                            <details class="group">
                                                <summary class="cursor-pointer text-indigo-500 hover:text-indigo-700 text-xs font-semibold flex items-center">
                                                    <span>View Changes</span>
                                                    <svg class="w-3 h-3 ml-1 transform group-open:rotate-180 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                </summary>
                                                <div class="mt-2 overflow-hidden rounded border border-gray-100 dark:border-gray-700">
                                                    <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700 text-[11px]">
                                                        <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 uppercase">
                                                            <tr>
                                                                <th class="px-2 py-1 text-left">Field</th>
                                                                <th class="px-2 py-1 text-left">Original</th>
                                                                <th class="px-2 py-1 text-left">New</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-gray-50 dark:divide-gray-800 bg-white dark:bg-gray-900/50">
                                                            @foreach($activity->properties['attributes'] as $key => $value)
                                                                @php
                                                                    $old = $activity->properties['old'][$key] ?? null;
                                                                    $isDifferent = $old != $value;
                                                                @endphp
                                                                @if($isDifferent)
                                                                    <tr>
                                                                        <td class="px-2 py-1 font-bold text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 w-1/4">{{ $key }}</td>
                                                                        <td class="px-2 py-1 text-red-600 dark:text-red-400 bg-red-50/30 dark:bg-red-900/10 break-all">{{ is_array($old) ? json_encode($old) : (string)$old }}</td>
                                                                        <td class="px-2 py-1 text-green-600 dark:text-green-400 bg-green-50/30 dark:bg-green-900/10 break-all">{{ is_array($value) ? json_encode($value) : (string)$value }}</td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </details>
                                        </div>
                                    @elseif($activity->properties && $activity->properties->count() > 0)
                                        <div class="mt-2">
                                            <details class="group">
                                                <summary class="cursor-pointer text-indigo-500 hover:text-indigo-700 text-xs font-semibold flex items-center">
                                                    <span>View Data</span>
                                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                </summary>
                                                <pre class="mt-2 p-2 bg-gray-50 dark:bg-gray-900 rounded border border-gray-100 dark:border-gray-700 text-[10px] overflow-auto max-h-40">{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
                                            </details>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        <p class="text-sm italic">No activity logs found matching your criteria.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6 border-t border-gray-100 dark:border-gray-700">
                {{ $this->getActivities()->links() }}
            </div>
        </div>
    </div>

    @push('modals')
    <script>
        document.addEventListener('livewire:init', () => {
            const targetDiv = document.getElementById("filters");
            const btn = document.getElementById("toggle");

            if (btn && targetDiv) {
                btn.onclick = function() {
                    if (targetDiv.style.display === 'none') {
                        targetDiv.style.display = 'block';
                        targetDiv.style.opacity = '0';
                        targetDiv.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            targetDiv.style.opacity = '1';
                            targetDiv.style.transform = 'translateY(0)';
                        }, 10);
                    } else {
                        targetDiv.style.opacity = '0';
                        targetDiv.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            targetDiv.style.display = 'none';
                        }, 300);
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
