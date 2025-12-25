@extends('layouts.app')

@section('content')
    <div class="container py-5">

        {{-- Top Navigation / Breadcrumb --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-secondary mb-0">Profile Overview</h4>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="row justify-content-center">

            {{-- LEFT COLUMN: ID Card Style --}}
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm rounded-4 text-center h-100 overflow-hidden">
                    {{-- Decorative Header Background --}}
                    <div class="bg-primary bg-gradient" style="height: 100px;"></div>

                    <div class="card-body px-4 pb-5" style="margin-top: -60px;">
                        @php
                            $photoPath = $user->profile_photo;
                            $photoUrl =
                                $photoPath && Storage::disk('public')->exists($photoPath)
                                    ? Storage::disk('public')->url($photoPath)
                                    : 'https://ui-avatars.com/api/?name=' .
                                        urlencode($user->name) .
                                        '&size=128&background=random';
                        @endphp
                        {{-- Profile Photo with border to separate from background --}}
                        <div class="mx-auto mb-3 p-1 bg-white rounded-circle shadow-sm"
                            style="width: 130px; height: 130px;">
                            <img src="{{ $photoUrl }}" class="rounded-circle w-100 h-100 object-fit-cover"
                                alt="{{ $user->name }}">
                        </div>

                        <h3 class="fw-bold mb-1">{{ $user->name }}</h3>
                        <p class="text-muted small mb-3">{{ $user->email }}</p>

                        @if ($user->providerApplication && $user->providerApplication->status === 'approved')
                            <span
                                class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 border border-success border-opacity-25">
                                <i class="bi bi-patch-check-fill me-1"></i> Verified Note Provider
                            </span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2">
                                Student
                            </span>
                        @endif

                        <div class="mt-4 pt-4 border-top">
                            <div class="d-grid gap-2">
                                @if (auth()->id() === $user->id)
                                    <a href="{{ route('profile.edit') }}" class="btn btn-primary rounded-pill fw-bold">
                                        <i class="bi bi-pencil-square me-2"></i> Edit Profile
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: Detailed Info --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 p-lg-5">

                        <h5 class="fw-bold text-primary mb-4"><i class="bi bi-person-lines-fill me-2"></i>Academic Details
                        </h5>

                        <div class="row g-4 mb-5">
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3 h-100 border border-light-subtle">
                                    <small class="text-uppercase text-muted fw-bold"
                                        style="font-size: 0.75rem;">University</small>
                                    <div class="fw-semibold text-dark mt-1">{{ $user->university ?? 'Not set' }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3 h-100 border border-light-subtle">
                                    <small class="text-uppercase text-muted fw-bold"
                                        style="font-size: 0.75rem;">Programme</small>
                                    <div class="fw-semibold text-dark mt-1">{{ $user->programme ?? 'Not set' }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3 h-100 border border-light-subtle">
                                    <small class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Year of
                                        Study</small>
                                    <div class="fw-semibold text-dark mt-1">{{ $user->year_of_study ?? 'Not set' }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3 h-100 border border-light-subtle">
                                    <small class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Student
                                        ID</small>
                                    <div class="fw-semibold text-dark mt-1 font-monospace">{{ $user->id ?? '-' }}</div>
                                </div>
                            </div>
                        </div>

                        <h5 class="fw-bold text-primary mb-3"><i class="bi bi-text-paragraph me-2"></i>Biography</h5>
                        <div class="bg-light p-4 rounded-3 border border-light-subtle text-secondary">
                            @if ($user->bio)
                                {{ $user->bio }}
                            @else
                                <em class="text-muted">No bio added yet.</em>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Provider Application Section (Collapsible if not crucial) --}}
                @if ($user->providerApplication)
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
                            <h5 class="fw-bold mb-0 text-dark">
                                <i class="bi bi-briefcase me-2 text-warning"></i>Provider Application
                            </h5>
                            <span
                                class="badge rounded-pill px-3 py-2
                        @if ($user->providerApplication->status === 'pending') bg-warning text-dark
                        @elseif($user->providerApplication->status === 'approved') bg-success
                        @else bg-danger @endif">
                                {{ ucfirst($user->providerApplication->status) }}
                            </span>
                        </div>
                        <div class="card-body p-4 pt-0">
                            <div class="alert alert-light border rounded-3 mb-0 mt-2">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <strong class="d-block small text-muted text-uppercase mb-1">Motivation</strong>
                                        <p class="mb-3">{{ $user->providerApplication->reason }}</p>
                                    </div>
                                    @if ($user->providerApplication->background_info)
                                        <div class="col-md-12 border-top pt-3">
                                            <strong class="d-block small text-muted text-uppercase mb-1">Additional
                                                Notes</strong>
                                            <p class="mb-0">{{ $user->providerApplication->background_info }}</p>
                                        </div>
                                    @endif
                                    <div class="col-12 text-end">
                                        <small class="text-muted fst-italic">Applied on
                                            {{ $user->providerApplication->created_at->format('d M Y, h:i A') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <style>
        .object-fit-cover {
            object-fit: cover;
        }

        /* Makes the cards feel clickable/interactive */
        .card {
            transition: transform 0.2s ease-in-out;
        }
    </style>
@endsection
