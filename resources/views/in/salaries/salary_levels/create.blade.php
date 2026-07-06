@extends('layouts.app')

@section('title', 'Add Salary Level')
@section('page-title', 'Add New Salary Level')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Create Salary Level</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('salary_levels.store') }}" method="POST">
                @csrf

                @include('in.salaries.salary_levels.form', ['salaryLevel' => null])

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-1"></i> Save
                    </button>
                    <a href="{{ route('salary_levels.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
