<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\PasswordResetRequestController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::prefix('auth')->group(function () {
    Route::post('register', [RegisterController::class, 'handle']);
    Route::post('login', [LoginController::class, 'handle']);
    Route::post('reset-password', [PasswordResetRequestController::class, 'sendPasswordResetEmail']);
    Route::post('change-password', [ChangePasswordController::class, 'passwordResetProcess']);

    Route::group(['middleware' => ['jwt.verify']], function () {
        Route::post('logout', [LogoutController::class, 'handle'])->middleware('jwt.refresh');
        Route::get('/user', [UserController::class, 'getMe']);
    });
});

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('/user', [UserController::class, 'index']);
});
