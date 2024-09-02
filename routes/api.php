<?php

use App\Http\Controllers\API\mahasiswa;
use App\Http\Controllers\API\userController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/halo', function () {
    return "Haloo Ini Gue";
});


Route::get('/mahasiswa', [mahasiswa::class, 'index']);
Route::post('/mahasiswa', [mahasiswa::class, 'store']);
Route::get('/mahasiswa/{id}', [mahasiswa::class, 'detail']);
Route::put('/mahasiswa/{id}', [mahasiswa::class, 'update']);
Route::delete('/mahasiswa/{id}', [mahasiswa::class, 'delete']);


// register
Route::post('/register', [userController::class, 'register']);
Route::post('/login', [userController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [userController::class, 'logout']);
});
