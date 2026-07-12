@extends('in.member.layouts.app')
@section('title', 'Profile')

@section('content')

    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="bg-ifl-navy text-white rounded-circle d-flex align-items-center justify-content-center fw-semibold flex-shrink-0"
             style="width:56px;height:56px;font-size:1.3rem;">
            {{ strtoupper(substr($user->FirstName ?? 'M', 0, 1)) }}
        </div>
        <div>
            <h1 class="fs-5 fw-semibold text-dark mb-0">
                {{ trim(($user->FirstName ?? '') . ' ' . ($user->MiddleName ?? '') . ' ' . ($user->LastName ?? '')) }}
            </h1>
            <p class="text-muted small mb-0">{{ $user->username ?? '' }}</p>
        </div>
    </div>

    @if(!$member)
        <div class="alert alert-warning d-flex align-items-start gap-2 mb-4">
            <i class="bi bi-exclamation-triangle mt-1"></i>
            <div>
                <strong>No membership record found.</strong>
                <p class="mb-0 small">Some membership details below won't be available. Please contact the office.</p>
            </div>
        </div>
    @endif

    {{-- Membership details — read only --}}
    <div class="card mb-3">
        <div class="card-body">
            <h2 class="fs-6 fw-semibold text-ifl-navy mb-3">
                <i class="bi bi-award me-1"></i> Membership Details
            </h2>
            <div class="row g-3">
                <div class="col-6 col-lg-3">
                    <p class="text-muted mb-1 field-label">Member Code</p>
                    <p class="fw-semibold text-dark mb-0 small">{{ $member->member_code ?? '—' }}</p>
                </div>
                <div class="col-6 col-lg-3">
                    <p class="text-muted mb-1 field-label">Category</p>
                    <p class="fw-semibold text-dark mb-0 small">{{ $member->memberCategory->member_category_name ?? '—' }}</p>
                </div>
                <div class="col-6 col-lg-3">
                    <p class="text-muted mb-1 field-label">Branch</p>
                    <p class="fw-semibold text-dark mb-0 small">{{ $member->branch->branch_name ?? '—' }}</p>
                </div>
                <div class="col-6 col-lg-3">
                    <p class="text-muted mb-1 field-label">Company</p>
                    <p class="fw-semibold text-dark mb-0 small">{{ $member->company->name ?? '—' }}</p>
                </div>
                <div class="col-6 col-lg-3">
                    <p class="text-muted mb-1 field-label">Admission Date</p>
                    <p class="fw-semibold text-dark mb-0 small">
                        {{ $member && $member->admission_date ? \Carbon\Carbon::parse($member->admission_date)->format('d M Y') : '—' }}
                    </p>
                </div>
                <div class="col-6 col-lg-3">
                    <p class="text-muted mb-1 field-label">NIDA</p>
                    <p class="fw-semibold text-dark mb-0 small">{{ $member->nida ?? '—' }}</p>
                </div>
                <div class="col-6 col-lg-3">
                    <p class="text-muted mb-1 field-label">TIN</p>
                    <p class="fw-semibold text-dark mb-0 small">{{ $member->tin ?? '—' }}</p>
                </div>
                <div class="col-6 col-lg-3">
                    <p class="text-muted mb-1 field-label">Work Permit</p>
                    <p class="fw-semibold text-dark mb-0 small">{{ $member->work_permit ?? '—' }}</p>
                </div>
            </div>
            <p class="text-muted mt-3 mb-0" style="font-size:.72rem;">
                <i class="bi bi-info-circle me-1"></i> These details are managed by the office. Contact support if any of this looks incorrect.
            </p>
        </div>
    </div>

    {{-- Editable contact details --}}
    <div class="card">
        <div class="card-body">
            <h2 class="fs-6 fw-semibold text-ifl-navy mb-3">
                <i class="bi bi-pencil-square me-1"></i> Contact Details
            </h2>

            <form method="POST" action="{{ route('member.profile.update') }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="email" class="form-label small fw-semibold text-muted text-uppercase field-label">Email address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                               class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="phone" class="form-label small fw-semibold text-muted text-uppercase field-label">Phone number</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                               class="form-control @error('phone') is-invalid @enderror" required>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-ifl-navy mt-3 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-check2"></i> Save changes
                </button>
            </form>
        </div>
    </div>

@endsection

@push('styles')
<style>
    .field-label { letter-spacing: .05em; font-size: .7rem; text-transform: uppercase; }
</style>
@endpush