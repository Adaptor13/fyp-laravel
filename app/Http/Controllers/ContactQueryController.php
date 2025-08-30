<?php

namespace App\Http\Controllers;

use App\Models\ContactQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactQueryController extends Controller
{
    /**
     * Show the contact form.
     */
    public function show()
    {
        return view('landing.contact');
    }

    /**
     * Store a new contact query.
     */
    public function store(Request $request)
    {
        $request->validate(ContactQuery::rules());

        $contactQuery = ContactQuery::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'user_id' => Auth::id(), // Will be null for anonymous users
        ]);

        return redirect()->back()->with('success', 'Thank you for your message. We will get back to you soon!');
    }
}
