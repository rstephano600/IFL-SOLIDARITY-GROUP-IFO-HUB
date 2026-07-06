{{-- Main Wrapper Card (Optional: you could remove this and just use the individual cards) --}}
<div class="container-xl">
    <form action="{{ route('clients.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-lock"></i> Account & Primary Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">

            <div class="col-md-4">
                <label for="group_id" class="form-label">Client Group</label>
                <select id="group_id" name="group_id" class="form-select @error('group_id') is-invalid @enderror">
                    <option value="">-- Select Group --</option>
                    @foreach($centres as $centre)
                        <option value="{{ $centre->id }}"
                            {{ (old('group_id', $selectedGroupId ?? ($client->group_id ?? '')) == $centre->id) ? 'selected' : '' }}>
                            {{ $centre->group_name }} ({{ $centre->group_code }})
                        </option>
                    @endforeach
                </select>
                @error('group_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

                    
                    <div class="col-md-4">
                        <label for="client_type" class="form-label">Client Type <span class="text-danger">*</span></label>
                        <select id="client_type" name="client_type" class="form-select @error('client_type') is-invalid @enderror" required>
                            <option value="">-- Select Type --</option>
                            @php $clientType = old('client_type', $client->client_type ?? ''); @endphp
                            <option value="individual" {{ $clientType == 'individual' ? 'selected' : '' }}>Individual</option>
                            <option value="business" {{ $clientType == 'business' ? 'selected' : '' }}>Business</option>
                            <option value="sole_proprietor" {{ $clientType == 'sole_proprietor' ? 'selected' : '' }}>Sole Proprietor</option>
                            <option value="partnership" {{ $clientType == 'partnership' ? 'selected' : '' }}>Partnership</option>
                            <option value="corporation" {{ $clientType == 'corporation' ? 'selected' : '' }}>Corporation</option>
                            <option value="llc" {{ $clientType == 'llc' ? 'selected' : '' }}>LLC</option>
                        </select>
                        @error('client_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" id="first_name" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                value="{{ old('first_name', $client->first_name ?? '') }}" required>
                        @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name" class="form-control @error('middle_name') is-invalid @enderror"
                                value="{{ old('middle_name', $client->middle_name ?? '') }}">
                        @error('middle_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" id="last_name" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                value="{{ old('last_name', $client->last_name ?? '') }}" required>
                        @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $client->email ?? '') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $client->phone ?? '') }}" required>
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="alternative_phone" class="form-label">Alternative Phone</label>
                        <input type="text" id="alternative_phone" name="alternative_phone" class="form-control @error('alternative_phone') is-invalid @enderror"
                                value="{{ old('alternative_phone', $client->alternative_phone ?? '') }}">
                        @error('alternative_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-briefcase"></i> Business Details (If Applicable)</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="business_name" class="form-label">Business Name</label>
                        <input type="text" id="business_name" name="business_name" class="form-control @error('business_name') is-invalid @enderror"
                                value="{{ old('business_name', $client->business_name ?? '') }}">
                        @error('business_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="business_capital" class="form-label">Business Capital</label>
                        <input type="number" id="business_capital" name="business_capital" class="form-control @error('business_capital') is-invalid @enderror"
                                value="{{ old('business_capital', $client->business_capital ?? '') }}">
                        @error('business_capital') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="business_income" class="form-label">Business Income</label>
                        <input type="number" id="business_income" name="business_income" class="form-control @error('business_income') is-invalid @enderror"
                                value="{{ old('business_income', $client->business_income ?? '') }}">
                        @error('business_income') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="business_location" class="form-label">Business Location</label>
                        <input type="text" id="business_location" name="business_location" class="form-control @error('business_location') is-invalid @enderror"
                                value="{{ old('business_location', $client->business_location ?? '') }}">
                        @error('business_location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="partner_in_business" class="form-label">Partner in Business</label>
                        <input type="text" id="partner_in_business" name="partner_in_business" class="form-control @error('partner_in_business') is-invalid @enderror"
                                value="{{ old('partner_in_business', $client->partner_in_business ?? '') }}">
                        @error('partner_in_business') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="business_registration_number" class="form-label">Business Registration Number</label>
                        <input type="text" id="business_registration_number" name="business_registration_number" class="form-control @error('business_registration_number') is-invalid @enderror"
                                value="{{ old('business_registration_number', $client->business_registration_number ?? '') }}">
                        @error('business_registration_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="tax_identification_number" class="form-label">Tax ID Number (TIN)</label>
                        <input type="text" id="tax_identification_number" name="tax_identification_number" class="form-control @error('tax_identification_number') is-invalid @enderror"
                                value="{{ old('tax_identification_number', $client->tax_identification_number ?? '') }}">
                        @error('tax_identification_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="industry_sector" class="form-label">Industry Sector</label>
                        <input type="text" id="industry_sector" name="industry_sector" class="form-control @error('industry_sector') is-invalid @enderror"
                                value="{{ old('industry_sector', $client->industry_sector ?? '') }}">
                        @error('industry_sector') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-2">
                        <label for="months_in_business" class="form-label">Months</label>
                        <input type="number" id="months_in_business" name="months_in_business" class="form-control @error('months_in_business') is-invalid @enderror"
                                value="{{ old('months_in_business', $client->months_in_business ?? '') }}" min="0">
                        @error('months_in_business') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-2">
                        <label for="years_in_business" class="form-label">Years</label>
                        <input type="number" id="years_in_business" name="years_in_business" class="form-control @error('years_in_business') is-invalid @enderror"
                                value="{{ old('years_in_business', $client->years_in_business ?? '') }}" min="0">
                        @error('years_in_business') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-2">
                        <label for="number_of_employees" class="form-label"># of Employees</label>
                        <input type="number" id="number_of_employees" name="number_of_employees" class="form-control @error('number_of_employees') is-invalid @enderror"
                                value="{{ old('number_of_employees', $client->number_of_employees ?? '') }}" min="0">
                        @error('number_of_employees') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Address Details</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="address_line1" class="form-label">Address Line 1</label>
                        <input type="text" id="address_line1" name="address_line1" class="form-control @error('address_line1') is-invalid @enderror"
                                value="{{ old('address_line1', $client->address_line1 ?? '') }}">
                        @error('address_line1') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="address_line2" class="form-label">Address Line 2 (Optional)</label>
                        <input type="text" id="address_line2" name="address_line2" class="form-control @error('address_line2') is-invalid @enderror"
                                value="{{ old('address_line2', $client->address_line2 ?? '') }}">
                        @error('address_line2') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" id="city" name="city" class="form-control @error('city') is-invalid @enderror"
                                value="{{ old('city', $client->city ?? '') }}">
                        @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="state_province" class="form-label">State / Province</label>
                        <input type="text" id="state_province" name="state_province" class="form-control @error('state_province') is-invalid @enderror"
                                value="{{ old('state_province', $client->state_province ?? '') }}">
                        @error('state_province') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="postal_code" class="form-label">Postal Code</label>
                        <input type="text" id="postal_code" name="postal_code" class="form-control @error('postal_code') is-invalid @enderror"
                                value="{{ old('postal_code', $client->postal_code ?? '') }}">
                        @error('postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" id="country" name="country" class="form-control @error('country') is-invalid @enderror"
                                value="{{ old('country', $client->country ?? '') }}">
                        @error('country') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-file-earmark-person"></i> Personal KYC Details</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="national_id" class="form-label">NIDA / National ID</label>
                        <input type="text" id="national_id" name="national_id" class="form-control @error('national_id') is-invalid @enderror"
                                value="{{ old('national_id', $client->national_id ?? '') }}">
                        @error('national_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="other_name" class="form-label">Other Name</label>
                        <input type="text" id="other_name" name="other_name" class="form-control @error('other_name') is-invalid @enderror"
                                value="{{ old('other_name', $client->other_name ?? '') }}">
                        @error('other_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror"
                                value="{{ old('date_of_birth', $client->date_of_birth ?? '') }}">
                        @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror">
                            <option value="">-- Select Gender --</option>
                            @php $gender = old('gender', $client->gender ?? ''); @endphp
                            <option value="male" {{ $gender == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $gender == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="marital_status" class="form-label">Marital Status</label>
                        <select id="marital_status" name="marital_status" class="form-select @error('marital_status') is-invalid @enderror">
                            <option value="">-- Select Status --</option>
                            @php $status = old('marital_status', $client->marital_status ?? ''); @endphp
                            <option value="single" {{ $status == 'single' ? 'selected' : '' }}>Single</option>
                            <option value="married" {{ $status == 'married' ? 'selected' : '' }}>Married</option>
                            <option value="divorced" {{ $status == 'divorced' ? 'selected' : '' }}>Divorced</option>
                            <option value="widowed" {{ $status == 'widowed' ? 'selected' : '' }}>Widowed</option>
                        </select>
                        @error('marital_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="spouse_name" class="form-label">Spouse Name (If Married)</label>
                        <input type="text" id="spouse_name" name="spouse_name" class="form-control @error('spouse_name') is-invalid @enderror"
                                value="{{ old('spouse_name', $client->spouse_name ?? '') }}">
                        @error('spouse_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6 d-flex flex-column">
                        <label for="profile_picture" class="form-label">Profile Picture</label>
                        <input class="form-control @error('profile_picture') is-invalid @enderror" type="file" id="profile_picture" name="profile_picture">
                        @error('profile_picture') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        {{-- NOTE: If $client->profile_picture exists, you might show a preview here --}}
                    </div>
                    
                    <div class="col-md-6 d-flex flex-column">
                        <label for="sign_image" class="form-label">Signature Image</label>
                        <input class="form-control @error('sign_image') is-invalid @enderror" type="file" id="sign_image" name="sign_image">
                        @error('sign_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="is_street_leader" name="is_street_leader"
                                    {{ old('is_street_leader', $client->is_street_leader ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_street_leader">
                                Designate as Street/Area Leader
                            </label>
                        </div>
                        @error('is_street_leader') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="bi bi-shield-lock"></i> Credit and Risk Details</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="credit_score" class="form-label">Credit Score (0-1000)</label>
                        <input type="number" id="credit_score" name="credit_score" class="form-control @error('credit_score') is-invalid @enderror"
                                value="{{ old('credit_score', $client->credit_score ?? '') }}" min="0" max="1000">
                        @error('credit_score') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="credit_rating" class="form-label">Credit Rating</label>
                        <input type="text" id="credit_rating" name="credit_rating" class="form-control @error('credit_rating') is-invalid @enderror"
                                value="{{ old('credit_rating', $client->credit_rating ?? '') }}">
                        @error('credit_rating') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="risk_category" class="form-label">Risk Category</label>
                        <input type="text" id="risk_category" name="risk_category" class="form-control @error('risk_category') is-invalid @enderror"
                                value="{{ old('risk_category', $client->risk_category ?? '') }}">
                        @error('risk_category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="">-- Select Status --</option>
                            @php $status = old('status', $client->status ?? ''); @endphp
                            <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="blacklisted" {{ $status == 'blacklisted' ? 'selected' : '' }}>Blacklisted</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label for="blacklist_reason" class="form-label">Blacklist Reason</label>
                        <textarea id="blacklist_reason" name="blacklist_reason" class="form-control @error('blacklist_reason') is-invalid @enderror" rows="2">{{ old('blacklist_reason', $client->blacklist_reason ?? '') }}</textarea>
                        <small class="form-text text-muted">Required only if status is "Blacklisted".</small>
                        @error('blacklist_reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-file-earmark-check"></i> Administrative & KYC Completion</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
<div class="col-md-6">
    <label for="assigned_loan_officer_id" class="form-label">Assigned Loan Officer</label>
    <select id="assigned_loan_officer_id" name="assigned_loan_officer_id"
        class="form-select @error('assigned_loan_officer_id') is-invalid @enderror">
        <option value="">-- Select Officer --</option>
        @foreach($loanOfficers as $loanOfficer)
            <option value="{{ $loanOfficer->id }}"
                {{ (old('assigned_loan_officer_id', $selectedLoanOfficerId ?? ($client->assigned_loan_officer_id ?? '')) == $loanOfficer->id) ? 'selected' : '' }}>
                {{ $loanOfficer->first_name }} {{ $loanOfficer->last_name }}
            </option>
        @endforeach
    </select>
    @error('assigned_loan_officer_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


                    <div class="col-md-6 d-flex align-items-center pt-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="kyc_completed" name="kyc_completed" value="1"
                                    {{ old('kyc_completed', $client->kyc_completed ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="kyc_completed">
                                **KYC Completed** (Know Your Customer)
                            </label>
                            @error('kyc_completed') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
