<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PostController;
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
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('token', [AuthController::class, 'token']);

Route::middleware('auth:sanctum')->group(function () {
    // Список разрешений
    Route::get('permissions', [PermissionController::class, 'index']);
    // Роли в системе. Управление разрешениями роли
    Route::apiResource('roles', RoleController::class);
    Route::get('roles/{id}/permissions', [RoleController::class, 'showPermissions']);
    Route::put('roles/{id}/permissions', [RoleController::class, 'attachPermissions']);
    // Получение и изменение списка ролей для текущего пользователя
    Route::get('user/roles', [UserController::class, 'showRoles']);
    Route::put('user/roles', [UserController::class, 'attachRoles']);
    // Посты
    Route::apiResource('posts', PostController::class);
});