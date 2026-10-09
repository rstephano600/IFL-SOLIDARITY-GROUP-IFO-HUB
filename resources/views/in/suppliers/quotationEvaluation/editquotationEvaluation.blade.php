@extends('layouts.workingside')

@section('title', 'Edit Quotation Evaluation')
@section('page-title', 'Edit Quotation Evaluation')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-pencil"></i>
        </div>
        Edit Quotation Evaluation
    </h3>

    <div>
        <a href="{{ route('quotationEvaluation') }}" class="btn btn-outline-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" action="{{ route('updatequotationEvaluation', encrypt($data->id)) }}">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Evaluation No <span class="text-danger">*</span></label>
                    <input type="text" name="evaluation_no" class="form-control" value="{{ old('evaluation_no', $data->evaluation_no) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Supplier Quotation <span class="text-danger">*</span></label>
                    <select name="supplier_quotation_id" class="form-select" data-searchable data-placeholder="Search quotation..." required>
                        <option value="">-- Select Quotation --</option>
                        @foreach($supplierQuotations as $quotation)
                            <option value="{{ $quotation->id }}" {{ $data->supplier_quotation_id == $quotation->id ? 'selected' : '' }}>
                                {{ $quotation->quotation_no }} - {{ $quotation->supplier->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select" data-searchable data-placeholder="Search branch..." required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $data->branch_id == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name ?? $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Evaluation Status</label>
                    <select name="evaluation_status" class="form-select">
                        <option value="Pending" {{ $data->evaluation_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ $data->evaluation_status == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ $data->evaluation_status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
            </div>

            <hr>
            <h6 class="fw-bold mb-3">Evaluation Scores (0 - 100)</h6>

            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label">Technical Score <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" max="100" name="technical_score" id="technical_score" class="form-control score-field" value="{{ old('technical_score', $data->technical_score) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Price Score <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" max="100" name="price_score" id="price_score" class="form-control score-field" value="{{ old('price_score', $data->price_score) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Delivery Score <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" max="100" name="delivery_score" id="delivery_score" class="form-control score-field" value="{{ old('delivery_score', $data->delivery_score) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Quality Score <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" max="100" name="quality_score" id="quality_score" class="form-control score-field" value="{{ old('quality_score', $data->quality_score) }}" required>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Calculated Total Score</label>
                    <input type="text" id="total_score_preview" class="form-control fw-bold" value="{{ number_format($data->total_score, 2) }}%" readonly style="background-color: #f8f9fa;">
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Recommendation</label>
                    <textarea name="recommendation" rows="4" class="form-control">{{ old('recommendation', $data->recommendation) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Comments / Remarks</label>
                    <textarea name="evaluation_comment" rows="4" class="form-control">{{ old('evaluation_comment', $data->evaluation_comment) }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('quotationEvaluation') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Update Evaluation</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.score-field').forEach(input => {
        input.addEventListener('input', () => {
            const tech = parseFloat(document.getElementById('technical_score').value) || 0;
            const price = parseFloat(document.getElementById('price_score').value) || 0;
            const delivery = parseFloat(document.getElementById('delivery_score').value) || 0;
            const quality = parseFloat(document.getElementById('quality_score').value) || 0;

            const total = (tech + price + delivery + quality) / 4;
            document.getElementById('total_score_preview').value = total.toFixed(2) + '%';
        });
    });
</script>
@endpush