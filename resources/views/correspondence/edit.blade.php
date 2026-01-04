<x-app-layout>
    <x-slot name="header">
        <x-page-header
            :title="'Edit: ' . $correspondence->register_number"
            :backUrl="route('correspondence.show', $correspondence)"
        />
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-status-message class="mb-4 mt-4 shadow-md" />
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <x-validation-errors class="mb-4 mt-4" />
                    <form method="POST" action="{{ route('correspondence.update', $correspondence) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @include('correspondence.partials.form-fields', [
                            'correspondence' => $correspondence,
                            'type' => $type,
                            'letterTypes' => $letterTypes,
                            'categories' => $categories,
                            'priorities' => $priorities,
                            'statuses' => $statuses,
                            'divisions' => $divisions,
                            'users' => $users,
                        ])

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('correspondence.show', $correspondence) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 mr-3">
                                Cancel
                            </a>
                            <x-button class="ml-4">
                                Update {{ $type === 'Receipt' ? 'Receipt' : 'Dispatch' }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
