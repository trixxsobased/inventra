<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inventra') - Equipment Borrowing System</title>
    
    <!-- Prevent flash of wrong theme - must be first script -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            if (savedTheme === 'dark') {
                document.documentElement.style.backgroundColor = '#0f172a';
            }
        })();
    </script>
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#059669">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Inventra">
    <meta name="description" content="Sistem Peminjaman Alat SMKN 1 Jenangan">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/images/icons/icon-192x192.png">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.5/css/perfect-scrollbar.css">
    
    
    <style>
        :root, [data-theme="light"] {
            /* Primary Color - Emerald Green */
            --bs-primary: #059669;
            --bs-primary-rgb: 5, 150, 105;
            --bs-secondary: #6c757d;
            --bs-success: #198754;
            --bs-danger: #dc3545;
            --bs-warning: #ffc107;
            --bs-info: #0dcaf0;
            
            /* Light Theme Colors */
            --bg-body: #f2f7ff;
            --bg-card: #ffffff;
            --bg-sidebar: #ffffff;
            --text-primary: #25396f;
            --text-secondary: #6c757d;
            --text-muted: #9ca3af;
            --border-color: #e5e7eb;
            --shadow-color: rgba(0, 0, 0, 0.05);
            --table-hover: #f8fafc;
        }
        
        [data-theme="dark"] {
            --bs-primary: #10b981;
            --bs-primary-rgb: 16, 185, 129;
            
            /* Dark Theme Colors */
            --bg-body: #0f172a;
            --bg-card: #1e293b;
            --bg-sidebar: #1e293b;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --border-color: #334155;
            --shadow-color: rgba(0, 0, 0, 0.3);
            --table-hover: #334155;
        }
        
        * {
            transition: background-color 0.3s ease, color 0.2s ease, border-color 0.3s ease;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-primary);
        }
        
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 260px;
            background: var(--bg-sidebar);
            box-shadow: 0 0 20px var(--shadow-color);
            z-index: 10;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }
        
        #sidebar .sidebar-wrapper {
            position: relative;
            height: 100%;
            overflow-y: hidden; /* Let PerfectScrollbar handle scrolling */
            overscroll-behavior: contain;
            scrollbar-width: none;
        }
        
        #sidebar .sidebar-wrapper::-webkit-scrollbar {
            display: none;
        }
        
        #sidebar .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
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
            background: var(--bg-card);
            padding: 0.875rem 1.25rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px var(--shadow-color);
            margin-bottom: 1.5rem;
        }
        
        .header-wrapper {
            min-height: 40px;
        }
        
        .burger-btn {
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: background 0.2s ease;
        }
        
        .burger-btn:hover {
            background: var(--table-hover);
        }
        
        .page-heading h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px var(--shadow-color);
            background: var(--bg-card);
            color: var(--text-primary);
        }
        
        .card-header, .card-body, .card-footer {
            background: transparent;
            color: var(--text-primary);
        }
        
        .table {
            color: var(--text-primary);
        }
        
        .table-hover tbody tr:hover {
            background-color: var(--table-hover);
        }
        
        .form-control, .form-select {
            background-color: var(--bg-card);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        .form-control:focus, .form-select:focus {
            background-color: var(--bg-card);
            color: var(--text-primary);
        }
        
        .modal-content {
            background-color: var(--bg-card);
            color: var(--text-primary);
        }
        
        .text-muted {
            color: var(--text-muted) !important;
        }
        
        /* Dark mode toggle button */
        .theme-toggle {
            background: none;
            border: none;
            padding: 8px;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .theme-toggle:hover {
            background: var(--table-hover);
        }
        
        .theme-toggle i {
            font-size: 1.25rem;
            color: var(--text-secondary);
        }
        
        /* ========================================
           COMPREHENSIVE DARK MODE STYLES
           ======================================== */
        
        /* Sidebar */
        #sidebar .menu .sidebar-link {
            color: var(--text-secondary);
        }
        
        [data-theme="dark"] #sidebar .menu .sidebar-link:hover,
        [data-theme="dark"] #sidebar .menu .sidebar-link.active {
            background: rgba(16, 185, 129, 0.1);
        }
        
        [data-theme="dark"] #sidebar .sidebar-header {
            border-bottom-color: var(--border-color);
        }
        
        /* Sidebar title */
        .sidebar-title {
            color: var(--text-muted);
        }
        
        /* Tables */
        .table {
            --bs-table-bg: transparent;
            --bs-table-striped-bg: var(--table-hover);
            --bs-table-hover-bg: var(--table-hover);
            border-color: var(--border-color);
        }
        
        .table > :not(caption) > * > * {
            border-bottom-color: var(--border-color);
            color: var(--text-primary);
        }
        
        .table thead th {
            background: var(--table-hover);
            color: var(--text-primary);
            border-bottom-color: var(--border-color);
        }
        
        /* Dropdowns */
        .dropdown-menu {
            background-color: var(--bg-card);
            border-color: var(--border-color);
            box-shadow: 0 10px 40px var(--shadow-color);
        }
        
        .dropdown-item {
            color: var(--text-primary);
        }
        
        .dropdown-item:hover, .dropdown-item:focus {
            background-color: var(--table-hover);
            color: var(--text-primary);
        }
        
        .dropdown-header {
            color: var(--text-muted);
        }
        
        .dropdown-divider {
            border-color: var(--border-color);
        }
        
        /* Alerts */
        [data-theme="dark"] .alert {
            border: 1px solid var(--border-color);
        }
        
        [data-theme="dark"] .alert-info {
            background-color: rgba(59, 130, 246, 0.15);
            color: #93c5fd;
        }
        
        [data-theme="dark"] .alert-success {
            background-color: rgba(16, 185, 129, 0.15);
            color: #6ee7b7;
        }
        
        [data-theme="dark"] .alert-warning {
            background-color: rgba(251, 191, 36, 0.15);
            color: #fcd34d;
        }
        
        [data-theme="dark"] .alert-danger {
            background-color: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
        }
        
        /* Buttons - outline variants */
        [data-theme="dark"] .btn-outline-primary {
            color: #10b981;
            border-color: #10b981;
        }
        
        [data-theme="dark"] .btn-outline-secondary {
            color: var(--text-secondary);
            border-color: var(--border-color);
        }
        
        [data-theme="dark"] .btn-light {
            background-color: var(--bg-sidebar);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        /* Input groups */
        .input-group-text {
            background-color: var(--table-hover);
            border-color: var(--border-color);
            color: var(--text-secondary);
        }
        
        /* Pagination */
        .page-link {
            background-color: var(--bg-card);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        .page-link:hover {
            background-color: var(--table-hover);
            color: var(--bs-primary);
        }
        
        .page-item.active .page-link {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }
        
        .page-item.disabled .page-link {
            background-color: var(--bg-card);
            color: var(--text-muted);
        }
        
        /* Breadcrumb */
        .breadcrumb {
            background-color: transparent;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            color: var(--text-muted);
        }
        
        .breadcrumb-item a {
            color: var(--bs-primary);
        }
        
        .breadcrumb-item.active {
            color: var(--text-muted);
        }
        
        /* List groups */
        .list-group-item {
            background-color: var(--bg-card);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        .list-group-item:hover {
            background-color: var(--table-hover);
        }
        
        /* Nav tabs and pills */
        .nav-tabs {
            border-bottom-color: var(--border-color);
        }
        
        .nav-tabs .nav-link {
            color: var(--text-secondary);
        }
        
        .nav-tabs .nav-link:hover {
            border-color: var(--border-color);
        }
        
        .nav-tabs .nav-link.active {
            background-color: var(--bg-card);
            border-color: var(--border-color) var(--border-color) var(--bg-card);
            color: var(--text-primary);
        }
        
        /* Labels/Form labels */
        label, .form-label {
            color: var(--text-primary);
        }
        
        /* Progress bars */
        .progress {
            background-color: var(--table-hover);
        }
        
        /* Close button */
        [data-theme="dark"] .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        
        /* Placeholder text */
        ::placeholder {
            color: var(--text-muted) !important;
            opacity: 1;
        }
        
        /* Text utilities that need dark mode */
        [data-theme="dark"] .text-dark {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .text-body {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .bg-light {
            background-color: var(--bg-sidebar) !important;
        }
        
        [data-theme="dark"] .border {
            border-color: var(--border-color) !important;
        }
        
        /* Footer */
        footer {
            color: var(--text-muted);
        }
        
        /* Card header border */
        .card-header {
            border-bottom-color: var(--border-color);
        }
        
        /* Card footer border */
        .card-footer {
            border-top-color: var(--border-color);
        }
        
        /* Scrollbar styling for dark mode */
        [data-theme="dark"] ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        [data-theme="dark"] ::-webkit-scrollbar-track {
            background: var(--bg-body);
        }
        
        [data-theme="dark"] ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }
        
        [data-theme="dark"] ::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
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
        
        /* UI/UX Spacing */
        
        /* Page heading */
        .page-heading {
            margin-bottom: 1.5rem;
        }
        
        .page-heading h3 {
            margin-bottom: 0;
        }
        
        /* Card improvements */
        .card {
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .card-header h4, .card-header h5 {
            margin-bottom: 0;
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        .card-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border-color);
        }
        
        /* Stats cards - compact */
        .card-body.px-4.py-4-5 {
            padding: 1rem !important;
        }
        
        /* Table improvements */
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.875rem 1rem;
        }
        
        .table td {
            padding: 0.875rem 1rem;
            vertical-align: middle;
        }
        
        .table-responsive {
            margin: -0.25rem;
            padding: 0.25rem;
        }

        /* Dark mode table danger override */
        [data-theme="dark"] .table-danger {
            --bs-table-bg: rgba(220, 53, 69, 0.15) !important;
            --bs-table-color: var(--text-primary) !important;
            box-shadow: inset 3px 0 0 #dc3545; /* Red indicator on left */
        }
        
        [data-theme="dark"] .table-danger td,
        [data-theme="dark"] .table-danger th {
            color: var(--text-primary) !important;
            background-color: transparent !important;
        }
        
        /* Form improvements */
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .form-control, .form-select {
            padding: 0.625rem 0.875rem;
            font-size: 0.95rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.15);
        }

        /* Dark mode for file inputs */
        [data-theme="dark"] input[type="file"] {
            background-color: var(--bg-body) !important;
            color: var(--text-primary);
        }
        
        [data-theme="dark"] input[type="file"]::file-selector-button {
            background-color: var(--bg-card);
            color: var(--text-primary);
            border-right: 1px solid var(--border-color);
        }
        
        [data-theme="dark"] input[type="file"]:hover::file-selector-button {
            background-color: var(--table-hover);
        }
        
        /* Button improvements */
        .btn {
            padding: 0.5rem 1rem;
            font-weight: 500;
            border-radius: 0.5rem;
        }
        
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.85rem;
        }
        
        .btn-lg {
            padding: 0.75rem 1.5rem;
        }
        
        /* Action buttons in tables */
        .btn-group .btn {
            margin-right: 0.25rem;
        }
        
        .btn-group .btn:last-child {
            margin-right: 0;
        }
        
        /* Badge improvements */
        .badge {
            padding: 0.5em 0.8em;
            font-weight: 600;
            font-size: 0.75rem;
            border-radius: 6px;
            letter-spacing: 0.3px;
        }
        
        .badge.bg-warning {
            color: #78350f !important;
            background-color: #fcd34d !important;
        }
        
        .badge.bg-info {
            color: #0c4a6e !important;
            background-color: #7dd3fc !important;
        }
        
        .badge.bg-success {
            color: #064e3b !important;
            background-color: #6ee7b7 !important;
        }
        
        .badge.bg-danger {
            color: #7f1d1d !important;
            background-color: #fca5a5 !important;
        }

        /* Table alignment */
        .table > :not(caption) > * > * {
            vertical-align: middle;
            padding: 1rem 1rem;
        }
        
        .table thead th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            color: var(--text-muted);
            border-bottom-width: 1px;
        }

        /* Modal improvements */
        .modal-header {
            padding: 1rem 1.25rem;
            border-bottom-color: var(--border-color);
        }
        
        .modal-body {
            padding: 1.25rem;
        }
        
        .modal-footer {
            padding: 1rem 1.25rem;
            border-top-color: var(--border-color);
        }

        /* Alert improvements */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
        
        /* Row/Column spacing */
        .row {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 1.5rem;
        }
        
        .row.g-3 {
            --bs-gutter-x: 1rem;
            --bs-gutter-y: 1rem;
        }
        
        /* Section spacing */
        .page-content {
            padding-bottom: 2rem;
        }
        
        /* Sidebar adjustments */
        #sidebar .sidebar-header {
            padding: 1.25rem 1.5rem;
        }
        
        #sidebar .menu {
            padding: 1rem 0;
        }
        
        #sidebar .menu .sidebar-link {
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
        }
        
        /* Header adjustments */
        #main header {
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }
        
        /* Main content padding */
        #main {
            padding: 1.25rem 1.5rem;
        }
        
        /* Consistent margin utilities */
        .mb-4 {
            margin-bottom: 1.5rem !important;
        }
        
        /* Quick action buttons consistency */
        .d-grid.gap-2 .btn {
            padding: 0.75rem 1rem;
            text-align: left;
        }
        
        .d-grid.gap-2 .btn i {
            margin-right: 0.5rem;
        }
        
        /* Reduce excessive white space */
        .col-12.col-lg-3 .card {
            margin-bottom: 1rem;
        }
        
        /* Font smoothing */
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Stats card text */
        .font-extrabold {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .font-semibold {
            font-weight: 600;
        }
        
        /* Transition for all elements (but faster) */
        * {
            transition: background-color 0.2s ease, color 0.15s ease, border-color 0.2s ease, box-shadow 0.2s ease;
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
                padding: 1rem;
            }
            
            .burger-btn {
                display: block !important;
            }
            
            /* Adjust card margins on mobile */
            .card {
                margin-bottom: 1rem;
            }
        }
        
        @media (max-width: 767px) {
            .page-heading h3 {
                font-size: 1.25rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .table th, .table td {
                padding: 0.625rem 0.75rem;
                font-size: 0.85rem;
            }
        }
        
        footer {
            margin-top: 2rem;
            padding: 1.5rem 0;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.875rem;
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
            
            <header>
                <div class="header-wrapper d-flex align-items-center">
                    <a href="#" class="burger-btn d-block d-xl-none me-3" onclick="toggleSidebar()">
                        <i class="bi bi-list fs-4" style="color: var(--text-primary);"></i>
                    </a>
                    <div class="flex-grow-1">
                        @include('components.navbar')
                    </div>
                </div>
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
    
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    
    <style>
        /* Custom SweetAlert2 Styling */
        .swal2-popup {
            border-radius: 16px !important;
            padding: 2rem !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
        }
        
        .swal2-title {
            font-size: 1.5rem !important;
            font-weight: 700 !important;
            color: #1f2937 !important;
        }
        
        .swal2-html-container {
            font-size: 1rem !important;
            color: #6b7280 !important;
            line-height: 1.6 !important;
        }
        
        .swal2-icon {
            margin: 1.5rem auto !important;
            border-width: 3px !important;
        }
        
        .swal2-icon.swal2-warning {
            border-color: #f59e0b !important;
            color: #f59e0b !important;
        }
        
        .swal2-icon.swal2-question {
            border-color: #8b5cf6 !important;
            color: #8b5cf6 !important;
        }
        
        .swal2-icon.swal2-success {
            border-color: #059669 !important;
        }
        
        .swal2-icon.swal2-success .swal2-success-ring {
            border-color: rgba(5, 150, 105, 0.3) !important;
        }
        
        .swal2-icon.swal2-success [class^='swal2-success-line'] {
            background-color: #059669 !important;
        }
        
        .swal2-icon.swal2-error {
            border-color: #dc2626 !important;
        }

        /* Dark Mode SweetAlert Override */
        [data-theme="dark"] .swal2-popup {
            background-color: var(--bg-card) !important;
            color: var(--text-primary) !important;
            border: 1px solid var(--border-color);
        }

        [data-theme="dark"] .swal2-title,
        [data-theme="dark"] .swal2-html-container {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .swal2-html-container p {
            color: var(--text-secondary) !important;
        }

        [data-theme="dark"] .swal2-popup.swal2-toast {
            background-color: var(--bg-card) !important;
            color: var(--text-primary) !important;
            box-shadow: 0 5px 15px rgba(0,0,0,0.5) !important;
        }
        
        .swal2-icon.swal2-error [class^='swal2-x-mark-line'] {
            background-color: #dc2626 !important;
        }
        
        .swal2-actions {
            gap: 12px !important;
        }
        
        .swal2-confirm, .swal2-cancel {
            padding: 12px 28px !important;
            font-size: 0.95rem !important;
            font-weight: 600 !important;
            border-radius: 10px !important;
            transition: all 0.2s ease !important;
        }
        
        .swal2-confirm:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }
        
        .swal2-cancel:hover {
            transform: translateY(-1px) !important;
            background-color: #4b5563 !important;
        }
        
        /* Toast notification styling */
        .swal2-popup.swal2-toast {
            border-radius: 12px !important;
            padding: 1rem 1.25rem !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15) !important;
        }
        
        .swal2-popup.swal2-toast .swal2-title {
            font-size: 0.95rem !important;
            font-weight: 500 !important;
        }
    </style>
    
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
        
        // Inisialisasi Perfect Scrollbar
        document.addEventListener('DOMContentLoaded', () => {
            const sidebarWrapper = document.querySelector('#sidebar .sidebar-wrapper');
            if (sidebarWrapper) {
                const ps = new PerfectScrollbar(sidebarWrapper);
                
                // Auto scroll to active item
                const activeItem = document.querySelector('.sidebar-item.active');
                if (activeItem) {
                    const topPos = activeItem.offsetTop;
                    sidebarWrapper.scrollTop = topPos - 100;
                    ps.update();
                }
            }
        });

        // Custom SweetAlert2 theme
        const SwalCustom = Swal.mixin({
            customClass: {
                popup: 'animate__animated animate__fadeInUp animate__faster',
                confirmButton: 'btn btn-primary me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false,
            backdrop: `rgba(15, 23, 42, 0.6)`,
            showClass: {
                popup: 'animate__animated animate__fadeInUp animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutDown animate__faster'
            }
        });

        // Global SweetAlert confirm dialog with enhanced UI
        function confirmAction(form, options = {}) {
            const defaults = {
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin melanjutkan?',
                icon: 'question',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: false,
                confirmButtonColor: 'primary' // primary, success, danger, warning, info
            };
            
            const config = { ...defaults, ...options };
            
            // Determine button class based on color config
            let btnClass = 'btn btn-primary me-2';
            if (config.confirmButtonColor === 'success' || config.confirmButtonColor === '#059669') btnClass = 'btn btn-success me-2';
            if (config.confirmButtonColor === 'danger' || config.confirmButtonColor === '#dc3545') btnClass = 'btn btn-danger me-2';
            if (config.confirmButtonColor === 'warning' || config.confirmButtonColor === '#ffc107') btnClass = 'btn btn-warning me-2 text-dark';
            
            Swal.fire({
                title: config.title,
                text: config.text,
                icon: config.icon,
                showCancelButton: true,
                confirmButtonText: config.confirmButtonText,
                cancelButtonText: config.cancelButtonText,
                reverseButtons: config.reverseButtons,
                focusCancel: config.focusCancel,
                customClass: {
                    popup: 'animate__animated animate__fadeInUp animate__faster',
                    confirmButton: btnClass,
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false,
                backdrop: `rgba(15, 23, 42, 0.6)`,
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Memproses...',
                        html: '<div class="d-flex justify-content-center mt-3"><div class="spinner-border text-primary" role="status"></div></div>',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            if (typeof form === 'string') {
                                document.getElementById(form).submit();
                            } else {
                                form.submit();
                            }
                        }
                    });
                }
            });
        }

        // Toast notification helper
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        // Quick action helpers
        function showSuccess(message) {
            Toast.fire({ icon: 'success', title: message });
        }

        function showError(message) {
            Toast.fire({ icon: 'error', title: message });
        }

        function showInfo(message) {
            Toast.fire({ icon: 'info', title: message });
        }

        // Dark Mode Toggle
        (function() {
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const html = document.documentElement;
            
            // Load saved theme or default to light
            const savedTheme = localStorage.getItem('theme') || 'light';
            html.setAttribute('data-theme', savedTheme);
            updateIcon(savedTheme);
            
            // Toggle theme on click
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const currentTheme = html.getAttribute('data-theme');
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    
                    html.setAttribute('data-theme', newTheme);
                    localStorage.setItem('theme', newTheme);
                    updateIcon(newTheme);
                    
                    // Show toast
                    Toast.fire({
                        icon: 'success',
                        title: newTheme === 'dark' ? 'Dark mode aktif' : 'Light mode aktif'
                    });
                });
            }
            
            function updateIcon(theme) {
                if (themeIcon) {
                    themeIcon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
                }
            }
        })();
    </script>
    
    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('ServiceWorker registration successful');
                    })
                    .catch(function(err) {
                        console.log('ServiceWorker registration failed: ', err);
                    });
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>

