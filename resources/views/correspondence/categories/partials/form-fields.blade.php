<div>
    <x-label for="name" value="Category Name" :required="true" />
    <x-input id="name" type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required autofocus
        class="mt-1 block w-full" placeholder="e.g., Financial Documents" />
</div>

<div class="mt-4">
    <x-label for="code" value="Category Code" />
    <x-input id="code" type="text" name="code" value="{{ old('code', $category->code ?? '') }}"
        class="mt-1 block w-full" placeholder="e.g., FD" maxlength="20" />
    <p class="text-xs text-gray-500 mt-1">Optional: A short code to identify the category</p>
</div>

<div class="mt-4">
    <x-label for="parent_id" value="Parent Category" />
    <select id="parent_id" name="parent_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        <option value="">-- No Parent (Root Category) --</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id ?? null) == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
                @if($cat->parent_id)
                    ({{ $cat->parent->name }})
                @endif
            </option>
        @endforeach
    </select>
    <p class="text-xs text-gray-500 mt-1">Optional: Create a hierarchy by selecting a parent category</p>
</div>