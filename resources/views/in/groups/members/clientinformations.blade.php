@extends('layouts.workingside')
@section('title', 'Client Informations')
@section('page-title', 'Clients Management')

@section('content')

<div class="arbif-page-header">

    <h3>
        <div class="page-icon">
            <i class="fas fa-user-friends"></i>
        </div>
        Clients Informations
    </h3>

    <button class="arbif-btn-submit"
            data-bs-toggle="modal"
            data-bs-target="#addFormModal">

        <i class="fas fa-plus"></i>
        Add Client

    </button>

</div>




<div class="arbif-card">

    <div class="arbif-card-body">

        <div class="arbif-table-wrap">

            <table class="arbif-table" id="employeeTable">

                <thead>

                    <tr>

                        <th class="sortable">#</th>
                        <th class="sortable">Client Code</th>
                        <th class="sortable">Full Name</th>
                        <th class="sortable">Phone</th>
                        <th class="sortable">Group</th>
                        <th class="sortable">Center</th>
                        <th class="sortable">Credit Officer</th>
                        <th class="sortable">Business</th>
                        <th>Actions</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($data as $index => $item)

                    <tr>

                        <td>{{ $index + 1 }}</td>

                        <td>

                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->client_code ?? 'N/A' }}
                            </span>

                        </td>

                        <td>

                            {{ optional($item->client)->name ?? '' }}

                        </td>

                        <td>
                            {{ optional($item->client)->phone ?? '—' }}
                        </td>
                        <td>
                            {{ optional($item->groupCenter)->center_name ?? '—' }}
                        </td>

                        <td>
                            {{ optional($item->group)->group_name ?? '—' }}
                        </td>


                        <td>

                            {{ optional($item->loanOfficer)->employee->name ?? '' }}
                        </td>

                        <td>
                            {{ $item->business_name ?? '—' }}
                        </td>

                        <td>
                            <a href="{{ route('showclientinformations', encrypt($item->id)) }}"
                            class="arbif-btn-view">
                                <i class="fas fa-eye"></i>
                                View
                            </a>
                            <a href="{{ route('editclientinformations', encrypt($item->id)) }}"
                               class="arbif-btn-edit">

                                <i class="fas fa-pencil"></i>
                                Edit

                            </a>

                            <a href="{{ route('destroyclientinformations', encrypt($item->id)) }}"
                               onclick="confirmDelete(event)"
                               class="arbif-btn-delete">

                                <i class="fas fa-trash"></i>
                                Delete

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="11" class="text-center">
                            No Client Informations Found
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
                    Register Client
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>




            <div class="modal-body">

                <form method="POST"
                      action="{{ route('storeclientinformations') }}"
                      enctype="multipart/form-data">

                    @csrf

                    <div class="row g-3">


                        <div class="col-12">
                            <h5 class="arbif-section-title">
                                Personal Informations
                            </h5>
                        </div>



                        <div class="col-md-4">

                            <label class="form-label">
                                First Name
                            </label>

                            <input type="text"
                                   name="FirstName"
                                   class="form-control @error('FirstName') is-invalid @enderror"
                                   required>

                            @error('FirstName')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Middle Name
                            </label>

                            <input type="text"
                                   name="MiddleName"
                                   class="form-control @error('MiddleName') is-invalid @enderror">

                            @error('MiddleName')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>



                        <div class="col-md-4">

                            <label class="form-label">
                                Last Name
                            </label>

                            <input type="text"
                                   name="LastName"
                                   class="form-control @error('LastName') is-invalid @enderror"
                                   required>

                            @error('LastName')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>




                        <div class="col-md-4">

                            <label class="form-label">
                                Email Address
                            </label>

                            <input type="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror">

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>




                        <div class="col-md-4">

                            <label class="form-label">
                                Phone Number
                            </label>

                            <input type="text"
                                   name="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   required>

                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>




                        <div class="col-md-4">

                            <label class="form-label">
                                Alternative Phone
                            </label>

                            <input type="text"
                                   name="alternative_phone"
                                   class="form-control @error('alternative_phone') is-invalid @enderror">

                            @error('alternative_phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>




                        <div class="col-md-4">

                            <label class="form-label">
                                Date of Birth
                            </label>

                            <input type="date"
                                   name="Dob"
                                   class="form-control @error('Dob') is-invalid @enderror">

                            @error('Dob')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>




                        <div class="col-md-4">

                            <label class="form-label">
                                Gender
                            </label>

                            <select name="gender" data-searchable data-placeholder="Search ...">

                                <option value="">
                                    Select Gender
                                </option>

                                <option value="male">
                                    Male
                                </option>

                                <option value="female">
                                    Female
                                </option>

                            </select>

                            @error('gender')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>




                        <div class="col-md-4">

                            <label class="form-label">
                                Marital Status
                            </label>

                            <select name="marital_status" data-searchable data-placeholder="Search ...">

                                <option value="">
                                    Select Status
                                </option>

                                <option value="Single">
                                    Single
                                </option>

                                <option value="Married">
                                    Married
                                </option>

                                <option value="Divorced">
                                    Divorced
                                </option>

                                <option value="Widowed">
                                    Widowed
                                </option>

                            </select>

                        </div>




                        <div class="col-12 mt-4">
                            <h5 class="arbif-section-title">
                                Group Informations
                            </h5>
                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Group
                            </label>

                            <select name="group_id" data-searchable data-placeholder="Search ...">

                                <option value="">
                                    Select Group
                                </option>

                                @foreach($groups as $group)

                                    <option value="{{ $group->id }}"
                                            data-center="{{ $group->group_center_id }}">

                                        {{ $group->group_name }} - (center : {{ $group->groupCenter->center_name }})

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <!-- <div class="col-md-4">

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

                        </div> -->



                        <div class="col-md-6">

                            <label class="form-label">
                                Client Type
                            </label>

                            <select name="client_type" data-searchable data-placeholder="Search ...">

                                <option value="">
                                    Select Type
                                </option>

                                <option value="Individual">
                                    Individual
                                </option>

                                <option value="Business">
                                    Business
                                </option>

                                <option value="Group">
                                    Group
                                </option>

                            </select>

                        </div>




                        <div class="col-12 mt-4">
                            <h5 class="arbif-section-title">
                                Business Informations
                            </h5>
                        </div>




                        <div class="col-md-6">

                            <label class="form-label">
                                Business Name
                            </label>

                            <input type="text"
                                   name="business_name"
                                   class="form-control">

                        </div>




                        <div class="col-md-6">

                            <label class="form-label">
                                Business Location
                            </label>

                            <input type="text"
                                   name="business_location"
                                   class="form-control">

                        </div>




                        <div class="col-md-4">

                            <label class="form-label">
                                Business Capital
                            </label>

                            <input type="number"
                                   step="0.01"
                                   name="business_capital"
                                   class="form-control">

                        </div>




                        <div class="col-md-4">

                            <label class="form-label">
                                Monthly Income
                            </label>

                            <input type="number"
                                   step="0.01"
                                   name="business_income"
                                   class="form-control">

                        </div>




                        <div class="col-md-4">

                            <label class="form-label">
                                Industry Sector
                            </label>

                            <input type="text"
                                   name="industry_sector"
                                   class="form-control">

                        </div>




                        <div class="col-md-6">

                            <label class="form-label">
                                Profile Picture
                            </label>

                            <input type="file"
                                   name="profile_picture"
                                   class="form-control">

                        </div>




                        <div class="col-md-6">

                            <label class="form-label">
                                Signature Image
                            </label>

                            <input type="file"
                                   name="sign_image"
                                   class="form-control">

                        </div>




                        <div class="col-md-12">

                            <label class="form-label">
                                Address
                            </label>

                            <textarea name="address_line1"
                                      rows="3"
                                      class="form-control"></textarea>

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
                            Save Client

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
            text: 'This client will be deleted permanently.',
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