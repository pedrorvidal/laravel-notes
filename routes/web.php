<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Middleware\CheckIsLogged;
use App\Http\Middleware\CheckIsNotLogged;
use Illuminate\Support\Facades\Route;

//Auth routes - middleware user not logged
Route::middleware([CheckIsNotLogged::class])->group(function () {
    Route::get('/login', [AuthController::class, 'login']);
    Route::post('/loginSubmit', [AuthController::class, 'loginSubmit']);
});

// authenticated routes, verified by middleware user logged
Route::middleware([CheckIsLogged::class])->group(function () {
    Route::get('/', [MainController::class, 'index'])->name('home');
    Route::get('/newNote', [MainController::class, 'newNote'])->name('new');

    //Edit note
    Route::get('/editNote/{id}', [MainController::class, 'editNote'])->name('edit');
    //Delete note
    Route::get('/deleteNote/{id}', [MainController::class, 'deleteNote'])->name('delete');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Route::get('/', [MainController::class, 'index']);
// Route::get('/newNote', [MainController::class, 'newNote']);
// Route::get('/logout', [AuthController::class, 'logout']);
