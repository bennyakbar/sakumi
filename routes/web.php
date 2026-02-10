<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterData\SchoolClassController;
use App\Http\Controllers\MasterData\StudentCategoryController;
use App\Http\Controllers\MasterData\FeeTypeController;
use App\Http\Controllers\MasterData\StudentController;
use App\Http\Controllers\MasterData\FeeMatrixController;
use App\Http\Controllers\Finance\TransactionController;
use App\Http\Controllers\Finance\ReportController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data Routes
    Route::prefix('master-data')->name('master-data.')->group(function () {
        Route::resource('classes', SchoolClassController::class);
        Route::resource('student-categories', StudentCategoryController::class);
        Route::resource('fee-types', FeeTypeController::class);
        Route::resource('students', StudentController::class);
        Route::get('students-template', [StudentController::class, 'downloadTemplate'])->name('students.template');
        Route::get('students-import', [StudentController::class, 'showImport'])->name('students.import');
        Route::post('students-import', [StudentController::class, 'import'])->name('students.import.post');
        Route::resource('fee-matrix', FeeMatrixController::class);
    });

    // Finance Routes
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::resource('transactions', TransactionController::class);
        Route::get('transactions/{transaction}/print', [TransactionController::class, 'print'])->name('transactions.print');
        Route::post('transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/yearly', [ReportController::class, 'yearly'])->name('reports.yearly');
    });
});

