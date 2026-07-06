@extends('layouts.app')

@section('title', 'View Employee Salary')
@section('page-title', 'Employee Salary Details')

@section('content')
<div class="container-fluid py-4">

    {{-- ================================================================= --}}
    {{-- HEADER & ACTIONS --}}
    {{-- ================================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-person-badge-fill me-2 text-primary"></i> Salary Details for: <span class="text-info">{{ $employeeSalary->employee->first_name }} {{ $employeeSalary->employee->last_name }}</span>
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('employees.show', $employeeSalary->employee_id) }}" class="btn btn-outline-secondary shadow-sm">
                <i class="bi bi-person me-1"></i> View Employee Profile
            </a>
            <a href="{{ route('employee_salaries.edit', $employeeSalary->id) }}" class="btn btn-warning shadow-sm">
                <i class="bi bi-pencil-square me-1"></i> Edit Record
            </a>
            <a href="{{ route('employee_salaries.index') }}" class="btn btn-secondary shadow-sm">
                <i class="bi bi-arrow-left-circle me-1"></i> Back to Salaries
            </a>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- MAIN DETAILS CARD --}}
    {{-- ================================================================= --}}
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 fw-bold">Salary Information & Status</h5>
        </div>
        <div class="card-body">
            
            <div class="row g-4">
                {{-- Left Column: Structural Details --}}
                <div class="col-lg-6 border-end">
                    <h6 class="text-primary fw-bold mb-3"><i class="bi bi-gear-fill me-1"></i> Payroll Structure</h6>
                    <dl class="row mb-0">
                        {{-- Salary Level --}}
                        <dt class="col-sm-5 fw-bold text-dark">Salary Level:</dt>
                        <dd class="col-sm-7">{{ $employeeSalary->salaryLevel->name ?? 'Custom / N/A' }}</dd>

                        {{-- Effective Period --}}
                        <dt class="col-sm-5 fw-bold text-dark">Effective From:</dt>
                        <dd class="col-sm-7">
                            <span class="badge bg-info fw-semibold py-2">
                                {{ $employeeSalary->effective_from ? \Carbon\Carbon::parse($employeeSalary->effective_from)->format('F d, Y') : '—' }}
                            </span>
                        </dd>
                        
                        {{-- Effective To --}}
                        <dt class="col-sm-5 fw-bold text-dark">Effective To:</dt>
                        <dd class="col-sm-7">
                            @if($employeeSalary->effective_to)
                                {{ \Carbon\Carbon::parse($employeeSalary->effective_to)->format('F d, Y') }}
                            @else
                                <span class="badge bg-secondary">Ongoing</span>
                            @endif
                        </dd>

                        {{-- Status --}}
                        <dt class="col-sm-5 fw-bold text-dark">Status:</dt>
                        <dd class="col-sm-7">
                            <span class="badge rounded-pill py-2 px-3 bg-{{ $employeeSalary->status == 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($employeeSalary->status) }}
                            </span>
                        </dd>
                        
                        {{-- Attachment --}}
                        <dt class="col-sm-5 fw-bold text-dark mt-3 pt-3 border-top"><i class="bi bi-paperclip me-1"></i> Contract File:</dt>
                        <dd class="col-sm-7 mt-3 pt-3 border-top">
                            @if($employeeSalary->attachment)
                                <a href="{{ asset('storage/'.$employeeSalary->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary fw-semibold">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> View Document
                                </a>
                            @else
                                None
                            @endif
                        </dd>
                    </dl>
                </div>

                {{-- Right Column: Financial Breakdown --}}
                <div class="col-lg-6">
                    <h6 class="text-success fw-bold mb-3"><i class="bi bi-calculator-fill me-1"></i> Financial Breakdown</h6>
                    
                    <div class="p-3 rounded-3 shadow-sm border border-success border-2">
                        <dl class="row mb-0 g-2">
                            {{-- Basic Amount --}}
                            <dt class="col-sm-6 fw-bold text-dark">Basic Salary (Gross):</dt>
                            <dd class="col-sm-6 text-end fw-medium text-primary">{{ number_format($employeeSalary->basic_amount, 2) }}</dd>

                            {{-- Bonus --}}
                            <dt class="col-sm-6 fw-bold text-dark">Bonus / Allowances:</dt>
                            <dd class="col-sm-6 text-end fw-medium text-info">{{ number_format($employeeSalary->bonus, 2) }}</dd>

                            <dt class="col-12"><hr class="my-1"></dt>
                            
                            {{-- Deductions --}}
                            <dt class="col-sm-6 text-danger"><i class="bi bi-dash-circle me-1"></i> Insurance (Deduction):</dt>
                            <dd class="col-sm-6 text-end text-danger">{{ number_format($employeeSalary->insurance_amount, 2) }}</dd>

                            <dt class="col-sm-6 text-danger"><i class="bi bi-dash-circle me-1"></i> NSSF (Deduction):</dt>
                            <dd class="col-sm-6 text-end text-danger">{{ number_format($employeeSalary->nssf, 2) }}</dd>

                            <dt class="col-sm-6 text-danger"><i class="bi bi-dash-circle me-1"></i> Income Tax (Deduction):</dt>
                            <dd class="col-sm-6 text-end text-danger">{{ number_format($employeeSalary->tax, 2) }}</dd>
                            
                            <dt class="col-12"><hr class="my-2 border-success border-2 opacity-100"></dt>

                            {{-- NET DUE (Highlight) --}}
                            <dt class="col-sm-6 fw-bold fs-5 text-success">NET PAY DUE:</dt>
                            <dd class="col-sm-6 text-end fw-bolder fs-5 text-success">{{ number_format($employeeSalary->net_amount_due, 2) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection