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
    Route::post('/login', [AuthController::class, 'login']);

    //Protected Routes
    // Admin routes
    Route::group(['middleware' => ['IsAdmin', 'auth:api']], function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::resource('books', BookController::class);
        Route::resource('book-loans', BookLoanController::class);
    });

    Route::middleware('auth:api')->group(function () {

        //Public Protected Routes
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('books', [BookController::class, 'books.index']);
        Route::get('book-loans', [BookLoanController::class,'book-loans.index']);
    });
});
