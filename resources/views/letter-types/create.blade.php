<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight inline-block">
            Create Letter Type
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
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form method="POST" action="{{ route('letter-types.store') }}" class="p-6 space-y-6">
                    @csrf

                    <div>
                        <x-label for="name" value="Letter Type Name" />
                        <x-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')"
                            placeholder="e.g., Urgent Letter" required autofocus />
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-label for="code" value="Code" />
                        <x-input id="code" name="code" type="text" class="mt-1 block w-full" :value="old('code')"
                            placeholder="e.g., UL" required />
                        @error('code')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-label for="requires_reply" value="Requires Reply" />
                        <div class="mt-2 flex items-center">
                            <input id="requires_reply" name="requires_reply" type="checkbox" value="1"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                {{ old('requires_reply') ? 'checked' : '' }} />
                            <label for="requires_reply" class="ms-2 text-sm text-gray-600">
                                Check if this letter type requires a reply
                            </label>
                        </div>
                    </div>

                    <div>
                        <x-label for="default_days_to_reply" value="Default Days to Reply" />
                        <x-input id="default_days_to_reply" name="default_days_to_reply" type="number"
                            class="mt-1 block w-full" :value="old('default_days_to_reply')" placeholder="e.g., 7"
                            min="0" />
                        @error('default_days_to_reply')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-label for="is_active" value="Status" />
                        <div class="mt-2 flex items-center">
                            <input id="is_active" name="is_active" type="checkbox" value="1"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                {{ old('is_active', true) ? 'checked' : '' }} />
                            <label for="is_active" class="ms-2 text-sm text-gray-600">
                                Active
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('letter-types.index') }}" class="inline-block">
                            <x-secondary-button>Cancel</x-secondary-button>
                        </a>
                        <x-button type="submit">Create</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>