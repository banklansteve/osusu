<?php

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\SavingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthPagesController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\PayoutTurnController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/terms-conditions', [PageController::class, 'termsConditions'])->name('terms.conditions');
Route::get('/join/{uid}', [PageController::class, 'joinSaving']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [AuthPagesController::class, 'showDashboard'])->name('dashboard');
    Route::get('/create-new', [AuthPagesController::class, 'createSavings'])->name('create-savings');
    Route::get('/get_payment_turn_methods', [PayoutTurnController::class, 'getAll']);
    Route::post('/create_savings', [SavingController::class, 'store'])->name('savings.store');
    Route::get('/my-savings', [SavingController::class, 'savingsList'])->name('savings.list');
    Route::get('/saving/{uid}', [SavingController::class, 'show'])->name('savings.show');
    Route::get('/join-request/{uid}', [PageController::class, 'joinRequest'])->name('join.request');
    Route::post('/post-membership-request/{uid}', [SavingController::class, 'sendMembershipRequest']);
    Route::get('/membership-request-sent', [PageController::class, 'MembershipRequestSent'])->name('membership_req.sent');
    Route::get('/membership-request/{id}', [PageController::class, 'showMembershipRequest'])->name('show.membership_req');
    Route::post('/approve-membership-request/{id}', [MembershipController::class, 'approveMembershipReq']);
    Route::post('/add-creator-to-savings-group/{id}', [MembershipController::class, 'addCreatorToSavings']);
    Route::get('/savings-membership-invite/{token}/{savings_uid}', [InviteController::class, 'viewSavingInvite'])->name('saving_invite.show');
    Route::get('/savings-invite-accepted/{token}/{savings_uid}', [InviteController::class, 'viewInviteAccepted'])->name('invite_accepted.show');
    Route::get('/membership-payout-turn/{uid}/{userId}', [MembershipController::class, 'setMemberPayoutTurn'])->name('member_payout_turn.show');
    Route::post('/create_test_event_for_websocket', [TestController::class, 'createTestEvent']);
    Route::get('/test', [TestController::class, 'showTest'])->name('test.show');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// / Email verification notice route
Route::get('/email/verify', function () {
    return inertia('Auth/VerifyEmail'); // Vue component for verification notice
})->middleware('auth')->name('verification.notice');


// Email verification handler
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // Mark email as verified
    return redirect('/dashboard'); // Redirect after successful verification
})->middleware(['auth', 'signed'])->name('verification.verify');


// Resend email verification link
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');



require __DIR__.'/auth.php';
