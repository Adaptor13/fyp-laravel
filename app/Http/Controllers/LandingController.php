<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function landing()
    {
        return view('landing.index');
    }

    public function report()
    {
        return view('landing.report');
    }

    
}
