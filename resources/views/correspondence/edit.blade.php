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
                    <form method="POST" action="{{ route('correspondence.update', $correspondence) }}" enctype="multipart/form-data" id="correspondenceEditForm">
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
                            'currentDivisionShortName' => $currentDivisionShortName,
                        ])

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('correspondence.show', $correspondence) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 mr-3">
                                Cancel
                            </a>
                            <x-button type="submit" class="ml-4" id="updateBtn">
                                <span id="updateText">Update {{ $type === 'Receipt' ? 'Receipt' : 'Dispatch' }}</span>
                                <span id="updatingText" class="hidden">Updating...</span>
                            </x-button>
                        </div>
                    </form>

                    <script>
                        document.getElementById('correspondenceEditForm').addEventListener('submit', function(e) {
                            const updateBtn = document.getElementById('updateBtn');
                            const updateText = document.getElementById('updateText');
                            const updatingText = document.getElementById('updatingText');
                            
                            updateBtn.disabled = true;
                            updateBtn.classList.add('opacity-50', 'cursor-not-allowed');
                            updateText.classList.add('hidden');
                            updatingText.classList.remove('hidden');
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
