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
                            <h5>{{ $note->title }}</h5>
                            <p class="text-muted small">
                                Subject: {{ $note->subject->name }} <br>
                                Provided by:@if ($note->provider && $note->provider->user)
                                                <a href="{{ route('student.providers.show', $note->provider->user) }}">
                                                    {{ $note->provider->user->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">Unknown Provider</span>
                                            @endif


                            @if ($note->is_premium)
                                <span class="badge bg-warning">Premium</span>
                            @else
                                <span class="badge bg-success">Free</span>
                            @endif
                        </div>

                        <div class="card-footer bg-white">
                            <a href="{{ route('student.notes.show', $note->id) }}" class="text-decoration-none text-dark">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body">
                                        <h5>{{ $note->title }}</h5>
                                        <p class="text-muted small">
                                            {{ Str::limit($note->description, 100) }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <p>No notes available.</p>
            @endforelse
        </div>

    </div>
@endsection
