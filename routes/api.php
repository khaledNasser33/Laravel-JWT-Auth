<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetRequestController;
use App\Http\Controllers\ChangePasswordController;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(
    [
        'middleware' => 'api',
        'prefix'     => 'auth'
    ], function($router){
        Route::post('login',[AuthController::class,'login']);
        Route::post('register',[AuthController::class,'register']);
        Route::post('logout',[AuthController::class,'logout']);
        Route::post('refresh',[AuthController::class,'refresh']);
        Route::post('reset-password-request', [PasswordResetRequestController::class,'sendPasswordResetEmail']);
        Route::post('change-password', [ChangePasswordController::class,'passwordResetProcess']);
        Route::get('login',[AuthController::class,'login'])->name('login');
        Route::get('register',[AuthController::class,'register']);
        Route::get('logout',[AuthController::class,'logout']);
        Route::get('refresh',[AuthController::class,'refresh']);
        Route::get('user-profile', [AuthController::class,'userProfile']);
        Route::get('reset-password-request', [PasswordResetRequestController::class,'sendPasswordResetEmail']);
        Route::get('change-password', [ChangePasswordController::class,'passwordResetProcess']);
        // Route::get('email/resend',[AuthController::class,'resend'])->name('verification.resend');
        // Route::get('email/verify/{id}/{hash}',[AuthController::class,'verify'])->name('verification.verify');
    }

);