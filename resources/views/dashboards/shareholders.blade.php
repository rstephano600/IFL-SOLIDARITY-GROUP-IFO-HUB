@extends('layouts.app')
@section('title', 'dashboard')
@section('content')
<div class="container mt-5">
    <h3>Admin Dashboard</h3>
    <p>Welcome, {{ auth()->user()->username }}!</p>
</div>
@endsection
