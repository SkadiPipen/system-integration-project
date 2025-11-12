@extends('layouts.app')

@section('title', 'Settings | Fesmera Inc.')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">SETTINGS</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('settings.update') }}">
            @csrf

            <h2 class="text-xl font-semibold mb-4">User Preferences</h2>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Default Page on Login</label>
                <select name="default_page" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="inventory" {{ Auth::user()->getSetting('default_page', 'inventory') == 'inventory' ? 'selected' : '' }}>Inventory</option>
                    <option value="purchase" {{ Auth::user()->getSetting('default_page') == 'purchase' ? 'selected' : '' }}>Purchase</option>
                    <option value="requisition" {{ Auth::user()->getSetting('default_page') == 'requisition' ? 'selected' : '' }}>Requisition</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Theme Mode</label>
                <div class="flex items-center space-x-4">
                    <label class="flex items-center">
                        <input type="radio" name="theme" value="light" {{ Auth::user()->getSetting('theme', 'light') == 'light' ? 'checked' : '' }}>
                        <span class="ml-2">Light</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="theme" value="dark" {{ Auth::user()->getSetting('theme') == 'dark' ? 'checked' : '' }}>
                        <span class="ml-2">Dark</span>
                    </label>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notification Settings</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="email_alerts" {{ Auth::user()->getSetting('email_alerts') ? 'checked' : '' }}>
                        <span class="ml-2">Email alerts</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="sms_alerts" {{ Auth::user()->getSetting('sms_alerts') ? 'checked' : '' }}>
                        <span class="ml-2">SMS alerts</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="system_alerts" {{ Auth::user()->getSetting('system_alerts', true) ? 'checked' : '' }}>
                        <span class="ml-2">System-only alerts</span>
                    </label>
                </div>
            </div>

            <!-- Account Security -->
            <!-- <div class="mt-6">
                <h2 class="text-xl font-semibold mb-4">Account Security</h2>
                <a href="#" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition">
                    Change Password
                </a>
            </div> -->

            <!-- System Configuration (Owner Only) -->
            @if(Auth::user()->position === 'owner')
            <div class="mt-6">
                <h2 class="text-xl font-semibold mb-4">System Configuration (Owner Only)</h2>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Stock Alert Threshold</label>
                    <input type="number" name="min_stock_threshold" 
                           value="{{ Auth::user()->getSetting('min_stock_threshold', 10) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Terms</label>
                    <input type="text" name="payment_terms" 
                           value="{{ Auth::user()->getSetting('payment_terms', 'Collect after 7 days') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
            </div>
            @endif

            <div class="mt-6">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection