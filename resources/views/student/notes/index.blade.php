@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm me-3">
            ← Back to Dashboard
        </a>
    </div>
    <div class="container py-4">

        <h2 class="mb-4">Browse Notes</h2>

        {{-- Filter --}}
        <form method="GET" class="mb-4">
            <div class="row g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control"
                            placeholder="Search by title, description, subject, or provider..."
                            value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="subject_id" class="form-select">
                        <option value="">All Subjects</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}"
                                {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="university" class="form-select">
                        <option value="">All Universities</option>
                        @php
                            $universities = [
                                'Universiti Teknologi Malaysia',
                                'Universiti Teknikal Malaysia Melaka',
                                'Universiti Kebangsaan Malaysia',
                            ];
                        @endphp
                        @foreach ($universities as $university)
                            <option value="{{ $university }}"
                                {{ request('university') == $university ? 'selected' : '' }}>
                                {{ $university }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </div>
            </div>
            @if (request('search') || request('subject_id'))
                <div class="mt-2">
                    <a href="{{ route('student.notes.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Clear Filters
                    </a>
                </div>
            @endif
        </form>

        {{-- Notes --}}
        <div class="row">
            @forelse($notes as $note)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">

                        <div class="card-body">
                            {{-- Title + Premium Indicator --}}
                            <h5 class="fw-bold">
                                {{ $note->title }}
                                @if ($note->is_premium)
                                    <span class="badge bg-warning text-dark ms-1">Premium</span>
                                @endif
                            </h5>

                            {{-- Subject --}}
                            <p class="text-muted small mb-1">
                                Subject: {{ $note->subject->name }}
                            </p>

                            {{-- Provider --}}
                            <p class="text-muted small">
                                Provided by:
                                @if ($note->provider && $note->provider->user)
                                    <a href="{{ route('student.providers.show', $note->provider->user) }}">
                                        {{ $note->provider->user->name }}
                                    </a>
                                @else
                                    <span class="text-muted">Unknown Provider</span>
                                @endif
                            </p>


                            {{-- Description --}}
                            <p class="small">
                                {{ Str::limit($note->description, 100) }}
                            </p>

                            {{-- Rating stars --}}
                            @php
                                $avg = round($note->ratings->avg('rating'), 1);
                                $count = $note->ratings->count();
                            @endphp

                            @if ($count > 0)
                                <div class="small text-warning">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($avg))
                                            ★
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                    <span class="text-muted">
                                        ({{ $avg }}/5 · {{ $count }})
                                    </span>
                                </div>
                            @else
                                <div class="small text-muted">
                                    No ratings yet
                                </div>
                            @endif
                        </div>


                        {{-- Action Button --}}
                        <div class="card-footer bg-white border-0 text-center">

                            @if ($note->is_premium)
                                @if ($note->isSubscribedBy(auth()->user()))
                                    <a href="{{ route('student.notes.show', $note->id) }}"
                                        class="btn btn-success btn-sm w-100">
                                        View Note
                                    </a>
                                @else
                                    <a href="{{ route('student.providers.show', $note->provider->user) }}"
                                        class="btn btn-warning btn-sm w-100">
                                        Subscribe to Access
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('student.notes.show', $note->id) }}"
                                    class="btn btn-primary btn-sm w-100">
                                    View Note
                                </a>
                            @endif

                        </div>

                    </div>
                </div>
            @empty
                <p>No notes available.</p>
            @endforelse
        </div>

    </div>
@endsection
