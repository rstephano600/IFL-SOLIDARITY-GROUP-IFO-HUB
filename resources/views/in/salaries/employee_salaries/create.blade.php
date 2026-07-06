@extends('layouts.app')
@section('title', 'Add Employee Salary')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-success text-white">
            <h5>Add Employee Salary</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('employee_salaries.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('in.salaries.employee_salaries.form', ['employeeSalary' => null])
                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Save</button>
                    <a href="{{ route('employee_salaries.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
