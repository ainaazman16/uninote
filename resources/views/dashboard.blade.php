@extends('layouts.app')

@section('content')

<style>
    /* === Clean White Background === */
    body {
        background: #f8f9fa !important; /* Light gray / almost white */
    }

    /* Sidebar styles */
    .sidebar {
        height: 100vh;
        width: 250px;
        background: #ffffff;
        border-right: 1px solid #e5e5e5;
        padding: 20px;
        transition: all 0.3s ease;
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
    }

    .sidebar.collapsed {
        width: 70px;
    }

    .sidebar .menu-text {
        opacity: 1;
        transition: opacity 0.25s ease;
        white-space: nowrap;
    }

    .sidebar.collapsed .menu-text {
        opacity: 0;
    }

    /* Toggle button */
    .toggle-btn {
        cursor: pointer;
        font-size: 24px;
        margin-bottom: 20px;
        color: #333;
    }

    /* Main content */
    .main-content {
        padding: 40px;
        margin-left: 250px;
        transition: margin-left 0.3s ease;
    }

    .collapsed + .main-content {
        margin-left: 70px !important;
    }

    /* Dashboard cards */
    .dashboard-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        color: #333;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
</style>

<div class="d-flex">

    <!-- =============== SIDEBAR =============== -->
    <div id="sidebar" class="sidebar">

        <div class="toggle-btn" onclick="toggleSidebar()">
            ☰
        </div>

        <ul class="list-unstyled mt-3">
            <li class="mb-3">
                <a href="{{ route('student.notes.index') }}" class="text-decoration-none text-dark d-flex align-items-center">
                    <span class="me-2">📚</span>
                    <span class="menu-text">Browse Notes</span>
                </a>
            </li>

            <li class="mb-3">
                <a href="#" class="text-decoration-none text-dark d-flex align-items-center">
                    <span class="me-2">✨</span>
                    <span class="menu-text">My Subscriptions</span>
                </a>
            </li>

            <li class="mb-3">
                <a href="{{ route('provider.apply') }}" 
                   class="text-decoration-none text-dark d-flex align-items-center">
                    <span class="me-2">📝</span>
                    <span class="menu-text">Become Provider</span>
                </a>
            </li>
            <li class="mb-3">
                        <a href="{{ route('student.transactions.index') }}"
                        class="text-decoration-none text-dark d-flex align-items-center">
                            <span class="me-2">💳</span>
                            <span class="menu-text">Transaction History</span>
                        </a>
                    </li>
                    <li class="mb-3">
            <a href="{{ route('wallet.index') }}" class="text-decoration-none text-dark d-flex align-items-center">
                <span class="me-2">💰</span>
                <span class="menu-text">My Wallet</span>
            </a>
        </li>



        </ul>

    </div>

    <!-- =============== MAIN CONTENT =============== -->
    <div id="mainContent" class="main-content flex-grow-1">

        <h2 class="fw-bold mb-4">Welcome, {{ Auth::user()->name }} 👋</h2>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="dashboard-card">
                    <h5>Total Notes Accessed</h5>
                    <h2 class="fw-bold">12</h2>
                </div>
            </div>

            <div class="col-md-4">
    <div class="dashboard-card">
        <h5>Subscribed Providers</h5>
        <h2 class="fw-bold">{{ $totalSubscriptions }}</h2>

       @if($subscriptions->count())
    <ul class="list-unstyled mt-2">
        @foreach($subscriptions as $sub)
            <li class="mb-2">
                <strong>{{ $sub->provider->name }}</strong><br>

                <small class="text-muted">
                    Expires on {{ $sub->expiresAt()->format('d M Y') }}
                </small>

                @if($sub->isActive())
                    <span class="badge bg-success ms-2">Active</span>
                @else
                    <span class="badge bg-danger ms-2">Expired</span>
                @endif

                <div class="mt-1">
                    <a href="{{ route('student.providers.show', $sub->provider) }}"
                       class="btn btn-sm btn-outline-primary">
                        View Provider
                    </a>
                </div>
            </li>
        @endforeach
    </ul>
@else
    <p class="text-muted small mt-2">You haven’t subscribed to any provider yet.</p>
@endif

    </div>
</div>


            <div class="col-md-4">
                <div class="dashboard-card">
                    <h5>Recent Activity</h5>
                    <h2 class="fw-bold">5</h2>
                </div>
            </div>

        </div>
        <hr class="my-4">

<h4 class="fw-bold mb-3">Find Providers</h4>

<form action="{{ route('student.providers.search') }}" method="GET" class="mb-4">
    <div class="input-group">
        <input type="text"
               name="query"
               class="form-control"
               placeholder="Search by provider name or email"
               value="{{ request('query') }}">
        <button class="btn btn-dark">Search</button>
    </div>
</form>

@if(isset($providers) && $providers->count())
    <div class="row g-3">
        @foreach($providers as $provider)
            <div class="col-md-4">
                <div class="dashboard-card">
                    <h5>{{ $provider->name }}</h5>
                    <p class="text-muted">{{ $provider->email }}</p>

                    <a href="{{ route('providers.show', $provider->id) }}"
                       class="btn btn-outline-primary btn-sm">
                        View Profile
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@elseif(request()->has('query'))
    <p class="text-muted">No providers found.</p>
@endif


    </div>

</div>

<script>
    function toggleSidebar() {
        let sidebar = document.getElementById('sidebar');
        let mainContent = document.getElementById('mainContent');

        sidebar.classList.toggle('collapsed');

        if (sidebar.classList.contains('collapsed')) {
            mainContent.style.marginLeft = "70px";
        } else {
            mainContent.style.marginLeft = "250px";
        }
    }
</script>

@endsection
