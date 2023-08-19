<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Messenger\MessengerController;
use App\Http\Controllers\Api\Profile\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::prefix('v1')->name('v1.')->group(function () {
    Route::prefix('auth')->name('auth.')->group(function () {

        Route::post('register', [RegisterController::class, 'register'])->name('register');
        Route::post('register/resend-code/{userId}', [RegisterController::class, 'resendCode'])->name('resendCode');
        Route::post('register/email-verify/{key}', [RegisterController::class, 'verify'])->name('verify');

        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');
    });

    Route::group(['middleware' => ['auth:api']], function () {
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('me', [ProfileController::class, 'me'])->name('me');

        });
    });

    Route::prefix('messenger')->name('messenger.')->middleware('auth:api')->group(function () {
        Route::get('chats', [MessengerController::class, 'getChats'])->name('getChats');
        Route::post('chats', [MessengerController::class, 'createChat'])->name('createChat');
    });
});




