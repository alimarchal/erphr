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
                    <form method="POST" action="{{ route('correspondence.store') }}" enctype="multipart/form-data" id="correspondenceCreateForm">
                        @csrf

                        @include('correspondence.partials.form-fields', [
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
                            <a href="{{ route('correspondence.index', ['type' => $type]) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 mr-3">
                                Cancel
                            </a>
                            <x-button type="submit" class="ml-4" id="submitBtn">
                                <span id="submitText">Create {{ $type === 'Receipt' ? 'Receipt' : 'Dispatch' }}</span>
                                <span id="loadingText" class="hidden">Creating...</span>
                            </x-button>
                        </div>
                    </form>

                    <script>
                        document.getElementById('correspondenceCreateForm').addEventListener('submit', function(e) {
                            const submitBtn = document.getElementById('submitBtn');
                            const submitText = document.getElementById('submitText');
                            const loadingText = document.getElementById('loadingText');
                            
                            submitBtn.disabled = true;
                            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                            submitText.classList.add('hidden');
                            loadingText.classList.remove('hidden');
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
