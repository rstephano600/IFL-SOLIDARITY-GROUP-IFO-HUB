@extends('layouts.configside') 
@section('title', 'Business Accounts')
@section('page-title', 'Business Accounts')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-briefcase"></i></i></div>Business Accounts</h3>
    <button class="arbif-btn-submit" onclick="openCreateForm()"><i class="fas fa-plus"></i> Add Business</button>
</div>
<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="countryTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Country</th>
                        <th class="sortable">Business Code</th>
                        <th class="sortable">Business Name</th>
                        <th>Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td> {{ $item->country->CountryName ?? '—' }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $item->BusinessCode }}</span></td>
                        <td>{{ $item->BusinessName }}</td>
                        <td>{{ $item->user->name ?? '—' }}</td>
                        <td> 
                            <a href="{{ route('editaccountBusiness', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-pencil"></i> Edit </a>
                            <a onclick="confirmDelete()" href="{{ route('destroyaccountBusiness', [encrypt($item->id)]) }}" class="arbif-btn-delete"> <i class="fas fa-trash"></i> Delete </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="arbif-table-empty"> <i class="bi bi-inbox"></i> No business records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="businessTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="businessTable"></div>
        </div>
    </div>
</div>

<div class="modal fade arbif-modal" id="addFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="bi bi-globe2"></i></div>
                <h5 class="modal-title">Add Business Branch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{route('storeaccountBusiness')}}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label"> Country </label>
                                <select style="width: 100%" class="select2_demo_3 form-control" name="Country_id">
                                    <option></option>
                                    @foreach($countries as $item)
                                    <option value="{{$item->id}}">{{$item->AccountCode}}</option>
                                    @endforeach
                                </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label"> Business Code </label>
                            <input  type="text" name="BusinessCode" class="form-control" placeholder="e.g. FIN" maxlength="20" required >
                        </div>

                        <div class="col-md-4">
                            <label class="form-label"> Business Name </label>
                            <input  type="text" name="BusinessName" class="form-control" placeholder="e.g. Finance" required >
                        </div>

                        <div class="modal-footer" style="margin-top: 20px;">
                            <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                                <i class="bi bi-x"></i> Cancel
                            </button>
                            <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                                <i class="bi bi-check2"></i> <span id="submitBtnText">Save Business</span>
                            </button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    const countryInput = document.getElementById('country-search');

    // Show all options on click/focus
    countryInput.addEventListener('focus', function() {
        this.setAttribute('placeholder', 'Type to search...');
        // This "empty string" trick triggers the browser to show the full list
        this.value = ''; 
    });

    // Capture the ID when they pick a country
    countryInput.addEventListener('input', function(e) {
        var list = document.getElementById('country-list');
        var hiddenInput = document.getElementById('country-id');
        
        for (var i = 0; i < list.options.length; i++) {
            if (list.options[i].value === this.value) {
                hiddenInput.value = list.options[i].getAttribute('data-id');
                return;
            }
        }
        hiddenInput.value = ""; // Clear if user types something not in list
    });
</script>

@endsection