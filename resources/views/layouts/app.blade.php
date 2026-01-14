<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inventra') - Equipment Borrowing System</title>
    
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.5/css/perfect-scrollbar.css">
    
    
    <style>
        :root {
            /* Primary Color - Emerald Green */
            --bs-primary: #059669;
            --bs-primary-rgb: 5, 150, 105;
            --bs-secondary: #6c757d;
            --bs-success: #198754;
            --bs-danger: #dc3545;
            --bs-warning: #ffc107;
            --bs-info: #0dcaf0;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f2f7ff;
        }
        
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 260px;
            background: #fff;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            z-index: 10;
            transition: transform 0.3s ease;
        }
        
        #sidebar .sidebar-wrapper {
            height: 100%;
            overflow-y: auto;
        }
        
        #sidebar .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        
        #sidebar .sidebar-header img {
            max-height: 40px;
        }
        
        #sidebar .menu {
            padding: 20px 0;
        }
        
        #sidebar .menu .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #6c757d;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        #sidebar .menu .sidebar-link:hover,
        #sidebar .menu .sidebar-link.active {
            background: #f2f7ff;
            color: var(--bs-primary);
        }
        
        #sidebar .menu .sidebar-link i {
            margin-right: 12px;
            font-size: 1.2rem;
        }
        
        #main {
            margin-left: 260px;
            min-height: 100vh;
            padding: 20px;
        }
        
        #main header {
            background: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        .page-heading h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #25396f;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #fff;
        }
        
        .stats-icon.purple { background: #936dff; }
        .stats-icon.blue { background: #57caeb; }
        .stats-icon.green { background: #5ddab4; }
        .stats-icon.red { background: #ff7976; }
        
        .burger-btn {
            display: none;
        }
        
        @media (max-width: 1199px) {
            #sidebar {
                transform: translateX(-260px);
            }
            
            #sidebar.active {
                transform: translateX(0);
            }
            
            #main {
                margin-left: 0;
            }
            
            .burger-btn {
                display: block !important;
            }
        }
        
        footer {
            margin-top: 40px;
            padding: 20px 0;
            text-align: center;
            color: #6c757d;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div id="app">
        
        <div id="sidebar">
            @include('components.sidebar')
        </div>
        
        
        <div id="main">
            
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none" onclick="toggleSidebar()">
                    <i class="bi bi-justify fs-3"></i>
                </a>
                @include('components.navbar')
            </header>
            
            
            @yield('content')
            
            
            <footer>
                <div class="container-fluid">
                    <p class="mb-0">&copy; {{ date('Y') }} Inventra. All rights reserved.</p>
                </div>
            </footer>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    
    <script src="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.5/dist/perfect-scrollbar.min.js"></script>
    
    
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
        
        // Inisialisasi Perfect Scrollbar
        if (document.querySelector('#sidebar .sidebar-wrapper')) {
            const ps = new PerfectScrollbar('#sidebar .sidebar-wrapper');
        }
    </script>
    
    @stack('scripts')
</body>
</html>
