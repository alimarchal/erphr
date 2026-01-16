@php
    /** @var \App\Models\LetterType|null $letterType */
    $letterType = $letterType ?? null;
@endphp

<div>
    <x-label for="name" value="Letter Type Name" :required="true" />
    <x-input id="name" type="text" name="name" class="mt-1 block w-full"
        :value="old('name', optional($letterType)->name)" required autofocus placeholder="e.g., Urgent Letter" />
</div>

<div class="mt-4">
    <x-label for="code" value="Code" :required="true" />
    <x-input id="code" type="text" name="code" class="mt-1 block w-full"
        :value="old('code', optional($letterType)->code)" required placeholder="e.g., UL" maxlength="50" />
    <p class="text-xs text-gray-500 mt-1">Required: A unique code to identify the letter type</p>
</div>

<div class="mt-4">
    <x-label for="requires_reply" value="Requires Reply" />
    <div class="mt-2 flex items-center">
        <input id="requires_reply" type="checkbox" name="requires_reply" value="1"
            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            {{ old('requires_reply', optional($letterType)->requires_reply) ? 'checked' : '' }} />
        <label for="requires_reply" class="ms-2 text-sm text-gray-600">
            This letter type requires a reply
        </label>
    </div>
</div>

<div class="mt-4">
    <x-label for="default_days_to_reply" value="Default Days to Reply" />
    <x-input id="default_days_to_reply" type="number" name="default_days_to_reply" class="mt-1 block w-full"
        :value="old('default_days_to_reply', optional($letterType)->default_days_to_reply)" placeholder="e.g., 7"
        min="0" />
    <p class="text-xs text-gray-500 mt-1">Optional: Number of days for reply when applicable</p>
</div>

<div class="mt-4">
    <x-label for="is_active" value="Status" />
    <div class="mt-2 flex items-center">
        <input id="is_active" type="checkbox" name="is_active" value="1"
            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            {{ old('is_active', optional($letterType)->is_active ?? true) ? 'checked' : '' }} />
        <label for="is_active" class="ms-2 text-sm text-gray-600">
            Active
        </label>
    </div>
</div>
