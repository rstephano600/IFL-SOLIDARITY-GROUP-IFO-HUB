@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">{{ $expenseCategory->name }}</h4>
            <a href="{{ route('expense-categories.index') }}" class="btn btn-light btn-sm">← Back</a>
        </div>
        <div class="card-body">
            <p><strong>Description:</strong> {{ $expenseCategory->description ?? '—' }}</p>
            <p><strong>Status:</strong> 
                <span class="badge {{ $expenseCategory->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                    {{ ucfirst($expenseCategory->status) }}
                </span>
            </p>
            <p><strong>Created By:</strong> {{ $expenseCategory->creator->name ?? '—' }}</p>
            <p><strong>Updated By:</strong> {{ $expenseCategory->editor->name ?? '—' }}</p>
            <p><strong>Created At:</strong> {{ $expenseCategory->created_at->format('d M Y, H:i') }}</p>
            <p><strong>Updated At:</strong> {{ $expenseCategory->updated_at->format('d M Y, H:i') }}</p>
        </div>
    </div>
</div>
@endsection
