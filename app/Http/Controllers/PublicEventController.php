<?php 

namespace App\Http\Controllers;

use App\Models\BloodDonationEvent;
use App\Models\HealthDetail;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PublicEventController extends Controller
{
    // Display detailed information about upcoming events
    public function index($eventID = null)
    {
        $today = Carbon::today();
        $userId = Auth::id();

        // Fetch upcoming events and check submission status
        $upcomingEvents = BloodDonationEvent::where('eventDate', '>=', $today)
            ->get()
            ->each(function ($event) use ($userId) {
                $event->alreadySubmitted = HealthDetail::where('eventID', $event->eventID)
                    ->where('user_id', $userId)
                    ->exists();

                    $event->isEligible = $this->isEligibleToDonate($userId) && $event->eventDate->isToday();
            });

        // Fetch past events (no need to check submission status for past events)
        $pastEvents = BloodDonationEvent::where('eventDate', '<', $today)->get();

        $selectedEvent = $eventID ? BloodDonationEvent::find($eventID) : null;

        return view('events.index', compact('upcomingEvents', 'pastEvents', 'selectedEvent'));
    }

    private function isEligibleToDonate($userId)
    {
        $lastDonation = Donation::where('user_id', $userId)
            ->latest('date_donated')
            ->first();

        if ($lastDonation) {
            $eightWeeksAgo = Carbon::now()->subWeeks(8);
            return $lastDonation->date_donated <= $eightWeeksAgo;
        }

        // If the user has no prior donations, they are eligible
        return true;
    }


    // Show the health questionnaire form for walk-in donors
    public function showHealthForm($eventID)
    {
        $event = BloodDonationEvent::findOrFail($eventID);
        $user = Auth::user();
        
        if (!$this->isEligibleToDonate($userId)) {
            session()->flash('error', 'You are not eligible to register for this event. Please wait 8 weeks after your last donation.');
            return redirect()->route('events.index');
        }
        
        // Check if the user has already submitted health details
        $alreadySubmitted = HealthDetail::where('eventID', $eventID)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadySubmitted) {
            session()->flash('info', 'You have already submitted your health details for this event.');
            return redirect()->back();
        }

        return view('shared._health_form', compact('event'))
            ->with('source_type', 'walk-in');
    }

    public function storeHealthDetails(Request $request)
    {
        // Validate the form input
        $validatedData = $request->validate([
            'eventID' => 'required|exists:blood_donation_events,eventID',
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

        $user = Auth::user();

        $eventID = $validatedData['eventID'];

        // Check if health details already exist
        $alreadySubmitted = HealthDetail::where('eventID', $eventID)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadySubmitted) {
            return redirect()->back()->with('info', 'You have already submitted your health details for this event.');
        }

        // Save the health details to the database
        HealthDetail::create([
            'user_id' => Auth::id(), // Save the current user's ID
            'eventID' => $eventID,
            'responses' => $validatedData['responses'], // Store responses as JSON
            'source_type' => $request->input('source_type', 'walk-in') // Default to 'walk-in' if not provided
        ]);

        session()->flash('success', 'Health form successfully submitted!');
        
        return redirect()->route('events.index');
    }
}
