<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function searchUsers(Request $request){
        $query = $request->input('query');
        $users = User::where('first_name', 'LIKE', '%' . $query . '%')
                        ->orWhere('last_name', 'LIKE', '%'.$query.'%') 
                        ->orWhere('email', 'LIKE', '%'.$query.'%') 
                        ->get();
    
        return response()->json([
            'users' => $users
        ]);
    }
}
