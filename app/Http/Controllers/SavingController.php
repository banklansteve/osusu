<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Saving;
use Illuminate\Http\Request;
use App\Models\MembershipRequest;
use Illuminate\Support\Facades\DB;
use App\Mail\MembershipRequestMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\SavingsRequest;
use App\Mail\MembershipRequestHasBeenSentMail;
use App\Notifications\MembershipRequestNotification;

class SavingController extends Controller
{
    public function store(SavingsRequest $request){
        $validated = $request->validated();
        $auth = auth()->id();

        $startDate = Carbon::createFromFormat('d-m-Y', $validated['start_date'])->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d-m-Y', $validated['end_date'])->format('Y-m-d');

        // dd($validated);
        $saving = new Saving;
        $saving->creator_id = $auth;
        $saving->title = $validated['title'];
        $saving->description = $validated['description'];
        $saving->saving_amount = $validated['saving_amount'];
        $saving->total_members = $validated['total_members'];
        $saving->total_payout = $validated['saving_amount'] * $validated['total_members'];
        $saving->savings_duration = $validated['savings_duration'];
        $saving->payout_turn_method_id = $validated['payout_turn'];
        $saving->payment_frequency = $validated['payment_frequency'];
        $saving->penalty_rate = $validated['penalty_rate'];
        $saving->start_date = $startDate;
        $saving->end_date = $endDate;
        $saving->save();

        //send email notification

        return redirect()->route('savings.show', $saving->savings_uid);
    }

    public function show(Request $request, $uid){
        // $saving = Saving::where('savings_uid', $uid)->first();
        
        $savingsGroup = Saving::with([
            'members' => function ($query) {
                $query->select('users.id', 'users.email', 'users.first_name', 'users.last_name')
                      ->orderBy('saving_user.created_at');
            }
        ])->where('savings_uid', $uid)->first();

        //get available savings turns 
        $assignedTurns = DB::table('saving_user')
                            ->where('saving_id', $savingsGroup->id)
                            ->pluck('payout_turn')
                            ->toArray();

             // Find available numbers
        // $availableTurns = array_diff(range(1, $savingsGroup->total_members), $assignedTurns);
        $availableTurns = array_values(array_diff(range(1, $savingsGroup->total_members), $assignedTurns));

        $members = $savingsGroup->members->map(function ($member) {
            return [
                'id' => $member->id,
                'uuid' => $member->uuid,
                'fullname' => $member->fullname,
                'email' => $member->email,
                'payout_turn' => $member->pivot->payout_turn,
                'joined_at' => $member->pivot->created_at->format('jS F, Y'), 
            ];
        });
    
    
        $auth = auth()->user();
        $user = User::findOrFail($auth->id);

        $memberTurn = null;

        if($savingsGroup->isMember($user)){
            $savingInstance = DB::table('saving_user')
                                ->where(['user_id' => $auth->id, 'saving_id' => $savingsGroup->id])
                                ->first();
            $memberTurn = $savingInstance->payout_turn;
        }

        // $savingGroup = DB::where('saving_id')

        //restrict view to members and creator by SavingPolicy.php
        $this->authorize('view', $savingsGroup);

        return Inertia::render("User/ShowSaving", [
            'saving' => $savingsGroup->load('creator', 'members'),
            'isCreator' => $savingsGroup->isCreator($user),
            'isMember' => $savingsGroup->isMember($user),
            'userSavings' => $user->savings()->with('creator')->get(),
            'createdSavings' => $user->createdSavings()->with('members')->get(),
            'members' => $members,
            'invites' => $savingsGroup->invites()->where('status', 'pending')->with('user', 'savingGroup')->get(),
            'availableTurns' => $availableTurns,
            'memberTurn' => $memberTurn
        ]);
    }

    public function savingsList(){
        $auth = auth()->user();
        // $savings = Saving::where('user_id', auth()->id())->get();
        $created = Saving::where('creator_id', $auth->id)->get();
        $savings = $auth->savings;
        return Inertia::render('User/MySavingsList', ['createdSavings' => $created, 'savings' => $savings]);
    }
    
    public function sendMembershipRequest(Request $request, $uid){
        
        $request->validate([
            'phone' => 'required|numeric',
        ]);

        $user_id = auth()->id();
        $user = User::findOrFail($user_id);

        $saving = Saving::where('savings_uid', $uid)->first();

        if ($saving->members()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'You are already a member of this savings group.'], 400);
        }

        $creator = $saving->creator;

        $user->update([
            'phone' => $request->phone
        ]);
            
        // dd($saving);
        $memReq = new MembershipRequest;
        $memReq->user_id = $user->id;
        $memReq->saving_id = $saving->id;
        $memReq->save();

        //send notification
        if($creator){
            $creator->notify(new MembershipRequestNotification($user, $saving, $memReq));

            Mail::to($creator->email)->send(new MembershipRequestMail($user, $saving));
        }

        Mail::to($user->email)->send(new MembershipRequestHasBeenSentMail($user, $saving));

        return redirect()->route('membership_req.sent');
    }
    
    public function joinSavingsGroup(Request $request, $id){
        $userId = auth()->id();
        $savingsGroup = Saving::findOrFail($id);

        $expectedMembers = $savingsGroup->total_members;

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

            // Save the user with the assigned payout turn
            // DB::table('saving_user')->insert([
            //     'user_id' => $userId,
            //     'savings_group_id' => $id,
            //     'payout_turn' => $randomTurn,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ]);

            // return response()->json([
            //     'message' => 'User joined successfully',
            //     'payout_turn' => $randomTurn
            // ], 201);

        }else if($savingsGroup->payout_turn_method_id == 2){
            $highestTurn = DB::table('saving_user')
                        ->where('saving_id', $id)
                        ->max('payout_turn');

            // Assign the next available number
            $payoutTurn = $highestTurn ? $highestTurn + 1 : 1;
        }else{
            $payoutTurn = null;
        }

        DB::table('saving_user')->insert([
            'user_id' => $userId,
            'saving_id' => $id,
            'payout_turn' => $payoutTurn,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
    
        return response()->json([
            'message' => 'User joined successfully',
            'payout_turn' => $payoutTurn
        ], 201);
    }


    public function memberChooseTurn(Request $request, $savingId){
        
        $request->validate([
            'turn' => 'required|numeric',
        ]);
        
        $auth = auth()->id();

        $user = User::findOrFail($auth);
        $saving = Saving::findOrFail($savingId);

        $user->savings()->updateExistingPivot($saving->id, [
            'payout_turn' => $request->turn
        ]);

        return response()->json(['message' => 'Turn set'], 201);
    }

    // public function createdSavings(){
    //     $auth = auth()->user();
    //     $savings = Saving::where('creator_id', $auth->id)->get();
    //     return Inertia::render('User/MySavingsList', ['savings' => $savings]);
    // }
}
