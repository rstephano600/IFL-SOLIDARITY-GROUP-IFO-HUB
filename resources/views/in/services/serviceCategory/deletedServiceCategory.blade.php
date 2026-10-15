@extends('layouts.workingside')

@section('title', 'Deleted Service Categories')
@section('page-title', 'Deleted Service Categories')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon text-danger">
            <i class="fas fa-trash-alt"></i>
        </div>
        Deleted Service Categories
    </h3>

    <a href="{{ route('serviceCategory') }}" class="btn btn-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
        <i class="fas fa-arrow-left me-2"></i> Back to Active Categories
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedServiceCategoriesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Reference</th>
                        <th>Code</th>
                        <th>Category Name</th>
                        <th>Branch</th>
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
                        <td><strong>{{ $item->category_code }}</strong></td>
                        <td>{{ $item->category_name }}</td>
                        <td>{{ $item->branch->branch_name ?? 'Global / Central' }}</td>
                        <td>{{ $item->deleted_at->format('d M Y, h:i A') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <form action="{{ route('restoreserviceCategory', encrypt($item->id)) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-success text-white d-flex align-items-center gap-1" onclick="return confirm('Are you sure you want to restore this category?')">
                                        <i class="fas fa-undo"></i> Restore
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No deleted service categories found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection