<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\EquipmentController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BorrowingController as AdminBorrowingController;
use App\Http\Controllers\Admin\FineController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
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
});


Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin,petugas'])
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        

        Route::resource('equipment', EquipmentController::class)->except(['create', 'edit']);
        

        Route::resource('categories', CategoryController::class);
        

        Route::middleware('role:admin')->group(function () {
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
            Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        });
        

        Route::get('/borrowings/pending', [AdminBorrowingController::class, 'pending'])->name('borrowings.pending');
        Route::get('/borrowings/active', [AdminBorrowingController::class, 'active'])->name('borrowings.active');
        Route::put('/borrowings/{borrowing}/approve', [AdminBorrowingController::class, 'approve'])->name('borrowings.approve');
        Route::put('/borrowings/{borrowing}/reject', [AdminBorrowingController::class, 'reject'])->name('borrowings.reject');
        Route::post('/borrowings/{borrowing}/verify', [AdminBorrowingController::class, 'verify'])->name('borrowings.verify');
        Route::get('/borrowings/{borrowing}/return', [AdminBorrowingController::class, 'showReturn'])->name('borrowings.return');
        Route::post('/borrowings/{borrowing}/return', [AdminBorrowingController::class, 'processReturn'])->name('borrowings.process-return');
        Route::get('/borrowings/{borrowing}', [AdminBorrowingController::class, 'show'])->name('borrowings.show');
        

        Route::get('/fines', [FineController::class, 'index'])->name('fines.index');
        Route::post('/fines/{fine}/pay', [FineController::class, 'markAsPaid'])->name('fines.pay');
        
        // Purchase Requisitions
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
            Route::get('/{purchaseRequisition}/export-pdf', [App\Http\Controllers\Admin\PurchaseRequisitionController::class, 'exportPDF'])->name('export-pdf');
        });

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    });


Route::middleware(['auth', 'role:peminjam'])
    ->group(function () {

        Route::get('/peminjam/dashboard', [App\Http\Controllers\Peminjam\DashboardController::class, 'index'])->name('peminjam.dashboard');
        

        Route::get('/equipment/browse', [EquipmentBrowseController::class, 'index'])->name('equipment.browse');
        Route::get('/equipment/{equipment}', [EquipmentBrowseController::class, 'show'])->name('equipment.show');
        

        Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
        Route::get('/borrowings/create/{equipment}', [BorrowingController::class, 'create'])->name('borrowings.create');
        Route::post('/borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
        Route::get('/borrowings/{borrowing}', [BorrowingController::class, 'show'])->name('borrowings.show');
    });
