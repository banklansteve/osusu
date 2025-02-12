<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class AuthPagesController extends Controller
{
    public function showDashboard(){
        return Inertia::render('User/Dashboard');
    }

    public function createSavings(){
        return Inertia::render('User/CreateSavings');
    }

    public function showSaving(){
        return Inertia::render('User/ShowMySaving');
    }
}
