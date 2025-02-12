<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Saving;
use Illuminate\Http\Request;
use App\Models\MembershipRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MembershipController extends Controller
{
    public function approveMembershipReq($id){
        $req = MembershipRequest::findOrFail($id);
        $req->update([
            'status' => 'approved'
        ]);

        $saving = Saving::findOrFail($req->saving_id);
        // $user = User::findOrFail($req->user_id);

        $saving->members()->syncWithoutDetaching([$req->user_id]);

        Session::flash('message', 'The membership request has been approved.');

        //send emails to them
        return response()->json(['message' => 'successful']);
    }

    public function addCreatorToSavings(Request $request, $id){
        $userId = auth()->id();
        $savingsGroup = Saving::findOrFail($id);

        $expectedMembers = $savingsGroup->total_members;
        $contrib = $savingsGroup->saving_amount;
        $payout_amount = $expectedMembers * $contrib;

        //check if the group has the expected number of members already
        $membersCount = DB::table('saving_user')
                        ->where('saving_id', $id)
                        ->count();

        // Check if the group is already full
        if ($membersCount >= $expectedMembers) {
            return response()->json(['message' => 'Savings group is already full'], 400);
        }

        $payoutTurn = null;

        if ($savingsGroup->payout_turn_method_id == 1) {

            // Get already assigned payout turns
            $assignedTurns = DB::table('saving_user')
                            ->where('saving_id', $id)
                            ->pluck('payout_turn')
                            ->toArray();

             // Find available numbers
            $availableTurns = array_diff(range(1, $expectedMembers), $assignedTurns);

            if (empty($availableTurns)) {
                return response()->json(['message' => 'No available payout positions'], 400);
            }

            // Pick a random available payout turn
            $payoutTurn = $availableTurns[array_rand($availableTurns)];

            
        }else if($savingsGroup->payout_turn_method_id == 2){
            $highestTurn = DB::table('saving_user')
                        ->where('saving_id', $id)
                        ->max('payout_turn');

            // Assign the next available number
            $payoutTurn = $highestTurn ? $highestTurn + 1 : 1;
        }else{
            $payoutTurn = null;
        }

        // DB::table('saving_user')->insert([
        //     'user_id' => $userId,
        //     'saving_id' => $id,
        //     'payout_turn' => $payoutTurn,
        //     'payout_amount' => $payout_amount,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        $savingsGroup->members()->attach($userId, [
            'payout_turn' => $payoutTurn,
            'payout_amount' => $payout_amount,
        ]);

        $newMember = $savingsGroup->members()
                    ->where('user_id', $userId)
                    ->first();

        // Session::flash('message', 'You have been successfully added as a member of the saving group.');
    
        return response()->json([
            'message' => 'Member joined successfully',
            'payout_turn' => $payoutTurn,
            'fullname' => $newMember->fullname,
            'joined_at'   => $newMember->pivot->created_at->format('d-m-Y'),
        ], 201);

    }

    public function setMemberPayoutTurn($uid, $userId){
        $savingGroup = Saving::where('savings_uid', $uid)->first();
        $user = User::findOrFail($userId);

        //get available savings turns 
        $assignedTurns = DB::table('saving_user')
                            ->where('saving_id', $savingGroup->id)
                            ->pluck('payout_turn')
                            ->toArray();

        $availableTurns = array_values(array_diff(range(1, $savingGroup->total_members), $assignedTurns));

        return Inertia::render("User/SetMemberPayoutTurn", ['saving' => $savingGroup, 'user' => $user, 'availableTurns' => $availableTurns]);
    }

    public function assignPayoutTurn(Request $request, $savingId, $userId){
        $user = User::findOrFail($userId);
        $saving = Saving::findOrFail($savingId);

        $user->savings()->updateExistingPivot($savingId, [
            'payout_turn' => $request->turn
        ]);

        Session::flash('message', `You have successfully assigned a payout turn to the member .$user->fullname`);
        return response()->json(['message' => 'Turn assigned'], 201);
        // return redirect()->route('savings.show', $saving->savings_uid);
    }
}
