<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        return view('pages.profile', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'f_name' => 'required|string|max:255',
            'l_name' => 'nullable|string|max:255',
            'contact_num' => 'nullable|string|max:20',
        ]);

        Auth::user()->update($request->only(['f_name', 'l_name', 'contact_num']));

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}