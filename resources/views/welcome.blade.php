@extends('layouts.app')

@section('content')

<div class="text-center py-5">

    <h1 class="display-4 fw-bold mb-3">
        Welcome to UniNote
    </h1>

    <p class="lead text-muted mb-4">
        A collaborative platform for university students to share notes, access study materials, 
        and enhance learning together.
    </p>

    @guest
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">Login</a>
            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg px-4">Register</a>
        </div>
    @endguest

    @auth
        <div class="mt-4">
            <p class="mb-2">You are logged in as <strong>{{ Auth::user()->name }}</strong></p>
            @auth
                @if (Auth::user()->role ==='provider')
                    <a href="{{ url('/provider/dashboard') }}" class="btn btn-primary btn-lg px-4">
                Go to Dashboard
            </a>
                @elseif (Auth::user()->role ==='admin')
                    <a href="{{ url('/admin/dashboard') }}" class="btn btn-primary btn-lg px-4">
                Go to Dashboard
            </a>
                @else
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg px-4">
                Go to Dashboard
            </a>
                @endif
            @endauth
        </div>
    @endauth

</div>

<hr class="my-5">

<div class="row text-center mt-5">

    <div class="col-md-4 mb-4">
        <div class="p-4 border rounded shadow-sm">
            <h4 class="fw-bold mb-2">Share Notes</h4>
            <p class="text-muted">Contribute your study notes and help others learn more effectively.</p>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="p-4 border rounded shadow-sm">
            <h4 class="fw-bold mb-2">Access Materials</h4>
            <p class="text-muted">Browse notes uploaded by students from various subjects.</p>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="p-4 border rounded shadow-sm">
            <h4 class="fw-bold mb-2">Become a Provider</h4>
            <p class="text-muted">Apply to become a content provider and share high-quality materials.</p>
        </div>
    </div>

</div>

@endsection
