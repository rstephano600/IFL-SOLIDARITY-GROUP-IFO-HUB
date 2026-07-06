@extends('layouts.workingside')

@section('title', 'Loan Penalty Categories')
@section('page-title', 'Loan Penalty Categories')

@section('content')

<div class="arbif-page-header">

    <h3>
        <div class="page-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        Loan Penalty Categories
    </h3>

    <button class="arbif-btn-submit"
            data-bs-toggle="modal"
            data-bs-target="#addFormModal">

        <i class="fas fa-plus"></i>
        Add Penalty Category

    </button>

</div>


<div class="arbif-card">

    <div class="arbif-card-body">

        <div class="arbif-table-wrap">

            <table class="arbif-table"
                   id="penaltyTable">

                <thead>

                    <tr>

                        <th class="sortable">#</th>

                        <th class="sortable">Name</th>

                        <th class="sortable">Conditions</th>

                        <th class="sortable">Description</th>

                        <th>Actions</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($data as $index => $item)

                    <tr>

                        <td>{{ $index + 1 }}</td>

                        <td>{{ $item->name }}</td>

                        <td>

                            {{ Str::limit($item->conditions, 50) }}

                        </td>

                        <td>

                            {{ Str::limit($item->descriptions, 50) }}

                        </td>

                        <td>

                            <a href="{{ route('viewloanpenaltycategory', encrypt($item->id)) }}"
                               class="arbif-btn-view">

                                <i class="fas fa-eye"></i>
                                View

                            </a>

                            <a href="{{ route('editloanpenaltycategory', encrypt($item->id)) }}"
                               class="arbif-btn-edit">

                                <i class="fas fa-pencil"></i>
                                Edit

                            </a>

                            <a href="{{ route('destroyloanpenaltycategory', encrypt($item->id)) }}"
                               class="arbif-btn-delete"
                               onclick="return confirm('Are you sure you want to DELETE this record?');">

                                <i class="fas fa-trash"></i>
                                Delete

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5"
                            class="text-center">

                            No Loan Penalty Categories Found

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

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <div class="modal-icon">
                    <i class="bi bi-plus-circle"></i>
                </div>

                <h5 class="modal-title">
                    Register Loan Penalty Category
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>

            </div>

            <div class="modal-body">

                <form method="POST"
                      action="{{ route('storeloanpenaltycategory') }}">

                    @csrf

                    <div class="row g-3">

                        <div class="col-md-12">

                            <label class="form-label">
                                Penalty Category Name
                            </label>

                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   required>

                        </div>

                        <div class="col-md-12">

                            <label class="form-label">
                                Conditions
                            </label>

                            <textarea name="conditions"
                                      rows="4"
                                      class="form-control"></textarea>

                        </div>

                        <div class="col-md-12">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea name="descriptions"
                                      rows="4"
                                      class="form-control"></textarea>

                        </div>

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