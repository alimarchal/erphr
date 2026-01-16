<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight inline-block">
            View Letter Type: {{ $letterType->name }}
        </h2>
        <div class="flex justify-center items-center float-right">
            <a href="{{ route('letter-types.index') }}"
                class="inline-flex items-center ml-2 px-4 py-2 bg-blue-950 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-800 focus:bg-green-800 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-status-message class="mb-4 mt-4" />
                <div class="p-6">
                    <form>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-label for="name" value="Name" />
                                <x-input id="name" type="text" name="name"
                                    class="mt-1 block w-full cursor-not-allowed bg-gray-100"
                                    :value="$letterType->name" disabled readonly />
                            </div>

                            <div>
                                <x-label for="code" value="Code" />
                                <x-input id="code" type="text" name="code"
                                    class="mt-1 block w-full cursor-not-allowed bg-gray-100"
                                    :value="$letterType->code" disabled readonly />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-label for="requires_reply" value="Requires Reply" />
                                <x-input id="requires_reply" type="text" name="requires_reply"
                                    class="mt-1 block w-full cursor-not-allowed bg-gray-100"
                                    :value="$letterType->requires_reply ? 'Yes' : 'No'" disabled readonly />
                            </div>

                            <div>
                                <x-label for="default_days_to_reply" value="Default Days to Reply" />
                                <x-input id="default_days_to_reply" type="text" name="default_days_to_reply"
                                    class="mt-1 block w-full cursor-not-allowed bg-gray-100"
                                    :value="$letterType->default_days_to_reply ?? '-'" disabled readonly />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-label for="is_active" value="Status" />
                                <x-input id="is_active" type="text" name="is_active"
                                    class="mt-1 block w-full cursor-not-allowed bg-gray-100"
                                    :value="$letterType->is_active ? 'Active' : 'Inactive'" disabled readonly />
                            </div>

                            <div>
                                <x-label for="created_at" value="Created At" />
                                <x-input id="created_at" type="text" name="created_at"
                                    class="mt-1 block w-full cursor-not-allowed bg-gray-100"
                                    :value="$letterType->created_at?->format('d-m-Y H:i:s') ?? '-'" disabled readonly />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-label for="updated_at" value="Updated At" />
                                <x-input id="updated_at" type="text" name="updated_at"
                                    class="mt-1 block w-full cursor-not-allowed bg-gray-100"
                                    :value="$letterType->updated_at?->format('d-m-Y H:i:s') ?? '-'" disabled readonly />
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('letter-types.edit', $letterType) }}" class="inline-block">
                                <x-button>Edit</x-button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>