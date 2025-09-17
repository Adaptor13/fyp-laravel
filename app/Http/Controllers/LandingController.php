<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    public function landing()
    {
        return view('landing.index');
    }

    public function report()
{
    $prefillName   = null;
    $prefillEmail  = null;
    $prefillPhone  = null;

    $readonlyEmail = false;
    $readonlyPhone = false; // define it here

    if (Auth::check()) {
        $user = Auth::user()->loadMissing('publicUserProfile', 'profile');

        $prefillName  = optional($user->publicUserProfile)->display_name ?: $user->name;
        $prefillEmail = $user->email; // Pre-populate email
        $prefillPhone = optional($user->profile)->phone;

        // Keep email editable (not readonly) - users can modify it if needed
        // keep $readonlyPhone = false unless you want to lock it
    }

    return view('landing.report', compact(
        'prefillName',
        'prefillEmail',
        'prefillPhone',
        'readonlyEmail',
        'readonlyPhone'
    ));
}



    
}
