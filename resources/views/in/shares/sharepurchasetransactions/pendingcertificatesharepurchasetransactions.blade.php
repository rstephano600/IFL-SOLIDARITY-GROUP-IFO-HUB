@extends('layouts.workingside')
@section('title', 'Pending Certificate Share Transactions')
@section('page-title', 'Pending Certificate Generation')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-certificate"></i></div>
        Pending Certificate Share Transactions
    </h3>
    <a href="{{ route('sharepurchasetransactions') }}" class="arbif-btn-submit text-decoration-none">
        <i class="fas fa-list me-1"></i> All Transactions
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="pendingCertificatesTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Transaction Ref</th>
                        <th class="sortable">Member</th>
                        <th class="sortable">Share Details</th>
                        <th class="sortable">Quantity</th>
                        <th class="sortable">Total Value</th>
                        <th class="sortable">Posted By</th>
                        <th class="sortable">Certificate Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $index => $tx)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $tx->TransactionRefNo ?? $tx->id }}</span></td>
                        <td>
                            <div class="fw-bold text-dark">{{ $tx->member->full_name ?? $tx->member->member_name ?? '—' }}</div>
                            <small class="text-muted">{{ $tx->member->member_code ?? '—' }}</small>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $tx->shareType->TypeName ?? '—' }}</div>
                            <small class="text-muted">{{ $tx->shareOffering->offering_name ?? '—' }}</small>
                        </td>
                        <td><span class="fw-bold text-dark">{{ number_format($tx->SharesQuantity ?? $tx->quantity ?? 0) }}</span></td>
                        <td><strong class="text-navy">{{ number_format($tx->TotalAmount ?? $tx->amount ?? 0, 2) }}</strong></td>
                        <td>
                            <div class="fw-bold text-dark">{{ $tx->poster->name ?? '—' }}</div>
                            <small class="text-muted">{{ $tx->posted_at ? \Carbon\Carbon::parse($tx->posted_at)->format('d M, Y H:i') : '' }}</small>
                        </td>
                        <td>
                            <span class="arbif-badge bg-secondary text-white">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $tx->certificate_status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('viewsharepurchasetransactions', [encrypt($tx->id)]) }}" class="arbif-btn-edit">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <button class="arbif-btn-submit text-white" style="background-color: #0dcaf0; border-color: #0dcaf0;" data-bs-toggle="modal" data-bs-target="#generateCertModal{{ $tx->id }}">
                                <i class="fas fa-award"></i> Issue Certificate
                            </button>
                        </td>
                    </tr>

                    <!-- Generate Certificate Action Modal -->
                    <div class="modal fade arbif-modal" id="generateCertModal{{ $tx->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="modal-icon"><i class="fas fa-certificate"></i></div>
                                    <h5 class="modal-title">Generate Share Certificate</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('generatecertificatesharepurchasetransactions', [encrypt($tx->id)]) }}" id="dataFormFill">
                                    @csrf
                                    <div class="modal-body">
                                        <p class="text-muted mb-3">Issue share certificate for <strong>{{ $tx->member->full_name ?? $tx->member->member_name ?? 'Member' }}</strong> covering <strong>{{ number_format($tx->SharesQuantity ?? $tx->quantity ?? 0) }}</strong> shares.</p>
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="form-label" for="certificate_number{{ $tx->id }}">Certificate Serial Number <span class="text-danger">*</span></label>
                                                <input type="text" id="certificate_number{{ $tx->id }}" name="certificate_number" class="form-control" placeholder="e.g. CERT-{{ date('Y') }}-{{ $tx->id }}" value="{{ old('certificate_number') }}" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label" for="issue_date{{ $tx->id }}">Issue Date <span class="text-danger">*</span></label>
                                                <input type="date" id="issue_date{{ $tx->id }}" name="issue_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light p-3">
                                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                                            <i class="bi bi-x"></i> Cancel
                                        </button>
                                        <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                                            <i class="bi bi-check2"></i> Generate & Finalize
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="9" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No transactions pending share certificate generation.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="pendingCertificatesTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="pendingCertificatesTable"></div>
        </div>
    </div>
</div>
@endsection