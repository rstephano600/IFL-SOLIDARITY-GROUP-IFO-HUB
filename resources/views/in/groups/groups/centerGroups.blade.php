@extends('layouts.workingside')
@section('title', 'Center Groups')
@section('page-title', 'Groups Management')

@section('content')

<div class="arbif-page-header">

    <h3>
        <div class="page-icon">
            <i class="fas fa-users"></i>
        </div>
        Groups Belonging to Centers
    </h3>

    <button class="arbif-btn-submit"
            data-bs-toggle="modal"
            data-bs-target="#addFormModal">

        <i class="fas fa-plus"></i>
        Add Group

    </button>

</div>


<div class="arbif-card">

    <div class="arbif-card-body">

        <div class="arbif-table-wrap">

            <table class="arbif-table" id="employeeTable">

                <thead>

                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Group Code</th>
                        <th class="sortable">Group Name</th>
                        <th class="sortable">Center</th>
                        <th class="sortable">Group Type</th>
                        <th class="sortable">Location</th>
                        <th class="sortable">Credit Officer</th>
                        <th class="sortable">Registration Date</th>
                        <th>Actions</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($data as $index => $item)

                    <tr>

                        <td>{{ $index + 1 }}</td>

                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->group_code ?? 'N/A' }}
                            </span>
                        </td>

                        <td>
                            {{ $item->group_name ?? '—' }}
                        </td>

                        <td>
                            {{ optional($item->groupCenter)->center_name ?? '—' }}
                        </td>

                        <td>
                            {{ $item->group_type ?? '—' }}
                        </td>

                        <td>
                            {{ $item->location ?? '—' }}
                        </td>

                        <td>
                            {{ optional($item->creditOfficer)->employee->name ?? '' }}
                        </td>

                        <td>
                            {{ $item->registration_date ? \Carbon\Carbon::parse($item->registration_date)->format('d M Y') : '—' }}
                        </td>

                        <td>

                            <a href="{{ route('editcenterGroups', encrypt($item->id)) }}"
                               class="arbif-btn-edit">

                                <i class="fas fa-pencil"></i>
                                Edit

                            </a>

                            <a href="{{ route('destroycenterGroups', encrypt($item->id)) }}"
                                onclick="return confirm('Are you sure you want to DELETED this RECORD?');"
                               class="arbif-btn-delete">

                                <i class="fas fa-trash"></i>
                                Delete

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="10" class="text-center">
                            No Groups Found
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
                    <i class="bi bi-people-fill"></i>
                </div>

                <h5 class="modal-title">
                    Create Group
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>


            <div class="modal-body">

                <form method="POST"
                      action="{{ route('storecenterGroups') }}"
                      id="dataFormFill">

                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label">
                                Group Center
                            </label>

                            <select name="group_center_id" data-searchable data-placeholder="Search ...">

                                <option value="">
                                    Select Group Center
                                </option>

                                @foreach($groupcenters as $center)

                                    <option value="{{ $center->id }}">

                                        {{ $center->center_name ?? '' }}
                                        -
                                        {{ $center->center_code ?? '' }}

                                    </option>

                                @endforeach

                            </select>

                            @error('group_center_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>



                        <div class="col-md-6">

                            <label class="form-label">
                                Group Name
                            </label>

                            <input 
                                type="text"
                                name="group_name"
                                class="form-control @error('group_name') is-invalid @enderror"
                                placeholder="Enter group name"
                                required
                            >

                            @error('group_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>



                        <div class="col-md-6">

                            <label class="form-label">
                                Group Type
                            </label>

                            <input 
                                type="text"
                                name="group_type"
                                class="form-control @error('group_type') is-invalid @enderror"
                                placeholder="Enter group type"
                            >

                            @error('group_type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>



                        <div class="col-md-6">

                            <label class="form-label">
                                Location
                            </label>

                            <input 
                                type="text"
                                name="location"
                                class="form-control @error('location') is-invalid @enderror"
                                placeholder="Enter group location"
                            >

                            @error('location')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>



                        <div class="col-md-6">

                            <label class="form-label">
                                Credit Officer
                            </label>

                            <select name="credit_officer_id" data-searchable data-placeholder="Search ...">

                                <option value="">
                                    Select Credit Officer
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

                            @error('credit_officer_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>



                        <div class="col-md-6">

                            <label class="form-label">
                                Registration Date
                            </label>

                            <input 
                                type="date"
                                name="registration_date"
                                class="form-control @error('registration_date') is-invalid @enderror"
                            >

                            @error('registration_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>



                        <div class="col-md-12">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea 
                                name="description"
                                rows="4"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Enter group description"
                            ></textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

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
                            Save Group

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
            text: 'This group will be deleted permanently.',
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