@extends('layouts.app')

@section('title', 'Profile | Fesmera Inc.')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">PROFILE</h1>

    <div class="bg-white rounded-xl shadow-lg p-6 max-w-2xl">
        <!-- Form -->
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Full Name</label>
                    <input type="text" name="f_name" value="{{ old('f_name', Auth::user()->f_name) }}" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Position</label>
                    <input type="text" value="{{ ucfirst(Auth::user()->position) }}" disabled
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Contact Number</label>
                    <input type="text" name="contact_num" value="{{ old('contact_num', Auth::user()->contact_num) }}" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Email</label>
                    <input type="email" value="{{ Auth::user()->e_email }}" disabled
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Role</label>
                    <input type="text" value="User" disabled
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Date Joined</label>
                    <input type="text" value="{{ Auth::user()->created_at->format('M d, Y') }}" disabled
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-center mt-8 space-x-4">
                    <button type="button" class="px-6 py-2 bg-yellow-100 text-yellow-800 rounded-md hover:bg-yellow-200 transition">
                        Add Contact Number
                    </button>
                @if(Auth::user()->position === 'owner')
                    <button type="button" class="px-6 py-2 bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200 transition">
                        Edit Profile
                    </button>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        Save Changes
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection