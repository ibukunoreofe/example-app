<?php

use App\Http\Controllers\API\Auth\AuthenticatedSessionController;
use App\Http\Controllers\API\Auth\RegisteredUserController;
use App\Http\Controllers\API\BooksController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/books', [BooksController::class, 'getBooks']);
    Route::post('/books', [BooksController::class, 'storeBook']);
    Route::put('/books/{book}', [BooksController::class, 'updateBook']);
    Route::delete('/books/{book}', [BooksController::class, 'deleteBook']);

    Route::post('/checkouts', [BooksController::class, 'checkouts']);
    Route::put('/checkouts/{checkout}', [BooksController::class, 'returnCheckout']);
});

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ->name('logout');

Route::post('/auth/google/login', [\App\Http\Controllers\API\Auth\GoogleAuthController::class, 'loginViaGoogleToken'])
    ->middleware('guest');

Route::post('/auth/social/fetch', [\App\Http\Controllers\API\Auth\DecodeSocialAuthController::class, 'getUser'])
    ->middleware('guest');
