<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Fesmera Inc.')</title>

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Tailwind Theme -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0ea5e9',   // Tailwind's default blue-500
                        warning: '#f59e0b',   // amber-500
                        success: '#10b981',   // emerald-500
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans text-gray-800">

    <!-- Top Navbar -->
    @include('includes.navbar')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('includes.footer')

</body>
</html>