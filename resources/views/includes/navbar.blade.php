<nav class="bg-white border-b border-gray-200">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
        <!-- Logo -->
        <a href="/" class="flex items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Fesmera Inc. Logo" class="h-10 w-auto rounded-full">
        </a>

        <!-- Top Home Menu -->
        <div class="hidden md:flex items-center space-x-6">
            <a href="#" class="text-gray-700 hover:text-gray-900 font-medium">Products</a>
            <a href="#" class="text-gray-700 hover:text-gray-900 font-medium">Community</a>
            <a href="#" class="text-gray-700 hover:text-gray-900 font-medium">Resources</a>
            <a href="#" class="text-gray-700 hover:text-gray-900 font-medium">Contact</a>
            
            <!-- Login Button - Only show when user is NOT logged in -->
            @guest
            <a href="{{ route('login.form') }}" class="ml-4 px-4 py-2 border border-primary text-primary rounded-md hover:bg-primary hover:text-white transition">
                Log in
            </a>
            @endguest
            
            <!-- Show when user IS logged in -->
            @auth
            <form action="{{ route('logout') }}" method="POST" class="ml-4">
                @csrf
                <button type="submit" class="px-4 py-2 border border-red-500 text-red-500 rounded-md hover:bg-red-500 hover:text-white transition">
                    Logout
                </button>
            </form>
            @endauth
        </div>

        <!-- Mobile Menu Button (optional - not implemented fully here) -->
        <button class="md:hidden text-gray-700">
            <i class="bi bi-list text-2xl"></i>
        </button>
    </div>
</nav>