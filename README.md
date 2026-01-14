# Inventra - Sistem Informasi Inventaris Sekolah

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat&logo=bootstrap&logoColor=white)

Aplikasi web untuk mengelola inventaris alat dan peminjaman di sekolah/SMK.

## Fitur Utama

- **Manajemen Alat**: CRUD alat dengan kategori, stok, kondisi
- **Peminjaman**: Request, approve/reject, tracking status
- **Pengembalian**: Proses return + kalkulasi denda otomatis
- **Laporan**: Export PDF & Excel untuk peminjaman, denda, inventaris
- **Multi Role**: Admin, Petugas, Peminjam

## Tech Stack

- Laravel 11
- MySQL
- Bootstrap 5 (Mazer template)
- PHP 8.2+

## Instalasi

1. Clone repo
```bash
git clone https://github.com/trixxsobased/inventra.git
cd inventra
```

2. Install dependencies
```bash
composer install
npm install && npm run build
```

3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Konfigurasi database di `.env`
```
DB_DATABASE=inventra
DB_USERNAME=root
DB_PASSWORD=
```

5. Migrate & seed
```bash
php artisan migrate --seed
```

6. Run server
```bash
php artisan serve
```

Buka `http://localhost:8000`

## Default Login

**Admin:**
- Username: `admin`
- Password: `admin123`

**Petugas:**
- Username: `petugas`
- Password: `petugas123`

**Peminjam (Siswa Demo):**
- Username: `siswa`
- Password: `siswa123`

## Screenshot

### Dashboard Peminjam
![Dashboard Peminjam](public/screenshots/dashboard-peminjam.png)

### Dashboard Admin/Petugas
![Dashboard Admin](public/screenshots/dashboard-admin.png)

## Database Schema

- `users` - Data user (admin/petugas/peminjam)
- `categories` - Kategori alat
- `equipment` - Data inventaris alat
- `borrowings` - Transaksi peminjaman
- `fines` - Denda keterlambatan
- `equipment_logs` - History perubahan stok

## Fitur Tambahan

- Auto update stok via database trigger
- Kalkulasi denda otomatis (Rp 5.000/hari)
- Export laporan ke CSV/Excel
- Upload avatar user
- Responsive design

## Developer

Dibuat sebagai project UKK/Tugas Akhir

---

Still learning 