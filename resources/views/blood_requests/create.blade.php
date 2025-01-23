<x-app-layout>
    <x-slot name="title">GoBlood | Create Blood Request</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Blood Request') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="container py-8 bg-white sm:rounded-lg">

            <!-- Display Error Message -->
            @if(session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: "{{ session('error') }}",
                        });
                    });
                </script>
            @endif

            <!-- Display Validation Errors -->
            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('blood_requests.store') }}" method="POST">
                @csrf

                <!-- Request Type -->
                <div class="form-group">
                    <label for="request_type" class="font-semibold">Request Type</label>
                    <select name="request_type" id="request_type" class="form-control w-full p-2 border rounded" required>
                        <option value="" disabled selected>Select Request Type</option>
                        <option value="self" {{ old('request_type') == 'self' ? 'selected' : '' }}>For Myself</option>
                        <option value="other" {{ old('request_type') == 'other' ? 'selected' : '' }}>For Someone Else</option>
                    </select>
                </div>

                <!-- IC Number -->
                <div class="form-group mt-4" id="ic_number_field" style="display: none;">
                    <label for="ic_number">IC Number</label>
                    <input type="text" name="ic_number" id="ic_number" class="form-control w-full p-2 border rounded" placeholder="Enter IC Number" value="{{ old('ic_number') }}">
                </div>

                <!-- Blood Type -->
                <div class="form-group mt-4" id="blood_type_group" style="display: none;">
                    <label for="blood_type" class="font-semibold">Blood Type</label>
                    <select name="blood_type" id="blood_type" class="form-control w-full p-2 border rounded">
                        <option value="" disabled {{ old('blood_type') ? '' : 'selected' }}>Select Blood Type</option>
                        <option value="A+" {{ old('blood_type') == 'A+' ? 'selected' : '' }}>A+</option>
                        <option value="A-" {{ old('blood_type') == 'A-' ? 'selected' : '' }}>A-</option>
                        <option value="B+" {{ old('blood_type') == 'B+' ? 'selected' : '' }}>B+</option>
                        <option value="B-" {{ old('blood_type') == 'B-' ? 'selected' : '' }}>B-</option>
                        <option value="AB+" {{ old('blood_type') == 'AB+' ? 'selected' : '' }}>AB+</option>
                        <option value="AB-" {{ old('blood_type') == 'AB-' ? 'selected' : '' }}>AB-</option>
                        <option value="O+" {{ old('blood_type') == 'O+' ? 'selected' : '' }}>O+</option>
                        <option value="O-" {{ old('blood_type') == 'O-' ? 'selected' : '' }}>O-</option>
                    </select>
                </div>

                <!-- Quantity -->
                <div class="form-group mt-4">
                    <label for="quantity" class="font-semibold">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control w-full p-2 border rounded"
                           value="{{ old('quantity', 1) }}" min="1" required>
                </div>

                <!-- Location -->
                <div class="form-group mt-4">
                    <label for="location" class="font-semibold">Location</label>
                    <input type="text" name="location" id="location" class="form-control w-full p-2 border rounded"
                           value="{{ old('location') }}" required>
                </div>

                <!-- Phone -->
                <div class="form-group mt-4" id="phone_group" style="display: none;">
                    <label for="phone" class="font-semibold">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control w-full p-2 border rounded"
                           value="{{ old('phone') }}">
                </div>

                <!-- Notes -->
                <div class="form-group mt-4">
                    <label for="notes" class="font-semibold">Notes (Optional)</label>
                    <textarea name="notes" id="notes" class="form-control w-full p-2 border rounded">{{ old('notes') }}</textarea>
                </div>

                <!-- Submit and Cancel Buttons -->
                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('blood_requests.index') }}" class="btn btn-secondary mt-3 mb-3">Cancel</a>
                    <button type="submit" class="btn btn-primary mt-3 mb-3">Submit Request</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const requestType = document.getElementById('request_type');
            const icNumberField = document.getElementById('ic_number_field');
            const bloodTypeGroup = document.getElementById('blood_type_group');
            const phoneGroup = document.getElementById('phone_group');

            function toggleFields() {
                if (requestType.value === 'self') {
                    icNumberField.style.display = 'none';
                    bloodTypeGroup.style.display = 'none';
                    phoneGroup.style.display = 'none';
                } else if (requestType.value === 'other') {
                    icNumberField.style.display = 'block';
                    bloodTypeGroup.style.display = 'block';
                    phoneGroup.style.display = 'block';
                }
            }

            // Initialize the fields on page load
            toggleFields();

            // Update fields on change
            requestType.addEventListener('change', toggleFields);
        });
    </script>
</x-app-layout>
