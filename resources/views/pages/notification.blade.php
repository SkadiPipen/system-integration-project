@extends('layouts.app')

@section('title', 'Notification | Fesmera Inc.')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">NOTIFICATION</h1>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($notifications as $notification)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $notification->message }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ ucfirst($notification->type) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $notification->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            @if($notification->read)
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Read</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Unread</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No notifications</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between mt-4 p-4 bg-gray-50">
            <form action="{{ route('notification.destroy') }}" method="POST" onsubmit="return confirm('Clear all notifications?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                    Clear All
                </button>
            </form>
            
            <form action="{{ route('notification.mark-as-read') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-900 transition">
                    Mark as Read
                </button>
            </form>
        </div>
    </div>
</div>
@endsection