@extends('layouts.app')

@section('content')

<div class="col-md-8 mx-auto">

    <h2 class="mb-4">Upload Note</h2>

    <form action="{{ route('notes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input name="title" type="text" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Subject</label>
            <select name="subject_id" class="form-control">
                @foreach ($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">File</label>
            <input type="file" name="file" class="form-control">
        </div>

        <button class="btn btn-primary">Upload</button>

    </form>
</div>

@endsection
