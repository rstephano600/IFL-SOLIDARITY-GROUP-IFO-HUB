@extends('layouts.configside')
@section('title', 'First Branch Accounts')
@section('page-title', 'First Branch Accounts')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-globe"></i></div>First Branch Accounts</h3>
    <button class="arbif-btn-submit" onclick="openCreateForm()"><i class="fas fa-plus"></i> Add First Branch Accounts</button>
</div>
<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="countryTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Root Account Code</th>
                        <th class="sortable">First Branch Code</th>
                        <th class="sortable">First Branch Name</th>
                        <th class="sortable">Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td> <span class="arbif-badge arbif-badge-navy"> {{ $item->root->AccountCode }} </span> </td>
                        <td> <span class="arbif-badge arbif-badge-navy"> {{ $item->FirstAccountCode }} </span> </td>
                        <td>{{ $item->FirstAccountName }}</td>
                        <td>{{ $item->user->name ?? '—' }}</td>
                        <td>
                        <a href="{{ route('editaccountFirstBranch', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-pencil"></i> Edit </a>
                        <a onclick="confirmDelete()" href="{{ route('destroyaccountFirstBranch', [encrypt($item->id)]) }}" class="arbif-btn-delete"> <i class="fas fa-trash"></i> Delete </a>
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
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="bi bi-globe2"></i></div>
                <h5 class="modal-title">Add Root Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{route('storeaccountFirstBranch')}}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label"> Account Root </label>
                                <select style="width: 100%" class="select2_demo_3 form-control" name="AccountRoot_id">
                                    <option></option>
                                    @foreach($accountroot as $item)
                                    <option value="{{$item->id}}"> {{$item->AccountCode}} - {{$item->AccountName}} </option>
                                    @endforeach
                                </select>
                        </div>

                        <div class="col-sm-4">
                            <label class="form-label" for="FirstAccountCode">First Branch code</label>
                            <input type="text" id="FirstAccountCode" name="FirstAccountCode" class="form-control" placeholder="e.g. 01" maxlength="5" required>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="FirstAccountName">First Branch Name</label>
                            <input type="text" id="FirstAccountName" name="FirstAccountName" class="form-control" placeholder="e.g. Asset" required>
                        </div>
                    </div>
                    
                    <div class="modal-footer" style="margin-top: 20px;">
                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button onclick="confirmSubmit()"  type="submit" class="arbif-btn-submit">
                            <i class="bi bi-check2"></i> <span id="submitBtnText">Save First Branch</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection



