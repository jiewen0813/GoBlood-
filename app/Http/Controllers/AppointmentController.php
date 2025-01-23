<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\BloodBankAdmin;
use App\Models\HealthDetail;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    // Show the health form for the logged-in user's appointment
    public function showHealthForm()
    {
        $userId = Auth::id();

        $appointment = Appointment::findOrFail($appointmentId);

        // Check if the user has already submitted health details for this appointment
        $alreadySubmitted = HealthDetail::where('appointment_id', $appointment->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($alreadySubmitted) {
            session()->flash('info', 'You have already submitted your health details for this appointment.');
            return redirect()->back();
        }

        // Show the health form if not submitted
        return view('shared.health_form', compact('appointment'))
            ->with('source_type', 'appointment');
    }

    // Store health form details for the user's appointment
    public function storeHealthDetails(Request $request)
    {
        $validatedData = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'responses' => 'required|array',
            'responses.healthy_today' => 'required|string',
            'responses.test_for_infections' => 'required|string',
            'responses.donated_before' => 'required|string',
            'responses.donation_problems' => 'nullable|string',
            'responses.problem_details' => 'nullable|string',
            'responses.taken_medication' => 'required|string',
            'responses.medication_details' => 'nullable|string',
            'responses.fever_cold_cough' => 'required|string',
            'responses.headache_migraine' => 'required|string',
            'responses.doctor_treatment' => 'required|string',
            'responses.doctor_treatment_details' => 'nullable|string',
            'responses.condition_jaundice' => 'required|string',
            'responses.condition_hepatitis' => 'required|string',
            'responses.condition_hiv' => 'required|string',
            'responses.condition_stds_syphilis' => 'required|string',
            'responses.condition_heart_disease' => 'required|string',
            'responses.condition_renal_disease' => 'required|string',
            'responses.condition_asthma' => 'required|string',
            'responses.condition_tuberculosis' => 'required|string',
            'responses.condition_diabetes' => 'required|string',
            'responses.condition_hypertension' => 'required|string',
            'responses.condition_malaria' => 'required|string',
            'responses.condition_mental_illness' => 'required|string',
            'responses.condition_epilepsy' => 'required|string',
            'responses.condition_others' => 'nullable|string',
            'responses.other_condition_details' => 'nullable|string',
            'responses.family_hepatitis' => 'required|string',
            'responses.family_relationship' => 'nullable|string',
            'responses.surgical_procedure' => 'required|string',
            'responses.blood_transfusion' => 'required|string',
            'responses.needle_injury' => 'required|string',
            'responses.recent_injection' => 'required|string',
            'responses.injection_details' => 'nullable|string',
            'responses.dental_treatment' => 'required|string',
            'responses.body_modification' => 'required|string',
            'responses.alcohol_intoxication' => 'required|string',
            'responses.growth_hormone_injection' => 'required|string',
            'responses.cornea_transplant' => 'required|string',
            'responses.brain_membrane_transplant' => 'required|string',
            'responses.bone_marrow_transplant' => 'required|string',
            'responses.uk_visit_1980_1996' => 'required|string',
            'responses.uk_blood_transfusion' => 'required|string',
            'responses.europe_visit_1980_now' => 'required|string',
            'responses.man_sex_with_man' => 'required|string',
            'responses.sex_with_prostitute' => 'required|string',
            'responses.paid_for_sex' => 'required|string',
            'responses.multiple_sexual_partners' => 'required|string',
            'responses.new_sexual_partner' => 'required|string',
            'responses.injected_illegal_drugs' => 'required|string',
            'responses.partner_in_high_risk_category' => 'required|string',
            'responses.partner_hiv_positive' => 'required|string',
            'responses.potential_hiv_positive' => 'required|string',
            'responses.gender' => 'required|string',
            'responses.menstrual_period' => 'nullable|string',
            'responses.pregnant' => 'nullable|string',
            'responses.breast_feeding' => 'nullable|string',
            'responses.recent_birth_miscarriage' => 'nullable|string',
            'responses.consent' => 'required|string',
        ]);

        $userId = Auth::id();
        
        $appointmentId = $validatedData['appointment_id'];

        // Check if health details already exist for this appointment
        $existingHealthDetail = HealthDetail::where('appointment_id', $appointmentId)
            ->where('user_id', $userId)
            ->exists();

        if ($existingHealthDetail) {
            return redirect()->back()->with('info', 'You have already submitted your health details for this appointment.');
        }

        // Save the health form details
        HealthDetail::create([
            'user_id' => $userId,
            'appointment_id' => $appointmentId,
            'responses' => $validatedData['responses'], // Store responses as JSON
            'source_type' => $request->input('source_type', 'appointment'),
        ]);

        session()->flash('success', 'Health form successfully submitted!');
        return redirect()->route('appointments.index');
    }

    private function isEligibleToDonate($userId)
    {
        $lastDonation = Donation::where('user_id', $userId)
            ->latest('date_donated')
            ->first();

        if ($lastDonation) {
            $eightWeeksAgo = Carbon::now()->subWeeks(8);

            // If the last donation date is within 8 weeks, they are not eligible
            return $lastDonation->date_donated <= $eightWeeksAgo;
        }

        // If the user has no prior donations, they are eligible
        return true;
    }

    // Show the form for creating a new appointment
    public function create(Request $request)
    {
        $userId = Auth::id();

        if (!$this->isEligibleToDonate($userId)) {
            return redirect()->route('appointments.index')
                ->with('error', 'You are not eligible to make an appointment yet. Please wait 8 weeks after your last donation.');
        }

        $bloodBanks = BloodBankAdmin::all();

        $timeSlots = [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
            '15:00', '15:30', '16:00'
        ];

        $bloodBankId = $request->get('blood_bank_id');
        $appointmentDate = $request->get('appointment_date') ?? Carbon::today()->format('Y-m-d');

        $availableTimeSlots = [];
        foreach ($timeSlots as $timeSlot) {
            $appointmentCount = Appointment::where('blood_bank_id', $bloodBankId)
                ->whereDate('appointment_date', $appointmentDate)
                ->where('time_slot', $timeSlot)
                ->count();

            $availableTimeSlots[] = [
                'time_slot' => $timeSlot,
                'available' => $appointmentCount < 5 // Mark as available if less than 5 appointments
            ];
        }

        return view('appointments.create', compact('bloodBanks', 'availableTimeSlots', 'bloodBankId', 'appointmentDate'));
    }

    public function getAvailableTimeSlots(Request $request)
    {
        $bloodBankId = $request->input('blood_bank_id');
        $appointmentDate = $request->input('appointment_date');

        // Define all possible time slots
        $timeSlots = [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
            '15:00', '15:30', '16:00'
        ];

        $currentTime = Carbon::now()->format('H:i');
        $currentDate = Carbon::today()->format('Y-m-d');

        $availableTimeSlots = [];
        foreach ($timeSlots as $timeSlot) {
            $appointmentCount = Appointment::where('blood_bank_id', $bloodBankId)
                ->whereDate('appointment_date', $appointmentDate)
                ->where('time_slot', $timeSlot)
                ->count();

            $isPastTimeSlot = $appointmentDate === $currentDate && $timeSlot <= $currentTime;

            $availableTimeSlots[] = [
                'time_slot' => $timeSlot,
                'available' => $appointmentCount < 5 && !$isPastTimeSlot
            ];
        }

        return response()->json($availableTimeSlots);
    }

    // Store a newly created appointment in the database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'blood_bank_id' => 'required|exists:blood_bank_admins,id',
            'appointment_date' => 'required|date',
            'time_slot' => 'required|string',
        ]);

        // Check if the selected time slot is fully booked
        $appointmentCount = Appointment::where('blood_bank_id', $validated['blood_bank_id'])
            ->whereDate('appointment_date', $validated['appointment_date'])
            ->where('time_slot', $validated['time_slot'])
            ->count();

        if ($appointmentCount >= 5) {
            return back()->with('error', 'The selected time slot is already fully booked.');
        }

        // Check for duplicate appointments for the same date
        $existingAppointment = Appointment::where('user_id', Auth::id())
            ->whereDate('appointment_date', $validated['appointment_date'])
            ->exists();

        if ($existingAppointment) {
            return back()->with('error', 'You already have an appointment on this date.');
        }

        // Create the new appointment
        Appointment::create([
            'user_id' => Auth::id(),
            'blood_bank_id' => $validated['blood_bank_id'],
            'appointment_date' => $validated['appointment_date'],
            'time_slot' => $validated['time_slot'],
        ]);

        return redirect()->route('appointments.index')->with('success', 'Appointment created successfully!');
    }

    // Show the form for editing an existing appointment
    public function edit(Appointment $appointment)
    {
        $bloodBanks = BloodBankAdmin::all();
        $timeSlots = [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
            '15:00', '15:30', '16:00'
        ];

        $formattedTimeSlot = Carbon::parse($appointment->time_slot)->format('H:i');

        return view('appointments.edit', compact('appointment', 'bloodBanks', 'timeSlots', 'formattedTimeSlot'));
    }


    // Update an existing appointment in the database
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'blood_bank_id' => 'required|exists:blood_bank_admins,id',
            'appointment_date' => 'required|date',
            'time_slot' => 'required|in:09:00,09:30,10:00,10:30,11:00,11:30,12:00,12:30,13:00,13:30,14:00,14:30,15:00,15:30,16:00',
        ]);

        // Check if the new date is a weekday
        $appointmentDate = Carbon::parse($request->appointment_date);
        if ($appointmentDate->isWeekend()) {
            return back()->with('error', 'Appointments can only be made from Monday to Friday.');
        }

        // Check if the new time slot is fully booked
        $appointmentCount = Appointment::where('blood_bank_id', $request->blood_bank_id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->where('time_slot', $request->time_slot)
            ->where('id', '!=', $appointment->id) // Exclude the current appointment
            ->count();

        if ($appointmentCount >= 5) {
            return back()->with('error', 'The selected time slot is already fully booked. Please choose another time slot.');
        }

        $appointment->update($validated);

        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully!');
    }


    // Show the list of appointments for the authenticated user
    public function index()
    {
        $userId = Auth::id();

        $todayAppointment = Appointment::where('user_id', $userId)
            ->whereDate('appointment_date', Carbon::today())
            ->first();

        if ($todayAppointment) {
            $todayAppointment->alreadySubmitted = HealthDetail::where('appointment_id', $todayAppointment->id)
                ->exists();
        }

        $upcomingAppointments = Appointment::where('user_id', $userId)
            ->where(function ($query) {
                $query->where('appointment_date', '>', now()->toDateString())
                    ->orWhere(function ($query) {
                        $query->where('appointment_date', '=', now()->toDateString())
                            ->where('time_slot', '>', Carbon::now()->format('H:i'));
                    });
            })
            ->get()
            ->each(function ($appointment) {
                $appointment->alreadySubmitted = HealthDetail::where('appointment_id', $appointment->id)->exists();
            });

        $pastAppointments = Appointment::where('user_id', $userId)
            ->where(function ($query) {
                $query->where('appointment_date', '<', now()->toDateString())
                    ->orWhere(function ($query) {
                        $query->where('appointment_date', '=', now()->toDateString())
                            ->where('time_slot', '<', Carbon::now()->format('H:i'));
                    });
            })
            ->get();
        
        $isEligible = $this->isEligibleToDonate($userId);

        return view('appointments.index', compact('upcomingAppointments', 'pastAppointments', 'isEligible'));
    }

    // Delete an existing appointment
    public function destroy(Appointment $appointment)
    {
        if ($appointment->user_id != Auth::id()) {
            return redirect()->route('appointments.index')->with('error', 'You cannot delete this appointment.');
        }

        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Appointment cancelled successfully.');
    }

}
