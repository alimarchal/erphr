<x-layouts.app.sidebar :title="$title ?? null" :header="$header ?? null">
    <flux:main>
        @if(isset($header))
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8">
                {{ $header }}
            </div>
        @endif
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
