<nav class="bg-white border-b border-gray-200">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Fesmera Inc." class="h-10 w-auto rounded-full">
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
                <div class="relative" id="user-dropdown">
                    <button id="dropdown-button" class="flex items-center space-x-1 text-gray-700 hover:text-gray-900 focus:outline-none">
                        <span>Hi, {{ Auth::user()->f_name }}</span>
                        <svg class="h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <div id="dropdown-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border hidden">
                        <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        <a href="{{ route('notification') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Notifications</a>
                        <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                        <hr class="my-1 border-gray-200">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>

        <!-- Mobile Menu Button (optional - not implemented fully here) -->
        <button class="md:hidden text-gray-700">
            <i class="bi bi-list text-2xl"></i>
        </button>
    </div>
</nav>