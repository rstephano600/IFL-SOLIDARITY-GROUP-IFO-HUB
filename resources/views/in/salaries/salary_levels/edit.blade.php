@extends('layouts.app')

@section('title', 'Edit Salary Level')
@section('page-title', 'Edit Salary Level')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Edit: {{ $salaryLevel->name }}</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('salary_levels.update', $salaryLevel->id) }}" method="POST">
                @csrf
                @method('PUT')

                @include('in.salaries.salary_levels.form', ['salaryLevel' => $salaryLevel])

                <div class="text-end">
                    <button type="submit" class="btn btn-warning text-white">
                        <i class="bi bi-save me-1"></i> Update
                    </button>
                    <a href="{{ route('salary_levels.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
