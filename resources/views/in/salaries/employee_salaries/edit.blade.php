@extends('layouts.app')
@section('title', 'Edit Employee Salary')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-warning text-dark">
            <h5>Edit Salary for {{ $employeeSalary->employee->first_name }} {{ $employeeSalary->employee->last_name }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('employee_salaries.update', $employeeSalary->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('in.salaries.employee_salaries.form', ['employeeSalary' => $employeeSalary])
                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-warning text-white"><i class="bi bi-save"></i> Update</button>
                    <a href="{{ route('employee_salaries.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
