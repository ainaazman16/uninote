<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProviderDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/provider/notes/create', [NoteController::class, 'create'])->name('notes.create');
    Route::post('/provider/notes', [NoteController::class, 'store'])->name('notes.store');

});

Route::middleware(['auth'])->group(function () {
    Route::get('/provider/dashboard', [ProviderDashboardController::class, 'index'])->name('provider.dashboard');
});


// Route::middleware(['auth','provider'])->group(function () {
//     Route::get('/provider/notes/create', [NoteController::class, 'create'])->name('notes.create');
//     Route::post('/provider/notes',[NoteController::class,'store'])->name('notes.store');
// });

require __DIR__.'/auth.php';
