@extends('layouts.workingside')
@section('title', 'Trashed Employees')
@section('page-title', 'Trashed Employees')

@section('content')

<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-trash-restore"></i></div>Trashed Employees</h3>
    <a href="{{ route('employeeinfo') }}" class="arbif-btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Active List
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="trashedEmployeeTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Employee ID</th>
                        <th class="sortable">Full Name</th>
                        <th class="sortable">Email</th>
                        <th class="sortable">Department</th>
                        <th class="sortable">Deactivated On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->EmployeeID ?? 'N/A' }}
                            </span>
                        </td>

                        <td>
                            {{ $item->user->FirstName ?? '' }}
                            {{ $item->user->MiddleName ?? '' }}
                            {{ $item->user->LastName ?? '' }}
                        </td>

                        <td>{{ $item->user->email ?? '—' }}</td>
                        <td>{{ $item->department ?? '—' }}</td>
                        <td>{{ $item->updated_at?->format('d M Y') ?? '—' }}</td>

                        <td>
                            <a href="{{ route('showemployeeinfo', [encrypt($item->id)]) }}" class="arbif-btn-view" title="View">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a onclick="confirmAction(event, 'Restore this employee?')"
                               href="{{ route('restoreemployeeinfo', [encrypt($item->id)]) }}"
                               class="arbif-btn-submit" title="Restore">
                                <i class="fas fa-trash-restore"></i> Restore
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="arbif-table-empty text-center py-4">
                            <i class="fas fa-inbox d-block mb-2 fs-3 text-muted"></i>
                            No trashed employee records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="trashedEmployeeTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="trashedEmployeeTable"></div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function confirmAction(event, message) {
        event.preventDefault();
        const url = event.currentTarget.href;
        Swal.fire({
            title: 'Are you sure?',
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, proceed',
        }).then((result) => {
            if (result.isConfirmed) window.location.href = url;
        });
    }
</script>
@endpush