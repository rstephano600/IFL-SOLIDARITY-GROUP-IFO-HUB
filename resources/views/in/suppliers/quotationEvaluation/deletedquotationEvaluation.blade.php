@extends('layouts.workingside')

@section('title', 'Deleted Quotation Evaluations')
@section('page-title', 'Deleted Quotation Evaluations')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-trash-alt"></i>
        </div>
        Deleted Quotation Evaluations
    </h3>

    <div>
        <a href="{{ route('quotationEvaluation') }}" class="btn btn-outline-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Active Evaluations
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedEvaluationTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Evaluation No</th>
                        <th>Quotation No</th>
                        <th>Supplier</th>
                        <th>Total Score</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->evaluation_no }}
                            </span>
                        </td>
                        <td>{{ $item->quotation->quotation_no ?? 'N/A' }}</td>
                        <td>{{ $item->quotation->supplier->name ?? 'N/A' }}</td>
                        <td><strong>{{ number_format($item->total_score, 2) }}%</strong></td>
                        <td>{{ $item->deleted_at ? $item->deleted_at->format('d M, Y H:i') : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('restorequotationEvaluation', encrypt($item->id)) }}" class="btn btn-sm btn-success text-white d-inline-flex align-items-center" onclick="return confirm('Are you sure you want to restore this evaluation?')">
                                <i class="fas fa-undo me-1"></i> restore
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No Deleted Quotation Evaluations Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    new DataTable('#deletedEvaluationTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush