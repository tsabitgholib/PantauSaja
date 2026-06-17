<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PantauSaja') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        * {
            font-family: 'Outfit', sans-serif;
            box-sizing: border-box;
        }
        body {
            background: linear-gradient(135deg, #FFF5E1 0%, #FFE4C4 100%);
            color: #0f172a;
            margin: 0;
        }
        .neo-card {
            background: #ffffff;
            border: 4px solid #0f172a;
            border-radius: 20px;
            box-shadow: 10px 10px 0px #0f172a;
            transition: all 0.2s ease;
        }
        .neo-btn {
            background: #0f172a;
            color: white;
            border: 4px solid #0f172a;
            border-radius: 16px;
            padding: 14px 28px;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 6px 6px 0px #0f172a;
            cursor: pointer;
            white-space: nowrap;
        }
        .neo-btn:hover {
            background: white;
            color: #0f172a;
        }
        .neo-btn-primary {
            background: #6366f1;
            border-color: #6366f1;
            box-shadow: 6px 6px 0px #4338ca;
        }
        .neo-btn-primary:hover {
            background: white;
            color: #6366f1;
        }
        .neo-btn-success {
            background: #22c55e;
            border-color: #22c55e;
            box-shadow: 6px 6px 0px #15803d;
        }
        .neo-btn-danger {
            background: #ef4444;
            border-color: #ef4444;
            box-shadow: 6px 6px 0px #b91c1c;
        }
        .neo-btn-success:hover, .neo-btn-danger:hover {
            background: white;
            color: #0f172a;
        }
        .neo-input, .neo-select {
            border: 4px solid #0f172a;
            border-radius: 14px;
            padding: 12px 16px;
            font-size: 15px;
            font-weight: 600;
            width: 100%;
        }
        .neo-input:focus, .neo-select:focus {
            border-color: #6366f1;
            box-shadow: 6px 6px 0px #6366f1;
            outline: none;
        }
        .neo-badge {
            background: #fef08a;
            color: #0f172a;
            border: 3px solid #0f172a;
            padding: 4px 10px;
            font-weight: 700;
            font-size: 12px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }
        .neo-badge-success {
            background: #bbf7d0;
            border-color: #15803d;
            color: #15803d;
        }
        .neo-badge-danger {
            background: #fecaca;
            border-color: #b91c1c;
            color: #b91c1c;
        }
        .neo-badge-primary {
            background: #c7d2fe;
            border-color: #4338ca;
            color: #4338ca;
        }
        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #FFF7ED 0%, #FFF5E1 100%);
            border-right: 5px solid #0f172a;
        }
        .sidebar .nav-link {
            color: #0f172a;
            padding: 16px 24px;
            margin: 8px 16px;
            font-weight: 700;
            border: 3px solid transparent;
            border-radius: 14px;
            font-size: 15px;
        }
        .sidebar .nav-link:hover {
            background: white;
            border: 3px solid #0f172a;
        }
        .sidebar .nav-link.active {
            background: white;
            border: 4px solid #0f172a;
            box-shadow: 6px 6px 0px #0f172a;
        }
        /* Mobile Sidebar */
        .mobile-sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
            display: none;
        }
        .mobile-sidebar-overlay.active {
            display: block;
        }
        .mobile-sidebar {
            position: fixed;
            top: 0;
            right: -300px;
            width: 300px;
            height: 100vh;
            background: linear-gradient(180deg, #FFF7ED 0%, #FFF5E1 100%);
            border-left: 5px solid #0f172a;
            z-index: 1050;
            overflow-y: auto;
            transition: right 0.3s ease;
        }
        .mobile-sidebar.active {
            right: 0;
        }
        .mobile-sidebar-toggle {
            position: fixed;
            top: 16px;
            right: 16px;
            width: 50px;
            height: 50px;
            border: 4px solid #0f172a;
            background: white;
            border-radius: 16px;
            box-shadow: 6px 6px 0px #0f172a;
            color: #0f172a;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            z-index: 1030;
        }
        /* Back Button */
        .back-btn {
            width: 48px;
            height: 48px;
            border: 3px solid #0f172a;
            background: white;
            box-shadow: 4px 4px 0px #0f172a;
            color: #0f172a;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 18px;
        }
        .stats-card-blue { background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-color: #1d4ed8; box-shadow: 10px 10px 0px #1d4ed8; }
        .stats-card-green { background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border-color: #15803d; box-shadow: 10px 10px 0px #15803d; }
        .stats-card-red { background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-color: #b91c1c; box-shadow: 10px 10px 0px #b91c1c; }
        .stats-card-purple { background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%); border-color: #7e22ce; box-shadow: 10px 10px 0px #7e22ce; }
        
        /* Modal Styles */
        .modal-backdrop {
            z-index: 1040;
        }
        .modal {
            z-index: 1050;
        }
        .modal-content {
            background: white !important;
            border: 4px solid #0f172a !important;
            border-radius: 20px !important;
            box-shadow: 10px 10px 0px #0f172a !important;
        }
        
        /* Canvas & Chart Styles */
        canvas {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
        }
        
        /* Mobile-specific styles */
        @media (max-width: 767.98px) {
            .neo-card {
                box-shadow: 6px 6px 0px #0f172a;
                border-radius: 16px;
                border-width: 3px;
            }
            .neo-btn {
                padding: 10px 16px;
                font-size: 14px;
                border-width: 3px;
                box-shadow: 4px 4px 0px #0f172a;
            }
            .p-3, .p-4, .p-5 {
                padding: 1rem !important;
            }
            .mobile-content {
                padding-top: 80px;
            }
            canvas {
                max-height: 300px !important;
            }
        }
    </style>
</head>
<body>
    @auth
    <!-- Desktop Sidebar (hidden on mobile) -->
    <div class="container-fluid d-none d-md-flex">
        <div class="row flex-nowrap">
            <nav class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-4 border-bottom border-4 border-dark">
                    <h3 class="fw-black mb-0">
                        <i class="fas fa-wallet me-2" style="color: #6366f1;"></i>PantauSaja
                    </h3>
                </div>
                <div class="py-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-home me-2" style="width:24px;"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('accounts.*') ? 'active' : '' }}" href="{{ route('accounts.index') }}">
                                <i class="fas fa-credit-card me-2" style="width:24px;"></i> Akun
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">
                                <i class="fas fa-exchange-alt me-2" style="width:24px;"></i> Transaksi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('budgets.*') ? 'active' : '' }}" href="{{ route('budgets.index') }}">
                                <i class="fas fa-piggy-bank me-2" style="width:24px;"></i> Budget
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('targets.*') ? 'active' : '' }}" href="{{ route('targets.index') }}">
                                <i class="fas fa-bullseye me-2" style="width:24px;"></i> Target
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('debts.*') ? 'active' : '' }}" href="{{ route('debts.index') }}">
                                <i class="fas fa-hand-holding-usd me-2" style="width:24px;"></i> Utang Piutang
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('subscriptions.*') ? 'active' : '' }}" href="{{ route('subscriptions.index') }}">
                                <i class="fas fa-sync-alt me-2" style="width:24px;"></i> Langganan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                                <i class="fas fa-chart-line me-2" style="width:24px;"></i> Laporan
                            </a>
                        </li>
                    </ul>
                </div>
                <hr class="border-4 border-dark mx-3 my-3">
                <div class="px-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-circle me-2" style="width:24px;"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="nav-link" href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="fas fa-sign-out-alt me-2" style="width:24px;"></i> Logout
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <!-- Mobile Sidebar Toggle -->
    <button class="mobile-sidebar-toggle d-md-none" id="mobileSidebarToggle">
        <i class="fas fa-bars"></i>
    </button>
    <!-- Mobile Sidebar Overlay -->
    <div class="mobile-sidebar-overlay d-md-none" id="mobileSidebarOverlay"></div>
    <!-- Mobile Sidebar -->
    <nav class="mobile-sidebar d-md-none" id="mobileSidebar">
        <div class="p-4 border-bottom border-4 border-dark">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="fw-black mb-0">
                    <i class="fas fa-wallet me-2" style="color: #6366f1;"></i>PantauSaja
                </h3>
                <button class="back-btn" id="mobileSidebarClose">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="py-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" onclick="closeMobileSidebar()">
                        <i class="fas fa-home me-2" style="width:24px;"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('accounts.*') ? 'active' : '' }}" href="{{ route('accounts.index') }}" onclick="closeMobileSidebar()">
                        <i class="fas fa-credit-card me-2" style="width:24px;"></i> Akun
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}" href="{{ route('transactions.index') }}" onclick="closeMobileSidebar()">
                        <i class="fas fa-exchange-alt me-2" style="width:24px;"></i> Transaksi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('budgets.*') ? 'active' : '' }}" href="{{ route('budgets.index') }}" onclick="closeMobileSidebar()">
                        <i class="fas fa-piggy-bank me-2" style="width:24px;"></i> Budget
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('targets.*') ? 'active' : '' }}" href="{{ route('targets.index') }}" onclick="closeMobileSidebar()">
                        <i class="fas fa-bullseye me-2" style="width:24px;"></i> Target
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('debts.*') ? 'active' : '' }}" href="{{ route('debts.index') }}" onclick="closeMobileSidebar()">
                        <i class="fas fa-hand-holding-usd me-2" style="width:24px;"></i> Utang Piutang
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('subscriptions.*') ? 'active' : '' }}" href="{{ route('subscriptions.index') }}" onclick="closeMobileSidebar()">
                        <i class="fas fa-sync-alt me-2" style="width:24px;"></i> Langganan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}" onclick="closeMobileSidebar()">
                        <i class="fas fa-chart-line me-2" style="width:24px;"></i> Laporan
                    </a>
                </li>
            </ul>
        </div>
        <hr class="border-4 border-dark mx-3 my-3">
        <div class="px-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.edit') }}" onclick="closeMobileSidebar()">
                        <i class="fas fa-user-circle me-2" style="width:24px;"></i> Profile
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="nav-link" href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fas fa-sign-out-alt me-2" style="width:24px;"></i> Logout
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <!-- SINGLE MAIN CONTENT for BOTH DESKTOP & MOBILE! -->
    <div class="d-md-none mobile-content px-3 pb-5">
        @if(session('success'))
            <div class="alert alert-success neo-card mb-4 p-3 border-3">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger neo-card mb-4 p-3 border-3">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif
        @yield('content')
    </div>
    
    <main class="d-none d-md-block col-md-9 ms-sm-auto col-lg-10 px-md-5 py-5" style="margin-left: 25%;">
        @if(session('success'))
            <div class="alert alert-success neo-card mb-4 p-3 border-3">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger neo-card mb-4 p-3 border-3">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </main>
    
    @endauth

    @guest
    <div class="container">
        @yield('content')
    </div>
    @endguest

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Mobile Sidebar
        const mobileSidebar = document.getElementById('mobileSidebar');
        const mobileSidebarOverlay = document.getElementById('mobileSidebarOverlay');
        const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
        const mobileSidebarClose = document.getElementById('mobileSidebarClose');

        function openMobileSidebar() {
            mobileSidebar.classList.add('active');
            mobileSidebarOverlay.classList.add('active');
        }

        function closeMobileSidebar() {
            mobileSidebar.classList.remove('active');
            mobileSidebarOverlay.classList.remove('active');
        }

        if(mobileSidebarToggle) {
            mobileSidebarToggle.addEventListener('click', openMobileSidebar);
        }
        if(mobileSidebarClose) {
            mobileSidebarClose.addEventListener('click', closeMobileSidebar);
        }
        if(mobileSidebarOverlay) {
            mobileSidebarOverlay.addEventListener('click', closeMobileSidebar);
        }
        window.closeMobileSidebar = closeMobileSidebar;

        // Back button
        document.querySelectorAll('.back-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if(!this.id || this.id !== 'mobileSidebarClose') {
                    window.history.back();
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
