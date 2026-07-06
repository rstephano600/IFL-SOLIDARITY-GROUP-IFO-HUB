@extends('layouts.app')

@section('title', 'Expense Details: ' . $expense->expense_title)
@section('page-title', 'Expense Details')

@section('content')
<div class="container-fluid py-4">

    {{-- ================================================================= --}}
    {{-- HEADER & ACTIONS --}}
    {{-- ================================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-receipt me-2 text-info"></i> Review Expense: <span class="text-primary">{{ $expense->expense_title }}</span>
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-warning shadow-sm">
                <i class="bi bi-pencil-square me-1"></i> Edit Expense
            </a>
            <a href="{{ route('expenses.index') }}" class="btn btn-secondary shadow-sm">
                <i class="bi bi-arrow-left-circle me-1"></i> Back to Expenses
            </a>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- MAIN DETAILS AND STATUS --}}
    {{-- ================================================================= --}}
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Expense Overview</h5>
            <span class="fs-6 fw-bold">
                Status: 
                <span class="badge bg-{{ 
                    $expense->status === 'approved' ? 'success' : 
                    ($expense->status === 'pending' ? 'warning text-dark' : 'danger') 
                }} fw-bold">
                    {{ ucfirst($expense->status) }}
                </span>
            </span>
        </div>
        <div class="card-body">
            
            <div class="row">
                <div class="col-lg-7">
                    <dl class="row mb-0">
                        {{-- Date --}}
                        <dt class="col-sm-4 fw-bold text-dark"><i class="bi bi-calendar me-1"></i> Expense Date:</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($expense->expense_date)->format('F d, Y') }}</dd>

                        {{-- Category --}}
                        <dt class="col-sm-4 fw-bold text-dark"><i class="bi bi-tag me-1"></i> Category:</dt>
                        <dd class="col-sm-8">{{ $expense->category->name ?? '—' }}</dd>

                        {{-- Created By --}}
                        <dt class="col-sm-4 fw-bold text-dark"><i class="bi bi-person me-1"></i> Recorded By:</dt>
                        <dd class="col-sm-8">{{ $expense->creator->name ?? 'System' }}</dd>
                        
                        {{-- Description --}}
                        <dt class="col-sm-4 fw-bold text-dark border-top pt-2 mt-2"><i class="bi bi-card-text me-1"></i> Description:</dt>
                        <dd class="col-sm-8 border-top pt-2 mt-2">{{ $expense->description ?? 'No description provided.' }}</dd>

                        {{-- Attachment --}}
                        @if($expense->attachment)
                        <dt class="col-sm-4 fw-bold text-dark"><i class="bi bi-paperclip me-1"></i> Main Attachment:</dt>
                        <dd class="col-sm-8">
                            <a href="{{ asset('storage/'.$expense->attachment) }}" target="_blank" class="text-info fw-semibold">
                                <i class="bi bi-box-arrow-up-right"></i> View File
                            </a>
                        </dd>
                        @endif
                    </dl>
                </div>
                
                {{-- Total Amount Highlight --}}
                <div class="col-lg-5 d-flex align-items-center justify-content-end">
                    <div class="text-end bg-light p-4 rounded-3 shadow-sm border border-danger border-3">
                        <h6 class="text-muted mb-1">TOTAL EXPENSE AMOUNT</h6>
                        <h1 class="display-4 fw-bold text-danger mb-0">
                            {{ $expense->currency ?? 'TZS' }} {{ number_format($expense->total_amount, 2) }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- EXPENSE LINE ITEMS --}}
    {{-- ================================================================= --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light fw-bold">
            <i class="bi bi-list-task me-2"></i> Detailed Expense Items
        </div>
        
        @if($expense->items->count() > 0)
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 25%;">Item Name</th>
                            <th style="width: 10%;">Quantity</th>
                            <th style="width: 15%;">Unit Cost</th>
                            <th style="width: 15%;">Total Cost</th>
                            <th style="width: 20%;">Supplier</th>
                            <th style="width: 10%;">Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expense->items as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="fw-semibold">{{ $item->item_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="text-nowrap">{{ $expense->currency ?? 'TZS' }} {{ number_format($item->unit_cost, 2) }}</td>
                            <td class="fw-bold text-danger text-nowrap">{{ $expense->currency ?? 'TZS' }} {{ number_format($item->total_cost, 2) }}</td>
                            <td class="small text-muted">{{ $item->supplier_name ?? '—' }}</td>
                            <td>
                                @if($item->attachment)
                                <a href="{{ asset('storage/'.$item->attachment) }}" target="_blank" class="btn btn-sm btn-outline-info p-1" title="View Item Receipt">
                                    <i class="bi bi-file-earmark-check-fill"></i>
                                </a>
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
            <div class="card-body">
                <p class="text-muted mb-0">This expense record does not have any detailed line items.</p>
            </div>
        @endif
    </div>
</div>
@endsection