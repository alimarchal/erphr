<x-app-layout>
    <x-slot name="header">
        <x-page-header
            :title="$correspondence->register_number"
            :backUrl="route('correspondence.index', ['type' => $correspondence->type])"
            :showSearch="false"
        />
    </x-slot>

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
                        @can('edit correspondence')
                        <a href="{{ route('correspondence.edit', $correspondence) }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-800 text-white text-xs font-bold uppercase tracking-widest rounded-md hover:bg-blue-900 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                        @endcan
                        <button type="button" onclick="document.getElementById('mark-modal').classList.remove('hidden')"
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-xs font-bold uppercase tracking-widest rounded-md hover:bg-green-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                            Mark To
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {{-- Section 1: Basic Details --}}
                        <div>
                            <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-4 border-b border-blue-100 pb-1">Basic Details</h4>
                            <dl class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                                <dt class="text-gray-500">Register No:</dt>
                                <dd class="font-bold text-gray-900">{{ $correspondence->register_number }}</dd>

                                <dt class="text-gray-500">Type:</dt>
                                <dd class="font-bold text-gray-900">{{ $correspondence->type }}</dd>

                                <dt class="text-gray-500">Letter Type:</dt>
                                <dd class="font-bold text-gray-900">{{ $correspondence->letterType?->name ?? '-' }}</dd>

                                <dt class="text-gray-500">Category:</dt>
                                <dd class="font-bold text-gray-900">{{ $correspondence->category?->name ?? '-' }}</dd>

                                <dt class="text-gray-500">Letter Date:</dt>
                                <dd class="font-bold text-gray-900">{{ $correspondence->letter_date?->format('d-M-Y') ?? '-' }}</dd>

                                <dt class="text-gray-500">{{ $correspondence->isReceipt() ? 'Received' : 'Dispatch' }} Date:</dt>
                                <dd class="font-bold text-gray-900">
                                    {{ $correspondence->isReceipt() ? $correspondence->received_date?->format('d-M-Y') : $correspondence->dispatch_date?->format('d-M-Y') }}
                                </dd>

                                <dt class="text-gray-500">Ref Number:</dt>
                                <dd class="font-bold text-gray-900">{{ $correspondence->reference_number ?? '-' }}</dd>
                            </dl>
                        </div>

                        {{-- Section 2: Source & Destination --}}
                        <div>
                            <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-4 border-b border-blue-100 pb-1">Source & Destination</h4>
                            <dl class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                                <dt class="text-gray-500">{{ $correspondence->isReceipt() ? 'From' : 'To' }} (External):</dt>
                                <dd class="font-bold text-gray-900">{{ $correspondence->sender_name ?? '-' }}</dd>

                                <dt class="text-gray-500">Division:</dt>
                                <dd class="font-bold text-gray-900">{{ $correspondence->toDivision?->name ?? '-' }}</dd>

                                <dt class="text-gray-500">Addressed To:</dt>
                                <dd class="font-bold text-gray-900">{{ $correspondence->addressedTo?->name ?? '-' }}</dd>

                                <dt class="text-gray-500">Current Holder:</dt>
                                <dd class="font-bold text-blue-700">
                                    {{ $correspondence->currentHolder?->name ?? 'Not assigned' }}
                                    @if($correspondence->current_holder_since)
                                        <div class="text-[10px] text-gray-400 font-normal">Since {{ $correspondence->current_holder_since->format('d-M-Y H:i') }}</div>
                                    @endif
                                </dd>

                                <dt class="text-gray-500">Due Date:</dt>
                                <dd class="font-bold {{ $correspondence->isOverdue() ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ $correspondence->due_date?->format('d-M-Y') ?? '-' }}
                                </dd>

                                <dt class="text-gray-500">Days Open:</dt>
                                <dd class="font-bold text-gray-900">{{ $correspondence->days_open }} days</dd>
                            </dl>
                        </div>

                        {{-- Section 3: Delivery & Record Info --}}
                        <div>
                            <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-4 border-b border-blue-100 pb-1">Delivery & Record Info</h4>
                            <dl class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                                <dt class="text-gray-500">Delivery Mode:</dt>
                                <dd class="font-bold text-gray-900">{{ $correspondence->delivery_mode ?? '-' }}</dd>

                                <dt class="text-gray-500">Courier:</dt>
                                <dd class="font-bold text-gray-900">{{ $correspondence->courier_name ?? '-' }}</dd>

                                <dt class="text-gray-500">Tracking No:</dt>
                                <dd class="font-bold text-gray-900">{{ $correspondence->courier_tracking ?? '-' }}</dd>

                                <dt class="text-gray-500">Created By:</dt>
                                <dd class="font-bold text-gray-900">
                                    {{ $correspondence->creator?->name ?? '-' }}
                                    <div class="text-[10px] text-gray-400 font-normal">{{ $correspondence->created_at?->format('d-M-Y H:i') }}</div>
                                </dd>

                                <dt class="text-gray-500">Last Updated:</dt>
                                <dd class="font-bold text-gray-900">
                                    {{ $correspondence->updater?->name ?? '-' }}
                                    <div class="text-[10px] text-gray-400 font-normal">{{ $correspondence->updated_at?->format('d-M-Y H:i') }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>

                    {{-- Subject & Description --}}
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-2">Subject</h4>
                        <p class="text-xl font-bold text-gray-900 leading-tight">{{ $correspondence->subject }}</p>
                        
                        @if($correspondence->description)
                            <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider mt-6 mb-2">Description</h4>
                            <div class="text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-lg border border-gray-100">
                                {{ $correspondence->description }}
                            </div>
                        @endif

                        @if($correspondence->remarks)
                            <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider mt-6 mb-2">Remarks</h4>
                            <p class="text-sm text-gray-600 italic">{{ $correspondence->remarks }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Movement Trail --}}
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-bold text-gray-900">Movement Trail</h3>
                        </div>
                        <div class="p-6">
                            @if($correspondence->movements->count() > 0)
                                <div class="space-y-6">
                                    @foreach($correspondence->movements as $movement)
                                        <div class="relative pl-8 pb-2 border-l-2 {{ $loop->last ? 'border-transparent' : 'border-gray-200' }}">
                                            {{-- Timeline Dot --}}
                                            <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full border-2 border-white shadow-sm
                                                {{ $movement->status === 'Pending' ? 'bg-yellow-400' : ($movement->status === 'Actioned' ? 'bg-green-500' : 'bg-blue-500') }}">
                                            </div>

                                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 shadow-sm">
                                                <div class="flex flex-wrap justify-between items-start gap-2 mb-2">
                                                    <div>
                                                        <span class="text-xs font-bold text-blue-800 uppercase tracking-widest">#{{ $movement->sequence }} - {{ $movement->action }}</span>
                                                        <div class="text-sm font-bold text-gray-900 mt-1">
                                                            {{ $movement->fromUser?->name ?? 'System' }} 
                                                            <span class="text-gray-400 font-normal mx-2">→</span> 
                                                            {{ $movement->toUser?->name ?? '-' }}
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                                            {{ $movement->status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                            {{ $movement->status === 'Received' ? 'bg-blue-100 text-blue-800' : '' }}
                                                            {{ $movement->status === 'Reviewed' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                                            {{ $movement->status === 'Actioned' ? 'bg-green-100 text-green-800' : '' }}">
                                                            {{ $movement->status }}
                                                        </span>
                                                        <div class="text-[10px] text-gray-400 mt-1 font-medium">{{ $movement->created_at->format('d-M-Y H:i') }}</div>
                                                    </div>
                                                </div>

                                                @if($movement->instructions)
                                                    <div class="text-sm text-gray-700 bg-white p-3 rounded border border-gray-100 italic mb-3">
                                                        "{{ $movement->instructions }}"
                                                    </div>
                                                @endif

                                                <div class="flex flex-wrap items-center gap-4 text-xs">
                                                    @if($movement->expected_response_date)
                                                        <div class="flex items-center {{ $movement->expected_response_date < now() && $movement->status === 'Pending' ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            Reply by: {{ $movement->expected_response_date->format('d-M-Y') }}
                                                            @if($movement->status === 'Pending')
                                                                ({{ $movement->expected_response_date->diffForHumans() }})
                                                            @endif
                                                        </div>
                                                    @endif

                                                    @if($movement->is_urgent)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-800 border border-red-200">
                                                            URGENT
                                                        </span>
                                                    @endif
                                                </div>

                                                {{-- Action buttons for pending movements --}}
                                                @if($movement->isPending() && $movement->to_user_id === auth()->id())
                                                    <div class="mt-4 flex space-x-2">
                                                        <form method="POST" action="{{ route('correspondence.movement.update', $correspondence) }}" class="inline">
                                                            @csrf
                                                            <input type="hidden" name="movement_id" value="{{ $movement->id }}">
                                                            <input type="hidden" name="action" value="receive">
                                                            <button type="submit" class="text-[10px] font-bold uppercase tracking-widest px-3 py-1.5 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                                                Mark Received
                                                            </button>
                                                        </form>
                                                    </div>
                                                @elseif($movement->status === 'Received' && $movement->to_user_id === auth()->id())
                                                    <div class="mt-4 flex space-x-2">
                                                        <form method="POST" action="{{ route('correspondence.movement.update', $correspondence) }}" class="inline">
                                                            @csrf
                                                            <input type="hidden" name="movement_id" value="{{ $movement->id }}">
                                                            <input type="hidden" name="action" value="review">
                                                            <button type="submit" class="text-[10px] font-bold uppercase tracking-widest px-3 py-1.5 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                                                                Mark Reviewed
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif

                                                @if($movement->action_taken)
                                                    <div class="mt-3 p-3 bg-green-50 rounded border border-green-100 text-sm">
                                                        <span class="text-xs font-bold text-green-800 uppercase tracking-wider block mb-1">Action Taken:</span>
                                                        <p class="text-gray-700">{{ $movement->action_taken }}</p>
                                                    </div>
                                                @endif

                                                @if($movement->getMedia('attachments')->count() > 0)
                                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block mb-2">Movement Attachments:</span>
                                                        <div class="flex flex-wrap gap-2">
                                                            @foreach($movement->getMedia('attachments') as $media)
                                                                <a href="{{ $media->getUrl() }}" target="_blank" 
                                                                   class="inline-flex items-center px-2 py-1 bg-white border border-gray-200 rounded text-xs text-blue-600 hover:bg-blue-50 transition">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                                    </svg>
                                                                    {{ Str::limit($media->file_name, 20) }}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                    <p class="text-gray-500 font-medium">No movements recorded yet.</p>
                                </div>
                            @endif
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
    <div id="mark-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
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

                {{-- Row 2: Expected Response Date & Status --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <x-label for="expected_response_date" value="Expected Response Date" />
                        <x-input id="expected_response_date" type="date" name="expected_response_date" class="mt-1 block w-full" />
                    </div>

                    <div>
                        <x-label for="status_id" value="Update Status (Optional)" />
                        <select id="status_id" name="status_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Keep Current Status</option>
                            @php
                                $markStatuses = \App\Models\CorrespondenceStatus::active()
                                    ->where(function($q) use ($correspondence) {
                                        $q->where('type', $correspondence->type)->orWhere('type', 'Both');
                                    })->ordered()->get();
                            @endphp
                            @foreach($markStatuses as $status)
                                <option value="{{ $status->id }}" {{ $correspondence->status_id == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
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
