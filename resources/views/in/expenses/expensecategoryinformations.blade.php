@extends('layouts.workingside')

@section('title', 'Expense Categories')
@section('page-title', 'Expense Categories')

@section('content')

<div class="arbif-page-header">

    <h3>
        <div class="page-icon">
            <i class="fas fa-wallet"></i>
        </div>

        Expense Categories
    </h3>

    <button class="arbif-btn-submit"
            data-bs-toggle="modal"
            data-bs-target="#addFormModal">

        <i class="fas fa-plus"></i>

        Add Expense Category

    </button>

</div>

<div class="arbif-card">

    <div class="arbif-card-body">

        <div class="arbif-table-wrap">

            <table class="arbif-table"
                   id="expenseCategoryTable">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Code</th>

                        <th>Name</th>

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
                                {{ $item->expense_code }}
                            </span>
                        </td>

                        <td>{{ $item->name }}</td>

                        <td>{{ $item->description }}</td>

                        <td>

                            <a href="{{ route('viewexpensecategory', encrypt($item->id)) }}"
                               class="arbif-btn-view">

                                <i class="fas fa-eye"></i>view

                            </a>

                            <a href="{{ route('editexpensecategory', encrypt($item->id)) }}"
                               class="arbif-btn-edit">

                                <i class="fas fa-pencil"></i>edit

                            </a>
@can('view-generally-delete-of-any-item')
                            <a href="{{ route('destroyexpensecategory', encrypt($item->id)) }}"
                               class="arbif-btn-delete"
                               onclick="return confirm('Are you sure?')">

                                <i class="fas fa-trash"></i>del

                            </a>
@endcan
                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5" class="text-center">

                            No Expense Categories Found

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- ADD MODAL --}}

<div class="modal fade arbif-modal"
     id="addFormModal"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <div class="modal-icon">
                    <i class="bi bi-wallet2"></i>
                </div>

                <h5 class="modal-title">

                    Register Expense Category

                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form method="POST"
                      action="{{ route('storeexpensecategory') }}">

                    @csrf

                        
                    <div class="mb-3">

                        <label class="form-label">

                            Expense Code

                        </label>

                        <input type="text"
                               name="expense_code"
                               class="form-control"
                               required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Expense Name

                        </label>

                        <input type="text"
                               name="name"
                               class="form-control"
                               required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Description

                        </label>

                        <textarea name="description"
                                  rows="4"
                                  class="form-control"></textarea>

                    </div>

                    <div class="modal-footer">

                        <button type="submit"
                                class="arbif-btn-submit">

                            Save Category

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

new DataTable('#expenseCategoryTable',{

    perPage:10,

    searchable:true,

    sortable:true

});

</script>

@endpush