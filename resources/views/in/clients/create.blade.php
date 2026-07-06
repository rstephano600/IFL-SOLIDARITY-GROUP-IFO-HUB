@extends('layouts.app')
@section('title', 'Clients')
@section('page-title', 'A client')

@section('content')
<div class="container-fluid">
    <h3>Add New Client</h3>

    <form action="{{ route('clients.store') }}" method="POST">
        @csrf

        @include('in.clients.form', ['mode' => 'create'])

        <div class="text-end mb-4">
            <button type="submit" class="btn btn-primary btn-lg">Save Client Information</button>
        </div>
        
        <div class="mt-3">
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Cancel</a>
        </div>

    </form>
</div>
@endsection
