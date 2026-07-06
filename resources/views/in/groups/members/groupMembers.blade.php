@extends('layouts.workingside')
@section('title', 'Group Members')
@section('page-title', 'Group Members Management')

@section('content')

<div class="arbif-page-header">

    <h3>
        <div class="page-icon">
            <i class="fas fa-users"></i>
        </div>
        Group Members
    </h3>

    <button class="arbif-btn-submit"
            data-bs-toggle="modal"
            data-bs-target="#addFormModal">

        <i class="fas fa-plus"></i>
        Add Group Member

    </button>

</div>



<div class="arbif-card">

    <div class="arbif-card-body">

        <div class="arbif-table-wrap">

            <table class="arbif-table" id="employeeTable">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Member Code</th>
                        <th>Group</th>
                        <th>Group Center</th>
                        <th>Client</th>
                        <th>Employee</th>
                        <th>Role In Group</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($data as $index => $item)

                    <tr>

                        <td>{{ $index + 1 }}</td>

                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->member_code ?? 'N/A' }}
                            </span>
                        </td>

                        <td>
                            {{ optional($item->group)->group_name ?? '—' }}
                        </td>

                        <td>
                            {{ optional(optional($item->group)->groupCenter)->center_name ?? '—' }}
                        </td>

                        <td>
                            {{ optional($item->client)->FirstName ?? '' }}
                            {{ optional($item->client)->MiddleName ?? '' }}
                            {{ optional($item->client)->LastName ?? '' }}
                        </td>

                        <td>
                            {{ optional($item->employee)->FirstName ?? '' }}
                            {{ optional($item->employee)->LastName ?? '' }}
                        </td>

                        <td>
                            {{ $item->role_in_group ?? '—' }}
                        </td>

                        <td>

                            @if($item->is_active == 1)

                                <span class="arbif-badge arbif-badge-success">
                                    Active
                                </span>

                            @else

                                <span class="arbif-badge arbif-badge-danger">
                                    Inactive
                                </span>

                            @endif

                        </td>

                        <td>

                            <a href="{{ route('editgroupMembers', encrypt($item->id)) }}"
                               class="arbif-btn-edit">

                                <i class="fas fa-pencil"></i>
                                Edit

                            </a>

                            <a href="{{ route('destroygroupMembers', encrypt($item->id)) }}"
                               onclick="confirmDelete(event)"
                               class="arbif-btn-delete">

                                <i class="fas fa-trash"></i>
                                Delete

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="9" class="text-center">
                            No Group Members Found
                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


<div class="modal fade arbif-modal" id="addFormModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered modal-xl">

        <div class="modal-content">

            <div class="modal-header">

                <div class="modal-icon">
                    <i class="bi bi-person-plus-fill"></i>
                </div>

                <h5 class="modal-title">
                    Add Group Member
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form method="POST"
                      action="{{ route('storegroupMembers') }}"
                      id="dataFormFill">

                    @csrf

                    <div class="row g-3">


                        <div class="col-md-6">

                            <label class="form-label">
                                Group Center
                            </label>

                            <select id="groupCenterSelect"
                                    class="form-select select2_demo_3">

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

                        </div>




                        <div class="col-md-6">

                            <label class="form-label">
                                Group
                            </label>

                            <select name="group_id"
                                    id="groupSelect"
                                    class="form-select select2_demo_3 @error('group_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    Select Group
                                </option>

                                @foreach($groups as $group)

                                    <option 
                                        value="{{ $group->id }}"
                                        data-center="{{ $group->group_center_id }}"
                                    >

                                        {{ $group->group_name ?? '' }}
                                        -
                                        {{ $group->group_code ?? '' }}

                                    </option>

                                @endforeach

                            </select>

                            @error('group_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>




                        <div class="col-md-6">

                            <label class="form-label">
                                Client
                            </label>

                            <select name="client_id"
                                    class="form-select select2_demo_3 @error('client_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    Select Client
                                </option>

                                @foreach($clients as $client)

                                    <option value="{{ $client->id }}">

                                        {{ $client->FirstName ?? '' }}
                                        {{ $client->MiddleName ?? '' }}
                                        {{ $client->LastName ?? '' }}
                                        -
                                        {{ $client->ClientCode ?? '' }}

                                    </option>

                                @endforeach

                            </select>

                            @error('client_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>




                        <div class="col-md-6">

                            <label class="form-label">
                                Employee
                            </label>

                            <select name="employee_id"
                                    class="form-select select2_demo_3 @error('employee_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    Select Employee
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

                            @error('employee_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>




                        <div class="col-md-12">

                            <label class="form-label">
                                Role In Group
                            </label>

                            <input 
                                type="text"
                                name="role_in_group"
                                class="form-control @error('role_in_group') is-invalid @enderror"
                                placeholder="Example: Chairperson, Secretary, Treasurer"
                            >

                            @error('role_in_group')
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
                            Save Group Member

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

    let employeeTableInstance;

    $(document).ready(function () {

        employeeTableInstance = new DataTable('#employeeTable', {
            perPage: 10,
            perPageSelect: [10, 25, 50, 100],
            sortable: true,
            searchable: true,
            fixedHeight: false,
            labels: {
                placeholder: "Search members...",
                perPage: "{select} entries per page",
                noRows: "No members found",
                info: "Showing {start} to {end} of {rows} entries"
            }
        });

        $('.select2_demo_3').select2({
            theme: 'bootstrap4',
            width: '100%'
        });



        $('#groupCenterSelect').on('change', function () {

            let centerId = $(this).val();

            $('#groupSelect option').each(function () {

                let optionCenter = $(this).data('center');

                if (!optionCenter || optionCenter == centerId || centerId == '') {
                    $(this).show();
                } else {
                    $(this).hide();
                }

            });

            $('#groupSelect').val('').trigger('change');

        });

    });



    function confirmDelete(event)
    {
        event.preventDefault();

        let url = event.currentTarget.getAttribute('href');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This group member will be deleted permanently.',
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