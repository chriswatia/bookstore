<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\BookLoanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::middleware(['IsAdmin', 'auth:api'])->group(function () {
            Route::post('register', [AuthController::class, 'register']);
            Route::resource('books', BookController::class);
            Route::resource('book-loans', BookLoanController::class);
            Route::put('book-loans/approve/{id}', [BookLoanController::class, 'approveBookLoan']);
            Route::put('book-loans/issue/{id}', [BookLoanController::class, 'issueBook']);
            Route::put('book-loans/receive/{id}', [BookLoanController::class, 'receiveBook']);
        });
    });

    // Public Protected Routes
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('books', [BookController::class, 'index']);
        Route::get('book-loans', [BookLoanController::class, 'index']);
        Route::post('book-loans/borrow', [BookLoanController::class, 'borrowBook']);
        Route::put('book-loans/extend/{id}', [BookLoanController::class, 'extendBookLoan']);
    });
});
