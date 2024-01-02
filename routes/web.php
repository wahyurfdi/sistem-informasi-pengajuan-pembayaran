<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CostCategoryController;
use App\Http\Controllers\PaymentRequestController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('cms')->middleware(['auth'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('/master-data')->group(function() {
        Route::get('/division', [DivisionController::class, 'index'])->name('masterData.division');
        Route::get('/region', [RegionController::class, 'index'])->name('masterData.region');
        Route::get('/cost-category', [CostCategoryController::class, 'index'])->name('masterData.costCategory');
        Route::get('/user-account', [UserController::class, 'index'])->name('masterData.userAccount');
        Route::post('/user-account', [UserController::class, 'store'])->name('masterData.userAccount.store');
    });
    Route::get('/reimbursement', [PaymentRequestController::class, 'index'])->name('reimbursement');
    Route::get('/reimbursement/{payreqCode}', [PaymentRequestController::class, 'detail'])->name('reimbursement.detail');
    Route::post('/reimbursement', [PaymentRequestController::class, 'store'])->name('reimbursement.store');
    Route::post('/reimbursement/update', [PaymentRequestController::class, 'update'])->name('reimbursement.update');
    Route::post('/reimbursement/status/update', [PaymentRequestController::class, 'updateStatus'])->name('reimbursement.status.update');
    Route::delete('/reimbursement', [PaymentRequestController::class, 'destroy'])->name('reimbursement.destroy');
    Route::prefix('/report')->group(function() {
        Route::get('/reimbursement', [ReportController::class, 'paymentRequestIndex'])->name('report.reimbursement');
    });
});
