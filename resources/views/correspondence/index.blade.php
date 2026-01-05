<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight inline-block">
                {{ $type === 'Receipt' ? 'Receipt Register' : ($type === 'Dispatch' ? 'Dispatch Register' : 'Correspondence Register') }}
            </h2>

            <div class="flex items-center space-x-3">
                {{-- Type Toggle Buttons --}}
                <div class="inline-flex rounded-lg border border-gray-200 bg-white p-1">
                    <a href="{{ route('correspondence.index') }}"
                       class="px-3 py-1.5 text-xs font-medium rounded-md {{ !$type ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        All
                    </a>
                    <a href="{{ route('correspondence.index', ['type' => 'Receipt']) }}"
                       class="px-3 py-1.5 text-xs font-medium rounded-md {{ $type === 'Receipt' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        Receipt
                    </a>
                    <a href="{{ route('correspondence.index', ['type' => 'Dispatch']) }}"
                       class="px-3 py-1.5 text-xs font-medium rounded-md {{ $type === 'Dispatch' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        Dispatch
                    </a>
                </div>

                {{-- Search Toggle Button --}}
                <button id="toggle"
                    class="inline-flex items-center px-4 py-2 bg-blue-950 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-950 focus:bg-green-800 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Search
                </button>

                {{-- New Receipt Button --}}
                <a href="{{ route('correspondence.create', ['type' => 'Receipt']) }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="hidden md:inline-block">New Receipt</span>
                </a>

                {{-- New Dispatch Button --}}
                <a href="{{ route('correspondence.create', ['type' => 'Dispatch']) }}"
                    class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="hidden md:inline-block">New Dispatch</span>
                </a>

                {{-- Refresh Button --}}
                <a href="javascript:window.location.reload();"
                    class="inline-flex items-center px-4 py-2 bg-blue-950 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-950 focus:bg-green-800 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                </a>

                {{-- Back Button --}}
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-950 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-800 focus:bg-green-800 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <x-filter-section :action="route('correspondence.index')">
        @if($type)
            <input type="hidden" name="type" value="{{ $type }}">
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <x-label for="filter_register_number" value="Register No." />
                <x-input id="filter_register_number" name="filter[register_number]" type="text" class="mt-1 block w-full"
                    :value="request('filter.register_number')" placeholder="Search..." />
            </div>

            <div>
                <x-label for="filter_subject" value="Subject" />
                <x-input id="filter_subject" name="filter[subject]" type="text" class="mt-1 block w-full"
                    :value="request('filter.subject')" placeholder="Search..." />
            </div>

            <div>
                <x-label for="filter_sender_name" value="Sender/External Party" />
                <x-input id="filter_sender_name" name="filter[sender_name]" type="text" class="mt-1 block w-full"
                    :value="request('filter.sender_name')" placeholder="Search..." />
            </div>

            <div>
                <x-label for="filter_letter_type_id" value="Letter Type" />
                <select id="filter_letter_type_id" name="filter[letter_type_id]" class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">All Types</option>
                    @foreach($letterTypes as $letterType)
                        <option value="{{ $letterType->id }}" {{ request('filter.letter_type_id') == $letterType->id ? 'selected' : '' }}>
                            {{ $letterType->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <x-label for="filter_category_id" value="Category" />
                <select id="filter_category_id" name="filter[category_id]" class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('filter.category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <x-label for="filter_priority_id" value="Priority" />
                <select id="filter_priority_id" name="filter[priority_id]" class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">All Priorities</option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority->id }}" {{ request('filter.priority_id') == $priority->id ? 'selected' : '' }}>
                            {{ $priority->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <x-label for="filter_status_id" value="Status" />
                <select id="filter_status_id" name="filter[status_id]" class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" {{ request('filter.status_id') == $status->id ? 'selected' : '' }}>
                            {{ $status->name }} ({{ $status->type }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <x-label for="filter_confidentiality" value="Confidentiality" />
                <select id="filter_confidentiality" name="filter[confidentiality]" class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">All Levels</option>
                    <option value="Normal" {{ request('filter.confidentiality') === 'Normal' ? 'selected' : '' }}>Normal</option>
                    <option value="Confidential" {{ request('filter.confidentiality') === 'Confidential' ? 'selected' : '' }}>Confidential</option>
                    <option value="Secret" {{ request('filter.confidentiality') === 'Secret' ? 'selected' : '' }}>Secret</option>
                    <option value="TopSecret" {{ request('filter.confidentiality') === 'TopSecret' ? 'selected' : '' }}>Top Secret</option>
                </select>
            </div>

            <div>
                <x-label for="filter_to_division_id" value="Assignment Division" />
                <select id="filter_to_division_id" name="filter[to_division_id]" class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">All Divisions</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division->id }}" {{ request('filter.to_division_id') == $division->id ? 'selected' : '' }}>
                            {{ $division->short_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <x-label for="filter_current_holder_id" value="Current Holder" />
                <select id="filter_current_holder_id" name="filter[current_holder_id]" class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('filter.current_holder_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <x-label for="filter_received_from" value="Date From" />
                <x-input id="filter_received_from" name="filter[received_from]" type="date" class="mt-1 block w-full"
                    :value="request('filter.received_from')" />
            </div>

            <div>
                <x-label for="filter_received_to" value="Date To" />
                <x-input id="filter_received_to" name="filter[received_to]" type="date" class="mt-1 block w-full"
                    :value="request('filter.received_to')" />
            </div>
        </div>

        <div class="mt-4 flex items-center gap-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="filter[overdue]" value="1" class="rounded border-gray-300 text-blue-600"
                    {{ request('filter.overdue') ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-600">Overdue Only</span>
            </label>
        </div>
    </x-filter-section>

    <x-data-table :items="$correspondences" :headers="[
        ['label' => 'S.No', 'align' => 'text-center'],
        ['label' => 'Type', 'align' => 'text-center'],
        ['label' => 'Date'],
        ['label' => 'Register No.'],
        ['label' => 'Subject'],
        ['label' => 'Category'],
        ['label' => 'Source/Destination'],
        ['label' => 'Priority', 'align' => 'text-center'],
        ['label' => 'Status', 'align' => 'text-center'],
        ['label' => 'Current Holder'],
        ['label' => 'Pending', 'align' => 'text-center'],
        ['label' => 'Creator'],
        ['label' => 'Actions', 'align' => 'text-center'],
    ]" emptyMessage="No correspondence found." :emptyRoute="route('correspondence.create', ['type' => 'Receipt'])"
        emptyLinkText="Create one">
        @foreach ($correspondences as $index => $item)
        <tr class="border-b border-gray-200 text-sm hover:bg-gray-50 {{ $item->isOverdue() ? 'bg-red-50' : '' }}">
            {{-- S.No --}}
            <td class="py-2 px-2 text-center">
                {{ $correspondences->firstItem() + $index }}
            </td>

            {{-- Type --}}
            <td class="py-2 px-2 text-center">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $item->type === 'Receipt' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-purple-100 text-purple-800 border border-purple-200' }}">
                    {{ $item->type }}
                </span>
            </td>

            {{-- Date --}}
            <td class="py-2 px-2 whitespace-nowrap">
                <div class="font-medium text-gray-900">
                    {{ $item->type === 'Receipt' ? $item->received_date?->format('d-m-Y') : $item->dispatch_date?->format('d-m-Y') }}
                </div>
                @if($item->letter_date)
                    <div class="text-[10px] text-gray-500">Letter: {{ $item->letter_date->format('d-m-Y') }}</div>
                @endif
            </td>

            {{-- Register No. --}}
            <td class="py-2 px-2">
                <a href="{{ route('correspondence.show', $item) }}" class="text-blue-600 hover:underline font-bold">
                    {{ $item->register_number }}
                </a>
                <div class="flex flex-col gap-0.5 mt-0.5">
                    @if($item->letterType)
                        <div class="text-[10px] font-semibold text-gray-600">{{ $item->letterType->name }}</div>
                    @endif
                    @if($item->confidentiality && $item->confidentiality !== 'Normal')
                        <div class="text-[10px] font-bold {{ $item->confidentiality === 'TopSecret' ? 'text-red-600' : ($item->confidentiality === 'Secret' ? 'text-orange-600' : 'text-blue-600') }}">
                            {{ $item->confidentiality }}
                        </div>
                    @endif
                </div>
            </td>

            {{-- Subject --}}
            <td class="py-2 px-2 max-w-xs">
                <div class="flex items-start gap-2">
                    <div title="{{ $item->subject }}" class="font-medium text-gray-900 flex-1 leading-tight">{{ Str::limit($item->subject, 60) }}</div>
                    @if($item->hasMedia('attachments'))
                        <div class="flex items-center text-gray-400 shrink-0" title="{{ $item->getMedia('attachments')->count() }} attachment(s)">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            <span class="text-[10px] ml-0.5">{{ $item->getMedia('attachments')->count() }}</span>
                        </div>
                    @endif
                </div>
                <div class="flex flex-wrap gap-1 mt-1">
                    @if($item->initial_action)
                        <span class="text-[9px] px-1 py-0.5 bg-blue-50 text-blue-600 rounded border border-blue-100 font-medium">
                            {{ $item->initial_action }}
                        </span>
                    @endif
                    @if($item->is_replied)
                        <span class="text-[9px] px-1 py-0.5 bg-green-50 text-green-600 rounded border border-green-100 font-medium">
                            Replied
                        </span>
                    @endif
                    @if($item->reference_number)
                        <span class="text-[9px] px-1 py-0.5 bg-gray-50 text-gray-600 rounded border border-gray-100 font-medium">
                            Ref: {{ Str::limit($item->reference_number, 15) }}
                        </span>
                    @endif
                </div>
            </td>

            {{-- Category --}}
            <td class="py-2 px-2">
                @if($item->category)
                    <span class="text-[11px] px-2 py-0.5 bg-gray-100 text-gray-700 rounded border border-gray-200 font-medium">
                        {{ $item->category->name }}
                    </span>
                @else
                    <span class="text-gray-400">-</span>
                @endif
            </td>

            {{-- Source/Destination --}}
            <td class="py-2 px-2">
                <div class="flex flex-col gap-0.5">
                    @if($item->sender_name)
                        <div class="font-semibold text-gray-900 leading-tight">{{ Str::limit($item->sender_name, 35) }}</div>
                    @endif

                    <div class="flex flex-wrap gap-x-2 gap-y-0.5 text-[10px] text-gray-500 font-medium">
                        @if($item->type === 'Receipt' && $item->fromDivision)
                            <span class="bg-gray-50 px-1 rounded border border-gray-100">From: {{ $item->fromDivision->short_name }}</span>
                        @elseif($item->type === 'Dispatch' && $item->toDivision)
                            <span class="bg-gray-50 px-1 rounded border border-gray-100">To: {{ $item->toDivision->short_name }}</span>
                        @endif

                        @if($item->region)
                            <span>{{ $item->region->name }}</span>
                        @endif

                        @if($item->branch)
                            <span>{{ $item->branch->name }}</span>
                        @endif
                    </div>
                </div>
            </td>

            {{-- Priority --}}
            <td class="py-2 px-2 text-center">
                @if($item->priority)
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold rounded-full border
                        {{ $item->priority->color === 'red' ? 'bg-red-50 text-red-700 border-red-100' : '' }}
                        {{ $item->priority->color === 'orange' ? 'bg-orange-50 text-orange-700 border-orange-100' : '' }}
                        {{ $item->priority->color === 'yellow' ? 'bg-yellow-50 text-yellow-700 border-yellow-100' : '' }}
                        {{ $item->priority->color === 'green' ? 'bg-green-50 text-green-700 border-green-100' : '' }}">
                        {{ $item->priority->name }}
                    </span>
                @else
                    <span class="text-gray-400">-</span>
                @endif
            </td>

            {{-- Status --}}
            <td class="py-2 px-2 text-center">
                @if($item->status)
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold rounded-full border
                        {{ $item->status->color === 'blue' ? 'bg-blue-50 text-blue-700 border-blue-100' : '' }}
                        {{ $item->status->color === 'yellow' ? 'bg-yellow-50 text-yellow-700 border-yellow-100' : '' }}
                        {{ $item->status->color === 'green' ? 'bg-green-50 text-green-700 border-green-100' : '' }}
                        {{ $item->status->color === 'gray' ? 'bg-gray-50 text-gray-700 border-gray-100' : '' }}
                        {{ $item->status->color === 'purple' ? 'bg-purple-50 text-purple-700 border-purple-100' : '' }}
                        {{ $item->status->color === 'orange' ? 'bg-orange-50 text-orange-700 border-orange-100' : '' }}
                        {{ $item->status->color === 'indigo' ? 'bg-indigo-50 text-indigo-700 border-indigo-100' : '' }}
                        {{ $item->status->color === 'teal' ? 'bg-teal-50 text-teal-700 border-teal-100' : '' }}">
                        {{ $item->status->name }}
                    </span>
                @else
                    <span class="text-gray-400">-</span>
                @endif
            </td>

            {{-- Current Holder --}}
            <td class="py-2 px-2">
                <div class="font-bold text-gray-900 leading-tight">{{ $item->currentHolder?->name ?? '-' }}</div>
                <div class="flex flex-col gap-0.5 mt-0.5">
                    @if($item->toDivision)
                        <div class="text-[9px] text-gray-500 uppercase tracking-wider font-bold">{{ $item->toDivision->short_name }}</div>
                    @endif
                    @if($item->current_holder_since && !$item->closed_at)
                        @php
                            $daysWithHolder = now()->diffInDays($item->current_holder_since);
                        @endphp
                        <div class="text-[9px] {{ $daysWithHolder > 3 ? 'text-orange-600 font-bold' : 'text-gray-400' }}">
                            Since {{ $daysWithHolder }} {{ Str::plural('day', $daysWithHolder) }}
                        </div>
                    @endif
                </div>
            </td>

            {{-- Pending --}}
            <td class="py-2 px-2 text-center">
                @php
                    $pendingDays = 0;
                    if (!$item->closed_at) {
                        $startDate = $item->received_date ?? $item->created_at;
                        $pendingDays = now()->diffInDays($startDate);
                    }
                @endphp

                @if(!$item->closed_at)
                    <div class="flex flex-col items-center">
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $pendingDays > 7 ? 'bg-red-50 text-red-700 border-red-100' : ($pendingDays > 3 ? 'bg-yellow-50 text-yellow-700 border-yellow-100' : 'bg-green-50 text-green-700 border-green-100') }}">
                            {{ $pendingDays }} {{ Str::plural('Day', $pendingDays) }}
                        </span>
                        @if($item->due_date)
                            <div class="text-[9px] mt-0.5 {{ $item->due_date->isPast() ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                                Due: {{ $item->due_date->format('d/m') }}
                            </div>
                        @endif
                    </div>
                @else
                    <span class="text-[10px] text-gray-400 italic font-medium">Closed</span>
                @endif
            </td>

            {{-- Creator --}}
            <td class="py-2 px-2">
                <div class="text-[10px] text-gray-600 font-medium">{{ $item->creator?->name ?? 'System' }}</div>
                <div class="text-[9px] text-gray-400">{{ $item->created_at->format('d/m/y H:i') }}</div>
            </td>

            {{-- Actions --}}
            <td class="py-2 px-2 text-center">
                <div class="flex justify-center space-x-1">
                    <a href="{{ route('correspondence.show', $item) }}"
                        class="inline-flex items-center justify-center w-7 h-7 text-blue-600 hover:bg-blue-100 rounded-md transition-colors"
                        title="View Details">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>
                    @can('edit correspondence')
                    <a href="{{ route('correspondence.edit', $item) }}"
                        class="inline-flex items-center justify-center w-7 h-7 text-green-600 hover:bg-green-100 rounded-md transition-colors"
                        title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                    @endcan
                </div>
            </td>
        </tr>
        @endforeach
    </x-data-table>
</x-app-layout>
