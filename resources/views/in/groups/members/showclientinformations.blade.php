@extends('layouts.workingside')
@section('title', 'Client Details')
@section('page-title', 'Clients Management')

@section('content')

{{-- PAGE HEADER --}}
<div class="arbif-page-header">

    <h3>
        <div class="page-icon">
            <i class="fas fa-user"></i>
        </div>
        Client Profile
    </h3>

    <div class="d-flex gap-2">

        <a href="{{ route('clientinformations') }}"
           class="arbif-btn-cancel">
            <i class="fas fa-arrow-left"></i>
            Back to Clients
        </a>

        <a href="{{ route('editclientinformations', encrypt($client->id)) }}"
           class="arbif-btn-submit">
            <i class="fas fa-pencil"></i>
            Edit Client
        </a>

    </div>

</div>




{{-- TOP PROFILE CARD --}}
<div class="arbif-card mb-4">

    <div class="arbif-card-body">

        <div class="d-flex align-items-center gap-4 flex-wrap">

            {{-- PROFILE PICTURE --}}
            <div class="client-avatar-wrap">

                @if($client->profile_picture)
                    <img src="{{ asset('storage/' . $client->profile_picture) }}"
                         alt="Profile Picture"
                         class="client-avatar-img">
                @else
                    <div class="client-avatar-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                @endif

            </div>


            {{-- BASIC INFO --}}
            <div class="flex-grow-1">

                <h4 class="mb-1 fw-bold">
                    {{ optional($client->client)->name ?? '—' }}
                </h4>

                <span class="arbif-badge arbif-badge-navy me-2">
                    {{ $client->client_code ?? 'N/A' }}
                </span>

                <span class="arbif-badge {{ $client->Status === 'Active' ? 'arbif-badge-success' : 'arbif-badge-danger' }}">
                    {{ $client->Status ?? '—' }}
                </span>

                <div class="mt-2 text-muted small">
                    <i class="fas fa-envelope me-1"></i> {{ optional($client->client)->email ?? '—' }}
                    &nbsp;&nbsp;
                    <i class="fas fa-phone me-1"></i> {{ optional($client->client)->phone ?? '—' }}
                    &nbsp;&nbsp;
                    <i class="fas fa-briefcase me-1"></i> {{ $client->client_type ?? '—' }}
                </div>

            </div>


            {{-- SIGNATURE IMAGE --}}
            @if($client->sign_image)
            <div class="text-center">
                <p class="text-muted small mb-1">Signature</p>
                <img src="{{ asset('storage/' . $client->sign_image) }}"
                     alt="Signature"
                     class="client-sign-img">
            </div>
            @endif

        </div>

    </div>

</div>




{{-- SECTION GRID --}}
<div class="row g-4">


    {{-- PERSONAL INFORMATION --}}
    <div class="col-lg-6">

        <div class="arbif-card h-100">

            <div class="arbif-card-header">
                <i class="fas fa-id-card me-2"></i>
                Personal Information
            </div>

            <div class="arbif-card-body">

                <table class="arbif-detail-table">

                    <tr>
                        <td class="arbif-detail-label">First Name</td>
                        <td>{{ optional($client->client)->FirstName ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Middle Name</td>
                        <td>{{ optional($client->client)->MiddleName ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Last Name</td>
                        <td>{{ optional($client->client)->LastName ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Date of Birth</td>
                        <td>{{ optional($client->client)->Dob ? \Carbon\Carbon::parse($client->client->Dob)->format('d M Y') : '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Gender</td>
                        <td>{{ optional($client->client)->gender ? ucfirst($client->client->gender) : '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Marital Status</td>
                        <td>{{ $client->marital_status ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Spouse Name</td>
                        <td>{{ $client->spouse_name ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">National ID</td>
                        <td>{{ $client->national_id ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Other Name</td>
                        <td>{{ $client->other_name ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Street Leader</td>
                        <td>{{ $client->street_leader ?? '—' }}</td>
                    </tr>

                </table>

            </div>

        </div>

    </div>




    {{-- CONTACT & ADDRESS --}}
    <div class="col-lg-6">

        <div class="arbif-card h-100">

            <div class="arbif-card-header">
                <i class="fas fa-map-marker-alt me-2"></i>
                Contact & Address
            </div>

            <div class="arbif-card-body">

                <table class="arbif-detail-table">

                    <tr>
                        <td class="arbif-detail-label">Email</td>
                        <td>{{ optional($client->client)->email ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Phone</td>
                        <td>{{ optional($client->client)->phone ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Alternative Phone</td>
                        <td>{{ $client->alternative_phone ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Address Line 1</td>
                        <td>{{ $client->address_line1 ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Address Line 2</td>
                        <td>{{ $client->address_line2 ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">City</td>
                        <td>{{ $client->city ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">State / Province</td>
                        <td>{{ $client->state_province ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Postal Code</td>
                        <td>{{ $client->postal_code ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Country</td>
                        <td>{{ optional($client->country)->country_name ?? $client->country_id ?? '—' }}</td>
                    </tr>

                </table>

            </div>

        </div>

    </div>




    {{-- GROUP & OFFICER INFORMATION --}}
    <div class="col-lg-6">

        <div class="arbif-card h-100">

            <div class="arbif-card-header">
                <i class="fas fa-users me-2"></i>
                Group & Officer Information
            </div>

            <div class="arbif-card-body">

                <table class="arbif-detail-table">

                    <tr>
                        <td class="arbif-detail-label">Group Center</td>
                        <td>{{ optional($client->groupCenter)->center_name ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Group</td>
                        <td>{{ optional($client->group)->group_name ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Credit Officer</td>
                        <td>
                            @if($client->loanOfficer)
                                {{ $client->loanOfficer->FirstName ?? '' }}
                                {{ $client->loanOfficer->MiddleName ?? '' }}
                                {{ $client->loanOfficer->LastName ?? '' }}
                                — {{ $client->loanOfficer->EmployeeID ?? '' }}
                            @else
                                —
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Client Type</td>
                        <td>{{ $client->client_type ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Client Code</td>
                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $client->client_code ?? '—' }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Status</td>
                        <td>
                            <span class="arbif-badge {{ $client->status === 'Active' ? 'arbif-badge-success' : 'arbif-badge-danger' }}">
                                {{ $client->status ?? '—' }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Blacklist Reason</td>
                        <td>{{ $client->blacklist_reason ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">KYC Completed</td>
                        <td>
                            @if($client->kyc_completed)
                                <span class="arbif-badge arbif-badge-success">
                                    <i class="fas fa-check me-1"></i> Yes
                                </span>
                                <span class="text-muted small ms-2">
                                    {{ $client->kyc_completed_at ? \Carbon\Carbon::parse($client->kyc_completed_at)->format('d M Y') : '' }}
                                </span>
                            @else
                                <span class="arbif-badge arbif-badge-danger">
                                    <i class="fas fa-times me-1"></i> No
                                </span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Registered On</td>
                        <td>{{ $client->created_at ? $client->created_at->format('d M Y') : '—' }}</td>
                    </tr>

                </table>

            </div>

        </div>

    </div>




    {{-- BUSINESS INFORMATION --}}
    <div class="col-lg-6">

        <div class="arbif-card h-100">

            <div class="arbif-card-header">
                <i class="fas fa-briefcase me-2"></i>
                Business Information
            </div>

            <div class="arbif-card-body">

                <table class="arbif-detail-table">

                    <tr>
                        <td class="arbif-detail-label">Business Name</td>
                        <td>{{ $client->business_name ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Business Location</td>
                        <td>{{ $client->business_location ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Business Capital</td>
                        <td>{{ $client->business_capital ? number_format($client->business_capital, 2) : '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Monthly Income</td>
                        <td>{{ $client->business_income ? number_format($client->business_income, 2) : '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Industry Sector</td>
                        <td>{{ $client->industry_sector ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Years in Business</td>
                        <td>{{ $client->years_in_business ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Months in Business</td>
                        <td>{{ $client->months_in_business ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">No. of Employees</td>
                        <td>{{ $client->number_of_employees ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Partner in Business</td>
                        <td>{{ $client->partner_in_business ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Registration No.</td>
                        <td>{{ $client->business_registration_number ?? '—' }}</td>
                    </tr>

                    <tr>
                        <td class="arbif-detail-label">Tax ID (TIN)</td>
                        <td>{{ $client->tax_identification_number ?? '—' }}</td>
                    </tr>

                </table>

            </div>

        </div>

    </div>




    {{-- CREDIT INFORMATION --}}
    <div class="col-12">

        <div class="arbif-card">

            <div class="arbif-card-header">
                <i class="fas fa-chart-line me-2"></i>
                Credit Information
            </div>

            <div class="arbif-card-body">

                <div class="row g-4">

                    <div class="col-md-4">
                        <div class="arbif-stat-box">
                            <div class="arbif-stat-label">Credit Score</div>
                            <div class="arbif-stat-value">
                                {{ $client->credit_score ?? '—' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="arbif-stat-box">
                            <div class="arbif-stat-label">Credit Rating</div>
                            <div class="arbif-stat-value">
                                {{ $client->credit_rating ?? '—' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="arbif-stat-box">
                            <div class="arbif-stat-label">Risk Category</div>
                            <div class="arbif-stat-value">
                                {{ $client->risk_category ?? '—' }}
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>


</div>{{-- end row --}}




{{-- BOTTOM EDIT BUTTON --}}
<div class="d-flex justify-content-end gap-2 mt-4">

    <a href="{{ route('clientinformations') }}"
       class="arbif-btn-cancel">
        <i class="fas fa-arrow-left"></i>
        Back to Clients
    </a>

    <a href="{{ route('editclientinformations', encrypt($client->id)) }}"
       class="arbif-btn-submit">
        <i class="fas fa-pencil"></i>
        Edit Client
    </a>

</div>




{{-- STYLES --}}
<style>

    .client-avatar-wrap {
        flex-shrink: 0;
    }

    .client-avatar-img {
        width: 110px;
        height: 110px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid var(--arbif-primary, #1a3c6e);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .client-avatar-placeholder {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: var(--arbif-primary, #1a3c6e);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .client-sign-img {
        max-width: 160px;
        max-height: 70px;
        object-fit: contain;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 4px;
        background: #fff;
    }

    .arbif-card-header {
        padding: 0.85rem 1.25rem;
        font-weight: 600;
        font-size: 0.95rem;
        border-bottom: 1px solid rgba(0,0,0,0.08);
        background: rgba(0,0,0,0.02);
        border-radius: 8px 8px 0 0;
    }

    /* Detail table inside cards */
    .arbif-detail-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }

    .arbif-detail-table tr {
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .arbif-detail-table tr:last-child {
        border-bottom: none;
    }

    .arbif-detail-table td {
        padding: 0.6rem 0.5rem;
        vertical-align: top;
    }

    .arbif-detail-label {
        color: #6c757d;
        font-weight: 500;
        width: 45%;
        white-space: nowrap;
    }

    /* Stat boxes for credit info */
    .arbif-stat-box {
        background: rgba(0,0,0,0.03);
        border-radius: 10px;
        padding: 1.2rem 1.5rem;
        text-align: center;
        border: 1px solid rgba(0,0,0,0.06);
    }

    .arbif-stat-label {
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #6c757d;
        margin-bottom: 0.4rem;
    }

    .arbif-stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--arbif-primary, #1a3c6e);
    }

    .arbif-badge-success {
        background-color: #d1fae5;
        color: #065f46;
    }

    .arbif-badge-danger {
        background-color: #fee2e2;
        color: #991b1b;
    }

</style>

@endsection