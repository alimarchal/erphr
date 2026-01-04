@php
    /** @var \App\Models\Correspondence|null $correspondence */
    $correspondence = $correspondence ?? null;
    $isReceipt = $type === 'Receipt';
@endphp

<input type="hidden" name="type" value="{{ $type }}">

{{-- Basic Information --}}
<div class="border-b pb-4 mb-4">
    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $isReceipt ? 'Receipt' : 'Dispatch' }} Information</h3>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <x-label for="letter_type_id" value="Letter Type" />
            <select id="letter_type_id" name="letter_type_id" class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Select Type</option>
                @foreach($letterTypes as $letterType)
                    <option value="{{ $letterType->id }}"
                        {{ old('letter_type_id', $correspondence?->letter_type_id) == $letterType->id ? 'selected' : '' }}>
                        {{ $letterType->name }} ({{ $letterType->code }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <x-label for="category_id" value="Category" />
            <select id="category_id" name="category_id" class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id', $correspondence?->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <x-label for="priority_id" value="Priority" />
            <select id="priority_id" name="priority_id" class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Select Priority</option>
                @foreach($priorities as $priority)
                    <option value="{{ $priority->id }}"
                        {{ old('priority_id', $correspondence?->priority_id) == $priority->id ? 'selected' : '' }}>
                        {{ $priority->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <x-label :for="$isReceipt ? 'received_date' : 'dispatch_date'"
                :value="$isReceipt ? 'Received Date' : 'Dispatch Date'" :required="true" />
            @if($isReceipt)
                <x-input id="received_date" type="date" name="received_date" class="mt-1 block w-full"
                    :value="old('received_date', $correspondence?->received_date?->format('Y-m-d') ?? now()->format('Y-m-d'))" required />
            @else
                <x-input id="dispatch_date" type="date" name="dispatch_date" class="mt-1 block w-full"
                    :value="old('dispatch_date', $correspondence?->dispatch_date?->format('Y-m-d') ?? now()->format('Y-m-d'))" required />
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
        <div>
            <x-label for="reference_number" value="Reference Number" />
            <x-input id="reference_number" type="text" name="reference_number" class="mt-1 block w-full"
                :value="old('reference_number', $correspondence?->reference_number)"
                placeholder="Original letter reference" />
        </div>

        <div>
            <x-label for="letter_date" value="Letter Date" />
            <x-input id="letter_date" type="date" name="letter_date" class="mt-1 block w-full"
                :value="old('letter_date', $correspondence?->letter_date?->format('Y-m-d'))" />
        </div>

        @if($isReceipt)
            <div>
                <x-label for="status_id" value="Status" />
                <select id="status_id" name="status_id" class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Auto (Initial)</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}"
                            {{ old('status_id', $correspondence?->status_id) == $status->id ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <x-label for="confidentiality" value="Confidentiality" />
                <select id="confidentiality" name="confidentiality" class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="Normal" {{ old('confidentiality', $correspondence?->confidentiality ?? 'Normal') === 'Normal' ? 'selected' : '' }}>Normal</option>
                    <option value="Confidential" {{ old('confidentiality', $correspondence?->confidentiality) === 'Confidential' ? 'selected' : '' }}>Confidential</option>
                    <option value="Secret" {{ old('confidentiality', $correspondence?->confidentiality) === 'Secret' ? 'selected' : '' }}>Secret</option>
                    <option value="TopSecret" {{ old('confidentiality', $correspondence?->confidentiality) === 'TopSecret' ? 'selected' : '' }}>Top Secret</option>
                </select>
            </div>
        @endif

        @if(!$isReceipt)
            <div>
                <x-label for="due_date" value="Expected Reply Date" />
                <x-input id="due_date" type="date" name="due_date" class="mt-1 block w-full"
                    :value="old('due_date', $correspondence?->due_date?->format('Y-m-d'))" />
            </div>

            <div>
                <x-label for="confidentiality" value="Confidentiality" />
                <select id="confidentiality" name="confidentiality" class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="Normal" {{ old('confidentiality', $correspondence?->confidentiality ?? 'Normal') === 'Normal' ? 'selected' : '' }}>Normal</option>
                    <option value="Confidential" {{ old('confidentiality', $correspondence?->confidentiality) === 'Confidential' ? 'selected' : '' }}>Confidential</option>
                    <option value="Secret" {{ old('confidentiality', $correspondence?->confidentiality) === 'Secret' ? 'selected' : '' }}>Secret</option>
                    <option value="TopSecret" {{ old('confidentiality', $correspondence?->confidentiality) === 'TopSecret' ? 'selected' : '' }}>Top Secret</option>
                </select>
            </div>
        @endif
    </div>

    @if(!$isReceipt)
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
            <div>
                <x-label for="status_id" value="Status" />
                <select id="status_id" name="status_id" class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Auto (Initial)</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}"
                            {{ old('status_id', $correspondence?->status_id) == $status->id ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
</div>

{{-- From/To Information (Most Important Section) --}}
<div class="border-b pb-4 mb-4">
    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $isReceipt ? 'Sender' : 'Recipient' }} Information</h3>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <x-label for="sender_name" value="External Party (Sender/Recipient)" />
            <x-input id="sender_name" type="text" name="sender_name" class="mt-1 block w-full"
                :value="old('sender_name', $correspondence?->sender_name)"
                placeholder="e.g., Ministry of Finance, ABC Company, President Office" />
            <p class="text-xs text-gray-500 mt-1">Enter for external correspondence</p>
        </div>

        <div>
            <x-label for="from_division_id" value="From Division (Internal)" />
            <select id="from_division_id" name="from_division_id"
                    class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Select Division</option>
                @foreach($divisions as $division)
                    <option value="{{ $division->id }}"
                        {{ old('from_division_id', $correspondence?->from_division_id) == $division->id ? 'selected' : '' }}>
                        {{ $division->name }} ({{ $division->short_name }})
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-1">Select for internal correspondence</p>
        </div>

        <div>
            <x-label for="region_id" value="Region" />
            <select id="region_id" name="region_id"
                    class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Select Region</option>
                @foreach($regions as $region)
                    <option value="{{ $region->id }}"
                        {{ old('region_id', $correspondence?->region_id) == $region->id ? 'selected' : '' }}>
                        {{ $region->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <x-label for="branch_id" value="Branch" />
            <select id="branch_id" name="branch_id"
                    class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Select Branch</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}"
                        data-region="{{ $branch->region_id }}"
                        {{ old('branch_id', $correspondence?->branch_id) == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }} ({{ $branch->code }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

{{-- Subject and Description --}}
<div class="border-b pb-4 mb-4">
    <div class="mb-4">
        <x-label for="subject" value="Subject" :required="true" />
        <x-input id="subject" type="text" name="subject" class="mt-1 block w-full"
            :value="old('subject', $correspondence?->subject)" required
            placeholder="Enter the subject of the correspondence" />
    </div>

    <div>
        <x-label for="description" value="Description / Summary" />
        <textarea id="description" name="description" rows="3"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
            placeholder="Brief description or summary...">{{ old('description', $correspondence?->description) }}</textarea>
    </div>
</div>

{{-- Assignment (Only for Receipt - not for Dispatch) --}}
@if($isReceipt)
<div class="border-b pb-4 mb-4">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Initial Marking / Action Assignment</h3>

    @php
        // Calculate 7 working days (Monday-Friday) from today
        $defaultDueDate = now();
        $workingDaysAdded = 0;
        while ($workingDaysAdded < 7) {
            $defaultDueDate->addDay();
            if ($defaultDueDate->isWeekday()) {
                $workingDaysAdded++;
            }
        }
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <x-label for="addressed_to_user_id" value="Addressed To" />
            <select id="addressed_to_user_id" name="addressed_to_user_id" class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Select Person</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}"
                        {{ old('addressed_to_user_id', $correspondence?->addressed_to_user_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <x-label for="initial_action" value="Action Required" />
            <select id="initial_action" name="initial_action" class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Select Action</option>
                <option value="Mark" {{ old('initial_action', $correspondence?->initial_action) === 'Mark' ? 'selected' : '' }}>Mark</option>
                <option value="ForAction" {{ old('initial_action', $correspondence?->initial_action) === 'ForAction' ? 'selected' : '' }}>For Action</option>
                <option value="ForComments" {{ old('initial_action', $correspondence?->initial_action) === 'ForComments' ? 'selected' : '' }}>For Comments</option>
                <option value="ForApproval" {{ old('initial_action', $correspondence?->initial_action) === 'ForApproval' ? 'selected' : '' }}>For Approval</option>
                <option value="ForSignature" {{ old('initial_action', $correspondence?->initial_action) === 'ForSignature' ? 'selected' : '' }}>For Signature</option>
                <option value="ForReview" {{ old('initial_action', $correspondence?->initial_action) === 'ForReview' ? 'selected' : '' }}>For Review</option>
                <option value="ForInfo" {{ old('initial_action', $correspondence?->initial_action) === 'ForInfo' ? 'selected' : '' }}>For Info</option>
                <option value="ForRecord" {{ old('initial_action', $correspondence?->initial_action) === 'ForRecord' ? 'selected' : '' }}>For Record</option>
            </select>
        </div>

        <div>
            <x-label for="due_date" value="Due Date" />
            <x-input id="due_date" type="date" name="due_date" class="mt-1 block w-full"
                :value="old('due_date', $correspondence?->due_date?->format('Y-m-d') ?? $defaultDueDate->format('Y-m-d'))" />
        </div>
    </div>
</div>
@endif

{{-- Delivery Information --}}
<div class="border-b pb-4 mb-4">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Delivery Information</h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <x-label for="delivery_mode" value="Delivery Mode" />
            <select id="delivery_mode" name="delivery_mode" class="select2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Select Mode</option>
                <option value="Hand" {{ old('delivery_mode', $correspondence?->delivery_mode) === 'Hand' ? 'selected' : '' }}>By Hand</option>
                <option value="Courier" {{ old('delivery_mode', $correspondence?->delivery_mode) === 'Courier' ? 'selected' : '' }}>Courier</option>
                <option value="Post" {{ old('delivery_mode', $correspondence?->delivery_mode) === 'Post' ? 'selected' : '' }}>Post</option>
                <option value="Email" {{ old('delivery_mode', $correspondence?->delivery_mode) === 'Email' ? 'selected' : '' }}>Email</option>
                <option value="Fax" {{ old('delivery_mode', $correspondence?->delivery_mode) === 'Fax' ? 'selected' : '' }}>Fax</option>
                <option value="Other" {{ old('delivery_mode', $correspondence?->delivery_mode) === 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <div>
            <x-label for="courier_name" value="Courier Name" />
            <x-input id="courier_name" type="text" name="courier_name" class="mt-1 block w-full"
                :value="old('courier_name', $correspondence?->courier_name)"
                placeholder="TCS, Leopard, etc." />
        </div>

        <div>
            <x-label for="courier_tracking" value="Tracking Number" />
            <x-input id="courier_tracking" type="text" name="courier_tracking" class="mt-1 block w-full"
                :value="old('courier_tracking', $correspondence?->courier_tracking)"
                placeholder="Tracking number" />
        </div>
    </div>
</div>

{{-- Attachments --}}
<div class="border-b pb-4 mb-4">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Attachments</h3>

    @if($correspondence && $correspondence->getMedia('attachments')->count() > 0)
        <div class="mb-4">
            <p class="text-sm font-medium text-gray-700 mb-2">Existing Attachments:</p>
            <ul class="space-y-1">
                @foreach($correspondence->getMedia('attachments') as $media)
                    <li class="flex items-center text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                        <a href="{{ $media->getUrl() }}" target="_blank" class="text-blue-600 hover:underline">
                            {{ $media->file_name }}
                        </a>
                        <span class="text-gray-400 ml-2">({{ number_format($media->size / 1024, 1) }} KB)</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div>
        <x-label for="attachments" value="Upload New Attachments" />
        <input type="file" id="attachments" name="attachments[]" multiple
            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
        <p class="text-xs text-gray-500 mt-1">You can upload multiple files. Max 15MB per file.</p>
    </div>
</div>

{{-- Remarks --}}
<div>
    <x-label for="remarks" value="Remarks" />
    <textarea id="remarks" name="remarks" rows="2"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
        placeholder="Any additional remarks...">{{ old('remarks', $correspondence?->remarks) }}</textarea>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const regionSelect = document.getElementById('region_id');
    const branchSelect = document.getElementById('branch_id');
    
    if (regionSelect && branchSelect) {
        const allBranchOptions = Array.from(branchSelect.options);

        function filterBranches() {
            const selectedRegionId = regionSelect.value;
            
            // Clear current options
            branchSelect.innerHTML = '';
            
            // Add the default "Select Branch" option
            const defaultOption = allBranchOptions.find(opt => opt.value === '');
            if (defaultOption) {
                branchSelect.appendChild(defaultOption.cloneNode(true));
            }

            // Filter and add matching options
            allBranchOptions.forEach(option => {
                if (option.value !== '' && (!selectedRegionId || option.getAttribute('data-region') === selectedRegionId)) {
                    branchSelect.appendChild(option.cloneNode(true));
                }
            });

            // Re-initialize Select2 if it's being used
            if (typeof $ !== 'undefined' && $(branchSelect).data('select2')) {
                $(branchSelect).trigger('change.select2');
            }
        }

        regionSelect.addEventListener('change', filterBranches);
        
        // Initial filter if region is already selected (e.g., on edit or validation error)
        if (regionSelect.value) {
            filterBranches();
        }
    }
});
</script>
