<div class="sidebar-wrapper">
    <div class="sidebar-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="logo">
                <a href="{{ route('dashboard') }}">
                    <h3 class="mb-0" style="color: #435ebe; font-weight: 700;">
                        <i class="bi bi-box-seam"></i> Inventra
                    </h3>
                </a>
            </div>
            <div class="sidebar-toggler">
                <a href="#" onclick="toggleSidebar()" class="sidebar-hide d-xl-none d-block">
                    <i class="bi bi-x bi-middle"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="menu">
        @if(auth()->check())
            @if(auth()->user()->role === 'peminjam')
                
                <div class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="sidebar-link">
                        <i class="bi bi-grid-fill"></i>
                        <span>Beranda</span>
                    </a>
                </div>
                
                <div class="sidebar-item {{ request()->routeIs('equipment.browse') ? 'active' : '' }}">
                    <a href="{{ route('equipment.browse') }}" class="sidebar-link">
                        <i class="bi bi-search"></i>
                        <span>Cari Alat</span>
                    </a>
                </div>
                
                <div class="sidebar-item {{ request()->routeIs('borrowings.index') ? 'active' : '' }}">
                    <a href="{{ route('borrowings.index') }}" class="sidebar-link">
                        <i class="bi bi-clock-history"></i>
                        <span>Riwayat Pinjaman</span>
                    </a>
                </div>
                
            @else
                
                <div class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                        <i class="bi bi-grid-fill"></i>
                        <span>Beranda</span>
                    </a>
                </div>
                
                <div class="sidebar-title">MASTER DATA</div>
                
                <div class="sidebar-item {{ request()->routeIs('admin.equipment.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.equipment.index') }}" class="sidebar-link">
                        <i class="bi bi-box"></i>
                        <span>Alat</span>
                    </a>
                </div>
                
                <div class="sidebar-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.categories.index') }}" class="sidebar-link">
                        <i class="bi bi-tag"></i>
                        <span>Kategori</span>
                    </a>
                </div>
                
                @if(auth()->user()->role === 'admin')
                <div class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}" class="sidebar-link">
                        <i class="bi bi-people"></i>
                        <span>Pengguna</span>
                    </a>
                </div>
                @endif
                
                <div class="sidebar-title">TRANSAKSI</div>
                
                <div class="sidebar-item {{ request()->routeIs('admin.borrowings.pending') ? 'active' : '' }}">
                    <a href="{{ route('admin.borrowings.pending') }}" class="sidebar-link">
                        <i class="bi bi-clipboard-check"></i>
                        <span>Verifikasi Pinjaman</span>
                    </a>
                </div>
                
                <div class="sidebar-item {{ request()->routeIs('admin.borrowings.active') ? 'active' : '' }}">
                    <a href="{{ route('admin.borrowings.active') }}" class="sidebar-link">
                        <i class="bi bi-arrow-return-left"></i>
                        <span>Pengembalian</span>
                    </a>
                </div>
                
                <div class="sidebar-item {{ request()->routeIs('admin.fines.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.fines.index') }}" class="sidebar-link">
                        <i class="bi bi-cash"></i>
                        <span>Denda</span>
                    </a>
                </div>
                
                <div class="sidebar-item {{ request()->routeIs('admin.purchase-requisitions.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.purchase-requisitions.index') }}" class="sidebar-link">
                        <i class="bi bi-cart-plus"></i>
                        <span>Pengajuan Pembelian</span>
                    </a>
                </div>
                
                <div class="sidebar-title">LAPORAN</div>
                
                <div class="sidebar-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.reports.index') }}" class="sidebar-link">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Laporan</span>
                    </a>
                </div>

                <div class="sidebar-item {{ request()->routeIs('admin.damaged-equipment.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.damaged-equipment.index') }}" class="sidebar-link">
                        <i class="bi bi-exclamation-octagon"></i>
                        <span>Barang Rusak</span>
                    </a>
                </div>

                <div class="sidebar-item {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.activity-logs.index') }}" class="sidebar-link">
                        <i class="bi bi-journal-text"></i>
                        <span>Activity Log</span>
                    </a>
                </div>
            @endif
            
            <div class="sidebar-title">AKUN</div>
            
            <div class="sidebar-item">
                <a href="{{ route('profile') }}" class="sidebar-link">
                    <i class="bi bi-person"></i>
                    <span>Profil</span>
                </a>
            </div>
            
            <div class="sidebar-item">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-link border-0 bg-transparent w-100 text-start">
                        <i class="bi bi-box-arrow-left"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

<style>
    .sidebar-title {
        padding: 20px 20px 10px 20px;
        font-size: 0.75rem;
        font-weight: 700;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .sidebar-item {
        margin: 2px 0;
    }
    
    .sidebar-item.active .sidebar-link {
        background: #f2f7ff;
        color: #435ebe;
    }
</style>
