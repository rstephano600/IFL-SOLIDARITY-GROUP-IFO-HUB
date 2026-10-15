@extends('layouts.configside')

@section('title', 'Deleted Sales Types')
@section('page-title', 'Deleted Sales Types')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon text-danger">
            <i class="fas fa-trash-alt"></i>
        </div>
        Deleted Sales Types
    </h3>

    <div>
        <a href="{{ route('salesType') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Sales Types
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedSalesTypeTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ref No.</th>
                        <th>Type Code</th>
                        <th>Type Name</th>
                        <th>Branch</th>
                        <th>Deleted By</th>
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
                                {{ $item->ReferenceNo }}
                            </span>
                        </td>
                        <td><strong>{{ $item->type_code }}</strong></td>
                        <td>{{ $item->type_name }}</td>
                        <td>{{ $item->branch->branch_name ?? 'Global / Central' }}</td>
                        <td>{{ $item->deleter->name ?? 'System' }}</td>
                        <td>{{ $item->deleted_at ? $item->deleted_at->format('d M, Y H:i') : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('restoresalesType', encrypt($item->id)) }}" class="btn btn-sm btn-success text-white" onclick="return confirm('Are you sure you want to restore this sales type?')">
                                <i class="fas fa-undo me-1"></i> Restore
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No Deleted Sales Types Found</td>
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
    new DataTable('#deletedSalesTypeTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush