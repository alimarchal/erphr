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
                @can('create correspondence')
                <a href="{{ route('correspondence.create', ['type' => 'Receipt']) }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="hidden md:inline-block">New Receipt</span>
                </a>
                @endcan

                {{-- New Dispatch Button --}}
                @can('create correspondence')
                <a href="{{ route('correspondence.create', ['type' => 'Dispatch']) }}"
                    class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="hidden md:inline-block">New Dispatch</span>
                </a>
                @endcan

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
        ['label' => 'Date'],
        ['label' => 'Register No.'],
        ['label' => 'Subject'],
        ['label' => 'From/To'],
        ['label' => 'Confidentiality', 'align' => 'text-center'],
        ['label' => 'Priority', 'align' => 'text-center'],
        ['label' => 'Status', 'align' => 'text-center'],
        ['label' => 'Current Holder'],
        ['label' => 'Actions', 'align' => 'text-center'],
    ]" emptyMessage="No correspondence found." :emptyRoute="route('correspondence.create', ['type' => 'Receipt'])"
        emptyLinkText="Create one">
        @foreach ($correspondences as $index => $item)
        <tr class="border-b border-gray-200 text-sm hover:bg-gray-50 {{ $item->isOverdue() ? 'bg-red-50' : '' }}">
            {{-- S.No --}}
            <td class="py-2 px-2 text-center">
                {{ $correspondences->firstItem() + $index }}
            </td>

            {{-- Date --}}
            <td class="py-2 px-2 whitespace-nowrap">
                {{ $item->type === 'Receipt' ? $item->received_date?->format('d-m-Y') : $item->dispatch_date?->format('d-m-Y') }}
                @if($item->letter_date)
                    <div class="text-xs text-gray-500">Letter: {{ $item->letter_date->format('d-m-Y') }}</div>
                @endif
            </td>

            {{-- Register No. --}}
            <td class="py-2 px-2">
                <a href="{{ route('correspondence.show', $item) }}" class="text-blue-600 hover:underline font-medium">
                    {{ $item->register_number }}
                </a>
                @if($item->letterType)
                    <div class="text-xs font-semibold text-gray-600">{{ $item->letterType->name }}</div>
                @endif
                @if($item->reference_number)
                    <div class="text-xs text-gray-500">Ref: {{ $item->reference_number }}</div>
                @endif
            </td>

            {{-- Subject --}}
            <td class="py-2 px-2 max-w-xs">
                <div title="{{ $item->subject }}">{{ Str::limit($item->subject, 50) }}</div>
                <div class="flex flex-wrap gap-1 mt-1">
                    @if($item->category)
                        <span class="text-[10px] px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded border border-gray-200">
                            {{ $item->category->name }}
                        </span>
                    @endif
                    @if($item->initial_action)
                        <span class="text-[10px] px-1.5 py-0.5 bg-blue-50 text-blue-600 rounded border border-blue-100">
                            Marking: {{ $item->initial_action }}
                        </span>
                    @endif
                </div>
            </td>

            {{-- From/To --}}
            <td class="py-2 px-2">
                @if($item->sender_name)
                    {{ Str::limit($item->sender_name, 30) }}
                @elseif($item->type === 'Receipt' && $item->fromDivision)
                    {{ $item->fromDivision->short_name }}
                @elseif($item->type === 'Dispatch' && $item->toDivision)
                    {{ $item->toDivision->short_name }}
                @else
                    -
                @endif
            </td>

            {{-- Confidentiality --}}
            <td class="py-2 px-2 text-center">
                @if($item->confidentiality)
                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                        {{ $item->confidentiality === 'Normal' ? 'bg-gray-100 text-gray-700' : '' }}
                        {{ $item->confidentiality === 'Confidential' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $item->confidentiality === 'Secret' ? 'bg-orange-100 text-orange-700' : '' }}
                        {{ $item->confidentiality === 'TopSecret' ? 'bg-red-100 text-red-700' : '' }}">
                        {{ $item->confidentiality }}
                    </span>
                @else
                    -
                @endif
            </td>

            {{-- Priority --}}
            <td class="py-2 px-2 text-center">
                @if($item->priority)
                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                        {{ $item->priority->color === 'red' ? 'bg-red-100 text-red-700' : '' }}
                        {{ $item->priority->color === 'orange' ? 'bg-orange-100 text-orange-700' : '' }}
                        {{ $item->priority->color === 'yellow' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $item->priority->color === 'green' ? 'bg-green-100 text-green-700' : '' }}">
                        {{ $item->priority->name }}
                    </span>
                @else
                    -
                @endif
            </td>

            {{-- Status --}}
            <td class="py-2 px-2 text-center">
                @if($item->status)
                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                        {{ $item->status->color === 'blue' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $item->status->color === 'yellow' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $item->status->color === 'green' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $item->status->color === 'gray' ? 'bg-gray-100 text-gray-700' : '' }}
                        {{ $item->status->color === 'purple' ? 'bg-purple-100 text-purple-700' : '' }}
                        {{ $item->status->color === 'orange' ? 'bg-orange-100 text-orange-700' : '' }}
                        {{ $item->status->color === 'indigo' ? 'bg-indigo-100 text-indigo-700' : '' }}
                        {{ $item->status->color === 'teal' ? 'bg-teal-100 text-teal-700' : '' }}">
                        {{ $item->status->name }}
                    </span>
                @else
                    -
                @endif
            </td>

            {{-- Current Holder --}}
            <td class="py-2 px-2">
                <div class="font-semibold text-gray-900">{{ $item->currentHolder?->name ?? '-' }}</div>
                @if($item->toDivision)
                    <div class="text-xs text-gray-500">{{ $item->toDivision->short_name }}</div>
                @endif
                @if($item->due_date)
                    <div class="text-xs {{ $item->isOverdue() ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                        Due: {{ $item->due_date->format('d-m-Y') }}
                    </div>
                @endif
            </td>

            {{-- Actions --}}
            <td class="py-2 px-2 text-center">
                <div class="flex justify-center space-x-1">
                    @can('view correspondence')
                    <a href="{{ route('correspondence.show', $item) }}"
                        class="inline-flex items-center justify-center w-7 h-7 text-blue-600 hover:bg-blue-100 rounded-md"
                        title="View">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>
                    @endcan
                    @can('edit correspondence')
                    <a href="{{ route('correspondence.edit', $item) }}"
                        class="inline-flex items-center justify-center w-7 h-7 text-green-600 hover:bg-green-100 rounded-md"
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