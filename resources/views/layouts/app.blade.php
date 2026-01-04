<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'UniNote' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa; /* Light grey background for better contrast */
        }
        .navbar-brand {
            letter-spacing: -0.5px;
        }
        .nav-link {
            font-weight: 500;
            color: #4b5563;
        }
        .nav-link:hover {
            color: #0d6efd;
        }
        /* Soft shadow for the navbar */
        .navbar-shadow {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
    </style>
    
    {{-- Place for page-specific CSS --}}
    @yield('head')
</head>
<body class="d-flex flex-column min-vh-100">

    @if(request()->is('login') || request()->is('register'))

        <nav class="navbar navbar-expand-lg navbar-dark position-absolute top-0 w-100 p-4" style="z-index: 10;">
            <div class="container d-flex justify-content-center">
                <a class="navbar-brand fw-bold fs-3 text-white" href="{{ url('/') }}">
                    <i class="bi bi-journal-bookmark-fill me-2"></i>UniNote
                </a>
            </div>
        </nav>

    @else

        <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top navbar-shadow py-3">
            <div class="container">
                
                <a class="navbar-brand fw-bold fs-4 text-primary" href="{{ url('/') }}">
                    <i class="bi bi-journal-bookmark-fill me-2"></i>UniNote
                </a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarMenu">
                    <ul class="navbar-nav ms-auto align-items-center gap-2">

                        @auth
                            {{-- STUDENT ROLE SPECIFICS --}}
                            @if(Auth::user()->role == 'student')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('provider.apply') }}">Become Provider</a>
                                </li>
                                
                                {{-- Wallet Badge --}}
                                <li class="nav-item me-2">
                                    <div class="d-inline-flex align-items-center bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold border border-success border-opacity-25">
                                        <i class="bi bi-wallet2 me-2"></i>
                                        <span>{{ number_format(auth()->user()->wallet->balance ?? 0, 2) }}</span>
                                    </div>
                                </li>
                            @endif

                            {{-- USER DROPDOWN (Consolidates Profile, Admin Links & Logout) --}}
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 35px; height: 35px;">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <span class="fw-semibold">{{ Auth::user()->name }}</span>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 mt-2" style="min-width: 220px;">
                                    
                                    <li><h6 class="dropdown-header text-uppercase small text-muted">Account</h6></li>
                                    
                                    {{-- Student: My Profile --}}
                                    @if(Auth::user()->role == 'student')
                                        <li>
                                            <a class="dropdown-item rounded-2 py-2" href="{{ route('profile.show', auth()->id()) }}">
                                                <i class="bi bi-person me-2 text-secondary"></i> My Profile
                                            </a>
                                        </li>
                                    @endif

                                    {{-- ADMIN ROLE SPECIFICS --}}
                                    @if(Auth::user()->role == 'admin')
                                        <li><hr class="dropdown-divider"></li>
                                        <li><h6 class="dropdown-header text-uppercase small text-muted text-danger">Admin Panel</h6></li>
                                        
                                        <li>
                                            <a class="dropdown-item rounded-2 py-2" href="{{ route('admin.dashboard') }}">
                                                <i class="bi bi-speedometer2 me-2 text-danger"></i> Dashboard
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item rounded-2 py-2" href="{{ route('admin.provider.applications') }}">
                                                <i class="bi bi-file-earmark-text me-2 text-danger"></i> Applications
                                            </a>
                                        </li>
                                        
                                    @endif

                                    {{-- PROVIDER ROLE SPECIFICS --}}
                                    @if (Auth::user()->role == 'provider')
                                        <li>
                                            <a class="dropdown-item rounded-2 py-2" href="{{ route('profile.show', auth()->id()) }}">
                                                <i class="bi bi-person me-2 text-secondary"></i> My Profile
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item rounded-2 py-2" href="{{ route('provider.notes.index') }}">
                                                <i class="bi bi-journal-text me-2 text-secondary"></i> My Notes
                                            </a>
                                        </li>

                                    @endif

                                    <li><hr class="dropdown-divider"></li>

                                    {{-- Logout --}}
                                    <li>
                                        <a class="dropdown-item rounded-2 py-2 text-danger fw-semibold" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                           <i class="bi bi-box-arrow-right me-2"></i> Log Out
                                        </a>
                                        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endauth

                        {{-- GUEST LINKS --}}
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm" href="{{ route('register') }}">Get Started</a>
                            </li>
                        @endguest

                    </ul>
                </div>
            </div>
        </nav>
    @endif

    <main class="flex-grow-1">
        @if(request()->is('login') || request()->is('register'))
            {{-- No container for auth pages (allows full-screen backgrounds) --}}
            @yield('content')
        @else
            {{-- Normal pages use container with spacing --}}
            <div class="container py-4 mb-5">
                @yield('content')
            </div>
        @endif
    </main>

    @if(!request()->is('login') && !request()->is('register'))
        <footer class="bg-white py-4 mt-auto border-top">
            <div class="container text-center text-muted small">
                &copy; {{ date('Y') }} UniNote. All rights reserved.
            </div>
        </footer>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>