<div class="row g-4">

    {{-- Users by Role --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Users by Role</h6>
                <canvas id="userChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Monthly Topups --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Wallet Top-Ups (Monthly)</h6>
                <canvas id="topupChart"></canvas>
            </div>
        </div>
    </div>
    
    {{-- Daily Topups --}}
    <div class="col-md-6 mt-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Wallet Top-Ups (Daily)</h6>
                <canvas id="dailyTopupChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Provider Earnings --}}
    <div class="col-md-6 mt-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Provider Earnings</h6>
                <canvas id="providerEarningsChart"></canvas>
            </div>
        </div>
    </div>

</div>
