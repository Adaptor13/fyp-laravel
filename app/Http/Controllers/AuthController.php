<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function signIn()
    {
        return view('auth.sign_in');
    }

    public function signUp()
    {
        return view('auth.sign_up');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required' => 'Please enter your username.',
            'email.required' => 'Email is required!',
            'email.email' => 'Enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'You must enter a password.',
            'password.min' => 'Password must be at least :min characters.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        $role = Role::where('name', 'public_user')->first(); // change to your role name if needed

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $role->id,
        ]);

        return redirect()->route('sign_in')->with('success', 'Account created successfully. Please log in.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Please enter your email.',
            'password.required' => 'Password is required.',
        ]);

        $remember = $request->has('remember'); // Remember Me checkbox

        // Attempt login
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            $request->session()->regenerate(); // Prevent session fixation

            // Store role in session for quick access (optional)
            session(['role' => Auth::user()->role->name]);

            // Redirect based on role
            if (Auth::user()->role->name === 'public_user') {
        // dd(Auth::check(), Auth::user());

                return redirect()->route('landing'); // Public user homepage
            }

            return redirect()->route('admin_index'); // Admin dashboard
        }
        // If login fails
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        // Get the role before logging out
        $role = Auth::check() ? Auth::user()->role->name : null;

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect based on role
        if ($role === 'public_user') {
            return redirect()->route('landing');
        } else {
            return redirect()->route('sign_in');
        }
    }




}
