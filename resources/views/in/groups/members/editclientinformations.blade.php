@extends('layouts.workingside')
@section('title', 'Edit Client')
@section('page-title', 'Clients Management')

@section('content')

{{-- PAGE HEADER --}}
<div class="arbif-page-header">

    <h3>
        <div class="page-icon">
            <i class="fas fa-user-edit"></i>
        </div>
        Edit Client
    </h3>

    <div class="d-flex gap-2">

        <a href="{{ route('clientinformations') }}"
           class="arbif-btn-cancel">
            <i class="fas fa-arrow-left"></i>
            Back to Clients
        </a>

    </div>

</div>




<div class="arbif-card">

    <div class="arbif-card-body">

        <form method="POST"
              action="{{ route('updateclientinformations', encrypt($client->id)) }}"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="row g-3">


                {{-- =============================== --}}
                {{-- SECTION: PERSONAL INFORMATION   --}}
                {{-- =============================== --}}

                <div class="col-12">
                    <h5 class="arbif-section-title">
                        <i class="fas fa-id-card me-2"></i>
                        Personal Information
                    </h5>
                </div>


                <div class="col-md-4">
                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text"
                           name="FirstName"
                           value="{{ old('FirstName', optional($client->client)->FirstName) }}"
                           class="form-control @error('FirstName') is-invalid @enderror"
                           required>
                    @error('FirstName')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-4">
                    <label class="form-label">Middle Name</label>
                    <input type="text"
                           name="MiddleName"
                           value="{{ old('MiddleName', optional($client->client)->MiddleName) }}"
                           class="form-control @error('MiddleName') is-invalid @enderror">
                    @error('MiddleName')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-4">
                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text"
                           name="LastName"
                           value="{{ old('LastName', optional($client->client)->LastName) }}"
                           class="form-control @error('LastName') is-invalid @enderror"
                           required>
                    @error('LastName')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-4">
                    <label class="form-label">Email Address</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email', optional($client->client)->email) }}"
                           class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-4">
                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                    <input type="text"
                           name="phone"
                           value="{{ old('phone', optional($client->client)->phone) }}"
                           class="form-control @error('phone') is-invalid @enderror"
                           required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-4">
                    <label class="form-label">Alternative Phone</label>
                    <input type="text"
                           name="alternative_phone"
                           value="{{ old('alternative_phone', $client->alternative_phone) }}"
                           class="form-control @error('alternative_phone') is-invalid @enderror">
                    @error('alternative_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-4">
                    <label class="form-label">Date of Birth</label>
                    <input type="date"
                           name="Dob"
                           value="{{ old('Dob', optional($client->client)->Dob ? \Carbon\Carbon::parse($client->client->Dob)->format('Y-m-d') : '') }}"
                           class="form-control @error('Dob') is-invalid @enderror">
                    @error('Dob')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-4">
                    <label class="form-label">Gender</label>
                    <select name="gender"
                            class="form-select @error('gender') is-invalid @enderror">
                        <option value="">Select Gender</option>
                        <option value="male"   {{ old('gender', optional($client->client)->gender) === 'male'   ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', optional($client->client)->gender) === 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-4">
                    <label class="form-label">Marital Status</label>
                    <select name="marital_status"
                            class="form-select @error('marital_status') is-invalid @enderror">
                        <option value="">Select Status</option>
                        @foreach(['Single','Married','Divorced','Widowed'] as $ms)
                            <option value="{{ $ms }}" {{ old('marital_status', $client->marital_status) === $ms ? 'selected' : '' }}>
                                {{ $ms }}
                            </option>
                        @endforeach
                    </select>
                    @error('marital_status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-4">
                    <label class="form-label">Spouse Name</label>
                    <input type="text"
                           name="spouse_name"
                           value="{{ old('spouse_name', $client->spouse_name) }}"
                           class="form-control @error('spouse_name') is-invalid @enderror">
                    @error('spouse_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-4">
                    <label class="form-label">National ID</label>
                    <input type="text"
                           name="national_id"
                           value="{{ old('national_id', $client->national_id) }}"
                           class="form-control @error('national_id') is-invalid @enderror">
                    @error('national_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-4">
                    <label class="form-label">Other Name</label>
                    <input type="text"
                           name="other_name"
                           value="{{ old('other_name', $client->other_name) }}"
                           class="form-control @error('other_name') is-invalid @enderror">
                    @error('other_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-4">
                    <label class="form-label">Street Leader</label>
                    <input type="text"
                           name="street_leader"
                           value="{{ old('street_leader', $client->street_leader) }}"
                           class="form-control @error('street_leader') is-invalid @enderror">
                    @error('street_leader')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>




                {{-- =============================== --}}
                {{-- SECTION: GROUP INFORMATION      --}}
                {{-- =============================== --}}

                <div class="col-12 mt-4">
                    <h5 class="arbif-section-title">
                        <i class="fas fa-users me-2"></i>
                        Group Information
                    </h5>
                </div>


                <div class="col-md-4">
                    <label class="form-label">Group Center</label>
                    <select id="groupCenterSelect"
                            class="form-select select2_demo_3">
                        <option value="">Select Group Center</option>
                        @foreach($groupcenters as $center)
                            <option value="{{ $center->id }}"
                                {{ $client->group_center_id == $center->id ? 'selected' : '' }}>
                                {{ $center->center_name }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="col-md-4">
                    <label class="form-label">Group</label>
                    <select name="group_id"
                            id="groupSelect"
                            class="form-select select2_demo_3 @error('group_id') is-invalid @enderror">
                        <option value="">Select Group</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}"
                                    data-center="{{ $group->group_center_id }}"
                                    {{ old('group_id', $client->group_id) == $group->id ? 'selected' : '' }}>
                                {{ $group->group_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('group_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-4">
                    <label class="form-label">Credit Officer</label>
                    <select name="credit_officer_id"
                            id="creditOfficerSelect"
                            class="form-select select2_demo_3 @error('credit_officer_id') is-invalid @enderror">
                        <option value="">Select Credit Officer</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}"
                                {{ old('credit_officer_id', $client->credit_officer_id) == $employee->id ? 'selected' : '' }}>
                                {{ $employee->FirstName ?? '' }}
                                {{ $employee->MiddleName ?? '' }}
                                {{ $employee->LastName ?? '' }}
                                — {{ $employee->EmployeeID ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('credit_officer_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-4">
                    <label class="form-label">Client Type <span class="text-danger">*</span></label>
                    <select name="client_type"
                            class="form-select @error('client_type') is-invalid @enderror"
                            required>
                        <option value="">Select Type</option>
                        @foreach(['Individual','Business','Group'] as $ct)
                            <option value="{{ $ct }}" {{ old('client_type', $client->client_type) === $ct ? 'selected' : '' }}>
                                {{ $ct }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-4">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required>
                        <option value="">Select Status</option>
                        @foreach(['Active','Inactive','Blacklisted','Pending'] as $st)
                            <option value="{{ $st }}" {{ old('status', $client->status) === $st ? 'selected' : '' }}>
                                {{ $st }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-4">
                    <label class="form-label">Blacklist Reason</label>
                    <input type="text"
                           name="blacklist_reason"
                           value="{{ old('blacklist_reason', $client->blacklist_reason) }}"
                           class="form-control @error('blacklist_reason') is-invalid @enderror">
                    @error('blacklist_reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-4 d-flex align-items-center mt-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               type="checkbox"
                               name="kyc_completed"
                               id="kycCompleted"
                               value="1"
                               {{ old('kyc_completed', $client->kyc_completed) ? 'checked' : '' }}>
                        <label class="form-check-label" for="kycCompleted">
                            KYC Completed
                        </label>
                    </div>
                </div>




                {{-- =============================== --}}
                {{-- SECTION: ADDRESS INFORMATION    --}}
                {{-- =============================== --}}

                <div class="col-12 mt-4">
                    <h5 class="arbif-section-title">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Address Information
                    </h5>
                </div>


                <div class="col-md-6">
                    <label class="form-label">Address Line 1</label>
                    <input type="text"
                           name="address_line1"
                           value="{{ old('address_line1', $client->address_line1) }}"
                           class="form-control @error('address_line1') is-invalid @enderror">
                    @error('address_line1')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-6">
                    <label class="form-label">Address Line 2</label>
                    <input type="text"
                           name="address_line2"
                           value="{{ old('address_line2', $client->address_line2) }}"
                           class="form-control @error('address_line2') is-invalid @enderror">
                    @error('address_line2')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-3">
                    <label class="form-label">City</label>
                    <input type="text"
                           name="city"
                           value="{{ old('city', $client->city) }}"
                           class="form-control @error('city') is-invalid @enderror">
                    @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-3">
                    <label class="form-label">State / Province</label>
                    <input type="text"
                           name="state_province"
                           value="{{ old('state_province', $client->state_province) }}"
                           class="form-control @error('state_province') is-invalid @enderror">
                    @error('state_province')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-3">
                    <label class="form-label">Postal Code</label>
                    <input type="text"
                           name="postal_code"
                           value="{{ old('postal_code', $client->postal_code) }}"
                           class="form-control @error('postal_code') is-invalid @enderror">
                    @error('postal_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-3">
                    <label class="form-label">Country</label>
                    <select name="country_id"
                            class="form-select select2_demo_3 @error('country_id') is-invalid @enderror">
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}"
                                {{ old('country_id', $client->country_id) == $country->id ? 'selected' : '' }}>
                                {{ $country->country_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('country_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>




                {{-- =============================== --}}
                {{-- SECTION: BUSINESS INFORMATION   --}}
                {{-- =============================== --}}

                <div class="col-12 mt-4">
                    <h5 class="arbif-section-title">
                        <i class="fas fa-briefcase me-2"></i>
                        Business Information
                    </h5>
                </div>


                <div class="col-md-6">
                    <label class="form-label">Business Name</label>
                    <input type="text"
                           name="business_name"
                           value="{{ old('business_name', $client->business_name) }}"
                           class="form-control">
                </div>


                <div class="col-md-6">
                    <label class="form-label">Business Location</label>
                    <input type="text"
                           name="business_location"
                           value="{{ old('business_location', $client->business_location) }}"
                           class="form-control">
                </div>


                <div class="col-md-4">
                    <label class="form-label">Business Capital</label>
                    <input type="number"
                           step="0.01"
                           name="business_capital"
                           value="{{ old('business_capital', $client->business_capital) }}"
                           class="form-control">
                </div>


                <div class="col-md-4">
                    <label class="form-label">Monthly Income</label>
                    <input type="number"
                           step="0.01"
                           name="business_income"
                           value="{{ old('business_income', $client->business_income) }}"
                           class="form-control">
                </div>


                <div class="col-md-4">
                    <label class="form-label">Industry Sector</label>
                    <input type="text"
                           name="industry_sector"
                           value="{{ old('industry_sector', $client->industry_sector) }}"
                           class="form-control">
                </div>


                <div class="col-md-4">
                    <label class="form-label">Years in Business</label>
                    <input type="number"
                           name="years_in_business"
                           value="{{ old('years_in_business', $client->years_in_business) }}"
                           class="form-control">
                </div>


                <div class="col-md-4">
                    <label class="form-label">Months in Business</label>
                    <input type="number"
                           name="months_in_business"
                           value="{{ old('months_in_business', $client->months_in_business) }}"
                           class="form-control">
                </div>


                <div class="col-md-4">
                    <label class="form-label">Number of Employees</label>
                    <input type="number"
                           name="number_of_employees"
                           value="{{ old('number_of_employees', $client->number_of_employees) }}"
                           class="form-control">
                </div>


                <div class="col-md-6">
                    <label class="form-label">Partner in Business</label>
                    <input type="text"
                           name="partner_in_business"
                           value="{{ old('partner_in_business', $client->partner_in_business) }}"
                           class="form-control">
                </div>


                <div class="col-md-6">
                    <label class="form-label">Business Registration Number</label>
                    <input type="text"
                           name="business_registration_number"
                           value="{{ old('business_registration_number', $client->business_registration_number) }}"
                           class="form-control">
                </div>


                <div class="col-md-6">
                    <label class="form-label">Tax Identification Number (TIN)</label>
                    <input type="text"
                           name="tax_identification_number"
                           value="{{ old('tax_identification_number', $client->tax_identification_number) }}"
                           class="form-control">
                </div>




                {{-- =============================== --}}
                {{-- SECTION: CREDIT INFORMATION     --}}
                {{-- =============================== --}}

                <div class="col-12 mt-4">
                    <h5 class="arbif-section-title">
                        <i class="fas fa-chart-line me-2"></i>
                        Credit Information
                    </h5>
                </div>


                <div class="col-md-4">
                    <label class="form-label">Credit Score</label>
                    <input type="number"
                           step="0.01"
                           name="credit_score"
                           value="{{ old('credit_score', $client->credit_score) }}"
                           class="form-control @error('credit_score') is-invalid @enderror">
                    @error('credit_score')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-4">
                    <label class="form-label">Credit Rating</label>
                    <input type="text"
                           name="credit_rating"
                           value="{{ old('credit_rating', $client->credit_rating) }}"
                           class="form-control @error('credit_rating') is-invalid @enderror">
                    @error('credit_rating')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-4">
                    <label class="form-label">Risk Category</label>
                    <input type="text"
                           name="risk_category"
                           value="{{ old('risk_category', $client->risk_category) }}"
                           class="form-control @error('risk_category') is-invalid @enderror">
                    @error('risk_category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>




                {{-- =============================== --}}
                {{-- SECTION: IMAGES                 --}}
                {{-- =============================== --}}

                <div class="col-12 mt-4">
                    <h5 class="arbif-section-title">
                        <i class="fas fa-image me-2"></i>
                        Images
                    </h5>
                </div>


                {{-- PROFILE PICTURE --}}
                <div class="col-md-6">

                    <label class="form-label">Profile Picture</label>

                    @if($client->profile_picture)
                        <div class="mb-2 d-flex align-items-center gap-3">
                            <img src="{{ asset('storage/' . $client->profile_picture) }}"
                                 alt="Current Profile Picture"
                                 class="current-img-preview">
                            <span class="text-muted small">Current photo</span>
                        </div>
                    @endif

                    <input type="file"
                           name="profile_picture"
                           accept="image/jpg,image/jpeg,image/png"
                           class="form-control @error('profile_picture') is-invalid @enderror">

                    <small class="text-muted">Leave blank to keep current image. Max 2MB (jpg, jpeg, png)</small>

                    @error('profile_picture')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>


                {{-- SIGNATURE IMAGE --}}
                <div class="col-md-6">

                    <label class="form-label">Signature Image</label>

                    @if($client->sign_image)
                        <div class="mb-2 d-flex align-items-center gap-3">
                            <img src="{{ asset('storage/' . $client->sign_image) }}"
                                 alt="Current Signature"
                                 class="current-sign-preview">
                            <span class="text-muted small">Current signature</span>
                        </div>
                    @endif

                    <input type="file"
                           name="sign_image"
                           accept="image/jpg,image/jpeg,image/png"
                           class="form-control @error('sign_image') is-invalid @enderror">

                    <small class="text-muted">Leave blank to keep current image. Max 2MB (jpg, jpeg, png)</small>

                    @error('sign_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>


            </div>{{-- end row --}}




            {{-- FORM FOOTER --}}
            <div class="d-flex justify-content-end gap-2 mt-4 pt-3"
                 style="border-top: 1px solid rgba(0,0,0,0.08);">

                <a href="{{ route('clientinformations') }}"
                   class="arbif-btn-cancel">
                    <i class="bi bi-x"></i>
                    Cancel
                </a>

                <button type="submit"
                        class="arbif-btn-submit">
                    <i class="bi bi-check-circle"></i>
                    Update Client
                </button>

            </div>

        </form>

    </div>

</div>




{{-- STYLES --}}
<style>

    .current-img-preview {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid var(--arbif-primary, #1a3c6e);
    }

    .current-sign-preview {
        max-width: 140px;
        max-height: 60px;
        object-fit: contain;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 4px;
        background: #fff;
    }

</style>




{{-- GROUP CENTER → GROUP FILTER SCRIPT --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const centerSelect  = document.getElementById('groupCenterSelect');
    const groupSelect   = document.getElementById('groupSelect');
    const allOptions    = Array.from(groupSelect.options);

    function filterGroups(centerId) {
        const currentGroup = groupSelect.value;
        groupSelect.innerHTML = '<option value="">Select Group</option>';
        allOptions.forEach(function (opt) {
            if (!opt.value) return;
            if (!centerId || opt.dataset.center === String(centerId)) {
                groupSelect.appendChild(opt.cloneNode(true));
            }
        });
        groupSelect.value = currentGroup;
    }

    // Run on page load with pre-selected center
    filterGroups(centerSelect.value);

    centerSelect.addEventListener('change', function () {
        filterGroups(this.value);
    });

});
</script>
@endpush

@endsection