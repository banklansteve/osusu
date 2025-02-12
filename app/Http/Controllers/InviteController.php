<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Invite;
use App\Models\Saving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\MembershipInvite;
use Illuminate\Support\Facades\Session;
use App\Notifications\MembershipInviteAccepted;

class InviteController extends Controller
{
    public function sendInvite(Request $request){
        $request->validate([
            'saving' => 'required|numeric',
            'user' => 'required|numeric'
        ]);

        $auth = auth()->id();
        $authUser = User::findOrFail($auth);
        $req_user = $request->user;
        $user = User::findOrFail($req_user);

        $saving = $request->saving;

        $savingGroup = Saving::with('creator')->findOrFail($saving);
        
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $length = 12;
        $inviteToken = substr(str_shuffle(str_repeat($characters, $length)), 0, $length); 

        $invite = new Invite;
        $invite->user_id = $request->user;
        $invite->saving_id = $saving;
        $invite->invite_token = $inviteToken;
        $invite->sent_at = now();
        $invite->invite_method = 'in-app';
        $invite->save();

        $user->notify(new MembershipInvite($savingGroup, $inviteToken, $user));

        return response()->json($invite->load('user'), 200);
    }

    public function viewSavingInvite($token, $uid){
        $savingsGroup = Saving::where('savings_uid', $uid)->first();
        $invite = Invite::where('invite_token', $token)->first();

        return Inertia::render('User/ShowMembershipInvite', ['saving' => $savingsGroup, 'invite' => $invite]); 
    }

    public function acceptInvite($savingId, $inviteToken){
        $savingGroup = Saving::findOrFail($savingId);
        $invite = Invite::where('invite_token', $inviteToken)->first();

        $user_id = auth()->id();
        $user = User::findOrFail($user_id);
        
        //check if a member first
        if($savingGroup->isMember($user)){
            return response()->json(['message' => 'duplicates not allowed']);
        }

        $invite->update([
            $invite->status = 'accepted',
            $invite->accepted_at = now()
        ]);

        //check if group not complete
        $expectedMembers = $savingGroup->total_members;
        $membersCount = DB::table('saving_user')
                        ->where('saving_id', $savingId)
                        ->count();

        if ($membersCount >= $expectedMembers) {
            return response()->json(['message' => 'Savings group is already full'], 400);
        }

        $payoutTurn = null;

        if ($savingGroup->payout_turn_method_id == 1) {

            // Get already assigned payout turns
            $assignedTurns = DB::table('saving_user')
                            ->where('saving_id', $savingId)
                            ->pluck('payout_turn')
                            ->toArray();

             // Find available numbers
            $availableTurns = array_diff(range(1, $expectedMembers), $assignedTurns);

            if (empty($availableTurns)) {
                return response()->json(['message' => 'No available payout positions'], 400);
            }

            // Pick a random available payout turn
            $payoutTurn = $availableTurns[array_rand($availableTurns)];

        }else if($savingGroup->payout_turn_method_id == 2){
            $highestTurn = DB::table('saving_user')
                        ->where('saving_id', $savingId)
                        ->max('payout_turn');

            // Assign the next available number
            $payoutTurn = $highestTurn ? $highestTurn + 1 : 1;
        }else{
            $payoutTurn = null;
        }

        // $savingGroup->members()->syncWithoutDetaching($user->id);
        // manually set as we need to add payout turn
        DB::table('saving_user')->insert([
            'user_id' => $user->id,
            'saving_id' => $savingId,
            'payout_turn' => $payoutTurn,
            'payout_amount' => $savingGroup->total_payout,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $creator = $savingGroup->creator_id;
        $creator_obj = User::findOrFail($creator);

        // notification/email
        $creator_obj->notify(new MembershipInviteAccepted($inviteToken, $user, $savingGroup, $creator_obj));

        Session::flash('message', 'You have successfully accepted the membership of the saving group.');
        
        return response()->json(['message' => 'Member accepted'], 201);
    }

    public function viewInviteAccepted($token, $uid){
        $savingsGroup = Saving::where('savings_uid', $uid)->first();
        $invite = Invite::where('invite_token', $token)->first();

        return Inertia::render('User/ShowInviteAccepted', ['saving' => $savingsGroup, 'invite' => $invite ]); 
    }
}
