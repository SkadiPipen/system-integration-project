<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function show()
    {
        return view('pages.settings');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Save User Preferences
        $user->setSetting('default_page', $request->default_page);
        $user->setSetting('theme', $request->theme);
        $user->setSetting('email_alerts', $request->has('email_alerts'));
        $user->setSetting('sms_alerts', $request->has('sms_alerts'));
        $user->setSetting('system_alerts', $request->has('system_alerts'));

        // Owner-only settings
        if ($user->position === 'owner') {
            $user->setSetting('min_stock_threshold', $request->min_stock_threshold);
            $user->setSetting('payment_terms', $request->payment_terms);
        }

        return redirect()->back()->with('success', 'Settings saved successfully!');
    }
}