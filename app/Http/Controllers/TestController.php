<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Events\TestEvent;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function showTest(){
        $auth = auth()->id();
        $user = User::findOrFail($auth);
        return Inertia::render('Test', ['user' => $user]);
    }


    public function createTestEvent(Request $request){
        $auth = auth()->id();
        $user = User::findOrFail($auth);

        broadcast(new TestEvent($user));
        // return redirect()->route('test.show');
        // return Inertia::render('Test', ['user' => $user]);
    }
}
