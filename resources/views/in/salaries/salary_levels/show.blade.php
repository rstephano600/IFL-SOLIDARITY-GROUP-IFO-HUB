@extends('layouts.app')

@section('title', 'View Salary Level')
@section('page-title', 'Salary Level Details')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">{{ $salaryLevel->name }}</h5>
        </div>

        <div class="card-body">
            <dl class="row">
                <dt class="col-md-4">Basic Amount</dt>
                <dd class="col-md-8">{{ number_format($salaryLevel->basic_amount, 2) }} {{ $salaryLevel->currency }}</dd>

                <dt class="col-md-4">Insurance</dt>
                <dd class="col-md-8">{{ number_format($salaryLevel->insurance_amount, 2) }}</dd>

                <dt class="col-md-4">NSSF</dt>
                <dd class="col-md-8">{{ number_format($salaryLevel->nssf, 2) }}</dd>

                <dt class="col-md-4">Tax</dt>
                <dd class="col-md-8">{{ number_format($salaryLevel->tax, 2) }}</dd>

                <dt class="col-md-4">Net Amount Due</dt>
                <dd class="col-md-8 fw-bold text-success">{{ number_format($salaryLevel->net_amount_due, 2) }} {{ $salaryLevel->currency }}</dd>

                <dt class="col-md-4">Status</dt>
                <dd class="col-md-8">
                    <span class="badge bg-{{ $salaryLevel->status === 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($salaryLevel->status) }}
                    </span>
                </dd>

                @if($salaryLevel->description)
                <dt class="col-md-4">Description</dt>
                <dd class="col-md-8">{{ $salaryLevel->description }}</dd>
                @endif

                <dt class="col-md-4">Created</dt>
                <dd class="col-md-8">{{ $salaryLevel->created_at->format('d M, Y H:i') }}</dd>
            </dl>

            <div class="text-end">
                <a href="{{ route('salary_levels.edit', $salaryLevel->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="{{ route('salary_levels.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection
