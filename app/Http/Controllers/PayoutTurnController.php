<?php

namespace App\Http\Controllers;

use App\Models\PayoutTurnMethod;
use Illuminate\Http\Request;

class PayoutTurnController extends Controller
{
    public function getAll(){
        $payoutTurns = PayoutTurnMethod::all();
        return response()->json($payoutTurns, 200);
    }
    
}
