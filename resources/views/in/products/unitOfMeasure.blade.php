@extends('layouts.configside')

@section('title', 'Units of Measure')
@section('page-title', 'Units of Measure')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-balance-scale"></i>
        </div>
        Units of Measure
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedunitOfMeasure') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Units
        </a>
        <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addUomModal">
            <i class="fas fa-plus"></i> Add Unit
        </button>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="uomTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ref No</th>
                        <th>Unit Code</th>
                        <th>Unit Name</th>
                        <th>Branch</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->UnitRefNo }}
                            </span>
                        </td>
                        <td><strong>{{ $item->UnitCode ?? 'N/A' }}</strong></td>
                        <td>{{ $item->UnitName }}</td>
                        <td>{{ $item->branch->branch_name ?? 'Global / Central' }}</td>
                        <td>{{ Str::limit($item->Description, 50) }}</td>
                        <td>
                            <a href="{{ route('viewunitOfMeasure', encrypt($item->id)) }}" class="arbif-btn-view">
                                <i class="fas fa-eye"></i>view
                            </a>

                            <a href="{{ route('editunitOfMeasure', encrypt($item->id)) }}" class="arbif-btn-edit">
                                <i class="fas fa-pencil"></i>edit
                            </a>
                            @can('view-generally-delete-of-any-item')

                            <a href="{{ route('destroyunitOfMeasure', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to remove this unit of measure?')">
                                <i class="fas fa-trash"></i>del
                            </a>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No Units of Measure Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- REGISTER UOM MODAL --}}
<div class="modal fade arbif-modal" id="addUomModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="bi bi-patch-check"></i>
                </div>
                <h5 class="modal-title">Register Unit of Measure</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('storeunitOfMeasure') }}">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                            <select name="branch_id" class="form-select" data-searchable data-placeholder="Search ..." required>
                                <option value="">-- Select Branch --</option>
                                @foreach($branchies as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Unit Code <span class="text-danger">*</span></label>
                            <input type="text" name="UnitCode" class="form-control" placeholder="e.g. PCS, KG, LTR, BOX" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Unit Name <span class="text-danger">*</span></label>
                        <input type="text" name="UnitName" class="form-control" placeholder="e.g. PIECES, KILOGRAMS, LITRES" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="Description" rows="4" class="form-control" placeholder="Provide metric metric usage specifications..."></textarea>
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">Save Unit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    new DataTable('#uomTable',{
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush