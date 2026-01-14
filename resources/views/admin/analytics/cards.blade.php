<div class="row g-4 mb-4">

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h6 class="text-muted">Total Users</h6>
                <h3 class="fw-bold">{{ array_sum($userData) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h6 class="text-muted">Total Subscriptions</h6>
                <h3 class="fw-bold">{{ $subscriptionCount }}</h3>
            </div>
        </div>
    </div>

    {{-- <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h6 class="text-muted">Total Universities</h6>
                <h3 class="fw-bold">{{  }}</h3>
            </div>
        </div>
    </div> --}}

</div>
