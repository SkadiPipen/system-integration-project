<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-b from-blue-100 via-gray-200 to-rose-200 font-sans">

    <!-- Back Arrow -->
    <div class="absolute top-6 left-6">
        <a href="{{ route('login') }}" class="flex items-center space-x-2 text-gray-600 hover:text-gray-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
    </div>

    <!-- Register Card -->
    <div class="bg-white rounded-2xl shadow-md w-full max-w-4xl p-10">
        <h2 class="text-2xl font-bold mb-6 text-center">REGISTER</h2>

        <form action="{{ route('signup') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf

            <!-- Left Column -->
            <div class="space-y-4">
                <div>
                    <input type="text" id="first_name" name="f_name"
                        placeholder="First Name"
                        class="w-full px-4 py-2 border border-gray-400 rounded-full placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300"
                        value="{{ old('f_name') }}">
                    <label for="first_name" class="text-gray-600 text-sm mt-1 block">First Name</label>
                    @error('f_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input type="text" id="last_name" name="l_name"
                        placeholder="Last Name"
                        class="w-full px-4 py-2 border border-gray-400 rounded-full placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300"
                        value="{{ old('l_name') }}">
                    <label for="last_name" class="text-gray-600 text-sm mt-1 block">Last Name</label>
                    @error('l_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input type="text" id="position" name="position"
                        placeholder="requestor/purchasor/owner"
                        class="w-full px-4 py-2 border border-gray-400 rounded-full placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300"
                        value="{{ old('position') }}">
                    <label for="position" class="text-gray-600 text-sm mt-1 block">Position</label>
                    @error('position')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4">
                <div>
                    <input type="email" id="email" name="e_email"
                        placeholder="email@example.com"
                        class="w-full px-4 py-2 border border-gray-400 rounded-full placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300"
                        value="{{ old('e_email') }}">
                    <label for="email" class="text-gray-600 text-sm mt-1 block">Email</label>
                    @error('e_email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input type="text" id="contact_number" name="e_num"
                        placeholder="09XX-XXX-XXXX"
                        class="w-full px-4 py-2 border border-gray-400 rounded-full placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300"
                        value="{{ old('e_num') }}">
                    <label for="contact_number" class="text-gray-600 text-sm mt-1 block">Contact Number</label>
                    @error('e_num')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative">
                    <input type="password" id="password" name="password"
                        placeholder="password"
                        class="w-full px-4 py-2 border border-gray-400 rounded-full placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <label for="password" class="text-gray-600 text-sm mt-1 block">Password</label>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        placeholder="confirm password"
                        class="w-full px-4 py-2 border border-gray-400 rounded-full placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <label for="password_confirmation" class="text-gray-600 text-sm mt-1 block">Confirm Password</label>
                </div>
            </div>

            <!-- Confirm Human + Button -->
            <div class="col-span-1 md:col-span-2 flex items-center justify-between mt-4">
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="human" class="accent-teal-500 w-5 h-5 border border-teal-500">
                    <label for="human" class="text-gray-700 text-sm">Confirm if you are human</label>
                </div>

                <button type="submit"
                    class="bg-blue-100 text-gray-900 font-semibold py-2 px-10 rounded-full hover:bg-blue-200 transition">
                    SIGN UP
                </button>
            </div>
        </form>
    </div>

</body>
</html>
