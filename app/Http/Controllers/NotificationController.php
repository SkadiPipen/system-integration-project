<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->get();
        return view('pages.notification', compact('notifications'));
    }

    public function markAsRead(Request $request)
    {
        Auth::user()->notifications()->where('read', false)->update(['read' => true]);
        return redirect()->back();
    }

    public function destroy(Request $request)
    {
        Auth::user()->notifications()->delete();
        return redirect()->back();
    }
}