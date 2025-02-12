<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\SavingController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/notifications', function () {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    // return Auth::user()->notifications;
    // return response()->json(Auth::user()->notifications);
    // return response()->json(Auth::user()->notifications);
    return Auth::user()->unreadNotifications;
});


Route::middleware('auth:sanctum')->post('/notifications/{id}/mark-as-read', function ($id) {
    $notification = Auth::user()->unreadNotifications->find($id);

    if (!$notification) {
        return response()->json(['message' => 'Notification not found.'], 404);
    }

    $notification->markAsRead();

    return response()->json(['message' => 'Notification marked as read.']);
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/search-users', [UserController::class, 'searchUsers']);
    Route::post('/send-savings-membership-invite', [InviteController::class, 'sendInvite']);
    Route::post('/accept-membership-invite/{savingId}/{inviteToken}', [InviteController::class, 'acceptInvite']);
    Route::post('/select-payout-turn-for-members-choose/{savingId}', [SavingController::class, 'memberChooseTurn']);
    Route::post('/assign-payout-turn/{savingId}/{userId}', [MembershipController::class, 'assignPayoutTurn']);
});
