@extends('layouts.configside')
@section('title', 'Root Accounts')
@section('page-title', 'Root Accounts')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-globe"></i></div>Root Account</h3>
    <button class="arbif-btn-submit" onclick="openCreateForm()"><i class="fas fa-plus"></i> Add Root Account</button>
</div>
<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="countryTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Root Account Code</th>
                        <th class="sortable">Root Account Name</th>
                        <th class="sortable">Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td> <span class="arbif-badge arbif-badge-navy"> {{ $item->AccountCode }} </span> </td>
                        <td>{{ $item->AccountName }}</td>
                        <td>{{ $item->user->name ?? '—' }}</td>
                        <td>
                        <a href="{{ route('editaccountRoot', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-pencil"></i> Edit </a>
                        <a onclick="confirmDelete()" href="{{ route('destroyaccountRoot', [encrypt($item->id)]) }}" class="arbif-btn-delete"> <i class="fas fa-trash"></i> Delete </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No country records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="countryTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="countryTable"></div>
        </div>
    </div>
</div>

<div class="modal fade arbif-modal" id="addFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="bi bi-globe2"></i></div>
                <h5 class="modal-title">Add Root Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{route('storeaccountRoot')}}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-sm-4">
                            <label class="form-label" for="AccountCode">Account Root code</label>
                            <input type="text" id="AccountCode" name="AccountCode" class="form-control" placeholder="e.g. 01" maxlength="5" required>
                        </div>
                        <div class="col-sm-8">
                            <label class="form-label" for="AccountName">Account Root Name</label>
                            <input type="text" id="AccountName" name="AccountName" class="form-control" placeholder="e.g. Asset" required>
                        </div>
                    </div>
                    
                    <div class="modal-footer" style="margin-top: 20px;">
                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button onclick="confirmSubmit()"  type="submit" class="arbif-btn-submit">
                            <i class="bi bi-check2"></i> <span id="submitBtnText">Save Account Root</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection



