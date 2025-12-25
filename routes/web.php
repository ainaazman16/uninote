<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NoteController;

use App\Http\Controllers\AdminNoteController;
use App\Http\Controllers\AdminProviderApprovalController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ProviderDashboardController;
use App\Http\Controllers\StudentDashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ProviderController;

// Route::get('/', function () {
//     return 'HOME PAGE TEST';
// });


Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        if (auth()->user()->role === 'provider') {
            return redirect('/provider/dashboard');
        }

        return redirect('/dashboard');
    }

    return redirect('/login');
});

// View public profile (student or provider)
Route::get('/users/{user}', [ProfileController::class, 'show'])
    ->name('profile.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

     Route::get('/notes', [\App\Http\Controllers\StudentNoteController::class, 'index'])
        ->name('student.notes.index');

    Route::get('/notes/{note}', [\App\Http\Controllers\StudentNoteController::class, 'show'])
        ->name('student.notes.show');

    Route::get('/notes/{note}/download', [\App\Http\Controllers\StudentNoteController::class, 'download'])
        ->name('student.notes.download');

    Route::get('/providers/{user}', [App\Http\Controllers\StudentProviderController::class, 'show'])
    ->name('student.providers.show');

    Route::get('/providers', [ProviderController::class, 'index'])
    ->name('provider.index');

    Route::get('/providers/{provider}', [ProviderController::class, 'show'])
    ->name('providers.show');

    Route::get('/student/providers/search',
        [StudentDashboardController::class, 'searchProviders']
    )->name('student.providers.search');

    Route::get('/dashboard', [StudentDashboardController::class, 'dashboard'])
        ->name('dashboard');

});

// Route::middleware(['auth'])->group(function () {
//     Route::get('/provider/notes/create', [NoteController::class, 'create'])->name('notes.create');
//     Route::post('/provider/notes', [NoteController::class, 'store'])->name('notes.store');

// });

// Route::middleware(['auth'])->group(function () {
//     Route::get('/provider/dashboard', [ProviderDashboardController::class, 'index'])->name('provider.dashboard');
// });

Route::middleware(['auth', 'provider'])->group(function () {

    Route::get('/provider/dashboard', [ProviderDashboardController::class, 'index'])->name('provider.dashboard');

    Route::get('/provider/notes', [NoteController::class, 'index'])
        ->name('provider.notes.index');

    Route::get('/provider/notes/create', [NoteController::class, 'create'])
        ->name('provider.notes.create');

    Route::post('/provider/notes', [NoteController::class, 'store'])
        ->name('provider.notes.store');

    Route::post('/subscriptions/{provider}', 
        [SubscriptionController::class, 'store']
        )->name('subscriptions.store');

});

Route::middleware('auth')->group(function () {

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

});


//Provider Application Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/provider/apply', [App\Http\Controllers\ProviderApplicationController::class, 'showForm'])->name('provider.apply');
    Route::post('/provider/apply', [App\Http\Controllers\ProviderApplicationController::class, 'submit'])->name('provider.apply.submit');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/subscribe/{provider}', [SubscriptionController::class, 'subscribe'])
        ->name('subscribe');
});

Route::post('/subscriptions/{provider}', 
    [SubscriptionController::class, 'store']
)->name('subscriptions.store')->middleware('auth');


//Admin Provider Approval Routes
Route::middleware(['auth', 'admin'])->group(function () {

    // Admin dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/provider-applications', 
        [App\Http\Controllers\AdminProviderApprovalController::class, 'index'])
        ->name('admin.provider.applications');

    Route::post('/admin/provider-applications/{id}/approve', 
        [App\Http\Controllers\AdminProviderApprovalController::class, 'approve'])
        ->name('admin.provider.approve');

    Route::post('/admin/provider-applications/{id}/reject', 
        [App\Http\Controllers\AdminProviderApprovalController::class, 'reject'])
        ->name('admin.provider.reject');

    // Admin notes approval
    Route::get('/admin/notes', [AdminNoteController::class, 'index'])
         ->name('admin.notes.index');

    Route::post('/admin/notes/{note}/approve', [AdminNoteController::class, 'approve'])
         ->name('admin.notes.approve');

    Route::post('/admin/notes/{note}/reject', [AdminNoteController::class, 'reject'])
         ->name('admin.notes.reject');

    Route::get('/admin/users/{user}', [AdminUserController::class, 'show'])
        ->name('admin.users.show');
});

Route::get('/dashboard', [StudentDashboardController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// Route::middleware(['auth','provider'])->group(function () {
//     Route::get('/provider/notes/create', [NoteController::class, 'create'])->name('notes.create');
//     Route::post('/provider/notes',[NoteController::class,'store'])->name('notes.store');
// });

require __DIR__.'/auth.php';
