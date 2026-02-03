<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\EquipmentController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BorrowingController as AdminBorrowingController;
use App\Http\Controllers\Admin\FineController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DamagedEquipmentController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\Petugas\BorrowingController as PetugasBorrowingController;
use App\Http\Controllers\Petugas\ReportController as PetugasReportController;
use App\Http\Controllers\Peminjam\EquipmentBrowseController;
use App\Http\Controllers\Peminjam\BorrowingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/equipment/browse', [EquipmentBrowseController::class, 'index'])->name('equipment.browse');

    Route::get('/equipment/{equipment}', [EquipmentBrowseController::class, 'show'])->name('equipment.show');
});

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('users', UserController::class)->except(['create', 'edit', 'show']);
        Route::resource('equipment', EquipmentController::class)->except(['create', 'edit']);
        Route::resource('categories', CategoryController::class);
        
        Route::get('/borrowings/pending', [AdminBorrowingController::class, 'pending'])->name('borrowings.pending');
        Route::get('/borrowings/active', [AdminBorrowingController::class, 'active'])->name('borrowings.active');
        Route::get('/borrowings/{borrowing}', [AdminBorrowingController::class, 'show'])->name('borrowings.show');
        Route::put('/borrowings/{borrowing}/approve', [AdminBorrowingController::class, 'approve'])->name('borrowings.approve');
        Route::put('/borrowings/{borrowing}/reject', [AdminBorrowingController::class, 'reject'])->name('borrowings.reject');
        Route::get('/borrowings/{borrowing}/return', [AdminBorrowingController::class, 'showReturn'])->name('borrowings.return');
        Route::post('/borrowings/{borrowing}/return', [AdminBorrowingController::class, 'processReturn'])->name('borrowings.process-return');
        Route::get('/borrowings/{borrowing}/print', [AdminBorrowingController::class, 'print'])->name('borrowings.print');

        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('/activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-logs.show');

        Route::get('/equipment/{equipment}/qr', [EquipmentController::class, 'generateQR'])->name('equipment.qr');
        Route::get('/equipment/{equipment}/qr/download', [EquipmentController::class, 'downloadQR'])->name('equipment.qr.download');
        Route::get('/equipment-qr/bulk', [EquipmentController::class, 'bulkQR'])->name('equipment.qr.bulk');
        Route::get('/equipment-qr/scan', [EquipmentController::class, 'scanQR'])->name('equipment.qr.scan');

        Route::get('/fines', [FineController::class, 'index'])->name('fines.index');
        Route::post('/fines/{fine}/pay', [FineController::class, 'markAsPaid'])->name('fines.pay');
        
        Route::resource('damaged-equipment', DamagedEquipmentController::class)->only(['index', 'show']);
        Route::put('/damaged-equipment/{damaged_equipment}/resolve', [DamagedEquipmentController::class, 'resolve'])->name('damaged-equipment.resolve');

        Route::prefix('purchase-requisitions')->name('purchase-requisitions.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\PurchaseRequisitionController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\PurchaseRequisitionController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\PurchaseRequisitionController::class, 'store'])->name('store');
            Route::get('/{purchaseRequisition}', [App\Http\Controllers\Admin\PurchaseRequisitionController::class, 'show'])->name('show');
            Route::get('/{purchaseRequisition}/edit', [App\Http\Controllers\Admin\PurchaseRequisitionController::class, 'edit'])->name('edit');
            Route::put('/{purchaseRequisition}', [App\Http\Controllers\Admin\PurchaseRequisitionController::class, 'update'])->name('update');
            Route::delete('/{purchaseRequisition}', [App\Http\Controllers\Admin\PurchaseRequisitionController::class, 'destroy'])->name('destroy');
            Route::post('/{purchaseRequisition}/approve', [App\Http\Controllers\Admin\PurchaseRequisitionController::class, 'approve'])->name('approve');
            Route::post('/{purchaseRequisition}/reject', [App\Http\Controllers\Admin\PurchaseRequisitionController::class, 'reject'])->name('reject');
            Route::post('/{purchaseRequisition}/receive', [App\Http\Controllers\Admin\PurchaseRequisitionController::class, 'markAsDone'])->name('receive');
            Route::get('/{purchaseRequisition}/export-pdf', [App\Http\Controllers\Admin\PurchaseRequisitionController::class, 'exportPDF'])->name('export-pdf');
        });

        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/generate', [AdminReportController::class, 'generate'])->name('reports.generate');
        Route::get('/reports/export', [AdminReportController::class, 'export'])->name('reports.export');
    });

Route::middleware(['auth', 'role:petugas'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {
        Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/borrowings/pending', [PetugasBorrowingController::class, 'pending'])->name('borrowings.pending');
        Route::get('/borrowings/active', [PetugasBorrowingController::class, 'active'])->name('borrowings.active');
        Route::get('/borrowings/{borrowing}', [PetugasBorrowingController::class, 'show'])->name('borrowings.show');
        Route::put('/borrowings/{borrowing}/approve', [PetugasBorrowingController::class, 'approve'])->name('borrowings.approve');
        Route::put('/borrowings/{borrowing}/reject', [PetugasBorrowingController::class, 'reject'])->name('borrowings.reject');
        
        Route::get('/borrowings/{borrowing}/return', [PetugasBorrowingController::class, 'showReturn'])->name('borrowings.return');
        Route::post('/borrowings/{borrowing}/return', [PetugasBorrowingController::class, 'processReturn'])->name('borrowings.process-return');
        Route::get('/borrowings/{borrowing}/print', [PetugasBorrowingController::class, 'print'])->name('borrowings.print');
        
        Route::get('/reports', [PetugasReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/generate', [PetugasReportController::class, 'generate'])->name('reports.generate');
    });

Route::middleware(['auth', 'role:peminjam'])
    ->group(function () {
        Route::get('/peminjam/dashboard', [App\Http\Controllers\Peminjam\DashboardController::class, 'index'])->name('peminjam.dashboard');

        Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
        Route::get('/borrowings/create/{equipment}', [BorrowingController::class, 'create'])->name('borrowings.create');
        Route::post('/borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
        Route::get('/borrowings/{borrowing}', [BorrowingController::class, 'show'])->name('borrowings.show');
        Route::delete('/borrowings/{borrowing}', [BorrowingController::class, 'destroy'])->name('borrowings.destroy');
    });

