<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlbumController;

Route::get('/', function () {
    return redirect()->route('albums.index');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::resource('albums', AlbumController::class);
    Route::post('albums/fetch', [AlbumController::class, 'fetchFromLastFm'])->name('albums.fetch');
    Route::delete('/albums/{album}', [AlbumController::class, 'destroy'])->name('albums.destroy');
    Route::put('/albums/{album}', [AlbumController::class, 'update'])->name('albums.update');
});

Route::get('albums', [AlbumController::class, 'index'])->name('albums.index');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/fetch-album-data', [AlbumController::class, 'fetchAlbumData']);
