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

            {{-- Quick Actions Bar --}}
            <div class="bg-white shadow-sm rounded-lg mb-4 p-4 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    @if($correspondence->status)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                            {{ $correspondence->status->color === 'blue' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $correspondence->status->color === 'yellow' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $correspondence->status->color === 'green' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $correspondence->status->color === 'gray' ? 'bg-gray-100 text-gray-700' : '' }}
                            {{ $correspondence->status->color === 'purple' ? 'bg-purple-100 text-purple-700' : '' }}">
                            {{ $correspondence->status->name }}
                        </span>
                    @endif

                    @if($correspondence->priority)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                            {{ $correspondence->priority->color === 'red' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $correspondence->priority->color === 'orange' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $correspondence->priority->color === 'yellow' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $correspondence->priority->color === 'green' ? 'bg-green-100 text-green-700' : '' }}">
                            {{ $correspondence->priority->name }}
                        </span>
                    @endif

                    @if($correspondence->isOverdue())
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-700">
                            OVERDUE
                        </span>
                    @endif

                    @if($correspondence->confidentiality !== 'Normal')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-purple-100 text-purple-700">
                            {{ strtoupper($correspondence->confidentiality) }}
                        </span>
                    @endif
                </div>

                <div class="flex items-center space-x-2">
                    <a href="{{ route('correspondence.edit', $correspondence) }}"
                       class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Main Details --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Basic Info Card --}}
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Correspondence Details</h3>

                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Register Number:</span>
                                <p class="font-semibold">{{ $correspondence->register_number }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Type:</span>
                                <p class="font-semibold">{{ $correspondence->type }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Reference Number:</span>
                                <p class="font-semibold">{{ $correspondence->reference_number ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Letter Date:</span>
                                <p class="font-semibold">{{ $correspondence->letter_date?->format('d-M-Y') ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">{{ $correspondence->isReceipt() ? 'Received Date' : 'Dispatch Date' }}:</span>
                                <p class="font-semibold">
                                    {{ $correspondence->isReceipt() ? $correspondence->received_date?->format('d-M-Y') : $correspondence->dispatch_date?->format('d-M-Y') }}
                                </p>
                            </div>
                            <div>
                                <span class="text-gray-500">Letter Type:</span>
                                <p class="font-semibold">{{ $correspondence->letterType?->name ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Category:</span>
                                <p class="font-semibold">{{ $correspondence->category?->name ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Days Open:</span>
                                <p class="font-semibold">{{ $correspondence->days_open }} days</p>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t">
                            <span class="text-gray-500 text-sm">Subject:</span>
                            <p class="font-semibold text-gray-900">{{ $correspondence->subject }}</p>
                        </div>

                        @if($correspondence->description)
                            <div class="mt-4 pt-4 border-t">
                                <span class="text-gray-500 text-sm">Description:</span>
                                <p class="text-gray-700 mt-1">{{ $correspondence->description }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- From/To Card --}}
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Source & Destination</h3>

                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">{{ $correspondence->isReceipt() ? 'From' : 'To' }} (External):</span>
                                <p class="font-semibold">{{ $correspondence->sender_name ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Division:</span>
                                <p class="font-semibold">{{ $correspondence->toDivision?->name ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Addressed To:</span>
                                <p class="font-semibold">{{ $correspondence->addressedTo?->name ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Current Holder:</span>
                                <p class="font-semibold text-blue-600">{{ $correspondence->currentHolder?->name ?? 'Not assigned' }}</p>
                                @if($correspondence->current_holder_since)
                                    <p class="text-xs text-gray-400">Since {{ $correspondence->current_holder_since->format('d-M-Y H:i') }}</p>
                                @endif
                            </div>
                        </div>

                        @if($correspondence->due_date)
                            <div class="mt-4 pt-4 border-t">
                                <span class="text-gray-500 text-sm">Due Date:</span>
                                <p class="font-semibold {{ $correspondence->isOverdue() ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ $correspondence->due_date->format('d-M-Y') }}
                                    @if($correspondence->isOverdue())
                                        ({{ $correspondence->due_date->diffForHumans() }})
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- Movement Trail --}}
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Movement Trail</h3>
                            <button type="button" onclick="document.getElementById('mark-modal').classList.remove('hidden')"
                                    class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                                Mark To
                            </button>
                        </div>

                        @if($correspondence->movements->count() > 0)
                            <div class="space-y-4">
                                @foreach($correspondence->movements as $movement)
                                    <div class="border-l-4 {{ $movement->isPending() ? 'border-yellow-400' : 'border-green-400' }} pl-4 py-2">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    #{{ $movement->sequence }} - {{ $movement->action }}
                                                </p>
                                                <p class="text-sm text-gray-600">
                                                    <span class="text-gray-400">From:</span> {{ $movement->fromUser?->name ?? 'System' }}
                                                    <span class="mx-2">→</span>
                                                    <span class="font-semibold">{{ $movement->toUser?->name ?? '-' }}</span>
                                                </p>
                                                @if($movement->instructions)
                                                    <p class="text-sm text-gray-500 mt-1 italic">"{{ $movement->instructions }}"</p>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                                    {{ $movement->status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $movement->status === 'Received' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $movement->status === 'Reviewed' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                                    {{ $movement->status === 'Actioned' ? 'bg-green-100 text-green-800' : '' }}">
                                                    {{ $movement->status }}
                                                </span>
                                                <p class="text-xs text-gray-400 mt-1">{{ $movement->created_at->format('d-M-Y H:i') }}</p>
                                                @if($movement->expected_response_date)
                                                    <p class="text-xs {{ $movement->expected_response_date < now() && $movement->status === 'Pending' ? 'text-red-600 font-semibold' : 'text-gray-500' }} mt-1">
                                                        Reply by: {{ $movement->expected_response_date->format('d-M-Y') }}
                                                        @if($movement->status === 'Pending')
                                                            ({{ $movement->expected_response_date->diffForHumans() }})
                                                        @endif
                                                    </p>
                                                @endif
                                                @if($movement->is_urgent)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 mt-1">
                                                        URGENT
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Action buttons for pending movements --}}
                                        @if($movement->isPending() && $movement->to_user_id === auth()->id())
                                            <div class="mt-2 flex space-x-2">
                                                <form method="POST" action="{{ route('correspondence.movement.update', $correspondence) }}" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="movement_id" value="{{ $movement->id }}">
                                                    <input type="hidden" name="action" value="receive">
                                                    <button type="submit" class="text-xs px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                                        Mark Received
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($movement->status === 'Received' && $movement->to_user_id === auth()->id())
                                            <div class="mt-2 flex space-x-2">
                                                <form method="POST" action="{{ route('correspondence.movement.update', $correspondence) }}" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="movement_id" value="{{ $movement->id }}">
                                                    <input type="hidden" name="action" value="review">
                                                    <button type="submit" class="text-xs px-2 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600">
                                                        Mark Reviewed
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        @if($movement->action_taken)
                                            <div class="mt-2 p-2 bg-gray-50 rounded text-sm">
                                                <span class="text-gray-500">Action Taken:</span>
                                                <p class="text-gray-700">{{ $movement->action_taken }}</p>
                                            </div>
                                        @endif

                                        @if($movement->getMedia('attachments')->count() > 0)
                                            <div class="mt-2 p-2 bg-blue-50 rounded text-sm">
                                                <span class="text-gray-500 font-medium">Attachments:</span>
                                                <ul class="mt-1 space-y-1">
                                                    @foreach($movement->getMedia('attachments') as $media)
                                                        <li class="flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                            </svg>
                                                            <a href="{{ $media->getUrl() }}" target="_blank" class="text-blue-600 hover:underline text-xs">
                                                                {{ $media->file_name }}
                                                            </a>
                                                            <span class="text-gray-400 text-xs ml-1">({{ number_format($media->size / 1024, 1) }} KB)</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No movements recorded yet.</p>
                        @endif
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    {{-- Attachments --}}
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Attachments</h3>

                        @if($correspondence->getMedia('attachments')->count() > 0)
                            <ul class="space-y-2">
                                @foreach($correspondence->getMedia('attachments') as $media)
                                    <li class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 truncate max-w-[150px]">{{ $media->file_name }}</p>
                                                <p class="text-xs text-gray-500">{{ number_format($media->size / 1024, 1) }} KB</p>
                                            </div>
                                        </div>
                                        <a href="{{ $media->getUrl() }}" target="_blank"
                                           class="text-blue-600 hover:text-blue-800 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 text-sm">No attachments.</p>
                        @endif
                    </div>

                    {{-- Delivery Info --}}
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Delivery Information</h3>

                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="text-gray-500">Mode:</span>
                                <p class="font-medium">{{ $correspondence->delivery_mode ?? '-' }}</p>
                            </div>
                            @if($correspondence->courier_name)
                                <div>
                                    <span class="text-gray-500">Courier:</span>
                                    <p class="font-medium">{{ $correspondence->courier_name }}</p>
                                </div>
                            @endif
                            @if($correspondence->courier_tracking)
                                <div>
                                    <span class="text-gray-500">Tracking:</span>
                                    <p class="font-medium">{{ $correspondence->courier_tracking }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Meta Info --}}
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Record Info</h3>

                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="text-gray-500">Created By:</span>
                                <p class="font-medium">{{ $correspondence->creator?->name ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ $correspondence->created_at?->format('d-M-Y H:i') }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Last Updated By:</span>
                                <p class="font-medium">{{ $correspondence->updater?->name ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ $correspondence->updated_at?->format('d-M-Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Remarks --}}
                    @if($correspondence->remarks)
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Remarks</h3>
                            <p class="text-sm text-gray-700">{{ $correspondence->remarks }}</p>
                        </div>
                    @endif
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
