<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Fesmera Inc.')</title>

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Enable Dark Mode -->
    <script>
        tailwind.config = {
            darkMode: 'class', // ← Enable dark mode via 'dark' class
            theme: {
                extend: {
                    colors: {
                        primary: '#0ea5e9',
                        warning: '#f59e0b',
                        success: '#10b981',
                    }
                }
            }
        }
    </script>

    <!-- Apply saved theme on load -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Load user's theme preference (if available)
            @auth
                const userTheme = '{{ Auth::user()->getSetting("theme", "light") }}';
                if (userTheme === 'dark') {
                    document.documentElement.classList.add('dark');
                }
            @endauth

            // Dropdown logic
            const dropdownButton = document.getElementById('dropdown-button');
            const dropdownMenu = document.getElementById('dropdown-menu');
            if (!dropdownButton || !dropdownMenu) return;

            dropdownButton.addEventListener('click', function (e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', function (e) {
                if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    dropdownMenu.classList.add('hidden');
                }
            });
        });
    </script>
</head>

<body class="font-sans text-gray-800 bg-white dark:bg-gray-900 dark:text-gray-200 transition-colors duration-200">
    @include('includes.navbar')
    <main>
        @yield('content')
    </main>
    @include('includes.footer')
</body>
</html>