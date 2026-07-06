@extends('layouts.app')

@section('title', 'Client Details')
@section('page-title', 'Client Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
    <li class="breadcrumb-item active">{{ $client->first_name }} {{ $client->last_name }}</li>
@endsection

@section('content')
<style>
    .client-profile-card {
        transition: transform 0.2s ease-in-out;
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .client-profile-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .card-header-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    
    .profile-image-container {
        position: relative;
        display: inline-block;
    }
    
    .profile-image {
        width: 120px;
        height: 120px;
        border: 4px solid #fff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .profile-image:hover {
        transform: scale(1.05);
    }
    
    .status-badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    
    .info-item {
        padding: 0.5rem 0;
        border-bottom: 1px solid #f1f5f9;
        transition: background-color 0.2s ease;
    }
    
    .info-item:hover {
        background-color: #f8fafc;
        border-radius: 6px;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #475569;
        font-size: 0.875rem;
    }
    
    .info-value {
        color: #1e293b;
        font-weight: 500;
    }
    
    .section-title {
        color: #334155;
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .action-btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .action-btn:hover {
        transform: translateY(-1px);
    }
    
    @media (max-width: 768px) {
        .profile-image {
            width: 100px;
            height: 100px;
        }
        
        .btn-group .btn {
            margin-bottom: 0.5rem;
        }
    }
    
    @media print {
        .no-print {
            display: none !important;
        }
        .client-profile-card {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }
        .card-header-gradient {
            background: #f8f9fa !important;
            color: #000 !important;
        }
        .action-btn {
            display: none !important;
        }
    }

    .guarantor-card {
        border-left: 4px solid #10b981;
    }
    
    .media-card {
        border-left: 4px solid #3b82f6;
    }
    
    .kyc-card {
        border-left: 4px solid #f59e0b;
    }
</style>

<div class="container-fluid py-3">

    <!-- Header Section -->
    <div class="row mb-4 no-print">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div class="mb-3 mb-md-0">
                    <h2 class="h4 text-gray-800 mb-1">
                        <i class="bi bi-person-circle me-2 text-primary"></i>
                        Client Profile
                    </h2>
                    <p class="text-muted mb-0">
                        {{ $client->first_name }} {{ $client->last_name }}
                        <span class="badge bg-light text-dark">Assigned Loan officer: {{ $client->assignedLoanOfficer->username ?? 'N/A' }}</span>
                    </p>
                </div>
                
                <div class="btn-group flex-wrap" role="group">
                    <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary action-btn no-print">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning action-btn no-print">
                        <i class="bi bi-pencil-square me-1"></i> Edit
                    </a>
                    <button onclick="printClientData()" class="btn btn-info action-btn no-print">
                        <i class="bi bi-printer me-1"></i> Print
                    </button>
                    <button onclick="exportClientData({{ $client->id }})" class="btn btn-success action-btn no-print">
                        <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export
                    </button>
                </div>
            </div>
        </div>
    </div>

        <div class="text-start mb-4">
            <button type="submit" class="btn btn-primary btn-lg">Apply for Loan</button>
        </div>

    <!-- Flash Messages -->
    <div class="row g-4">
        
        <!-- Column 1: Identity & Contact -->
        <div class="col-xl-4 col-lg-6">
            <div class="card client-profile-card h-100">
                <div class="card-header card-header-gradient text-white py-3">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="bi bi-person-badge me-2"></i>
                        Identity & Contact
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="profile-image-container">
                            <img src="{{ $client->profile_picture ?? 'https://ui-avatars.com/api/?name=' . urlencode($client->first_name . ' ' . $client->last_name) . '&background=667eea&color=fff&size=200' }}" 
                                 alt="Profile Picture" 
                                 class="profile-image rounded-circle">
                        </div>
                        <h4 class="mt-3 mb-1">{{ $client->first_name }} {{ $client->last_name }}</h4>
                        <p class="text-muted">{{ $client->client_type ? ucfirst($client->client_type) : 'Client' }}</p>
                    </div>

                    <div class="section-title">Personal Information</div>
                    <div class="info-item">
                        <span class="info-label">Full Name:</span>
                        <div class="info-value">{{ $client->first_name }} {{ $client->middle_name ?? '' }} {{ $client->last_name }}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Other Name:</span>
                        <div class="info-value">{{ $client->other_name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">National ID:</span>
                        <div class="info-value">{{ $client->national_id ?? 'N/A' }}</div>
                    </div>

                    <div class="section-title mt-4">Contact Details</div>
                    <div class="info-item">
                        <span class="info-label d-flex align-items-center">
                            <i class="bi bi-envelope me-2 text-primary"></i>Email:
                        </span>
                        <div class="info-value">
                            <a href="mailto:{{ $client->email }}" class="text-decoration-none">{{ $client->email }}</a>
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="info-label d-flex align-items-center">
                            <i class="bi bi-phone me-2 text-primary"></i>Phone:
                        </span>
                        <div class="info-value">
                            <a href="tel:{{ $client->phone }}" class="text-decoration-none">{{ $client->phone }}</a>
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="info-label d-flex align-items-center">
                            <i class="bi bi-telephone me-2 text-primary"></i>Alt Phone:
                        </span>
                        <div class="info-value">{{ $client->alternative_phone ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Column 2: Address & Business -->
        <div class="col-xl-4 col-lg-6">
            <div class="card client-profile-card h-100">
                <div class="card-header bg-secondary text-white py-3">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="bi bi-geo-alt-fill me-2"></i>
                        Address & Business
                    </h5>
                </div>
                <div class="card-body">
                    @if($client->business_name)
                        <div class="section-title">Business Information</div>
                        <div class="info-item">
                            <span class="info-label">Business Name:</span>
                            <div class="info-value">{{ $client->business_name }}</div>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Registration No:</span>
                            <div class="info-value">{{ $client->business_registration_number ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <span class="info-label">TIN Number:</span>
                            <div class="info-value">{{ $client->tax_identification_number ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Industry:</span>
                            <div class="info-value">{{ $client->industry_sector ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Years in Business:</span>
                            <div class="info-value">{{ $client->years_in_business ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Employees:</span>
                            <div class="info-value">{{ $client->number_of_employees ?? 'N/A' }}</div>
                        </div>
                    @endif

                    <div class="section-title mt-4">Physical Address</div>
                    <div class="info-item">
                        <span class="info-label">Address Line 1:</span>
                        <div class="info-value">{{ $client->address_line1 }}</div>
                    </div>
                    @if($client->address_line2)
                    <div class="info-item">
                        <span class="info-label">Address Line 2:</span>
                        <div class="info-value">{{ $client->address_line2 }}</div>
                    </div>
                    @endif
                    <div class="info-item">
                        <span class="info-label">City/State:</span>
                        <div class="info-value">{{ $client->city }}, {{ $client->state_province ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Postal Code:</span>
                        <div class="info-value">{{ $client->postal_code ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Country:</span>
                        <div class="info-value">{{ $client->country ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Column 3: KYC & Risk Assessment -->
        <div class="col-xl-4 col-lg-6">
            <div class="card client-profile-card h-100 kyc-card">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="bi bi-file-check-fill me-2"></i>
                        KYC & Risk Assessment
                    </h5>
                </div>
                <div class="card-body">
                    <div class="section-title">Personal Details</div>
                 <div class="info-item">
                     <span class="info-label">Group Belongs:</span>
                     <div class="info-value">
                         <span class="badge bg-primary">
                             {{ $client->group->group_name ?? 'Not Assigned' }}
                         </span>
                     </div>
                 </div>
                    <div class="info-item">
                        <span class="info-label">Gender:</span>
                        <div class="info-value">{{ ucfirst($client->gender ?? 'N/A') }}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date of Birth:</span>
                        <div class="info-value">
                            {{ $client->date_of_birth ? \Carbon\Carbon::parse($client->date_of_birth)->format('M d, Y') : 'N/A' }}
                            @if($client->date_of_birth)
                                <small class="text-muted">({{ \Carbon\Carbon::parse($client->date_of_birth)->age }} years old)</small>
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Marital Status:</span>
                        <div class="info-value">{{ ucfirst($client->marital_status ?? 'N/A') }}</div>
                    </div>
                    @if($client->spouse_name)
                    <div class="info-item">
                        <span class="info-label">Spouse Name:</span>
                        <div class="info-value">{{ $client->spouse_name }}</div>
                    </div>
                    @endif
                    <div class="info-item">
                        <span class="info-label">Street Leader:</span>
                        <div class="info-value">
                            <span class="badge bg-{{ $client->is_street_leader ? 'success' : 'secondary' }}">
                                {{ $client->is_street_leader ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>

                    <div class="section-title mt-4">KYC Status</div>
                    <div class="info-item">
                        <span class="info-label">KYC Completed:</span>
                        <div class="info-value">
                            <span class="badge bg-{{ $client->kyc_completed ? 'success' : 'danger' }} status-badge">
                                {{ $client->kyc_completed ? 'Completed' : 'Pending' }}
                            </span>
                            @if($client->kyc_completed_at)
                                <small class="text-muted d-block mt-1">
                                    Completed {{ \Carbon\Carbon::parse($client->kyc_completed_at)->diffForHumans() }}
                                </small>
                            @endif
                        </div>
                    </div>

                    <div class="section-title mt-4">Risk & Administration</div>
                    <div class="info-item">
                        <span class="info-label">Status:</span>
                        <div class="info-value">
                            @php
                                $statusConfig = [
                                    'active' => ['class' => 'success', 'icon' => 'check-circle'],
                                    'inactive' => ['class' => 'secondary', 'icon' => 'pause-circle'],
                                    'blacklisted' => ['class' => 'danger', 'icon' => 'slash-circle'],
                                    'pending' => ['class' => 'warning', 'icon' => 'clock'],
                                ];
                                $status = $statusConfig[$client->status] ?? ['class' => 'info', 'icon' => 'question-circle'];
                            @endphp
                            <span class="badge bg-{{ $status['class'] }} status-badge">
                                <i class="bi bi-{{ $status['icon'] }} me-1"></i>
                                {{ ucfirst($client->status) }}
                            </span>
                        </div>
                    </div>
                    
                    @if($client->status == 'blacklisted' && $client->blacklist_reason)
                    <div class="info-item">
                        <span class="info-label text-danger">Blacklist Reason:</span>
                        <div class="info-value text-danger">{{ $client->blacklist_reason }}</div>
                    </div>
                    @endif
                    
                    <div class="info-item">
                        <span class="info-label">Credit Score:</span>
                        <div class="info-value">
                            @if($client->credit_score)
                                <span class="badge bg-{{ $client->credit_score >= 700 ? 'success' : ($client->credit_score >= 600 ? 'warning' : 'danger') }}">
                                    {{ $client->credit_score }}
                                </span>
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Credit Rating:</span>
                        <div class="info-value">{{ $client->credit_rating ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Risk Category:</span>
                        <div class="info-value">
                            @if($client->risk_category)
                                <span class="badge bg-{{ $client->risk_category == 'low' ? 'success' : ($client->risk_category == 'medium' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($client->risk_category) }}
                                </span>
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Loan Officer:</span>
                        <div class="info-value">
                            {{ $client->assignedLoanOfficer->first_name ?? 'Unassigned' }} {{ $client->assignedLoanOfficer->last_name ?? 'Unassigned' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Column 4: Client Media & Signature -->
        <div class="col-xl-6 col-lg-6">
            <div class="card client-profile-card h-100 media-card">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="bi bi-images me-2"></i>
                        Client Media & Documents
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 text-center mb-4">
                            <h6 class="fw-bold text-gray-700 mb-3">
                                <i class="bi bi-pen-fill me-2"></i>Client Signature
                            </h6>
                            @if($client->sign_image)
                                <img src="{{ $client->sign_image }}" 
                                     alt="Signature" 
                                     class="img-fluid border rounded shadow-sm"
                                     style="max-height: 120px;">
                                <p class="text-muted mt-2">Signature on file</p>
                            @else
                                <div class="text-muted py-4">
                                    <i class="bi bi-x-circle display-6 text-muted"></i>
                                    <p class="mt-2">No signature uploaded</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-6 text-center">
                            <h6 class="fw-bold text-gray-700 mb-3">
                                <i class="bi bi-folder-fill me-2"></i>Documents
                            </h6>
                            <div class="text-muted py-3">
                                <i class="bi bi-folder-symlink display-5 text-muted"></i>
                                <p class="mt-2">Document management</p>
                                <button class="btn btn-sm btn-outline-primary no-print">
                                    <i class="bi bi-upload me-1"></i>Upload Documents
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Column 5: Guarantor Information -->
        <div class="col-xl-6 col-lg-6">
            <div class="card client-profile-card h-100 guarantor-card">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="bi bi-shield-lock-fill me-2"></i>
                        Guarantor Information
                    </h5>
                </div>
                <div class="card-body">
                    @if($client->guarantor)
                        <div class="alert alert-success mb-4">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Guarantor assigned and verified
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Full Name:</span>
                                    <div class="info-value fw-bold">
                                        {{ $client->guarantor->first_name }} {{ $client->guarantor->last_name }}
                                    </div>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Relationship:</span>
                                    <div class="info-value">{{ $client->guarantor->relationship_to_client ?? 'N/A' }}</div>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">National ID:</span>
                                    <div class="info-value">{{ $client->guarantor->national_id ?? 'N/A' }}</div>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Status:</span>
                                    <div class="info-value">
                                        <span class="badge bg-{{ $client->guarantor->status == 'active' ? 'success' : 'secondary' }} status-badge">
                                            {{ ucfirst($client->guarantor->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Phone Number:</span>
                                    <div class="info-value">{{ $client->guarantor->phone_number }}</div>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Email:</span>
                                    <div class="info-value">{{ $client->guarantor->email ?? 'N/A' }}</div>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Occupation:</span>
                                    <div class="info-value">{{ $client->guarantor->occupation ?? 'N/A' }}</div>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Monthly Income:</span>
                                    <div class="info-value">
                                        {{ $client->guarantor->monthly_income ? 'TZS ' . number_format($client->guarantor->monthly_income, 2) : 'N/A' }}
                                    </div>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Verified:</span>
                                    <div class="info-value">
                                        <span class="badge bg-{{ $client->guarantor->verified ? 'primary' : 'danger' }} status-badge">
                                            {{ $client->guarantor->verified ? 'Verified' : 'Not Verified' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 no-print">
                            <a href="{{ route('guarantors.edit', $client->guarantor->id) }}" 
                               class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square me-1"></i>Edit Guarantor
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-person-x display-4 text-muted mb-3"></i>
                            <h6 class="text-muted">No Guarantor Assigned</h6>
                            <p class="text-muted mb-3">This client doesn't have a guarantor yet.</p>
                            <a href="{{ route('guarantors.create', ['client_id' => $client->id]) }}" 
                               class="btn btn-success no-print">
                                <i class="bi bi-person-plus me-1"></i>Add Guarantor
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printClientData() {
        window.print();
    }

    function exportClientData(clientId) {
        // Show loading state
        const exportBtn = event.target;
        const originalText = exportBtn.innerHTML;
        exportBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Exporting...';
        exportBtn.disabled = true;
        
        // Simulate API call - replace with actual export functionality
        setTimeout(() => {
            alert(`Export functionality for Client ID ${clientId} would be implemented here.`);
            exportBtn.innerHTML = originalText;
            exportBtn.disabled = false;
        }, 1000);
    }

    // Add some interactive features
    document.addEventListener('DOMContentLoaded', function() {
        // Add click effect to profile image
        const profileImage = document.querySelector('.profile-image');
        if (profileImage) {
            profileImage.addEventListener('click', function() {
                this.style.transform = this.style.transform === 'scale(1.1)' ? 'scale(1)' : 'scale(1.1)';
            });
        }
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    });
</script>
@endsection