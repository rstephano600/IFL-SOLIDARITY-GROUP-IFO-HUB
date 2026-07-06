@extends('layouts.app')

@section('title', 'Salary Levels Management')
@section('page-title', 'Salary Levels')

@section('content')
<div class="container-fluid py-4">

    {{-- ================================================================= --}}
    {{-- HEADER & ACTIONS --}}
    {{-- ================================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-bank me-2 text-success"></i> Salary Structure Levels
        </h2>
        <a href="{{ route('salary_levels.create') }}" class="btn btn-success shadow-sm fw-semibold">
            <i class="bi bi-plus-circle-fill me-1"></i> Create New Level
        </a>
    </div>

    {{-- ================================================================= --}}
    {{-- ALERT MESSAGE --}}
    {{-- ================================================================= --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ================================================================= --}}
    {{-- SALARY LEVELS CARD & TABLE --}}
    {{-- ================================================================= --}}
    <div class="card shadow-lg border-0">
        
        @if($salaryLevels->count() > 0)
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 20%;">Level Name</th>
                                <th class="text-end" style="width: 15%;">Basic Salary</th>
                                <th class="text-end text-info" style="width: 10%;">Insurance</th>
                                <th class="text-end text-info" style="width: 10%;">NSSF</th>
                                <th class="text-end text-danger" style="width: 10%;">Tax</th>
                                <th class="text-end text-success" style="width: 15%;">Net Due</th>
                                <th style="width: 10%;">Status</th>
                                <th class="text-end" style="width: 10%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salaryLevels as $level)
                                <tr>
                                    <td class="fw-semibold text-primary">{{ $level->name }}</td>
                                    <td class="text-end fw-medium">{{ number_format($level->basic_amount, 2) }}</td>
                                    <td class="text-end text-info small">{{ number_format($level->insurance_amount, 2) }}</td>
                                    <td class="text-end text-info small">{{ number_format($level->nssf, 2) }}</td>
                                    <td class="text-end text-danger small">{{ number_format($level->tax, 2) }}</td>
                                    <td class="text-end fw-bold text-success">{{ number_format($level->net_amount_due, 2) }}</td>
                                    <td>
                                        <span class="badge rounded-pill py-2 px-3 bg-{{ $level->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($level->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('salary_levels.show', $level->id) }}" class="btn btn-outline-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('salary_levels.edit', $level->id) }}" class="btn btn-outline-warning" title="Edit Level">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('salary_levels.destroy', $level->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete Level" 
                                                    onclick="return confirm('Are you sure you want to delete the salary level: {{ $level->name }}? This action cannot be undone.')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            @if ($salaryLevels->hasPages())
                <div class="card-footer bg-light border-top">
                    <div class="d-flex justify-content-center">
                        {{ $salaryLevels->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        @else
            <div class="card-body">
                <div class="alert alert-info text-center shadow-sm py-4">
                    <i class="bi bi-currency-dollar display-4 d-block mb-3"></i>
                    <p class="mb-0">No salary levels have been defined yet. Get started by creating one!</p>
                    <a href="{{ route('salary_levels.create') }}" class="btn btn-primary mt-3 fw-semibold">
                        <i class="bi bi-plus-circle-fill me-1"></i> Define First Salary Level
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection