@extends('member.layouts.app')
@section('title', 'My Shares')

@section('content')
<div class="container-fluid py-4 px-4">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">
                <i class="fas fa-chart-pie text-primary me-2"></i>My Shares
            </h4>
            <p class="text-muted mb-0 small">
                <span class="fw-semibold text-body">{{ $member->member_code }}</span>
                <span class="mx-1">•</span>
                {{ $member->memberUser->name ?? $member->member_name }}
            </p>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    {{-- MEMBER INFORMATION CARD --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary-subtle text-primary rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h6 class="card-title fw-bold mb-0 text-dark">Member Profile</h6>
            </div>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.75rem;">Member Number</small>
                    <span class="fw-bold text-dark fs-6">{{ $member->member_code }}</span>
                </div>
                <div class="col-6 col-md-3">
                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.75rem;">Member Name</small>
                    <span class="fw-bold text-dark fs-6">{{ $member->memberUser->name ?? $member->member_name }}</span>
                </div>
                <div class="col-6 col-md-3">
                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.75rem;">Phone Number</small>
                    <span class="fw-bold text-dark fs-6">{{ $member->memberUser->phone ?? $member->memberUser->Phone ?? 'N/A' }}</span>
                </div>
                <div class="col-6 col-md-3">
                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.75rem;">Account Status</small>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 mt-1">
                        <i class="fas fa-circle fs-6 me-1 align-middle" style="font-size: 6px !important;"></i>
                        {{ $member->Status ?? 'Active' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- SHARE SUMMARY KPIS --}}
    <div class="row g-3 mb-4">
        {{-- Current Shares --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Current Shares</span>
                        <h4 class="fw-bold mb-0 text-dark">{{ number_format($currentShares, 4) }}</h4>
                    </div>
                    <div class="bg-primary-subtle text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="fas fa-chart-pie fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Investment --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Total Investment</span>
                        <h4 class="fw-bold mb-0 text-dark">TSh {{ number_format($totalShareInvestment, 2) }}</h4>
                    </div>
                    <div class="bg-success-subtle text-success rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="fas fa-wallet fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Purchases --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Share Purchases</span>
                        <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalPurchases) }}</h4>
                    </div>
                    <div class="bg-info-subtle text-info rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="fas fa-shopping-cart fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dividends --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Total Dividends</span>
                        <h4 class="fw-bold mb-0 text-dark">TSh {{ number_format($totalDividend, 2) }}</h4>
                    </div>
                    <div class="bg-warning-subtle text-warning rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="fas fa-coins fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SHARE HOLDING DETAILS --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="fas fa-chart-line text-primary me-2"></i>Share Holding Details
            </h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-3 text-center">
                <div class="col-md-3 border-end border-light-subtle">
                    <span class="text-muted small d-block mb-1">Purchased Shares</span>
                    <h5 class="fw-bold text-dark mb-0">{{ number_format($totalSharesPurchased, 4) }}</h5>
                </div>
                <div class="col-md-3 border-end border-light-subtle">
                    <span class="text-muted small d-block mb-1">Shares Received</span>
                    <h5 class="fw-bold text-success mb-0">+{{ number_format($sharesTransferredIn, 4) }}</h5>
                </div>
                <div class="col-md-3 border-end border-light-subtle">
                    <span class="text-muted small d-block mb-1">Shares Transferred Out</span>
                    <h5 class="fw-bold text-danger mb-0">-{{ number_format($sharesTransferredOut, 4) }}</h5>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small d-block mb-1">Current Balance</span>
                    <h5 class="fw-bold text-primary mb-0">{{ number_format($currentShares, 4) }}</h5>
                </div>
            </div>
        </div>
    </div>

    {{-- SHARE PURCHASE HISTORY --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="fas fa-history text-success me-2"></i>Share Purchase History
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Date</th>
                        <th>Reference</th>
                        <th>Share Type</th>
                        <th class="text-end">Share Price</th>
                        <th class="text-end">Shares</th>
                        <th class="text-end">Amount</th>
                        <th>Status</th>
                        <th class="pe-4">Certificate</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sharePurchases as $purchase)
                        <tr>
                            <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                            <td class="text-nowrap">{{ optional($purchase->TransactionDate)->format('d/m/Y') ?? '-' }}</td>
                            <td class="fw-bold text-dark">{{ $purchase->TransactionRefNo ?? '-' }}</td>
                            <td>{{ $purchase->shareType->TypeName ?? '-' }}</td>
                            <td class="text-end fw-semibold text-dark">TSh {{ number_format($purchase->PricePerShare, 2) }}</td>
                            <td class="text-end fw-semibold text-dark">{{ number_format($purchase->SharesQuantity, 4) }}</td>
                            <td class="text-end fw-bold text-dark">TSh {{ number_format($purchase->TotalAmount, 2) }}</td>
                            <td>
                                @if($purchase->transaction_status === 'Completed')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Completed</span>
                                @elseif($purchase->transaction_status === 'Posted')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3">Posted</span>
                                @elseif($purchase->transaction_status === 'Rejected')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">Rejected</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3">{{ $purchase->transaction_status }}</span>
                                @endif
                            </td>
                            <td class="pe-4">
                                @if($purchase->certificate)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                                        <i class="fas fa-certificate me-1"></i>{{ $purchase->certificate->certificate_no ?? 'Generated' }}
                                    </span>
                                @else
                                    <span class="badge bg-light text-secondary border rounded-pill px-2 py-1">Not Generated</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block text-secondary opacity-50"></i>
                                No completed share purchases found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SHARE CERTIFICATES --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="fas fa-certificate text-warning me-2"></i>My Share Certificates
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Certificate No.</th>
                        <th>Issue Date</th>
                        <th>Share Type</th>
                        <th class="text-end">Shares</th>
                        <th class="pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificates as $certificate)
                        <tr>
                            <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                            <td class="fw-bold text-dark">{{ $certificate->certificate_no ?? '-' }}</td>
                            <td>{{ optional($certificate->certificate_date)->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ $certificate->shareType->name ?? '-' }}</td>
                            <td class="text-end fw-semibold text-dark">{{ number_format($certificate->shares ?? 0, 4) }}</td>
                            <td class="pe-4">
                                @if(($certificate->Status ?? '') === 'Active')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Active</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-3">{{ $certificate->Status ?? '-' }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-file-alt fa-2x mb-2 d-block text-secondary opacity-50"></i>
                                No share certificates found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SHARE TRANSFERS --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="fas fa-exchange-alt text-info me-2"></i>Share Transfers
            </h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="p-3 border border-success-subtle bg-success-subtle rounded-3 d-flex align-items-center justify-content-between">
                        <span class="fw-semibold text-success"><i class="fas fa-arrow-down me-2"></i>Shares Received</span>
                        <h5 class="fw-bold text-success mb-0">{{ number_format($sharesTransferredIn, 4) }}</h5>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border border-danger-subtle bg-danger-subtle rounded-3 d-flex align-items-center justify-content-between">
                        <span class="fw-semibold text-danger"><i class="fas fa-arrow-up me-2"></i>Shares Transferred Out</span>
                        <h5 class="fw-bold text-danger mb-0">{{ number_format($sharesTransferredOut, 4) }}</h5>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th class="text-end">Shares</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shareTransfers as $transfer)
                            <tr>
                                <td class="ps-3 text-muted small">{{ $loop->iteration }}</td>
                                <td>{{ optional($transfer->transfer_date)->format('d/m/Y') ?? '-' }}</td>
                                <td>
                                    @if($transfer->from_member_id == $member->id)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">Transfer Out</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Transfer In</span>
                                    @endif
                                </td>
                                <td class="text-end fw-semibold text-dark">{{ number_format($transfer->shares_transferred ?? 0, 4) }}</td>
                                <td><span class="badge bg-light text-dark border rounded-pill px-3">{{ $transfer->Status ?? '-' }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-exchange-alt fa-2x mb-2 d-block text-secondary opacity-50"></i>
                                    No share transfers found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- DIVIDENDS --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="fas fa-coins text-warning me-2"></i>Share Dividends
            </h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="p-3 border rounded-3 bg-light">
                        <small class="text-muted d-block text-uppercase fw-semibold mb-1">Total Dividend</small>
                        <h5 class="fw-bold text-dark mb-0">TSh {{ number_format($totalDividend, 2) }}</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border border-success-subtle bg-success-subtle rounded-3">
                        <small class="text-success d-block text-uppercase fw-semibold mb-1">Paid Dividend</small>
                        <h5 class="fw-bold text-success mb-0">TSh {{ number_format($paidDividend, 2) }}</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border border-warning-subtle bg-warning-subtle rounded-3">
                        <small class="text-warning-emphasis d-block text-uppercase fw-semibold mb-1">Unpaid Dividend</small>
                        <h5 class="fw-bold text-warning-emphasis mb-0">TSh {{ number_format($unpaidDividend, 2) }}</h5>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Declaration Ref</th>
                            <th class="text-end">Shares</th>
                            <th class="text-end">Dividend Amount</th>
                            <th class="pe-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dividendAllocations as $allocation)
                            <tr>
                                <td class="ps-3 text-muted small">{{ $loop->iteration }}</td>
                                <td class="fw-bold text-dark">{{ $allocation->declaration->reference_no ?? '-' }}</td>
                                <td class="text-end fw-semibold text-dark">{{ number_format($allocation->shares ?? 0, 4) }}</td>
                                <td class="text-end fw-bold text-dark">TSh {{ number_format($allocation->dividend_amount ?? 0, 2) }}</td>
                                <td class="pe-3">
                                    @if(($allocation->payment_status ?? '') === 'Paid')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Paid</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3">{{ $allocation->payment_status ?? 'Pending' }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-coins fa-2x mb-2 d-block text-secondary opacity-50"></i>
                                    No dividend allocations found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection