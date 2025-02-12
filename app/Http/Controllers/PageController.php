<?php

namespace App\Http\Controllers;

use App\Models\MembershipRequest;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Saving;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function faq(){
        return Inertia::render('FAQ'); 
    }
    public function termsConditions(){
        return Inertia::render('TermsConditions'); 
    }

    public function joinSaving($uid){
        $saving = Saving::where('savings_uid', $uid)->first();
        // $user = User::findOrFail(auth()->id());
        
        return Inertia::render('Join', ['saving' => $saving]);
    }
    
    public function joinRequest($uid){
        $saving = Saving::where('savings_uid', $uid)->first();

        return Inertia::render('JoinRequest', ['saving' => $saving]);
    }
    
    public function MembershipRequestSent(){
        
        return Inertia::render('MembershipRequestSent');
    }
    
    public function showMembershipRequest($id){
        $req = MembershipRequest::with(['savings', 'user'])->findOrFail($id);
        
        return Inertia::render('User/ShowMembershipRequest', ['membershipReq' => $req]);
    }
}
