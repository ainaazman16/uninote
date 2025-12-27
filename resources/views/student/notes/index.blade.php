@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">Browse Notes</h2>

    {{-- Filter --}}
    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <select name="subject_id" class="form-select" onchange="this.form.submit()">
                    <option value="">All Subjects</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}"
                            {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
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
                            @if($note->is_premium)
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
                    </div>

                    {{-- Action Button --}}
                    <div class="card-footer bg-white border-0 text-center">

                        @if($note->is_premium)
                            @if($note->isSubscribedBy(auth()->user()))
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
