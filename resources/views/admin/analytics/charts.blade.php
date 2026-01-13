<div class="row g-4">

    {{-- Users by Role --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Users by Role</h6>
                    <button class="btn btn-sm btn-outline-primary" onclick="downloadChart('userChart', 'users-by-role')">
                        <i class="bi bi-download"></i> Download
                    </button>
                </div>
                <canvas id="userChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Monthly Topups --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Wallet Top-Ups (Monthly)</h6>
                    <button class="btn btn-sm btn-outline-primary"
                        onclick="downloadChart('topupChart', 'monthly-topups')">
                        <i class="bi bi-download"></i> Download
                    </button>
                </div>
                <canvas id="topupChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Daily Topups --}}
    <div class="col-md-6 mt-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Wallet Top-Ups (Daily)</h6>
                    <button class="btn btn-sm btn-outline-primary"
                        onclick="downloadChart('dailyTopupChart', 'daily-topups')">
                        <i class="bi bi-download"></i> Download
                    </button>
                </div>
                <canvas id="dailyTopupChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Provider Earnings --}}
    <div class="col-md-6 mt-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Provider Earnings</h6>
                    <button class="btn btn-sm btn-outline-primary"
                        onclick="downloadChart('providerEarningsChart', 'provider-earnings')">
                        <i class="bi bi-download"></i> Download
                    </button>
                </div>
                <canvas id="providerEarningsChart"></canvas>
            </div>
        </div>
    </div>

</div>

<script>
    function downloadChart(canvasId, filename) {
        const canvas = document.getElementById(canvasId);
        const url = canvas.toDataURL('image/png');
        const link = document.createElement('a');
        link.download = filename + '-' + new Date().toISOString().split('T')[0] + '.png';
        link.href = url;
        link.click();
    }
</script>
