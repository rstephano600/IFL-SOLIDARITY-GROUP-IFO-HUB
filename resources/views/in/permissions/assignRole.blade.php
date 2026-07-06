@extends('layouts.configside') 
@section('title', 'Assign Users Informations')
@section('page-title', 'User Permissions Access')

@section('content')
<style>
    .row-assigned { background-color: #d4edda !important; color: #155724; }
    .row-unassigned { background-color: #f8d7da !important; color: #721c24; }
    .btn-assign { background-color: #28a745; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; margin-right: 10px; }
    .btn-remove { background-color: #dc3545; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; }
    .action-bar { margin-bottom: 20px; background: #f8f9fa; padding: 15px; border-radius: 6px; border: 1px solid #dee2e6; }
</style>

<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-globe"></i></div>Manage Permissions for: {{ $user->name }}</h3>
</div>

<!-- Master Form Container -->
<form id="permissionsForm" method="POST" action="">
    @csrf
    <input type="hidden" name="User_id" value="{{ $user->id }}">

    <!-- Fixed Floating Action Buttons Bar -->
    <div class="action-bar">
        <span>Select permissions below, then click an action:</span>
        <div style="margin-top: 10px;">
            <button type="button" class="btn-assign" onclick="submitBulkAction('{{ route('permissionsstore') }}')">
                <i class="fas fa-plus-circle"></i> Assign Selected
            </button>
            <button type="button" class="btn-remove" onclick="submitBulkAction('{{ route('permissionsremove') }}')">
                <i class="fas fa-trash-alt"></i> Remove Selected
            </button>
        </div>
    </div>

    <div class="arbif-card">
        <div class="arbif-card-body">
            <div class="arbif-table-wrap">
                <table class="arbif-table" id="countryTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">Select</th>
                            <th class="sortable">Permission Name</th>
                            <th class="sortable">Current Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $permission)
                        @php 
                            $hasPermission = in_array($permission->id, $assigned);
                        @endphp
                        <tr class="{{ $hasPermission ? 'row-assigned' : 'row-unassigned' }}">
                            <td>
                                <!-- Checkbox is entirely fresh and blank to only capture user choices -->
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="permission-checkbox">
                            </td>
                            <td><strong>{{ $permission->name }}</strong></td>
                            <td>
                                @if($hasPermission)
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Assigned</span>
                                @else
                                    <span class="badge badge-danger"><i class="fas fa-times"></i> Not Assigned</span>
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
function submitBulkAction(routeUrl) {
    const form = document.getElementById('permissionsForm');
    const checkboxes = document.querySelectorAll('.permission-checkbox:checked');
    
    // Safety check to ensure they checked at least one box
    if (checkboxes.length === 0) {
        alert('Please select at least one permission checkbox first.');
        return;
    }
    
    // Dynamically inject the route action and submit
    form.action = routeUrl;
    form.submit();
}
</script>
@endsection
