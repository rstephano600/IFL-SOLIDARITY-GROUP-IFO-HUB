@extends('layouts.configside')
@section('title', 'Country Accounts')
@section('page-title', 'Country Accounts')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-globe"></i></div>Country Accounts</h3>
    <button class="arbif-btn-submit" onclick="openCreateForm()"><i class="fas fa-plus"></i> Add Country</button>
</div>
<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="countryTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Country Code</th>
                        <th class="sortable">Country Name</th>
                        <th class="sortable">Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td> <span class="arbif-badge arbif-badge-navy"> {{ $item->CountryCode }} </span> </td>
                        <td>{{ $item->CountryName }}</td>
                        <td>{{ $item->user->name ?? '—' }}</td>
                        <td>
                        <a href="{{ route('editaccountCountry', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-pencil"></i> Edit </a>
                        <a onclick="confirmDelete()" href="{{ route('destroyaccountCountry', [encrypt($item->id)]) }}" class="arbif-btn-delete"> <i class="fas fa-trash"></i> Delete </a>
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
                <h5 class="modal-title">Add Country</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{route('storeaccountCountry')}}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="country_id" id="countryId">
                    
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <label class="form-label" for="CountryCode">Country Code</label>
                            <input type="text" id="CountryCode" name="CountryCode" class="form-control" placeholder="e.g. TZ" maxlength="5" required>
                        </div>
                        <div class="col-sm-8">
                            <label class="form-label" for="CountryName">Country Name</label>
                            <input type="text" id="CountryName" name="CountryName" class="form-control" placeholder="e.g. Tanzania" required>
                        </div>
                    </div>
                    
                    <div class="modal-footer" style="margin-top: 20px;">
                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button  onclick="confirmSubmit()"  type="submit" class="arbif-btn-submit">
                            <i class="bi bi-check2"></i> <span id="submitBtnText">Save Country</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection



