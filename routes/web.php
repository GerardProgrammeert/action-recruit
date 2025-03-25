<?php

use App\Http\Controllers\ProfilesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/profiles', [ProfilesController::class, 'index'])->name('profiles.index');
Route::get('/profiles/tables', [ProfilesController::class, 'table'])->name('profiles.table');

Route::post('/profiles/{profile}', [ProfilesController::class, 'done'])->name('profiles.done');
