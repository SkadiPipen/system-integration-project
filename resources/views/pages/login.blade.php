<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-b from-blue-100 via-gray-200 to-rose-200 font-sans">

    <!-- Back Arrow -->
    <div class="absolute top-6 left-6">
        <a href="/" class="flex items-center space-x-2 text-gray-600 hover:text-gray-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
    </div>

    <!-- Login Card -->
    <div class="bg-white rounded-2xl shadow-md w-full max-w-md p-8">
        <h2 class="text-2xl font-bold mb-6 text-center">LOGIN</h2>

        <!-- Success Message -->
        @if(session('success'))
        <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(function() {
                const msg = document.getElementById('success-message');
                if (msg) {
                    msg.style.transition = "opacity 0.5s ease-out";
                    msg.style.opacity = 0;
                    setTimeout(() => msg.remove(), 500); 
                }
            }, 2500);
        </script>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <input type="email" id="e_email" name="e_email" value="{{ old('e_email') }}"
                    placeholder="Enter your email"
                    class="w-full px-4 py-2 border border-gray-400 rounded-full placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
                <label for="e_email" class="text-gray-600 text-sm mt-1 block">Email</label>
            </div>

            <div>
                <input type="password" id="password" name="password"
                    placeholder="Password"
                    class="w-full px-4 py-2 border border-gray-400 rounded-full placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
                <label for="password" class="text-gray-600 text-sm mt-1 block">Password</label>
            </div>

            <div class="flex items-center space-x-2">
                <input type="checkbox" id="human" class="accent-teal-500 w-5 h-5 border border-teal-500">
                <label for="human" class="text-gray-700 text-sm">Confirm if you are human</label>
            </div>

            <div class="text-center text-sm text-gray-600">
                Don't have an account?
                <a href="{{ route('signup.form') }}" class="text-blue-600 hover:underline">Sign up</a>
            </div>

            <div class="text-center">
                <button type="submit"
                    class="mt-4 w-full bg-blue-100 text-gray-900 font-semibold py-2 rounded-full hover:bg-blue-200 transition">
                    LOG IN
                </button>
            </div>
        </form>
    </div>

</body>

</html>