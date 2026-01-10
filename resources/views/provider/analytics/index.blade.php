@extends('layouts.app')

@section('content')
    <h2 class="fw-bold mb-4">Earnings Analytics</h2>

    <div class="row g-4">

        {{-- Total Earnings --}}
        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h6 class="text-muted">Total Earnings</h6>
                    <h3 class="fw-bold text-success">
                        {{ number_format($totalEarnings, 2) }} credits
                    </h3>
                </div>
            </div>
        </div>

        {{-- Subscribers --}}
        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h6 class="text-muted">Subscribers</h6>
                    <h3 class="fw-bold">
                        {{ $totalSubscribers }}
                    </h3>
                </div>
            </div>
        </div>

        {{-- Downloads --}}
        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h6 class="text-muted">Total Downloads</h6>
                    <h3 class="fw-bold">
                        {{ $totalDownloads }}
                    </h3>
                </div>
            </div>
        </div>

    </div>

    {{-- Monthly Earnings Chart --}}
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Monthly Earnings</h5>
            <canvas id="earningsChart"></canvas>
        </div>
    </div>

    {{-- Top Notes --}}
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Top Performing Notes</h5>

            <table class="table">
                <thead>
                    <tr>
                        <th>Note</th>
                        <th>Downloads</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topNotes as $note)
                        <tr>
                            <td>{{ $note->title }}</td>
                            <td>{{ $note->download_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('earningsChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthLabels ?? []) !!},
                datasets: [{
                    label: 'Earnings',
                    data: {!! json_encode($monthTotals ?? []) !!},
                    borderWidth: 2,
                    fill: false
                }]
            }
        });
    </script>
@endsection
