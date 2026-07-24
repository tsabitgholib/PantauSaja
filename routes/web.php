<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('accounts', AccountController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('budgets', BudgetController::class);
    Route::resource('targets', TargetController::class);
    Route::resource('debts', DebtController::class);
    Route::resource('subscriptions', SubscriptionController::class);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-csv', [ReportController::class, 'exportCsv'])->name('reports.export-csv');
    Route::get('/backup/download', [\App\Http\Controllers\BackupController::class, 'download'])->name('backup.download');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Web Artisan (Shared Hosting Fix)
Route::get('/web-artisan/migrate', [\App\Http\Controllers\ArtisanController::class, 'migrate']);
Route::get('/web-artisan/seed', [\App\Http\Controllers\ArtisanController::class, 'seed']);

require __DIR__.'/auth.php';
