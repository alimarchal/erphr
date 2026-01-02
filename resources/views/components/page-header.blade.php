@props(['title', 'createRoute' => null, 'createLabel' => 'Create New'])

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8">
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title }}
        </h2>

        @if($createRoute)
            <a href="{{ $createRoute }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                {{ $createLabel }}
            </a>
        @endif
    </div>
</div>