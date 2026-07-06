@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="h3 mb-4">➕ Create New Loan</h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="{{ route('loans.store') }}">
                @csrf

                @include('in.loans.loans.form', ['loan' => new \App\Models\Loan()])

                <button type="submit" class="btn btn-success mt-4">Propose Loan</button>
                <a href="{{ route('loans.index') }}" class="btn btn-secondary mt-4">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection