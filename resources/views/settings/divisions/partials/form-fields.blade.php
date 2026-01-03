@php
    /** @var \App\Models\Division|null $division */
    $division = $division ?? null;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-label for="name" value="Division Name" :required="true" />
        <x-input id="name" type="text" name="name" class="mt-1 block w-full"
            :value="old('name', optional($division)->name)" required autofocus placeholder="Human Resource Management Division" />
    </div>

    <div>
        <x-label for="short_name" value="Short Name" :required="true" />
        <x-input id="short_name" type="text" name="short_name" class="mt-1 block w-full"
            :value="old('short_name', optional($division)->short_name)" required placeholder="HRMD" />
    </div>
</div>
