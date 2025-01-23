<div class="modal fade" id="healthDetailModal{{ $detail->id }}" tabindex="-1" role="dialog" aria-labelledby="healthDetailModalLabel{{ $detail->id }}" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="healthDetailModalLabel{{ $detail->id }}">Health Details for {{ $detail->user->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Display the health detail ID -->
                <div class="mb-3">
                    <strong>Health Detail ID: {{ $detail->id }}</strong>
                </div>
                            <!-- Question 1 -->
                            <div class="form-group mb-3">
                                <label>1. Are you feeling healthy and well today?</label><br>
                                <label>
                                    <input type="radio" value="Yes" 
                                           {{ isset($detail->responses['healthy_today']) && $detail->responses['healthy_today'] == 'Yes' ? 'checked' : '' }} 
                                           disabled> Yes
                                </label>
                                <label>
                                    <input type="radio" value="No" 
                                           {{ isset($detail->responses['healthy_today']) && $detail->responses['healthy_today'] == 'No' ? 'checked' : '' }} 
                                           disabled> No
                                </label>
                            </div>

                            <div class="form-group mb-3">
                                <label>2. Are you donating today to test your blood for HIV, Hepatitis and/or Syphilis?</label><br>
                                <label>
                                    <input type="radio" value="Yes" 
                                           {{ isset($detail->responses['test_for_infections']) && $detail->responses['test_for_infections'] == 'Yes' ? 'checked' : '' }} 
                                           disabled> Yes
                                </label>
                                <label>
                                    <input type="radio" value="No" 
                                           {{ isset($detail->responses['test_for_infections']) && $detail->responses['test_for_infections'] == 'No' ? 'checked' : '' }} 
                                           disabled> No
                                </label>
                            </div>

                            <div class="form-group mb-3">
                                <label>3. Have you donated blood before?</label><br>
                                <label>
                                    <input type="radio" value="Yes" 
                                        {{ isset($detail->responses['donated_before']) && $detail->responses['donated_before'] == 'Yes' ? 'checked' : '' }} 
                                        disabled> Yes
                                </label>
                                <label>
                                    <input type="radio" value="No" 
                                        {{ isset($detail->responses['donated_before']) && $detail->responses['donated_before'] == 'No' ? 'checked' : '' }} 
                                        disabled> No
                                </label>

                                <!-- Additional question if "Yes" was selected -->
                                @if(isset($detail->responses['donated_before']) && $detail->responses['donated_before'] === 'Yes')
                                    <div style="margin-left: 20px; margin-top: 10px;">
                                        <label>If yes, have you had any problem during or after the donation?</label><br>
                                        <label>
                                            <input type="radio" value="Yes" 
                                                {{ isset($detail->responses['donation_problems']) && $detail->responses['donation_problems'] == 'Yes' ? 'checked' : '' }} 
                                                disabled> Yes
                                        </label>
                                        <label>
                                            <input type="radio" value="No" 
                                                {{ isset($detail->responses['donation_problems']) && $detail->responses['donation_problems'] == 'No' ? 'checked' : '' }} 
                                                disabled> No
                                        </label>

                                        <!-- Additional input if "Yes" was selected for donation problems -->
                                        @if(isset($detail->responses['donation_problems']) && $detail->responses['donation_problems'] === 'Yes')
                                            <div style="margin-left: 20px; margin-top: 10px;">
                                                <label>If yes, please specify:</label>
                                                <p>{{ $detail->responses['problem_details'] ?? 'N/A' }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <label>4. In the past one week, have you:</label>

                                <!-- Sub-question 4a -->
                                <div class="mt-2">
                                    <label>a) Taken any medication?</label><br>
                                    <label>
                                        <input type="radio" value="Yes"
                                            {{ isset($detail->responses['taken_medication']) && $detail->responses['taken_medication'] == 'Yes' ? 'checked' : '' }} 
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No"
                                            {{ isset($detail->responses['taken_medication']) && $detail->responses['taken_medication'] == 'No' ? 'checked' : '' }} 
                                            disabled> No
                                    </label>

                                    @if(isset($detail->responses['taken_medication']) && $detail->responses['taken_medication'] === 'Yes')
                                        <div style="margin-left: 20px; margin-top: 10px;">
                                            <label>If yes, please specify:</label>
                                            <p>{{ $detail->responses['medication_details'] ?? 'N/A' }}</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Sub-question 4b -->
                                <div class="mt-2">
                                    <label>b) Suffered from fever, cold, and/or cough?</label><br>
                                    <label>
                                        <input type="radio" value="Yes"
                                            {{ isset($detail->responses['fever_cold_cough']) && $detail->responses['fever_cold_cough'] == 'Yes' ? 'checked' : '' }} 
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No"
                                            {{ isset($detail->responses['fever_cold_cough']) && $detail->responses['fever_cold_cough'] == 'No' ? 'checked' : '' }} 
                                            disabled> No
                                    </label>
                                </div>

                                <!-- Sub-question 4c -->
                                <div class="mt-2">
                                    <label>c) Suffered from headache or migraine?</label><br>
                                    <label>
                                        <input type="radio" value="Yes"
                                            {{ isset($detail->responses['headache_migraine']) && $detail->responses['headache_migraine'] == 'Yes' ? 'checked' : '' }} 
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No"
                                            {{ isset($detail->responses['headache_migraine']) && $detail->responses['headache_migraine'] == 'No' ? 'checked' : '' }} 
                                            disabled> No
                                    </label>
                                </div>

                                <!-- Sub-question 4d -->
                                <div class="mt-2">
                                    <label>d) Seek treatment from a doctor for any health problem?</label><br>
                                    <label>
                                        <input type="radio" value="Yes"
                                            {{ isset($detail->responses['doctor_treatment']) && $detail->responses['doctor_treatment'] == 'Yes' ? 'checked' : '' }} 
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No"
                                            {{ isset($detail->responses['doctor_treatment']) && $detail->responses['doctor_treatment'] == 'No' ? 'checked' : '' }} 
                                            disabled> No
                                    </label>

                                    @if(isset($detail->responses['doctor_treatment']) && $detail->responses['doctor_treatment'] === 'Yes')
                                        <div style="margin-left: 20px; margin-top: 10px;">
                                            <label>If yes, please specify:</label>
                                            <p>{{ $detail->responses['doctor_treatment_details'] ?? 'N/A' }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>5. Are you suffering from / have ever suffered from / undergoing treatment for / had been treated for any of the following health problems?</label>

                                <div class="row">
                                    <!-- Column 1 -->
                                    <div class="col-md-6">
                                        <div class="mt-2">
                                            <label>Jaundice</label><br>
                                            <label>
                                                <input type="radio" value="Yes"
                                                    {{ isset($detail->responses['condition_jaundice']) && $detail->responses['condition_jaundice'] == 'Yes' ? 'checked' : '' }} 
                                                    disabled> Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="No"
                                                    {{ isset($detail->responses['condition_jaundice']) && $detail->responses['condition_jaundice'] == 'No' ? 'checked' : '' }} 
                                                    disabled> No
                                            </label>
                                        </div>

                                        <div class="mt-2">
                                            <label>Hepatitis B or Hepatitis C</label><br>
                                            <label>
                                                <input type="radio" value="Yes"
                                                    {{ isset($detail->responses['condition_hepatitis']) && $detail->responses['condition_hepatitis'] == 'Yes' ? 'checked' : '' }} 
                                                    disabled> Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="No"
                                                    {{ isset($detail->responses['condition_hepatitis']) && $detail->responses['condition_hepatitis'] == 'No' ? 'checked' : '' }} 
                                                    disabled> No
                                            </label>
                                        </div>
                                        
                                        <div class="mt-2">
                                            <label>HIV</label><br>
                                            <label>
                                                <input type="radio" value="Yes"
                                                    {{ isset($detail->responses['condition_hiv']) && $detail->responses['condition_hiv'] == 'Yes' ? 'checked' : '' }} 
                                                    disabled> Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="No"
                                                    {{ isset($detail->responses['condition_hiv']) && $detail->responses['condition_hiv'] == 'No' ? 'checked' : '' }} 
                                                    disabled> No
                                            </label>
                                        </div>
                                        
                                        <div class="mt-2">
                                            <label>STDs / Syphilis</label><br>
                                            <label>
                                                <input type="radio" value="Yes"
                                                    {{ isset($detail->responses['condition_stds_syphilis']) && $detail->responses['condition_stds_syphilis'] == 'Yes' ? 'checked' : '' }} 
                                                    disabled> Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="No"
                                                    {{ isset($detail->responses['condition_stds_syphilis']) && $detail->responses['condition_stds_syphilis'] == 'No' ? 'checked' : '' }} 
                                                    disabled> No
                                            </label>
                                        </div>

                                        <div class="mt-2">
                                            <label>Heart Disease</label><br>
                                            <label>
                                                <input type="radio" value="Yes"
                                                    {{ isset($detail->responses['condition_heart_disease']) && $detail->responses['condition_heart_disease'] == 'Yes' ? 'checked' : '' }} 
                                                    disabled> Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="No"
                                                    {{ isset($detail->responses['condition_heart_disease']) && $detail->responses['condition_heart_disease'] == 'No' ? 'checked' : '' }} 
                                                    disabled> No
                                            </label>
                                        </div>

                                        <div class="mt-2">
                                            <label>Renal Disease / Renal Failure</label><br>
                                            <label>
                                                <input type="radio" value="Yes"
                                                    {{ isset($detail->responses['condition_renal_disease']) && $detail->responses['condition_renal_disease'] == 'Yes' ? 'checked' : '' }} 
                                                    disabled> Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="No"
                                                    {{ isset($detail->responses['condition_renal_disease']) && $detail->responses['condition_renal_disease'] == 'No' ? 'checked' : '' }} 
                                                    disabled> No
                                            </label>
                                        </div>

                                        <div class="mt-2">
                                            <label>Asthma</label><br>
                                            <label>
                                                <input type="radio" value="Yes"
                                                    {{ isset($detail->responses['condition_asthma']) && $detail->responses['condition_asthma'] == 'Yes' ? 'checked' : '' }} 
                                                    disabled> Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="No"
                                                    {{ isset($detail->responses['condition_asthma']) && $detail->responses['condition_asthma'] == 'No' ? 'checked' : '' }} 
                                                    disabled> No
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Column 2 -->
                                    <div class="col-md-6">
                                        <div class="mt-2">
                                            <label>Tuberculosis</label><br>
                                            <label>
                                                <input type="radio" value="Yes"
                                                    {{ isset($detail->responses['condition_tuberculosis']) && $detail->responses['condition_tuberculosis'] == 'Yes' ? 'checked' : '' }} 
                                                    disabled> Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="No"
                                                    {{ isset($detail->responses['condition_tuberculosis']) && $detail->responses['condition_tuberculosis'] == 'No' ? 'checked' : '' }} 
                                                    disabled> No
                                            </label>
                                        </div>

                                        <div class="mt-2">
                                            <label>Diabetes</label><br>
                                            <label>
                                                <input type="radio" value="Yes"
                                                    {{ isset($detail->responses['condition_diabetes']) && $detail->responses['condition_diabetes'] == 'Yes' ? 'checked' : '' }} 
                                                    disabled> Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="No"
                                                    {{ isset($detail->responses['condition_diabetes']) && $detail->responses['condition_diabetes'] == 'No' ? 'checked' : '' }} 
                                                    disabled> No
                                            </label>
                                        </div>

                                        <div class="mt-2">
                                            <label>Hypertension</label><br>
                                            <label>
                                                <input type="radio" value="Yes"
                                                    {{ isset($detail->responses['condition_hypertension']) && $detail->responses['condition_hypertension'] == 'Yes' ? 'checked' : '' }} 
                                                    disabled> Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="No"
                                                    {{ isset($detail->responses['condition_hypertension']) && $detail->responses['condition_hypertension'] == 'No' ? 'checked' : '' }} 
                                                    disabled> No
                                            </label>
                                        </div>

                                        <div class="mt-2">
                                            <label>Malaria</label><br>
                                            <label>
                                                <input type="radio" value="Yes"
                                                    {{ isset($detail->responses['condition_malaria']) && $detail->responses['condition_malaria'] == 'Yes' ? 'checked' : '' }} 
                                                    disabled> Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="No"
                                                    {{ isset($detail->responses['condition_malaria']) && $detail->responses['condition_malaria'] == 'No' ? 'checked' : '' }} 
                                                    disabled> No
                                            </label>
                                        </div>

                                        <div class="mt-2">
                                            <label>Mental Illness</label><br>
                                            <label>
                                                <input type="radio" value="Yes"
                                                    {{ isset($detail->responses['condition_mental_illness']) && $detail->responses['condition_mental_illness'] == 'Yes' ? 'checked' : '' }} 
                                                    disabled> Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="No"
                                                    {{ isset($detail->responses['condition_mental_illness']) && $detail->responses['condition_mental_illness'] == 'No' ? 'checked' : '' }} 
                                                    disabled> No
                                            </label>
                                        </div><div class="mt-2">
                                            <label>Epilepsy</label><br>
                                            <label>
                                                <input type="radio" value="Yes"
                                                    {{ isset($detail->responses['condition_epilepsy']) && $detail->responses['condition_epilepsy'] == 'Yes' ? 'checked' : '' }} 
                                                    disabled> Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="No"
                                                    {{ isset($detail->responses['condition_epilepsy']) && $detail->responses['condition_epilepsy'] == 'No' ? 'checked' : '' }} 
                                                    disabled> No
                                            </label>
                                        </div>

                                        <div class="mt-2">
                                            <label>Others (please specify)</label><br>
                                            <label>
                                                <input type="radio" value="Yes"
                                                    {{ isset($detail->responses['condition_others']) && $detail->responses['condition_others'] == 'Yes' ? 'checked' : '' }} 
                                                    disabled> Yes
                                            </label>
                                            <label>
                                                <input type="radio" value="No"
                                                    {{ isset($detail->responses['condition_others']) && $detail->responses['condition_others'] == 'No' ? 'checked' : '' }} 
                                                    disabled> No
                                            </label>

                                            @if(isset($detail->responses['condition_others']) && $detail->responses['condition_others'] == 'Yes')
                                                <div style="margin-top: 10px;">
                                                    <label>If yes, please specify:</label>
                                                    <p>{{ $detail->responses['other_condition_details'] ?? 'N/A' }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>6. Has anybody in your family been diagnosed with or currently being treated for Hepatitis B or Hepatitis C?</label><br>
                                <label>
                                    <input type="radio" value="Yes" 
                                        {{ isset($detail->responses['family_hepatitis']) && $detail->responses['family_hepatitis'] == 'Yes' ? 'checked' : '' }} 
                                        disabled> Yes
                                </label>
                                <label>
                                    <input type="radio" value="No" 
                                        {{ isset($detail->responses['family_hepatitis']) && $detail->responses['family_hepatitis'] == 'No' ? 'checked' : '' }} 
                                        disabled> No
                                </label>

                                <!-- Show relationship input if 'Yes' was selected -->
                                @if(isset($detail->responses['family_hepatitis']) && $detail->responses['family_hepatitis'] == 'Yes')
                                    <div style="margin-left: 20px; margin-top: 10px;">
                                        <label>If yes, please state your relationship with him/her:</label>
                                        <p>{{ $detail->responses['family_relationship'] ?? 'N/A' }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <label>7. In the last 6 months, have you:</label>

                                <!-- Sub-question 7a -->
                                <div class="mt-2">
                                    <label>a) Underwent any surgical procedure or operation?</label><br>
                                    <label>
                                        <input type="radio" value="Yes" 
                                            {{ isset($detail->responses['surgical_procedure']) && $detail->responses['surgical_procedure'] == 'Yes' ? 'checked' : '' }} 
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No" 
                                            {{ isset($detail->responses['surgical_procedure']) && $detail->responses['surgical_procedure'] == 'No' ? 'checked' : '' }} 
                                            disabled> No
                                    </label>
                                </div>

                                <!-- Sub-question 7b -->
                                <div class="mt-2">
                                    <label>b) Received any blood transfusion?</label><br>
                                    <label>
                                        <input type="radio" value="Yes" 
                                            {{ isset($detail->responses['blood_transfusion']) && $detail->responses['blood_transfusion'] == 'Yes' ? 'checked' : '' }} 
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No" 
                                            {{ isset($detail->responses['blood_transfusion']) && $detail->responses['blood_transfusion'] == 'No' ? 'checked' : '' }} 
                                            disabled> No
                                    </label>
                                </div>

                                <!-- Sub-question 7c -->
                                <div class="mt-2">
                                    <label>c) Had any accidental needle stick injury?</label><br>
                                    <label>
                                        <input type="radio" value="Yes" 
                                            {{ isset($detail->responses['needle_injury']) && $detail->responses['needle_injury'] == 'Yes' ? 'checked' : '' }} 
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No" 
                                            {{ isset($detail->responses['needle_injury']) && $detail->responses['needle_injury'] == 'No' ? 'checked' : '' }} 
                                            disabled> No
                                    </label>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>8. Have you received any immunisation injection or any type of injection for beauty (e.g. botox, collagen) within the past 4 weeks?</label><br>
                                
                                <label>
                                    <input type="radio" value="Yes" 
                                        {{ isset($detail->responses['recent_injection']) && $detail->responses['recent_injection'] == 'Yes' ? 'checked' : '' }} 
                                        disabled> Yes
                                </label>
                                <label>
                                    <input type="radio" value="No" 
                                        {{ isset($detail->responses['recent_injection']) && $detail->responses['recent_injection'] == 'No' ? 'checked' : '' }} 
                                        disabled> No
                                </label>

                                <!-- Follow-up input for injection details, shown only if the user selected "Yes" -->
                                @if(isset($detail->responses['recent_injection']) && $detail->responses['recent_injection'] == 'Yes')
                                    <div style="margin-left: 20px; margin-top: 10px;">
                                        <label>If yes, please specify type and/or purpose:</label>
                                        <p>{{ $detail->responses['injection_details'] ?? 'N/A' }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <label>9. Have you had any dental treatment in the past 24 hours?</label><br>
                                
                                <label>
                                    <input type="radio" value="Yes" 
                                        {{ isset($detail->responses['dental_treatment']) && $detail->responses['dental_treatment'] == 'Yes' ? 'checked' : '' }} 
                                        disabled> Yes
                                </label>
                                <label>
                                    <input type="radio" value="No" 
                                        {{ isset($detail->responses['dental_treatment']) && $detail->responses['dental_treatment'] == 'No' ? 'checked' : '' }} 
                                        disabled> No
                                </label>
                            </div>

                            <div class="form-group mb-3">
                                <label>10. Have you had any body piercing, tattooing, blood-letting / cupping (berbekam), or acupuncture done within the past 6 months?</label><br>
                                
                                <label>
                                    <input type="radio" value="Yes" 
                                        {{ isset($detail->responses['body_modification']) && $detail->responses['body_modification'] == 'Yes' ? 'checked' : '' }} 
                                        disabled> Yes
                                </label>
                                <label>
                                    <input type="radio" value="No" 
                                        {{ isset($detail->responses['body_modification']) && $detail->responses['body_modification'] == 'No' ? 'checked' : '' }} 
                                        disabled> No
                                </label>
                            </div>

                            <div class="form-group mb-3">
                                <label>11. In the past 24 hours, have you taken any alcoholic drink until you were drunk or intoxicated?</label><br>
                                
                                <label>
                                    <input type="radio" value="Yes" 
                                        {{ isset($detail->responses['alcohol_intoxication']) && $detail->responses['alcohol_intoxication'] == 'Yes' ? 'checked' : '' }} 
                                        disabled> Yes
                                </label>
                                <label>
                                    <input type="radio" value="No" 
                                        {{ isset($detail->responses['alcohol_intoxication']) && $detail->responses['alcohol_intoxication'] == 'No' ? 'checked' : '' }} 
                                        disabled> No
                                </label>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label>12. Have you ever received:</label>

                                <!-- Sub-question 12a -->
                                <div class="mt-2">
                                    <label>a) Injection with human growth hormone?</label><br>
                                    <label>
                                        <input type="radio" value="Yes" 
                                            {{ isset($detail->responses['growth_hormone_injection']) && $detail->responses['growth_hormone_injection'] == 'Yes' ? 'checked' : '' }} 
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No" 
                                            {{ isset($detail->responses['growth_hormone_injection']) && $detail->responses['growth_hormone_injection'] == 'No' ? 'checked' : '' }} 
                                            disabled> No
                                    </label>
                                </div>

                                <!-- Sub-question 12b -->
                                <div class="mt-2">
                                    <label>b) Cornea transplant?</label><br>
                                    <label>
                                        <input type="radio" value="Yes" 
                                            {{ isset($detail->responses['cornea_transplant']) && $detail->responses['cornea_transplant'] == 'Yes' ? 'checked' : '' }} 
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No" 
                                            {{ isset($detail->responses['cornea_transplant']) && $detail->responses['cornea_transplant'] == 'No' ? 'checked' : '' }} 
                                            disabled> No
                                    </label>
                                </div>

                                <!-- Sub-question 12c -->
                                <div class="mt-2">
                                    <label>c) Brain membrane (duramater) transplant?</label><br>
                                    <label>
                                        <input type="radio" value="Yes" 
                                            {{ isset($detail->responses['brain_membrane_transplant']) && $detail->responses['brain_membrane_transplant'] == 'Yes' ? 'checked' : '' }} 
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No" 
                                            {{ isset($detail->responses['brain_membrane_transplant']) && $detail->responses['brain_membrane_transplant'] == 'No' ? 'checked' : '' }} 
                                            disabled> No
                                    </label>
                                </div>

                                <!-- Sub-question 12d -->
                                <div class="mt-2">
                                    <label>d) Bone marrow or stem cell transplant?</label><br>
                                    <label>
                                        <input type="radio" value="Yes" 
                                            {{ isset($detail->responses['bone_marrow_transplant']) && $detail->responses['bone_marrow_transplant'] == 'Yes' ? 'checked' : '' }} 
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No" 
                                            {{ isset($detail->responses['bone_marrow_transplant']) && $detail->responses['bone_marrow_transplant'] == 'No' ? 'checked' : '' }} 
                                            disabled> No
                                    </label>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>13. Risk of infection with variant Creutzfeldt-Jakob Disease (vCJD)</label>

                                <!-- Sub-question 13a -->
                                <div class="mt-2">
                                    <label>a) Have you ever visited or lived in the United Kingdom (England, Northern Ireland, Ireland, Wales, Scotland, the Isle of Man, the Channel Island) or the Republic of Ireland for a cumulative period of 6 months or more between 1st January 1980 and 31st December 1996?</label><br>
                                    <label>
                                        <input type="radio" value="Yes" 
                                            {{ isset($detail->responses['uk_visit_1980_1996']) && $detail->responses['uk_visit_1980_1996'] == 'Yes' ? 'checked' : '' }} 
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No" 
                                            {{ isset($detail->responses['uk_visit_1980_1996']) && $detail->responses['uk_visit_1980_1996'] == 'No' ? 'checked' : '' }} 
                                            disabled> No
                                    </label>
                                </div>

                                <!-- Sub-question 13b -->
                                <div class="mt-2">
                                    <label>b) Have you ever received a transfusion or injection of blood or blood product while in the United Kingdom between 1st January 1980 until now?</label><br>
                                    <label>
                                        <input type="radio" value="Yes" 
                                            {{ isset($detail->responses['uk_blood_transfusion']) && $detail->responses['uk_blood_transfusion'] == 'Yes' ? 'checked' : '' }} 
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No" 
                                            {{ isset($detail->responses['uk_blood_transfusion']) && $detail->responses['uk_blood_transfusion'] == 'No' ? 'checked' : '' }} 
                                            disabled> No
                                    </label>
                                </div>

                                <!-- Sub-question 13c -->
                                <div class="mt-2">
                                    <label>c) Have you ever visited or lived in the following European countries for a cumulative period of 5 years or more between 1st January 1980 until now? (Austria, Belgium, Denmark, Finland, France, Germany, Greece, Holland, Italy, Liechtenstein, Luxembourg, Norway, Portugal, Spain, Sweden, and Switzerland)</label><br>
                                    <label>
                                        <input type="radio" value="Yes" 
                                            {{ isset($detail->responses['europe_visit_1980_now']) && $detail->responses['europe_visit_1980_now'] == 'Yes' ? 'checked' : '' }} 
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No" 
                                            {{ isset($detail->responses['europe_visit_1980_now']) && $detail->responses['europe_visit_1980_now'] == 'No' ? 'checked' : '' }} 
                                            disabled> No
                                    </label>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label><strong>14. For patient safety, the following questions SHALL be answered HONESTLY, even if you were only involved in it once. You are required to answer the following questions in front of the assigned doctor or officer from MOH who interviews you.</strong></label>

                                <!-- Sub-question 14a -->
                                <div class="mt-2">
                                    <label>a) If you are a man, have you ever had sex with another man?</label><br>
                                    <label>
                                        <input type="radio" value="Yes"
                                            {{ isset($detail->responses['man_sex_with_man']) && $detail->responses['man_sex_with_man'] == 'Yes' ? 'checked' : '' }}
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No"
                                            {{ isset($detail->responses['man_sex_with_man']) && $detail->responses['man_sex_with_man'] == 'No' ? 'checked' : '' }}
                                            disabled> No
                                    </label>
                                </div>

                                <!-- Sub-question 14b -->
                                <div class="mt-2">
                                    <label>b) Have you ever had sex with a commercial sex worker/prostitute?</label><br>
                                    <label>
                                        <input type="radio" value="Yes"
                                            {{ isset($detail->responses['sex_with_prostitute']) && $detail->responses['sex_with_prostitute'] == 'Yes' ? 'checked' : '' }}
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No"
                                            {{ isset($detail->responses['sex_with_prostitute']) && $detail->responses['sex_with_prostitute'] == 'No' ? 'checked' : '' }}
                                            disabled> No
                                    </label>
                                </div>

                                <!-- Repeat the same logic for other sub-questions -->

                                <!-- Sub-question 14c -->
                                <div class="mt-2">
                                    <label>c) Have you ever paid or received payment in exchange for sex?</label><br>
                                    <label>
                                        <input type="radio" value="Yes"
                                            {{ isset($detail->responses['paid_for_sex']) && $detail->responses['paid_for_sex'] == 'Yes' ? 'checked' : '' }}
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No"
                                            {{ isset($detail->responses['paid_for_sex']) && $detail->responses['paid_for_sex'] == 'No' ? 'checked' : '' }}
                                            disabled> No
                                    </label>
                                </div>

                                <!-- Sub-question 14d -->
                                <div class="mt-2">
                                    <label>d) Have you ever had more than one sexual partner?</label><br>
                                    <label>
                                        <input type="radio" value="Yes"
                                            {{ isset($detail->responses['multiple_sexual_partners']) && $detail->responses['multiple_sexual_partners'] == 'Yes' ? 'checked' : '' }}
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No"
                                            {{ isset($detail->responses['multiple_sexual_partners']) && $detail->responses['multiple_sexual_partners'] == 'No' ? 'checked' : '' }}
                                            disabled> No
                                    </label>
                                </div>

                                <!-- Sub-question 14e -->
                                <div class="mt-2">
                                    <label>e) Have you had any new sexual partner(s) within the past 12 months?</label><br>
                                    <label>
                                        <input type="radio" value="Yes"
                                            {{ isset($detail->responses['new_sexual_partner']) && $detail->responses['new_sexual_partner'] == 'Yes' ? 'checked' : '' }}
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No"
                                            {{ isset($detail->responses['new_sexual_partner']) && $detail->responses['new_sexual_partner'] == 'No' ? 'checked' : '' }}
                                            disabled> No
                                    </label>
                                </div>

                                <!-- Sub-question 14f -->
                                <div class="mt-2">
                                    <label>f) Have you ever injected yourself with illegal drugs, including drugs for bodybuilding?</label><br>
                                    <label>
                                        <input type="radio" value="Yes"
                                            {{ isset($detail->responses['injected_illegal_drugs']) && $detail->responses['injected_illegal_drugs'] == 'Yes' ? 'checked' : '' }}
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No"
                                            {{ isset($detail->responses['injected_illegal_drugs']) && $detail->responses['injected_illegal_drugs'] == 'No' ? 'checked' : '' }}
                                            disabled> No
                                    </label>
                                </div>

                                <!-- Sub-question 14g -->
                                <div class="mt-2">
                                    <label>g) Does your sexual partner belong to any of the above categories?</label><br>
                                    <label>
                                        <input type="radio" value="Yes"
                                            {{ isset($detail->responses['partner_in_high_risk_category']) && $detail->responses['partner_in_high_risk_category'] == 'Yes' ? 'checked' : '' }}
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No"
                                            {{ isset($detail->responses['partner_in_high_risk_category']) && $detail->responses['partner_in_high_risk_category'] == 'No' ? 'checked' : '' }}
                                            disabled> No
                                    </label>
                                </div>

                                <!-- Sub-question 14h -->
                                <div class="mt-2">
                                    <label>h) Have you or your sexual partner ever been tested positive for HIV?</label><br>
                                    <label>
                                        <input type="radio" value="Yes"
                                            {{ isset($detail->responses['partner_hiv_positive']) && $detail->responses['partner_hiv_positive'] == 'Yes' ? 'checked' : '' }}
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No"
                                            {{ isset($detail->responses['partner_hiv_positive']) && $detail->responses['partner_hiv_positive'] == 'No' ? 'checked' : '' }}
                                            disabled> No
                                    </label>
                                </div>

                                <!-- Sub-question 14i -->
                                <div class="mt-2">
                                    <label>i) Do you think you or your sexual partner may be tested positive for HIV?</label><br>
                                    <label>
                                        <input type="radio" value="Yes"
                                            {{ isset($detail->responses['potential_hiv_positive']) && $detail->responses['potential_hiv_positive'] == 'Yes' ? 'checked' : '' }}
                                            disabled> Yes
                                    </label>
                                    <label>
                                        <input type="radio" value="No"
                                            {{ isset($detail->responses['potential_hiv_positive']) && $detail->responses['potential_hiv_positive'] == 'No' ? 'checked' : '' }}
                                            disabled> No
                                    </label>
                                </div>
                            </div>

                                <!-- Gender Selection -->
                                <div class="form-group mb-3">
                                    <label>Gender</label><br>
                                    <label>
                                        <input type="radio" value="Male" 
                                            {{ isset($detail->responses['gender']) && $detail->responses['gender'] == 'Male' ? 'checked' : '' }} 
                                            disabled> Male
                                    </label>
                                    <label>
                                        <input type="radio" value="Female" 
                                            {{ isset($detail->responses['gender']) && $detail->responses['gender'] == 'Female' ? 'checked' : '' }} 
                                            disabled> Female
                                    </label>
                                </div>

                                <!-- Question 15 (for female donors only) -->
                                <div class="form-group mb-3" id="femaleQuestions">
                                    <label><strong>15. To be answered by female donors only</strong></label><br>
                                    
                                    <!-- Sub-question 15a -->
                                    <div class="mt-2">
                                        <label>a) Are you having your menstrual period?</label><br>
                                        <label>
                                            <input type="radio" value="Yes" 
                                                {{ isset($detail->responses['menstrual_period']) && $detail->responses['menstrual_period'] == 'Yes' ? 'checked' : '' }} 
                                                disabled> Yes
                                        </label>
                                        <label>
                                            <input type="radio" value="No" 
                                                {{ isset($detail->responses['menstrual_period']) && $detail->responses['menstrual_period'] == 'No' ? 'checked' : '' }} 
                                                disabled> No
                                        </label>
                                    </div>

                                    <!-- Sub-question 15b -->
                                    <div class="mt-2">
                                        <label>b) Are you pregnant or may be pregnant?</label><br>
                                        <label>
                                            <input type="radio" value="Yes" 
                                                {{ isset($detail->responses['pregnant']) && $detail->responses['pregnant'] == 'Yes' ? 'checked' : '' }} 
                                                disabled> Yes
                                        </label>
                                        <label>
                                            <input type="radio" value="No" 
                                                {{ isset($detail->responses['pregnant']) && $detail->responses['pregnant'] == 'No' ? 'checked' : '' }} 
                                                disabled> No
                                        </label>
                                    </div>

                                    <!-- Sub-question 15c -->
                                    <div class="mt-2">
                                        <label>c) Do you have a child that is still breast-feeding?</label><br>
                                        <label>
                                            <input type="radio" value="Yes" 
                                                {{ isset($detail->responses['breast_feeding']) && $detail->responses['breast_feeding'] == 'Yes' ? 'checked' : '' }} 
                                                disabled> Yes
                                        </label>
                                        <label>
                                            <input type="radio" value="No" 
                                                {{ isset($detail->responses['breast_feeding']) && $detail->responses['breast_feeding'] == 'No' ? 'checked' : '' }} 
                                                disabled> No
                                        </label>
                                    </div>

                                    <!-- Sub-question 15d -->
                                    <div class="mt-2">
                                        <label>d) Have you given birth or had a miscarriage in the past 6 months?</label><br>
                                        <label>
                                            <input type="radio" value="Yes" 
                                                {{ isset($detail->responses['recent_birth_miscarriage']) && $detail->responses['recent_birth_miscarriage'] == 'Yes' ? 'checked' : '' }} 
                                                disabled> Yes
                                        </label>
                                        <label>
                                            <input type="radio" value="No" 
                                                {{ isset($detail->responses['recent_birth_miscarriage']) && $detail->responses['recent_birth_miscarriage'] == 'No' ? 'checked' : '' }} 
                                                disabled> No
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label>
                                        <input type="checkbox" 
                                            value="Yes" 
                                            {{ isset($detail->responses['consent']) && $detail->responses['consent'] == 'Yes' ? 'checked' : '' }} 
                                            disabled> 
                                        I confirm that I understand all the above questions and declare that I have answered them truthfully and sincerely.
                                    </label>
                                </div>
                        </div>
                    </div>
                </div>
            </div>