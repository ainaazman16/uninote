@extends('layouts.app')

@section('content')
    <div class="container py-4">

        <h3 class="fw-bold mb-4">Ratings & Feedback Moderation</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($ratings->count())
            <div class="card shadow-sm border-0 rounded-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Student</th>
                                <th>Provider</th>
                                <th>Rating</th>
                                <th>Feedback</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ratings as $rating)
                                <tr>
                                    <td>
                                        {{ $rating->created_at->format('d M Y') }}<br>
                                        <small class="text-muted">
                                            {{ $rating->created_at->format('h:i A') }}
                                        </small>
                                    </td>

                                    <td>{{ $rating->student?->name ?? 'Unknown' }}</td>
                                    <td>{{ $rating->note?->provider?->user?->name ?? 'Provider' }}</td>

                                    <td>⭐ {{ $rating->rating }}/5</td>

                                    <td style="max-width: 300px">
                                        {{ $rating->comment ?? '-' }}
                                    </td>

                                    <td class="text-end">
                                        <form action="{{ route('admin.ratings.destroy', $rating) }}" method="POST"
                                            onsubmit="return confirm('Delete this feedback?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3">
                {{ $ratings->links() }}
            </div>
        @else
            <p class="text-muted">No feedback found.</p>
        @endif

    </div>
@endsection
