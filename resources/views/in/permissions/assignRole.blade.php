@extends('layouts.configside')
@section('title', 'Assign Users Informations')
@section('page-title', 'User Permissions Access')

@section('content')
<style>
    .row-assigned {
        background-color: #d4edda !important;
        color: #155724;
    }

    .row-unassigned {
        background-color: #f8d7da !important;
        color: #721c24;
    }

    .btn-assign {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        margin-right: 10px;
    }

    .btn-remove {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
    }

    .action-bar {
        margin-bottom: 20px;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 6px;
        border: 1px solid #dee2e6;
    }

    .select-all-label {
        font-weight: 600;
        margin-left: 5px;
    }

    .selection-count {
        color: #0d6efd;
        font-weight: 600;
        margin-left: 15px;
    }
</style>

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-globe"></i>
        </div>
        Manage Permissions for: {{ $user->name }}
    </h3>
</div>

<form id="permissionsForm" method="POST" action="">
    @csrf
    <input type="hidden" name="User_id" value="{{ $user->id }}">

    <div class="action-bar">

        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">

            <div>
                <label class="select-all-label">
                    <input type="checkbox" id="selectAll">
                    Select All Permissions
                </label>

                <span class="selection-count">
                    Selected:
                    <span id="selectedCount">0</span>
                </span>
            </div>

            <div>
                <button type="button"
                        class="btn-assign"
                        onclick="submitBulkAction('{{ route('permissionsstore') }}')">
                    <i class="fas fa-plus-circle"></i>
                    Assign Selected
                </button>

                <button type="button"
                        class="btn-remove"
                        onclick="submitBulkAction('{{ route('permissionsremove') }}')">
                    <i class="fas fa-trash-alt"></i>
                    Remove Selected
                </button>
            </div>

        </div>

    </div>

    <div class="arbif-card">
        <div class="arbif-card-body">
            <div class="arbif-table-wrap">

                <table class="arbif-table" id="countryTable">

                    <thead>
                        <tr>

                            <th style="width:60px;text-align:center;">
                                <input type="checkbox" id="selectAllHeader">
                            </th>

                            <th class="sortable">
                                Permission Name
                            </th>

                            <th class="sortable">
                                Current Status
                            </th>

                        </tr>
                    </thead>

                    <tbody>

                        @foreach($permissions as $permission)

                        @php
                            $hasPermission = in_array($permission->id, $assigned);
                        @endphp

                        <tr class="{{ $hasPermission ? 'row-assigned' : 'row-unassigned' }}">

                            <td style="text-align:center;">
                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission->id }}"
                                    class="permission-checkbox">
                            </td>

                            <td>
                                <strong>{{ $permission->name }}</strong>
                            </td>

                            <td>

                                @if($hasPermission)

                                    <span class="badge badge-success">
                                        <i class="fas fa-check"></i>
                                        Assigned
                                    </span>

                                @else

                                    <span class="badge badge-danger">
                                        <i class="fas fa-times"></i>
                                        Not Assigned
                                    </span>

                                @endif

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>
        </div>
    </div>

</form>

<script>
const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
const selectAll = document.getElementById('selectAll');
const selectAllHeader = document.getElementById('selectAllHeader');
const selectedCount = document.getElementById('selectedCount');

function updateSelectionCount() {

    const checked = document.querySelectorAll('.permission-checkbox:checked').length;

    selectedCount.textContent = checked;

    const allChecked = checked === permissionCheckboxes.length && permissionCheckboxes.length > 0;

    selectAll.checked = allChecked;
    selectAllHeader.checked = allChecked;
}

function toggleAll(state) {

    permissionCheckboxes.forEach(function(box) {
        box.checked = state;
    });

    updateSelectionCount();
}

selectAll.addEventListener('change', function () {
    toggleAll(this.checked);
});

selectAllHeader.addEventListener('change', function () {
    toggleAll(this.checked);
});

permissionCheckboxes.forEach(function(box){

    box.addEventListener('change', function(){

        updateSelectionCount();

    });

});

updateSelectionCount();

function submitBulkAction(routeUrl) {

    const form = document.getElementById('permissionsForm');

    const checked = document.querySelectorAll('.permission-checkbox:checked');

    if (checked.length === 0) {

        alert('Please select at least one permission.');

        return;

    }

    form.action = routeUrl;

    form.submit();

}
</script>

@endsection