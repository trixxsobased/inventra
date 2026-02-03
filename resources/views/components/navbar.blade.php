<div class="d-flex justify-content-between align-items-center">
    <div>
        <h5 class="mb-0" style="color: var(--text-primary);">@yield('page-title', 'Beranda')</h5>
    </div>
    <div class="d-flex align-items-center gap-3">
        <!-- Dark Mode Toggle -->
        <button class="theme-toggle" id="themeToggle" title="Toggle Dark Mode">
            <i class="bi bi-moon-fill" id="themeIcon"></i>
        </button>
        <script>
            (function() {
                const savedTheme = localStorage.getItem('theme') || 'light';
                const icon = document.getElementById('themeIcon');
                if (savedTheme === 'dark') {
                    icon.className = 'bi bi-sun-fill';
                } else {
                    icon.className = 'bi bi-moon-fill';
                }
            })();
        </script>
        
        <!-- User Dropdown -->
        <div class="dropdown">
            <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-menu d-flex align-items-center">
                    <div class="user-img d-flex align-items-center justify-content-center me-2">
                        <i class="bi bi-person-circle fs-4"></i>
                    </div>
                    <div class="user-name text-end me-3">
                        <h6 class="mb-0" style="color: var(--text-primary);">{{ auth()->user()->name ?? 'Guest' }}</h6>
                        <p class="mb-0 text-sm" style="color: var(--text-secondary);">
                            @if(auth()->check())
                                {{ ucfirst(auth()->user()->role) }}
                            @endif
                        </p>
                    </div>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="min-width: 11rem;">
                <li>
                    <h6 class="dropdown-header">Hello, {{ auth()->user()->name ?? 'Guest' }}!</h6>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('profile') }}">
                        <i class="bi bi-person"></i> My Profile
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-left"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
    .user-menu {
        cursor: pointer;
        padding: 5px 10px;
        border-radius: 8px;
        transition: background 0.3s ease;
    }
    
    .user-menu:hover {
        background: var(--table-hover);
    }
    
    .user-name h6 {
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .user-name p {
        font-size: 0.75rem;
    }
    
    .user-img i {
        color: var(--text-secondary);
    }
</style>

