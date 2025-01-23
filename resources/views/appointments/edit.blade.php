<x-app-layout>
    <x-slot name="title">GoBlood | Edit Appointment</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Appointment') }}
        </h2>
    </x-slot>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="container py-8 bg-white sm:rounded-lg">
            <!-- Display Error Message -->
            @if(session('error'))
                <div class="alert alert-danger mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Select Blood Bank -->
                <div class="form-group mt-3">
                    <label for="blood_bank_id">Select Blood Bank</label>
                    <select name="blood_bank_id" id="blood_bank_id" class="form-control" required>
                        @foreach($bloodBanks as $bloodBank)
                            <option value="{{ $bloodBank->id }}" {{ $bloodBank->id == $appointment->blood_bank_id ? 'selected' : '' }}>
                                {{ $bloodBank->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Appointment Date -->
                <div class="form-group mt-3">
                    <label for="appointment_date">Appointment Date</label>
                    <input type="text" name="appointment_date" id="appointment_date" class="form-control" 
                           required value="{{ $appointment->appointment_date }}">
                </div>

                <!-- Time Slot -->
                <div class="form-group mt-3">
                    <label for="time_slot">Time Slot</label>
                    <div id="time_slot" class="card-container">
                        <!-- Cards will be dynamically generated here -->
                    </div>
                    <input type="hidden" name="time_slot" id="selected_time_slot" required value="{{ $formattedTimeSlot }}">
                </div>

                <!-- Submit and Cancel Buttons -->
                <div class="flex items-center justify-end">
                    <a href="{{ route('appointments.index') }}" class="btn btn-secondary mt-3 mb-3">Cancel</a>
                    <button type="submit" class="btn btn-primary mt-3 mb-3">Update Appointment</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Initialize Flatpickr
            flatpickr("#appointment_date", {
                dateFormat: "Y-m-d",
                minDate: "today",
                disable: [
                    function (date) {
                        // Disable weekends: Saturday = 6, Sunday = 0
                        return date.getDay() === 0 || date.getDay() === 6;
                    }
                ],
                defaultDate: "{{ $appointment->appointment_date }}",
                locale: {
                    firstDayOfWeek: 1
                }
            });

            // Initialize Select2
            $('.select2').select2();

            // Fetch available time slots dynamically
            function fetchAvailableTimeSlots() {
                const bloodBankId = document.getElementById('blood_bank_id').value;
                const appointmentDate = document.getElementById('appointment_date').value;

                if (bloodBankId && appointmentDate) {
                    $.ajax({
                        url: "{{ route('appointments.getAvailableTimeSlots') }}",
                        type: "POST",
                        data: {
                            blood_bank_id: bloodBankId,
                            appointment_date: appointmentDate,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function (response) {
                            const cardContainer = document.getElementById('time_slot');
                            const selectedTimeSlotInput = document.getElementById('selected_time_slot');
                            cardContainer.innerHTML = ''; // Clear existing cards

                            response.forEach(slot => {
                                const card = document.createElement('div');
                                card.className = `time-slot-card ${!slot.available ? 'disabled' : ''}`;
                                card.textContent = slot.time_slot;

                                if (slot.available) {
                                    card.addEventListener('click', function () {
                                        // Remove selected class from other cards
                                        document.querySelectorAll('.time-slot-card').forEach(card => {
                                            card.classList.remove('selected');
                                        });

                                        // Mark this card as selected
                                        card.classList.add('selected');
                                        selectedTimeSlotInput.value = slot.time_slot;
                                    });
                                }

                                // Preselect the current time slot
                                if (slot.time_slot === "{{ $formattedTimeSlot }}") {
                                    card.classList.add('selected');
                                }

                                cardContainer.appendChild(card);
                            });
                        },
                        error: function () {
                            alert('Failed to fetch available time slots.');
                        }
                    });
                }
            }

            // Add event listeners for changes in blood bank or date
            document.getElementById('blood_bank_id').addEventListener('change', fetchAvailableTimeSlots);
            document.getElementById('appointment_date').addEventListener('change', fetchAvailableTimeSlots);

            // Fetch time slots on page load
            fetchAvailableTimeSlots();
        });
    </script>

    <!-- Custom CSS -->
    <style>
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .time-slot-card {
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            min-width: 80px;
            background-color: #f9f9f9;
        }

        .time-slot-card:hover {
            background-color: #e0e0e0;
        }

        .time-slot-card.selected {
            background-color: #07b31e;
            color: white;
            border-color: #0056b3;
        }

        .time-slot-card.disabled {
            background-color: #858383;
            color: black;
            cursor: not-allowed;
        }
    </style>
</x-app-layout>
