@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="mb-4">
        <h2 class="fw-bold">{{ $note->title }}</h2>

        <p class="text-muted">
            Subject: {{ $note->subject->name ?? 'N/A' }} <br>
            Provider: {{ optional($note->provider?->user)->name ?? 'Unknown' }}
        </p>

        <p>{{ $note->description }}</p>
    </div>

    {{-- NOTE PREVIEW --}}
    @if($note->is_premium)
    <span class="badge bg-warning text-dark mb-2">🔒 Premium Note</span>
@endif

<h3>{{ $note->title }}</h3>

<p class="text-muted">{{ $note->description }}</p>

<a href="{{ route('student.notes.download', $note->id) }}"
   class="btn btn-primary">
    Download Note
</a>

    @if(Str::endsWith($note->file_path, '.pdf'))
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Preview</h5>

                <iframe
                    src="{{ asset('storage/'.$note->file_path) }}#page=1"
                    width="100%"
                    height="600px"
                    style="border:none;">
                </iframe>

                <p class="text-muted small mt-2">
                    Preview shows first page only.
                </p>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            Preview not available for this file type.
        </div>
    @endif

</div>
@endsection
