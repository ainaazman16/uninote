@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between mb-4">
        <h3 class="fw-bold">Rating Analytics</h3>
        <a href="{{ route('provider.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            ← Back
        </a>
    </div>

    @forelse($notes as $note)
        <div class="card shadow-sm mb-4">
            <div class="card-body">

                <h5 class="fw-bold">{{ $note->title }}</h5>

                <p class="text-muted mb-2">
                    Average Rating:
                    <strong>
                        {{ number_format($note->ratings_avg_rating ?? 0, 2) }} ⭐
                    </strong>
                    ({{ $note->ratings_count }} reviews)
                </p>

                {{-- Chart --}}
                <canvas id="chart-{{ $note->id }}" height="120"></canvas>

            </div>
        </div>
    @empty
        <p class="text-muted">No notes found.</p>
    @endforelse

</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
@foreach($notes as $note)
    const ctx{{ $note->id }} = document.getElementById('chart-{{ $note->id }}');

    new Chart(ctx{{ $note->id }}, {
        type: 'bar',
        data: {
            labels: ['1⭐', '2⭐', '3⭐', '4⭐', '5⭐'],
            datasets: [{
                label: 'Ratings Count',
                data: [
                    {{ $note->ratings->where('rating', 1)->first()->total ?? 0 }},
                    {{ $note->ratings->where('rating', 2)->first()->total ?? 0 }},
                    {{ $note->ratings->where('rating', 3)->first()->total ?? 0 }},
                    {{ $note->ratings->where('rating', 4)->first()->total ?? 0 }},
                    {{ $note->ratings->where('rating', 5)->first()->total ?? 0 }},
                ],
                backgroundColor: '#0d6efd'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
@endforeach
</script>

@endsection

