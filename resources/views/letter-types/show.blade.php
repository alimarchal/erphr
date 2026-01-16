<x-app-layout>
    <x-slot name="header">
        <x-page-header title="{{ $letterType->name }}" backRoute="letter-types.index" />
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Basic Information</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Name</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $letterType->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Code</p>
                                    <p class="text-lg font-semibold text-indigo-600">{{ $letterType->code }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="border-t pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Settings</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Requires Reply</p>
                                    @if($letterType->requires_reply)
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 mt-1">
                                            Yes
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700 mt-1">
                                            No
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Default Days to Reply</p>
                                    <p class="text-lg font-semibold text-gray-900 mt-1">
                                        {{ $letterType->default_days_to_reply ?? '-' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Status</p>
                                    @if($letterType->is_active)
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 mt-1">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 mt-1">
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="border-t pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Audit Information</h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-600">Created At</p>
                                    <p class="font-semibold text-gray-900">{{ $letterType->created_at->format('M d, Y H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Updated At</p>
                                    <p class="font-semibold text-gray-900">{{ $letterType->updated_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t pt-6 mt-6 flex justify-end gap-3">
                        <a href="{{ route('letter-types.edit', $letterType) }}" class="inline-block">
                            <x-button>Edit</x-button>
                        </a>
                        <form method="POST" action="{{ route('letter-types.destroy', $letterType) }}"
                            onsubmit="return confirm('Are you sure? This action cannot be undone.')" class="inline">
                            @csrf
                            @method('DELETE')
                            <x-danger-button type="submit">Delete</x-danger-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
