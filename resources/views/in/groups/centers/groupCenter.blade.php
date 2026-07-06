@extends('layouts.workingside')
@section('title', 'Group Centers')
@section('page-title', 'Group Collection Centers')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-building"></i>
        </div>
        Group Collection Centers
    </h3>

    <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addFormModal">
        <i class="fas fa-plus"></i> Add Group Center
    </button>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">

        <div class="arbif-table-wrap">
            <table class="arbif-table" id="employeeTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Center Code</th>
                        <th class="sortable">Center Name</th>
                        <th class="sortable">Location</th>
                        <th class="sortable">Area</th>
                        <th class="sortable">Collection Officer</th>
                        <th class="sortable">Established Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->center_code ?? 'N/A' }}
                            </span>
                        </td>

                        <td>{{ $item->center_name ?? '—' }}</td>

                        <td>{{ $item->location ?? '—' }}</td>

                        <td>{{ $item->area ?? '—' }}</td>

                        <td>
                            {{ optional($item->collectionOfficer)->employee->name ?? '—' }}
                            {{ optional($item->collectionOfficer)->LastName ?? '' }}
                        </td>

                        <td>
                            {{ $item->established_date ? \Carbon\Carbon::parse($item->established_date)->format('d M Y') : '—' }}
                        </td>

                        <td>
                            <a href="{{ route('editgroupCenter', encrypt($item->id)) }}"
                               class="arbif-btn-edit">
                                <i class="fas fa-pencil"></i> Edit
                            </a>

                            <a href="{{ route('destroygroupCenter', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to DELETED this RECORD?');">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            No Group Centers Found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>


<div class="modal fade arbif-modal" id="addFormModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <div class="modal-icon">
                    <i class="bi bi-building-add"></i>
                </div>

                <h5 class="modal-title">
                    Create Group Collection Center
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <form method="POST"
                      action="{{ route('storegroupCenter') }}"
                      id="dataFormFill">
                    @csrf

                    <div class="row g-3">

                        <div class="col-12">
                            <h5 class="arbif-section-title">
                                Center Information
                            </h5>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Center Name
                            </label>

                            <input type="text"
                                   name="center_name"
                                   class="form-control"
                                   placeholder="Enter center name"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Location
                            </label>

                            <input type="text"
                                   name="location"
                                   class="form-control"
                                   placeholder="Enter location">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Area
                            </label>

                            <input type="text"
                                   name="area"
                                   class="form-control"
                                   placeholder="Enter area">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Established Date
                            </label>

                            <input type="date"
                                   name="established_date"
                                   class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">
                                Collection Officer
                            </label>

                            <select name="collection_officer_id" data-searchable data-placeholder="Search ...">
                                <option value="">
                                    Select Collection Officer
                                </option>

                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->FirstName ?? '' }}
                                        {{ $employee->MiddleName ?? '' }}
                                        {{ $employee->LastName ?? '' }}
                                        -
                                        {{ $employee->EmployeeID ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">
                                Description
                            </label>

                            <textarea name="description"
                                      rows="4"
                                      class="form-control"
                                      placeholder="Enter center description"></textarea>
                        </div>

                    </div>

                    <div class="modal-footer mt-4">

                        <button type="button"
                                class="arbif-btn-cancel"
                                data-bs-dismiss="modal">
                            <i class="bi bi-x"></i>
                            Cancel
                        </button>

                        <button type="submit"
                                class="arbif-btn-submit">
                            <i class="bi bi-check-circle"></i>
                            Save Group Center
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>

    function confirmDelete(event)
    {
        event.preventDefault();

        let url = event.currentTarget.getAttribute('href');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This group center will be deleted permanently.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {

            if (result.isConfirmed) {
                window.location.href = url;
            }

        });
    }
</script>
@endpush