<x-app-layout>
    <x-slot name="header">
        <x-page-header
            :title="$type === 'Receipt' ? 'New Receipt Entry' : 'New Dispatch Entry'"
            :backUrl="route('correspondence.index', ['type' => $type])"
        />
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-status-message class="mb-4 mt-4 shadow-md" />
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <x-validation-errors class="mb-4 mt-4" />
                    <form method="POST" action="{{ route('correspondence.store') }}" enctype="multipart/form-data">
                        @csrf

                        @include('correspondence.partials.form-fields', [
                            'type' => $type,
                            'letterTypes' => $letterTypes,
                            'categories' => $categories,
                            'priorities' => $priorities,
                            'statuses' => $statuses,
                            'divisions' => $divisions,
                            'users' => $users,
                        ])

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('correspondence.index', ['type' => $type]) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 mr-3">
                                Cancel
                            </a>
                            <x-button class="ml-4">
                                Create {{ $type === 'Receipt' ? 'Receipt' : 'Dispatch' }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
