{{-- Mark To Modal --}}
<div id="mark-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-6 border w-full max-w-3xl shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-900">Mark Correspondence</h3>
            <button type="button" onclick="document.getElementById('mark-modal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('correspondence.mark', $correspondence) }}" enctype="multipart/form-data">
            @csrf

            {{-- Row 1: Mark To & Action --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <x-label for="to_user_id" value="Mark To" :required="true" />
                    <select id="to_user_id" name="to_user_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Select Person</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-label for="action" value="Action" :required="true" />
                    <select id="action" name="action" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="Mark">Mark</option>
                        <option value="Forward">Forward</option>
                        <option value="ForInfo">For Information</option>
                        <option value="ForAction">For Action</option>
                        <option value="ForApproval">For Approval</option>
                        <option value="ForSignature">For Signature</option>
                        <option value="ForComments">For Comments</option>
                        <option value="ForReview">For Review</option>
                        <option value="ForReply">For Reply</option>
                        <option value="Return">Return</option>
                    </select>
                </div>
            </div>

            {{-- Row 2: Expected Response Date & Status --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <x-label for="expected_response_date" value="Expected Response Date" />
                    <x-input id="expected_response_date" type="date" name="expected_response_date" class="mt-1 block w-full" />
                </div>

                <div>
                    <x-label for="status_id" value="Update Status (Optional)" />
                    <select id="status_id" name="status_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Keep Current Status</option>
                        @php
                            $markStatuses = \App\Models\CorrespondenceStatus::active()
                                ->where(function($q) use ($correspondence) {
                                    $q->where('type', $correspondence->type)->orWhere('type', 'Both');
                                })->ordered()->get();
                        @endphp
                        @foreach($markStatuses as $status)
                            <option value="{{ $status->id }}" {{ $correspondence->status_id == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Row 3: Instructions (Full Width) --}}
            <div class="mb-4">
                <x-label for="instructions" value="Instructions" />
                <textarea id="instructions" name="instructions" rows="3"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    placeholder="Any specific instructions..."></textarea>
            </div>

            {{-- Row 4: Attachments (Full Width) --}}
            <div class="mb-4">
                <x-label for="mark_attachments" value="Attach Files (Optional)" />
                <input type="file" id="mark_attachments" name="attachments[]" multiple
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                <p class="text-xs text-gray-500 mt-1">You can upload multiple files. Max 15MB per file.</p>
            </div>

            {{-- Row 5: Urgent Checkbox --}}
            <div class="mb-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_urgent" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                    <span class="ml-2 text-sm font-medium text-gray-700">Mark as Urgent</span>
                </label>
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button type="button" onclick="document.getElementById('mark-modal').classList.add('hidden')"
                        class="px-5 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Submit
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
