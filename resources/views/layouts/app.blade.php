<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ ucfirst(Auth::user()->role) }} Panel | Dalia Coffee</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #d7ccc8, #a1887f);
            margin: 0;
        }

        .sidebar {
            width: 260px;
            min-height: 100vh;
            background-color: #4e342e;
            padding: 30px 20px;
            color: #fff8f0;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
        }

        .sidebar h4 {
            font-weight: 600;
            color: #fff8f0;
        }

        .nav-link {
            transition: all 0.2s ease;
            padding: 10px 15px;
            border-radius: 10px;
            color: #fff8f0;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-link i {
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }

        .nav-link:hover {
            background-color: #6d4c41;
        }

        .nav-link.active {
            background-color: #fff8f0;
            color: #4e342e !important;
            font-weight: 600;
        }

        .logout-btn {
            position: absolute;
            bottom: 30px;
            left: 20px;
            right: 20px;
        }

        .logout-btn button {
            background-color: transparent;
            border: 2px solid #fff8f0;
            color: #fff8f0;
            font-weight: 500;
        }

        .logout-btn button:hover {
            background-color: #fff8f0;
            color: #4e342e;
        }

        .main-content {
            background-color: #fff8f0;
            padding: 40px;
            flex-grow: 1;
            min-height: 100vh;
        }

        .nav .nav-item {
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: static;
                width: 100%;
                height: auto;
                box-shadow: none;
            }

            .logout-btn {
                position: relative;
                bottom: auto;
                left: auto;
                right: auto;
                margin-top: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="d-flex flex-wrap">
        <!-- Sidebar -->
        <aside class="sidebar position-relative">
            <!-- LOGO -->
            <div class="text-center mb-2">
                <img src="{{ asset('images/dalia-coffee2.png') }}" alt="Logo Dalia Coffee" style="max-width: 200px;">
            </div>

            <h4 class="mb-4">{{ ucfirst(Auth::user()->role) }} Dashboard</h4>
            <hr class="text-light mb-4">

            <!-- Menu berdasarkan role -->
            <ul class="nav flex-column">
                @if(Auth::user()->role === 'owner')
                    <li class="nav-item">
                        <a href="{{ route('karyawan.index') }}" class="nav-link {{ Route::is('karyawan.index') ? 'active' : '' }}">
                            <i class="fas fa-users"></i> Karyawan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('categories.index') }}" class="nav-link {{ Route::is('categories.index') ? 'active' : '' }}">
                            <i class="fas fa-folder-open"></i> Kategori
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('menu.index') }}" class="nav-link {{ Route::is('menu.index') ? 'active' : '' }}">
                            <i class="fas fa-utensils"></i> Menu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('ingredients.index') }}" class="nav-link {{ Route::is('ingredients.index') ? 'active' : '' }}">
                            <i class="fas fa-leaf"></i> Bahan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('stocks.index') }}" class="nav-link {{ Route::is('stocks.index') ? 'active' : '' }}">
                            <i class="fas fa-boxes-stacked"></i> Stok Bahan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('orders.report') }}" class="nav-link {{ Route::is('orders.report') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i> Laporan
                        </a>
                    </li>
                @elseif(Auth::user()->role === 'kasir')
                    <li class="nav-item">
                        <a href="{{ route('orders.index') }}" class="nav-link {{ Route::is('orders.index') ? 'active' : '' }}">
                            <i class="fas fa-cash-register"></i> Transaksi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('orders.report') }}" class="nav-link {{ Route::is('orders.report') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i> Laporan
                        </a>
                    </li>
                @endif
            </ul>

            <!-- Logout -->
            <div class="logout-btn">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn w-100">Logout</button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
