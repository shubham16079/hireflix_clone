<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Interview') | Hireflix</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen">
    <!-- Header -->
    <header class="bg-white/10 backdrop-blur-md shadow-lg">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="#" class="text-2xl font-bold text-white">🎥 Hireflix</a>
            <div class="text-white text-sm">
                Video Interview Platform
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white/10 backdrop-blur-md mt-auto">
        <div class="container mx-auto px-4 py-6">
            <div class="text-center text-white/80">
                <p>&copy; {{ date('Y') }} Hireflix. All rights reserved.</p>
                <p class="text-sm mt-2">Powered by advanced video interview technology</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
