<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRegisterRequest;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // Show registration page
    public function showRegisterForm()
    {
        return view('pages.signup');
    }

    // Handle registration
    public function register(AuthRegisterRequest $request)
    {
        Employee::create($request->validated());
        return redirect()->route('login.form')->with('success', 'Registration successful. Please login.');
    }

    // Show login page
    public function showLoginForm()
    {
        return view('pages.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'e_email' => 'required|email',
            'password' => 'required',
        ]);

        Log::info('Login attempt:', $credentials);
        
        // Check if employee exists
        $employee = Employee::where('e_email', $credentials['e_email'])->first();
        Log::info('Employee found:', [$employee ? $employee->id : 'not found']);

        if (Auth::attempt($credentials)) {
            Log::info('Login successful');
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        Log::info('Login failed');
        return back()->withErrors([
            'e_email' => 'Invalid email or password.',
        ])->withInput($request->only('e_email'));
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}