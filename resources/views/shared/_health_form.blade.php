<form action="{{ isset($event) ? route('events.health.form.store') : route('appointments.health.form.store') }}" method="POST" id="healthForm" onsubmit="this.querySelector('button[type=submit]').disabled = true;">
    @csrf
    @if(isset($event))
        <input type="hidden" name="eventID" value="{{ $event->eventID }}">
        <input type="hidden" name="source_type" value="walk-in">
    @endif

    @if(isset($appointment))
        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
        <input type="hidden" name="source_type" value="appointment">
    @endif

    <!-- Warning Statement -->
    <div class="alert alert-warning mb-4" role="alert">
        <strong>Note:</strong> Any blood donor who is found to make false declaration pertaining to his or her high-risk lifestyle behaviours will be prosecuted in Court under the existing laws.
    </div>

    <!-- Question 1 -->
    <div class="form-group mb-3">
        <label>1. Are you feeling healthy and well today?</label><br>
        <label><input type="radio" name="responses[healthy_today]" value="Yes" required> Yes</label>
        <label><input type="radio" name="responses[healthy_today]" value="No"> No</label>
    </div>

    <!-- Question 2 -->
    <div class="form-group mb-3">
        <label>2. Are you donating today to test your blood for HIV, Hepatitis and/or Syphilis?</label><br>
        <label><input type="radio" name="responses[test_for_infections]" value="Yes" required> Yes</label>
        <label><input type="radio" name="responses[test_for_infections]" value="No"> No</label>
    </div>

    <!-- Question 3 -->
    <div class="form-group mb-3">
        <label>3. Have you donated blood before?</label><br>
        <label><input type="radio" name="responses[donated_before]" value="Yes" onclick="toggleProblems(true, {{ isset($event) ? $event->eventID : $appointment->id }})"> Yes</label>
        <label><input type="radio" name="responses[donated_before]" value="No" onclick="toggleProblems(false, {{ isset($event) ? $event->eventID : $appointment->id }})"> No</label>

        <!-- Follow-up question if "Yes" is selected -->
        <div id="donationProblems{{ isset($event) ? $event->eventID : $appointment->id }}" style="display: none; margin-left: 20px; margin-top: 10px;">
            <label>If yes, have you had any problem during or after the donation?</label><br>
            <label><input type="radio" name="responses[donation_problems]" value="Yes" onclick="toggleProblemDetails(true, {{ isset($event) ? $event->eventID : $appointment->id }})"> Yes</label>
            <label><input type="radio" name="responses[donation_problems]" value="No" onclick="toggleProblemDetails(false, {{ isset($event) ? $event->eventID : $appointment->id }})"> No</label>

            <!-- Problem details input, shown only if "Yes" is selected for donation problems -->
            <div id="problemDetails{{ isset($event) ? $event->eventID : $appointment->id }}" style="display: none; margin-left: 20px; margin-top: 10px;">
                <label>If yes, please specify</label>
                <input type="text" name="responses[problem_details]" class="form-control" placeholder="Describe any problems" />
            </div>
        </div>
    </div>

    <!-- Question 4 -->
    <div class="form-group mb-3">
        <label>4. In the past one week, have you:</label>

        <!-- Sub-question 4a -->
        <div class="mt-2">
            <label>a) Taken any medication?</label><br>
            <label><input type="radio" name="responses[taken_medication]" value="Yes" onclick="toggleMedicationDetails(true, {{ isset($event) ? $event->eventID : $appointment->id }})" required> Yes</label>
            <label><input type="radio" name="responses[taken_medication]" value="No" onclick="toggleMedicationDetails(false, {{ isset($event) ? $event->eventID : $appointment->id }})"> No</label>

            <div id="medicationDetails{{ isset($event) ? $event->eventID : $appointment->id }}" style="display: none; margin-left: 20px; margin-top: 10px;">
                <label>If yes, please specify:</label>
                <input type="text" name="responses[medication_details]" class="form-control" placeholder="Specify medication" />
            </div>
        </div>

        <!-- Sub-question 4b -->
        <div class="mt-2">
            <label>b) Suffered from fever, cold, and/or cough?</label><br>
            <label><input type="radio" name="responses[fever_cold_cough]" value="Yes" required> Yes</label>
            <label><input type="radio" name="responses[fever_cold_cough]" value="No"> No</label>
        </div>

        <!-- Sub-question 4c -->
        <div class="mt-2">
            <label>c) Suffered from headache or migraine?</label><br>
            <label><input type="radio" name="responses[headache_migraine]" value="Yes" required> Yes</label>
            <label><input type="radio" name="responses[headache_migraine]" value="No"> No</label>
        </div>

        <!-- Sub-question 4d -->
        <div class="mt-2">
            <label>d) Seek treatment from a doctor for any health problem?</label><br>
            <label><input type="radio" name="responses[doctor_treatment]" value="Yes" onclick="toggleDoctorDetails(true, {{ isset($event) ? $event->eventID : $appointment->id }})" required> Yes</label>
            <label><input type="radio" name="responses[doctor_treatment]" value="No" onclick="toggleDoctorDetails(false, {{ isset($event) ? $event->eventID : $appointment->id }})"> No</label>

            <div id="doctorDetails{{ isset($event) ? $event->eventID : $appointment->id }}" style="display: none; margin-left: 20px; margin-top: 10px;">
                <label>If yes, please specify:</label>
                <input type="text" name="responses[doctor_treatment_details]" class="form-control" placeholder="Specify treatment" />
            </div>
        </div>
    </div>

    <!-- Question 5 -->
    <div class="form-group mb-3">
        <label>5. Are you suffering from / have ever suffered from / undergoing treatment for / had been treated for any of the following health problems?</label>

        <div class="row">
            <!-- Column 1 -->
            <div class="col-md-6">
                <div class="mt-2">
                    <label>Jaundice</label><br>
                    <label><input type="radio" name="responses[condition_jaundice]" value="Yes" required> Yes</label>
                    <label><input type="radio" name="responses[condition_jaundice]" value="No"> No</label>
                </div>

                <div class="mt-2">
                    <label>Hepatitis B or Hepatitis C</label><br>
                    <label><input type="radio" name="responses[condition_hepatitis]" value="Yes" required> Yes</label>
                    <label><input type="radio" name="responses[condition_hepatitis]" value="No"> No</label>
                </div>

                <div class="mt-2">
                    <label>HIV</label><br>
                    <label><input type="radio" name="responses[condition_hiv]" value="Yes" required> Yes</label>
                    <label><input type="radio" name="responses[condition_hiv]" value="No"> No</label>
                </div>

                <div class="mt-2">
                    <label>STDs / Syphilis</label><br>
                    <label><input type="radio" name="responses[condition_stds_syphilis]" value="Yes" required> Yes</label>
                    <label><input type="radio" name="responses[condition_stds_syphilis]" value="No"> No</label>
                </div>

                <div class="mt-2">
                    <label>Heart Disease</label><br>
                    <label><input type="radio" name="responses[condition_heart_disease]" value="Yes" required> Yes</label>
                    <label><input type="radio" name="responses[condition_heart_disease]" value="No"> No</label>
                </div>

                <div class="mt-2">
                    <label>Renal Disease / Renal Failure</label><br>
                    <label><input type="radio" name="responses[condition_renal_disease]" value="Yes" required> Yes</label>
                    <label><input type="radio" name="responses[condition_renal_disease]" value="No"> No</label>
                </div>

                <div class="mt-2">
                    <label>Asthma</label><br>
                    <label><input type="radio" name="responses[condition_asthma]" value="Yes" required> Yes</label>
                    <label><input type="radio" name="responses[condition_asthma]" value="No"> No</label>
                </div>
            </div>

            <!-- Column 2 -->
            <div class="col-md-6">
                <div class="mt-2">
                    <label>Tuberculosis</label><br>
                    <label><input type="radio" name="responses[condition_tuberculosis]" value="Yes" required> Yes</label>
                    <label><input type="radio" name="responses[condition_tuberculosis]" value="No"> No</label>
                </div>

                <div class="mt-2">
                    <label>Diabetes</label><br>
                    <label><input type="radio" name="responses[condition_diabetes]" value="Yes" required> Yes</label>
                    <label><input type="radio" name="responses[condition_diabetes]" value="No"> No</label>
                </div>

                <div class="mt-2">
                    <label>Hypertension</label><br>
                    <label><input type="radio" name="responses[condition_hypertension]" value="Yes" required> Yes</label>
                    <label><input type="radio" name="responses[condition_hypertension]" value="No"> No</label>
                </div>

                <div class="mt-2">
                    <label>Malaria</label><br>
                    <label><input type="radio" name="responses[condition_malaria]" value="Yes" required> Yes</label>
                    <label><input type="radio" name="responses[condition_malaria]" value="No"> No</label>
                </div>

                <div class="mt-2">
                    <label>Mental Illness</label><br>
                    <label><input type="radio" name="responses[condition_mental_illness]" value="Yes" required> Yes</label>
                    <label><input type="radio" name="responses[condition_mental_illness]" value="No"> No</label>
                </div>

                <div class="mt-2">
                    <label>Epilepsy</label><br>
                    <label><input type="radio" name="responses[condition_epilepsy]" value="Yes" required> Yes</label>
                    <label><input type="radio" name="responses[condition_epilepsy]" value="No"> No</label>
                </div>

                <div class="mt-2">
                    <label>Others (please specify)</label><br>
                    <label><input type="radio" name="responses[condition_others]" value="Yes" onclick="toggleOtherCondition(true, {{ isset($event) ? $event->eventID : $appointment->id }})"> Yes</label>
                    <label><input type="radio" name="responses[condition_others]" value="No" onclick="toggleOtherCondition(false, {{ isset($event) ? $event->eventID : $appointment->id }})"> No</label>

                    <div id="otherConditionDetails{{ isset($event) ? $event->eventID : $appointment->id }}" style="display: none; margin-left: 20px; margin-top: 10px;">
                        <input type="text" name="responses[other_condition_details]" class="form-control" placeholder="Specify other conditions" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Question 6 -->
    <div class="form-group mb-3">
        <label>6. Has anybody in your family been diagnosed with or currently being treated for Hepatitis B or Hepatitis C?</label><br>
        <label><input type="radio" name="responses[family_hepatitis]" value="Yes" onclick="toggleRelationshipInput(true, {{ isset($event) ? $event->eventID : $appointment->id }})" required> Yes</label>
        <label><input type="radio" name="responses[family_hepatitis]" value="No" onclick="toggleRelationshipInput(false, {{ isset($event) ? $event->eventID : $appointment->id }})"> No</label>

        <!-- Follow-up input for specifying relationship, shown only if "Yes" is selected -->
        <div id="relationshipInput{{ isset($event) ? $event->eventID : $appointment->id }}" style="display: none; margin-left: 20px; margin-top: 10px;">
            <label>If yes, please state your relationship with him/her:</label>
            <input type="text" name="responses[family_relationship]" class="form-control" placeholder="Specify relationship" />
        </div>
    </div>

    <!-- Question 7 -->
    <div class="form-group mb-3">
        <label>7. In the last 6 months, have you:</label>

        <!-- Sub-question 7a -->
        <div class="mt-2">
            <label>a) Underwent any surgical procedure or operation?</label><br>
            <label><input type="radio" name="responses[surgical_procedure]" value="Yes" required> Yes</label>
            <label><input type="radio" name="responses[surgical_procedure]" value="No"> No</label>
        </div>

        <!-- Sub-question 7b -->
        <div class="mt-2">
            <label>b) Received any blood transfusion?</label><br>
            <label><input type="radio" name="responses[blood_transfusion]" value="Yes" required> Yes</label>
            <label><input type="radio" name="responses[blood_transfusion]" value="No"> No</label>
        </div>

        <!-- Sub-question 7c -->
        <div class="mt-2">
            <label>c) Had any accidental needle stick injury?</label><br>
            <label><input type="radio" name="responses[needle_injury]" value="Yes" required> Yes</label>
            <label><input type="radio" name="responses[needle_injury]" value="No"> No</label>
        </div>
    </div>

    <!-- Question 8 -->
    <div class="form-group mb-3">
        <label>8. Have you received any immunisation injection or any type of injection for beauty (e.g. botox, collagen) within the past 4 weeks?</label><br>
        <label><input type="radio" name="responses[recent_injection]" value="Yes" onclick="toggleInjectionDetails(true, {{ isset($event) ? $event->eventID : $appointment->id }})" required> Yes</label>
        <label><input type="radio" name="responses[recent_injection]" value="No" onclick="toggleInjectionDetails(false, {{ isset($event) ? $event->eventID : $appointment->id }})"> No</label>

        <!-- Follow-up input for specifying injection type and/or purpose, shown only if "Yes" is selected -->
        <div id="injectionDetails{{ isset($event) ? $event->eventID : $appointment->id }}" style="display: none; margin-left: 20px; margin-top: 10px;">
            <label>If yes, please specify type and/or purpose:</label>
            <input type="text" name="responses[injection_details]" class="form-control" placeholder="Specify injection type and/or purpose" />
        </div>
    </div>

    <!-- Question 9 -->
    <div class="form-group mb-3">
        <label>9. Have you had any dental treatment in the past 24 hours?</label><br>
        <label><input type="radio" name="responses[dental_treatment]" value="Yes" required> Yes</label>
        <label><input type="radio" name="responses[dental_treatment]" value="No"> No</label>
    </div>

    <!-- Question 10 -->
    <div class="form-group mb-3">
        <label>10. Have you had any body piercing, tattooing, blood-letting / cupping (berbekam), or acupuncture done within the past 6 months?</label><br>
        <label><input type="radio" name="responses[body_modification]" value="Yes" required> Yes</label>
        <label><input type="radio" name="responses[body_modification]" value="No"> No</label>
    </div>

    <!-- Question 11 -->
    <div class="form-group mb-3">
        <label>11. In the past 24 hours, have you taken any alcoholic drink until you were drunk or intoxicated?</label><br>
        <label><input type="radio" name="responses[alcohol_intoxication]" value="Yes" required> Yes</label>
        <label><input type="radio" name="responses[alcohol_intoxication]" value="No"> No</label>
    </div>

    <!-- Question 12 -->
    <div class="form-group mb-3">
        <label>12. Have you ever received:</label>

        <!-- Sub-question 12a -->
        <div class="mt-2">
            <label>a) Injection with human growth hormone?</label><br>
            <label><input type="radio" name="responses[growth_hormone_injection]" value="Yes" required> Yes</label>
            <label><input type="radio" name="responses[growth_hormone_injection]" value="No"> No</label>
        </div>

        <!-- Sub-question 12b -->
        <div class="mt-2">
            <label>b) Cornea transplant?</label><br>
            <label><input type="radio" name="responses[cornea_transplant]" value="Yes" required> Yes</label>
            <label><input type="radio" name="responses[cornea_transplant]" value="No"> No</label>
        </div>

        <!-- Sub-question 12c -->
        <div class="mt-2">
            <label>c) Brain membrane (duramater) transplant?</label><br>
            <label><input type="radio" name="responses[brain_membrane_transplant]" value="Yes" required> Yes</label>
            <label><input type="radio" name="responses[brain_membrane_transplant]" value="No"> No</label>
        </div>

        <!-- Sub-question 12d -->
        <div class="mt-2">
            <label>d) Bone marrow or stem cell transplant?</label><br>
            <label><input type="radio" name="responses[bone_marrow_transplant]" value="Yes" required> Yes</label>
            <label><input type="radio" name="responses[bone_marrow_transplant]" value="No"> No</label>
        </div>
    </div>

    <!-- Question 13 -->
    <div class="form-group mb-3">
        <label>13. Risk of infection with variant Creutzfeldt-Jakob Disease (vCJD)</label>

        <!-- Sub-question 13a -->
        <div class="mt-2">
            <label>a) Have you ever visited or lived in the United Kingdom (England, Northern Ireland, Ireland, Wales, Scotland, the Isle of Man, the Channel Island) or the Republic of Ireland for a cumulative period of 6 months or more between 1st January 1980 and 31st December 1996?</label><br>
            <label><input type="radio" name="responses[uk_visit_1980_1996]" value="Yes" required> Yes</label>
            <label><input type="radio" name="responses[uk_visit_1980_1996]" value="No"> No</label>
        </div>

        <!-- Sub-question 13b -->
        <div class="mt-2">
            <label>b) Have you ever received a transfusion or injection of blood or blood product while in the United Kingdom between 1st January 1980 until now?</label><br>
            <label><input type="radio" name="responses[uk_blood_transfusion]" value="Yes" required> Yes</label>
            <label><input type="radio" name="responses[uk_blood_transfusion]" value="No"> No</label>
        </div>

        <!-- Sub-question 13c -->
        <div class="mt-2">
            <label>c) Have you ever visited or lived in the following European countries for a cumulative period of 5 years or more between 1st January 1980 until now? (Austria, Belgium, Denmark, Finland, France, Germany, Greece, Holland, Italy, Liechtenstein, Luxembourg, Norway, Portugal, Spain, Sweden, and Switzerland)</label><br>
            <label><input type="radio" name="responses[europe_visit_1980_now]" value="Yes" required> Yes</label>
            <label><input type="radio" name="responses[europe_visit_1980_now]" value="No"> No</label>
        </div>
    </div>

    <!-- Question 14 --> 
    <div class="form-group mb-3">
        <label><strong>14. For patient safety, the following questions SHALL be answered HONESTLY, even if you were only involved in it once. You are required to answer the following questions in front of the assigned doctor or officer from MOH who interviews you.</strong></label>

        <!-- Sub-question 14a -->
        <div class="mt-2">
            <label>a) If you are a man, have you ever had sex with another man?</label><br>
            <label><input type="radio" name="responses[man_sex_with_man]" value="Yes" required> Yes</label>
            <label><input type="radio" name="responses[man_sex_with_man]" value="No"> No</label>
        </div>

        <!-- Sub-question 14b -->
        <div class="mt-2">
            <label>b) Have you ever had sex with a commercial sex worker/prostitute?</label><br>
            <label><input type="radio" name="responses[sex_with_prostitute]" value="Yes" required> Yes</label>
            <label><input type="radio" name="responses[sex_with_prostitute]" value="No"> No</label>
        </div>

        <!-- Sub-question 14c -->
        <div class="mt-2">
            <label>c) Have you ever paid or received payment in exchange for sex?</label><br>
            <label><input type="radio" name="responses[paid_for_sex]" value="Yes" required> Yes</label>
            <label><input type="radio" name="responses[paid_for_sex]" value="No"> No</label>
        </div>

        <!-- Sub-question 14d -->
        <div class="mt-2">
            <label>d) Have you ever had more than one sexual partner?</label><br>
            <label><input type="radio" name="responses[multiple_sexual_partners]" value="Yes" required> Yes</label>
            <label><input type="radio" name="responses[multiple_sexual_partners]" value="No"> No</label>
        </div>

        <!-- Sub-question 14e -->
        <div class="mt-2">
            <label>e) Have you had any new sexual partner(s) within the past 12 months?</label><br>
            <label><input type="radio" name="responses[new_sexual_partner]" value="Yes" required> Yes</label>
            <label><input type="radio" name="responses[new_sexual_partner]" value="No"> No</label>
        </div>

        <!-- Sub-question 14f -->
        <div class="mt-2">
            <label>f) Have you ever injected yourself with illegal drugs, including drugs for bodybuilding?</label><br>
            <label><input type="radio" name="responses[injected_illegal_drugs]" value="Yes" required> Yes</label>
            <label><input type="radio" name="responses[injected_illegal_drugs]" value="No"> No</label>
        </div>

        <!-- Sub-question 14g -->
        <div class="mt-2">
            <label>g) Does your sexual partner belong to any of the above categories?</label><br>
            <label><input type="radio" name="responses[partner_in_high_risk_category]" value="Yes" required> Yes</label>
            <label><input type="radio" name="responses[partner_in_high_risk_category]" value="No"> No</label>
        </div>

        <!-- Sub-question 14h -->
        <div class="mt-2">
            <label>h) Have you or your sexual partner ever been tested positive for HIV?</label><br>
            <label><input type="radio" name="responses[partner_hiv_positive]" value="Yes" required> Yes</label>
            <label><input type="radio" name="responses[partner_hiv_positive]" value="No"> No</label>
        </div>

        <!-- Sub-question 14i -->
        <div class="mt-2">
            <label>i) Do you think you or your sexual partner may be tested positive for HIV?</label><br>
            <label><input type="radio" name="responses[potential_hiv_positive]" value="Yes" required> Yes</label>
            <label><input type="radio" name="responses[potential_hiv_positive]" value="No"> No</label>
        </div>
    </div>

    <!-- Gender Selection -->
    <div class="form-group mb-3">
        <label>Gender</label><br>
        <label><input type="radio" name="responses[gender]" value="Male" onclick="toggleFemaleQuestions(false, '{{ isset($event) ? $event->eventID : $appointment->id }}')" required> Male</label>
        <label><input type="radio" name="responses[gender]" value="Female" onclick="toggleFemaleQuestions(true, '{{ isset($event) ? $event->eventID : $appointment->id }}')"> Female</label>
    </div>

    <!-- Question 15 (for female donors only) -->
    <div class="form-group mb-3" id="femaleQuestions{{ isset($event) ? $event->eventID : $appointment->id }}" style="display: none;">
        <label><strong>15. To be answered by female donors only</strong></label><br>
        
        <!-- Sub-question 15a -->
        <div class="mt-2">
            <label>a) Are you having your menstrual period?</label><br>
            <label><input type="radio" name="responses[menstrual_period]" value="Yes"> Yes</label>
            <label><input type="radio" name="responses[menstrual_period]" value="No"> No</label>
        </div>

        <!-- Sub-question 15b -->
        <div class="mt-2">
            <label>b) Are you pregnant or may be pregnant?</label><br>
            <label><input type="radio" name="responses[pregnant]" value="Yes"> Yes</label>
            <label><input type="radio" name="responses[pregnant]" value="No"> No</label>
        </div>

        <!-- Sub-question 15c -->
        <div class="mt-2">
            <label>c) Do you have a child that is still breast-feeding?</label><br>
            <label><input type="radio" name="responses[breast_feeding]" value="Yes"> Yes</label>
            <label><input type="radio" name="responses[breast_feeding]" value="No"> No</label>
        </div>

        <!-- Sub-question 15d -->
        <div class="mt-2">
            <label>d) Have you given birth or had a miscarriage in the past 6 months?</label><br>
            <label><input type="radio" name="responses[recent_birth_miscarriage]" value="Yes"> Yes</label>
            <label><input type="radio" name="responses[recent_birth_miscarriage]" value="No"> No</label>
        </div>
    </div>

    <!-- Consent Checkbox -->
    <div class="form-group mb-3">
        <label>
            <input type="checkbox" name="responses[consent]" value="Yes" required> 
            I confirm that I understand all the above questions and declare that I have answered them truthfully and sincerely.
        </label>
    </div>

    <button type="submit" class="btn btn-success">Submit Health Details</button>
</form>


<!-- JavaScript to toggle visibility of follow-up questions -->
<script>
    function toggleProblems(show, id) {
        const element = document.getElementById(`donationProblems${id}`);
        if (element) {
            element.style.display = show ? 'block' : 'none';
        }
    }
    function toggleProblemDetails(show, id) {
        const element = document.getElementById(`problemDetails${id}`);
        if (element) {
            element.style.display = show ? 'block' : 'none';
        }
    }
    function toggleMedicationDetails(show, id) {
        const element = document.getElementById(`medicationDetails${id}`);
        if (element) {
            element.style.display = show ? 'block' : 'none';
        }
    }
    function toggleDoctorDetails(show, id) {
        const element = document.getElementById(`doctorDetails${id}`);
        if (element) {
            element.style.display = show ? 'block' : 'none';
        }
    }
    function toggleOtherCondition(show, id) {
        const element = document.getElementById(`otherConditionDetails${id}`);
        if (element) {
            element.style.display = show ? 'block' : 'none';
        }
    }
    function toggleRelationshipInput(show, id) {
        const element = document.getElementById(`relationshipInput${id}`);
        if (element) {
            element.style.display = show ? 'block' : 'none';
        }
    }
    function toggleInjectionDetails(show, id) {
        const element = document.getElementById(`injectionDetails${id}`);
        if (element) {
            element.style.display = show ? 'block' : 'none';
        }
    }
    function toggleFemaleQuestions(isFemale, id) {
        const element = document.getElementById(`femaleQuestions${id}`);
        if (element) {
            element.style.display = isFemale ? 'block' : 'none';
        }
    }
</script>
