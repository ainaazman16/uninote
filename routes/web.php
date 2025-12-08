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

//Provider Application Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/provider/apply', [App\Http\Controllers\ProviderApplicationController::class, 'showForm'])->name('provider.apply');
    Route::post('/provider/apply', [App\Http\Controllers\ProviderApplicationController::class, 'submit'])->name('provider.apply.submit');
});

//Admin Provider Approval Routes
Route::middleware(['auth'])->group(function () {

    
    Route::middleware('admin')->group(function () {

        Route::get('/admin/provider-applications', 
            [App\Http\Controllers\AdminProviderApprovalController::class, 'index'])
            ->name('admin.provider.applications');

        Route::post('/admin/provider-applications/{id}/approve', 
            [App\Http\Controllers\AdminProviderApprovalController::class, 'approve'])
            ->name('admin.provider.approve');

        Route::post('/admin/provider-applications/{id}/reject', 
            [App\Http\Controllers\AdminProviderApprovalController::class, 'reject'])
            ->name('admin.provider.reject');
    });
});


// Route::middleware(['auth','provider'])->group(function () {
//     Route::get('/provider/notes/create', [NoteController::class, 'create'])->name('notes.create');
//     Route::post('/provider/notes',[NoteController::class,'store'])->name('notes.store');
// });

require __DIR__.'/auth.php';
