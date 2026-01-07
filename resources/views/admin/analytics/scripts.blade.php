<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// USERS CHART
new Chart(document.getElementById('userChart'), {
    type: 'bar',
    data: {
        labels: @json($userLabels),
        datasets: [{
            label: 'Users',
            data: @json($userData),
        }]
    }
});

// TOPUP CHART
new Chart(document.getElementById('topupChart'), {
    type: 'line',
    data: {
        labels: @json($months),
        datasets: [{
            label: 'Top-Ups',
            data: @json($topupData),
            tension: 0.4,
            fill: true
        }]
    }
});

// DAILY TOPUP CHART
new Chart(document.getElementById('dailyTopupChart'), {
    type: 'line',
    data: {
        labels: @json($days),
        datasets: [{
            label: 'Daily Top-Ups',
            data: @json($dailyTopupData),
            tension: 0.4,
            fill: true
        }]
    }
});

// PROVIDER EARNINGS CHART
new Chart(document.getElementById('providerEarningsChart'), {
    type: 'bar',
    data: {
        labels: @json($months),
        datasets: [{
            label: 'Earnings',
            data: @json($providerEarnings),
        }]
    }
});
</script>
