<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        return view('profile.show', ['user' => $user]);
    }
}
