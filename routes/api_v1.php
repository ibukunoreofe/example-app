<?php

use App\Http\Controllers\API\v1\Auth\AuthenticatedSessionController;
use App\Http\Controllers\API\v1\Auth\RegisteredUserController;
use App\Http\Controllers\API\v1\BooksController;
use App\Http\Controllers\API\v1\PermissionsController;
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
    Route::get('/books', [BooksController::class, 'getBooks'])->middleware("throttle:few");
    Route::post('/books', [BooksController::class, 'storeBook']);
    Route::put('/books/{book}', [BooksController::class, 'updateBook']);
    Route::delete('/books/{book}', [BooksController::class, 'deleteBook']);

    Route::resource('permissions', PermissionsController::class)->except(["create","edit"]);

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

Route::post('/auth/google/login', [\App\Http\Controllers\API\v1\Auth\GoogleAuthController::class, 'loginViaGoogleToken'])
    ->middleware('guest');

Route::post('/auth/social/fetch', [\App\Http\Controllers\API\v1\Auth\DecodeSocialAuthController::class, 'getUser'])
    ->middleware('guest');

Route::get('/test', function () {
    return request()->secure() ? 'Secure' : 'Not Secure';
});