@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-10">

            {{-- 1. HEADER SECTION: Welcoming and Clear --}}
            <div class="text-center mb-5">
                <div class="bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 64px; height: 64px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-mortarboard" viewBox="0 0 16 16">
                        <path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1-1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917l-7.5-3.5ZM8 8.46 1.758 5.965 8 3.052l6.242 2.913L8 8.46Z"/>
                        <path d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466 4.176 9.032Zm-.068 1.873.22-.748 3.496 1.317a.5.5 0 0 0 .352 0l3.496-1.317.22.748L8 12.46l-3.892-1.556Z"/>
                    </svg>
                </div>
                <h2 class="fw-bold">Become a Note Provider</h2>
                <p class="text-muted col-10 mx-auto">
                    Share your academic excellence. Help other students succeed while earning recognition for your high-quality, original materials.
                </p>
            </div>

            {{-- 2. STATUS FEEDBACK: Flash Messages --}}
            @if (session('success'))
                <div class="alert alert-success d-flex align-items-center shadow-sm border-0 mb-4" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            <div class="card border-0 shadow-lg overflow-hidden">
                
                {{-- 3. CURRENT APPLICATION DASHBOARD --}}
                @if ($application)
                    {{-- Dynamic Status Header --}}
                    <div class="card-header border-0 py-3 
                        @if($application->status == 'pending') bg-warning bg-opacity-25 text-warning-emphasis
                        @elseif($application->status == 'approved') bg-success bg-opacity-25 text-success-emphasis
                        @else bg-danger bg-opacity-25 text-danger-emphasis @endif">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold small text-uppercase tracking-wide">Application Status</span>
                            <span class="badge rounded-pill 
                                @if($application->status == 'pending') bg-warning text-dark
                                @elseif($application->status == 'approved') bg-success
                                @else bg-danger @endif">
                                {{ ucfirst($application->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        @if ($application->admin_comment)
                            <div class="alert alert-secondary border-start border-4 border-secondary bg-light">
                                <h6 class="alert-heading fw-bold"><i class="bi bi-chat-left-text me-2"></i>Admin Feedback:</h6>
                                <p class="mb-0 text-muted">{{ $application->admin_comment }}</p>
                            </div>
                        @endif

                        <div class="row g-4 mt-1">
                            <div class="col-md-12">
                                <label class="text-muted small text-uppercase fw-bold mb-1">Your Motivation</label>
                                <p class="bg-light p-3 rounded text-dark mb-0">{{ $application->reason }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold mb-1">Expertise</label>
                                <div class="p-3 border rounded bg-white h-100">
                                    {{ $application->academic_strength }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold mb-1">Plan</label>
                                <div class="p-3 border rounded bg-white h-100">
                                    {{ $application->notes_plan }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- 4. APPLICATION FORM --}}
                @if (!$application || $application->status === 'rejected')
                    <div class="card-body p-4 p-md-5">
                        @if($application && $application->status === 'rejected')
                            <div class="alert alert-info mb-4">
                                <small>You may submit a new application below. Please address the admin feedback before reapplying.</small>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('provider.apply.submit') }}">
                            @csrf
                            
                            {{-- Motivation --}}
                            <div class="form-floating mb-3">
                                <textarea name="reason" style="height: 100px" 
                                    class="form-control @error('reason') is-invalid @enderror" 
                                    id="reasonInput" placeholder="Why do you want to join?">{{ old('reason') }}</textarea>
                                <label for="reasonInput">Motivation to become a provider</label>
                                <div class="form-text">Briefly explain why you want to share your notes.</div>
                                @error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row g-3 mb-3">
                                {{-- Academic Strength --}}
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <textarea name="academic_strength" style="height: 100px" 
                                            class="form-control @error('academic_strength') is-invalid @enderror" 
                                            id="academicInput" placeholder="E.g. Biology, Math">{{ old('academic_strength') }}</textarea>
                                        <label for="academicInput">Academic Strengths</label>
                                        @error('academic_strength') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                {{-- Content Type Selection --}}
                                <div class="col-md-6">
                                    <div class="form-floating h-100">
                                        <select name="content_type" class="form-select h-100" id="contentType" required>
                                            <option value="" disabled selected>Select type...</option>
                                            <option value="lecture_notes">Lecture Notes</option>
                                            <option value="tutorial_solutions">Tutorial Solutions</option>
                                            <option value="exam_revision">Exam Revision Materials</option>
                                            <option value="mixed">Mixed Content</option>
                                        </select>
                                        <label for="contentType">Material Type</label>
                                    </div>
                                </div>
                            </div>

                            {{-- Declaration --}}
                            <div class="bg-light p-3 rounded mb-4 border d-flex align-items-start">
                                <input class="form-check-input mt-1 me-3" type="checkbox" required id="declarationCheck">
                                <label class="form-check-label small text-secondary" for="declarationCheck">
                                    <strong>I pledge academic integrity.</strong> I confirm that all materials I upload will be original works created by me, factual, and accurate. I understand that plagiarism leads to an immediate ban.
                                </label>
                            </div>

                            <button class="btn btn-primary w-100 py-3 fw-bold shadow-sm hover-elevate">
                                Submit Application
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- 5. HISTORY: Collapsible Section (Less Intrusive) --}}
            @if (isset($allApplications) && $allApplications->count() > 1)
                <div class="accordion mt-4" id="historyAccordion">
                    <div class="accordion-item border-0 bg-transparent">
                        <h2 class="accordion-header" id="headingHistory">
                            <button class="accordion-button collapsed bg-white shadow-sm rounded text-muted" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHistory">
                                <small class="fw-bold">View Previous Applications ({{ $allApplications->count() - 1 }})</small>
                            </button>
                        </h2>
                        <div id="collapseHistory" class="accordion-collapse collapse" data-bs-parent="#historyAccordion">
                            <div class="accordion-body px-0 pt-3">
                                @foreach ($allApplications->skip(1) as $old)
                                    <div class="card border-0 shadow-sm mb-2">
                                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="badge bg-secondary mb-1">{{ $old->created_at->format('d M Y') }}</span>
                                                <div class="small text-muted text-truncate" style="max-width: 250px;">
                                                    {{ $old->admin_comment ?? 'No admin feedback.' }}
                                                </div>
                                            </div>
                                            <span class="badge {{ $old->status == 'approved' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucfirst($old->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection