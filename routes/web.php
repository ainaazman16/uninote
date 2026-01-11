<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminNoteController;
use App\Http\Controllers\AdminWithdrawalController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\AdminRatingController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\StudentWalletController;
use App\Http\Controllers\AdminWalletTopupController;
use App\Http\Controllers\NoteRatingController;
use App\Http\Controllers\ProviderRatingAnalyticsController;
use App\Http\Controllers\ProviderAnalyticsController;
use App\Http\Controllers\StudentQuizController;
use App\Http\Controllers\ProviderQuizController;
use App\Http\Controllers\ProviderDashboardController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProviderWithdrawalController;
use App\Http\Controllers\RatingController;
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

    Route::get('/student/transactions', [TransactionController::class, 'index'])
    ->name('student.transactions.index');

    Route::get('/providers', [ProviderController::class, 'index'])
    ->name('provider.index');

    Route::get('/providers/{provider}', [ProviderController::class, 'show'])
    ->name('providers.show');

    Route::get(
    '/student/subscriptions',
    [SubscriptionController::class, 'mySubscriptions']
)->name('student.subscriptions.index');


    Route::get('/student/providers/search',
        [StudentDashboardController::class, 'searchProviders']
    )->name('student.providers.search');

    Route::get('/dashboard', [StudentDashboardController::class, 'dashboard'])
        ->middleware('user.active')
        ->name('dashboard');
        


    Route::post('/notes/{note}/rate', [NoteRatingController::class, 'store'])
    ->middleware('auth')
    ->name('notes.rate');
    
    Route::put('/ratings/{rating}', 
        [RatingController::class, 'update']
    )->name('ratings.update');

    Route::delete('/ratings/{rating}', 
        [RatingController::class, 'destroy']
    )->name('ratings.destroy');


});

Route::prefix('student')->middleware('auth')->group(function () {
    // Wallet overview
    Route::get('wallet',
        [StudentWalletController::class, 'index']
    )->name('student.wallet.index');

    Route::get('wallet/topup',
        [StudentWalletController::class, 'create']
    )->name('student.wallet.topup.form');

    Route::post('wallet/topup',
        [StudentWalletController::class, 'store']
    )->name('student.wallet.topup.process');


    // Show quiz attempt page
    Route::get(
        'notes/{note}/quiz',
        [StudentQuizController::class, 'attempt']
    )->name('student.quiz.attempt');

    // Submit quiz
    Route::post(
        'notes/{note}/quiz',
        [StudentQuizController::class, 'submit']
    )->name('student.quiz.submit');

    // View result
    Route::get(
        'quiz-attempts/{attempt}/result',
        [StudentQuizController::class, 'result']
    )->name('student.quiz.result');

    
});

Route::post(
    '/providers/{provider}/rate',
    [RatingController::class, 'store']
)->middleware('auth')->name('providers.rate');


Route::middleware(['auth', 'provider'])->group(function () {

    Route::get('/provider/dashboard', [ProviderDashboardController::class, 'index'])->name('provider.dashboard');

    Route::get('/provider/notes', [NoteController::class, 'index'])
        ->name('provider.notes.index');

    Route::get('/provider/notes/create', [NoteController::class, 'create'])
        ->name('provider.notes.create');

    Route::post('/provider/notes', [NoteController::class, 'store'])
        ->name('provider.notes.store');

         Route::get('/provider/notes/{note}/edit',
        [NoteController::class, 'edit']
    )->name('provider.notes.edit');

    Route::put('/provider/notes/{note}',
        [NoteController::class, 'update']
    )->name('provider.notes.update');

    Route::delete('/provider/notes/{note}',
        [NoteController::class, 'destroy']
    )->name('provider.notes.destroy');
    
    Route::get('/provider/withdrawals', [ProviderWithdrawalController::class, 'index'])
    ->name('provider.withdrawals.index');

    Route::post('/provider/withdraw', [ProviderWithdrawalController::class, 'store'])
    ->name('provider.withdraw');

    Route::post('/subscriptions/{provider}', 
        [SubscriptionController::class, 'store']
        )->name('subscriptions.store');

        Route::get('/provider/notes/{note}/quiz/create',
        [ProviderQuizController::class, 'create']
    )->name('provider.quiz.create');

    Route::post('/provider/notes/{note}/quiz',
        [ProviderQuizController::class, 'store']
    )->name('provider.quiz.store');

    Route::get('/provider/quiz/{quiz}/edit',
        [ProviderQuizController::class, 'edit']
    )->name('provider.quiz.edit');

    Route::put('/provider/quiz/{quiz}',
        [ProviderQuizController::class, 'update']
    )->name('provider.quiz.update');

     Route::get('/provider/analytics', 
        [ProviderAnalyticsController::class, 'index']
    )->name('provider.analytics');

    Route::get('/support/chat', [App\Http\Controllers\ProviderChatController::class, 'index'])
            ->name('provider.chat');

        Route::post('/support/chat/send', [App\Http\Controllers\ProviderChatController::class, 'send'])
            ->name('provider.chat.send');

        Route::get('/support/chat/{chat}/messages', [App\Http\Controllers\ProviderChatController::class, 'getMessages'])
            ->name('provider.chat.messages');

});
Route::get('/provider/notifications', function () {
    return view('provider.notifications', [
        'notifications' => auth()->user()->notifications
    ]);
})->middleware(['auth', 'provider'])->name('provider.notifications');
Route::get('/provider/analytics/ratings', 
    [ProviderRatingAnalyticsController::class, 'index']
)->middleware(['auth', 'provider'])
 ->name('provider.analytics.ratings');


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
    Route::post('/subscriptions/{subscription}/cancel', 
        [SubscriptionController::class, 'cancel']
    )->name('subscriptions.cancel');

});

Route::post('/subscriptions/{provider}', 
    [SubscriptionController::class, 'store']
)->name('subscriptions.store')->middleware('auth');


//Admin Provider Approval Routes
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/notifications', [AdminNotificationController::class, 'index'])
        ->name('admin.notifications');

    // Admin dashboard
     Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
    
    Route::get('/admin/users', 
        [AdminUserController::class, 'index']
    )->name('admin.users.index');

    Route::post('/admin/users/{user}/toggle-status',
        [AdminUserController::class, 'toggleStatus']
    )->name('admin.users.toggleStatus');
    Route::post('/admin/users/{user}/suspend',
        [AdminUserController::class, 'suspend']
    )->name('admin.users.suspend');

    Route::post('/admin/users/{user}/unsuspend',
        [AdminUserController::class, 'unsuspend']
    )->name('admin.users.unsuspend');

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

     Route::get('/wallet-topups', [AdminWalletTopupController::class, 'index'])
        ->name('admin.wallet.topups');

    Route::post('/wallet-topups/{topup}/approve', [AdminWalletTopupController::class, 'approve'])
        ->name('admin.wallet.topups.approve');

    Route::post('/wallet-topups/{topup}/reject', [AdminWalletTopupController::class, 'reject'])
        ->name('admin.wallet.topups.reject');

     Route::get('/admin/ratings', [AdminRatingController::class, 'index'])
        ->name('admin.ratings.index');

    Route::delete('/admin/ratings/{rating}', [AdminRatingController::class, 'destroy'])
        ->name('admin.ratings.destroy');

    //Admin withdrawal approval
      Route::get('/admin/withdrawals', [AdminWithdrawalController::class, 'index'])
        ->name('admin.withdrawals.index');

    Route::post('/admin/withdrawals/{withdrawal}/approve', [AdminWithdrawalController::class, 'approve'])
        ->name('admin.withdrawals.approve');

    Route::post('/admin/withdrawals/{withdrawal}/reject', [AdminWithdrawalController::class, 'reject'])
        ->name('admin.withdrawals.reject');

    // Admin Chat Management
    Route::get('/admin/chats', [App\Http\Controllers\AdminChatController::class, 'index'])
        ->name('admin.chats');

    Route::get('/admin/chats/{chat}', [App\Http\Controllers\AdminChatController::class, 'show'])
        ->name('admin.chats.show');

    Route::post('/admin/chats/{chat}/send', [App\Http\Controllers\AdminChatController::class, 'send'])
        ->name('admin.chats.send');

    Route::get('/admin/chats/{chat}/messages', [App\Http\Controllers\AdminChatController::class, 'getMessages'])
        ->name('admin.chats.messages');
});

 
Route::middleware(['auth', 'admin', 'user.active'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/users', [AdminUserController::class, 'index'])
            ->name('admin.users.index');

});


require __DIR__.'/auth.php';


