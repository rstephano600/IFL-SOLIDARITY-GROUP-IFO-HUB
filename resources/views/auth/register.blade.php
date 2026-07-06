@extends('layouts.auth-app')

@section('title', 'Register')
@section('auth-title', 'Create Account')
@section('auth-footer', 'Join Our Loan Management System')

@section('auth-links')
    <div class="d-flex justify-content-center gap-4">
        @if(Route::has('login'))
            <a href="{{ route('login') }}">Sign In</a>
        @endif
        @if(Route::has('password.request'))
            <a href="{{ route('password.request') }}">Forgot Password?</a>
        @endif
    </div>
@endsection

@section('content')
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        @foreach($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('register.submit') }}" method="POST">
    @csrf
    
    <div class="mb-3">
        <label for="username" class="form-label fw-semibold">Username</label>
        <input type="text" 
               name="username" 
               class="form-control" 
               placeholder="Choose a username" 
               value="{{ old('username') }}" 
               required
               autofocus>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label fw-semibold">Email Address</label>
        <input type="email" 
               name="email" 
               class="form-control" 
               placeholder="Enter your email address" 
               value="{{ old('email') }}" 
               required>
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label fw-semibold">Phone Number</label>
        <input type="tel" 
               name="phone" 
               class="form-control" 
               placeholder="+255 XXX XXX XXX" 
               value="{{ old('phone') }}" 
               required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="password" class="form-label fw-semibold">Password</label>
            <input type="password" 
                   name="password" 
                   class="form-control" 
                   placeholder="Create password" 
                   required>
        </div>

        <div class="col-md-6 mb-3">
            <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
            <input type="password" 
                   name="password_confirmation" 
                   class="form-control" 
                   placeholder="Confirm password" 
                   required>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2 mb-3 fw-semibold">
        <i class="bi bi-person-plus me-2"></i>Create Account
    </button>

    <div class="text-center">
        <small class="text-muted">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Sign in here</a>
        </small>
    </div>
</form>
@endsection