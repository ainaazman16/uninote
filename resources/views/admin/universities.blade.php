@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm me-3">
            ← Back to Dashboard
        </a>
    </div>
    <div class="container py-4">
        <h2 class="fw-bold mb-4">University List</h2>
        <div class="mb-3">
            <span class="badge bg-primary fs-6">Total: {{ $universities->count() }} universities</span>
        </div>
        <div class="card">
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @foreach ($universities as $university)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $university }}
                            <span class="badge bg-secondary">{{ $universityCounts[$university] ?? 0 }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">University Distribution Chart</h5>
                <canvas id="universityChart" height="120"></canvas>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('universityChart').getContext('2d');
                const universityChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($universities->toArray()) !!},
                        datasets: [{
                            label: 'User Count',
                            data: {!! json_encode(array_values($universities->map(fn($u) => $universityCounts[$u] ?? 0)->toArray())) !!},
                            backgroundColor: 'rgba(13, 110, 253, 0.7)',
                            borderColor: 'rgba(13, 110, 253, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                precision: 0
                            }
                        }
                    }
                });
            });
        </script>
    </div>
@endsection
