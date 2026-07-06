@extends('layouts.workingside')
@section('title', 'Loan Categories')
@section('page-title', 'Loan Categories Management')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-hand-holding-usd"></i>
        </div>
        Loan Categories
    </h3>

    <button class="arbif-btn-submit"
            data-bs-toggle="modal"
            data-bs-target="#addFormModal">
        <i class="fas fa-plus"></i>
        Add Loan Category
    </button>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">

        <div class="arbif-table-wrap">

            <table class="arbif-table" id="loanCategoryTable">

                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Category Name</th>
                        <th class="sortable">Amount</th>
                        <th class="sortable">Interest Rate</th>
                        <th class="sortable">Interest Amount</th>
                        <th class="sortable">Repayment Frequency</th>
                        <th class="sortable">Total Due</th>
                        <th class="sortable">Currency</th>
                        <th class="sortable">New Client</th>
                        <th class="sortable">Status</th>
                        <th>View More</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($data as $index => $item)

                    <tr>

                        <td>{{ $index + 1 }}</td>

                        <td>
                            <strong>
                                {{ $item->name ?? 'N/A' }}
                            </strong>
                        </td>

                        <td>
                            {{ number_format($item->amount_disbursed ?? 0, 2) }}
                        </td>

                        <td>
                            {{ $item->interest_rate ?? 0 }}%
                        </td>

                        <td>
                            {{ number_format($item->interest_amount ?? 0, 2) }}
                        </td>

                        <td>
                            <span class="arbif-badge arbif-badge-info">
                                {{ ucfirst($item->repayment_frequency ?? 'N/A') }}
                            </span>
                        </td>

                        <td>
                            {{ number_format($item->total_due ?? 0, 2) }}
                        </td>

                        <td>
                            {{ $item->currency ?? 'TZS' }}
                        </td>

                        <td>
                            @if($item->is_new_client)
                                <span class="arbif-badge arbif-badge-success">
                                    YES
                                </span>
                            @else
                                <span class="arbif-badge arbif-badge-warning">
                                    NO
                                </span>
                            @endif
                        </td>

                        <td>
                            @if($item->is_active)
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

                            <a href="{{ route('viewloancategory', encrypt($item->id)) }}"
                               class="arbif-btn-edit">
                                <i class="fas fa-eye"></i>
                                View
                            </a>
                        </td>
                        <td>

                            <a href="{{ route('editloancategory', encrypt($item->id)) }}"
                               class="arbif-btn-edit">
                                <i class="fas fa-pencil"></i>
                                Edit
                            </a>

                            <a href="{{ route('destroyloancategory', encrypt($item->id)) }}"
                               class="arbif-btn-delete"
                               onclick="return confirm('Are you sure you want to DELETE this Loan Category?');">
                                <i class="fas fa-trash"></i>
                                Delete
                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="11" class="text-center">
                            No Loan Categories Found
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>
</div>


<div class="modal fade arbif-modal"
     id="addFormModal"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered modal-xl">

        <div class="modal-content">

            <div class="modal-header">

                <div class="modal-icon">
                    <i class="bi bi-cash-stack"></i>
                </div>

                <h5 class="modal-title">
                    Create Loan Category
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form method="POST"
                        action="{{ route('storeloancategory') }}"
                        id="dataFormFill">

                        @csrf

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">
                                    Loan Category Name
                                </label>

                                <input type="text"
                                    name="name"
                                    class="form-control"
                                    placeholder="Enter loan category name">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Amount Disbursed
                                </label>

                                <input type="number"
                                    step="0.01"
                                    name="amount_disbursed"
                                    class="form-control"
                                    placeholder="0.00"
                                    required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Principal Due
                                </label>

                                <input type="number"
                                    step="0.01"
                                    name="principal_due"
                                    class="form-control"
                                    placeholder="0.00"
                                    required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Insurance Fee
                                </label>

                                <input type="number"
                                    step="0.01"
                                    name="insurance_fee"
                                    class="form-control"
                                    placeholder="0.00">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Officer Visit Fee
                                </label>

                                <input type="number"
                                    step="0.01"
                                    name="officer_visit_fee"
                                    class="form-control"
                                    placeholder="0.00">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Interest Rate (%)
                                </label>

                                <input type="number"
                                    step="0.01"
                                    name="interest_rate"
                                    class="form-control"
                                    placeholder="0.00">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Repayment Frequency
                                </label>

                                <select name="repayment_frequency"
                                        class="form-control" data-searchable data-placeholder="Search..."
                                        required>

                                    <option value="">
                                        Select Frequency
                                    </option>

                                    <option value="daily">
                                        Daily
                                    </option>

                                    <option value="weekly">
                                        Weekly
                                    </option>

                                    <option value="bi_weekly">
                                        Bi Weekly
                                    </option>

                                    <option value="monthly">
                                        Monthly
                                    </option>

                                    <option value="quarterly">
                                        Quarterly
                                    </option>

                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Maximum Term Days
                                </label>

                                <input type="number"
                                    name="max_term_days"
                                    class="form-control"
                                    placeholder="Enter max term days">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Maximum Term Months
                                </label>

                                <input type="number"
                                    name="max_term_months"
                                    class="form-control"
                                    placeholder="Enter max term months">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Currency
                                </label>

                                <input type="text"
                                    name="currency"
                                    class="form-control"
                                    placeholder="TZS">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">
                                    Conditions
                                </label>

                                <textarea name="conditions"
                                        rows="4"
                                        class="form-control"
                                        placeholder="Enter loan conditions"></textarea>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">
                                    Descriptions
                                </label>

                                <textarea name="descriptions"
                                        rows="4"
                                        class="form-control"
                                        placeholder="Enter loan category descriptions"></textarea>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">
                                    New Client
                                </label>

                                <select name="is_new_client"
                                        class="form-control">

                                    <option value="1">
                                        YES
                                    </option>

                                    <option value="0">
                                        NO
                                    </option>

                                </select>
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
                                Save Loan Category

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

    let loanCategoryTable;

    $(document).ready(function () {

        loanCategoryTable = new DataTable('#loanCategoryTable', {

            perPage: 10,
            perPageSelect: [10, 25, 50, 100],
            sortable: true,
            searchable: true,
            fixedHeight: false,

            labels: {
                placeholder: "Search loan category...",
                perPage: "{select} entries per page",
                noRows: "No loan categories found",
                info: "Showing {start} to {end} of {rows} entries"
            }

        });

        $('.select2_demo_3').select2({
            theme: 'bootstrap4',
            width: '100%'
        });

    });

</script>

@endpush