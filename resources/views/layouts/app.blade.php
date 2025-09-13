<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hireflix') | Hireflix</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <nav class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-gray-900">Hireflix</a>
            
            @auth
            <div class="flex items-center space-x-4">
                <span class="text-gray-600">Welcome, {{ Auth::user()->name }}</span>
                
                <!-- Role-based navigation -->
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'reviewer')
                    <a href="{{ route('interviews.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors duration-300 {{ request()->routeIs('interviews.index') ? 'text-blue-600 font-semibold' : '' }}">
                        📋 Interviews
                    </a>
                    <a href="{{ route('interviews.create') }}" class="text-gray-600 hover:text-gray-900 transition-colors duration-300 {{ request()->routeIs('interviews.create') ? 'text-blue-600 font-semibold' : '' }}">
                        ➕ Create
                    </a>
                    <a href="{{ route('submissions.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors duration-300 {{ request()->routeIs('submissions.*') ? 'text-blue-600 font-semibold' : '' }}">
                        📊 Submissions
                    </a>
                    <a href="{{ route('reviews.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors duration-300 {{ request()->routeIs('reviews.*') ? 'text-blue-600 font-semibold' : '' }}">
                        ⭐ Reviews
                    </a>
                @endif
                
                @if(Auth::user()->role === 'candidate')
                    <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors duration-300">
                        My Applications
                    </a>
                @endif
                
                <!-- Logout Button -->
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="bg-red-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-red-700 transition duration-300">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
            @endauth
        </nav>
    </header>

    <!-- Main Content -->
    <main class="flex-1 container mx-auto p-4 md:p-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-auto">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Hireflix</h3>
                    <p class="text-gray-300">Streamline your hiring process with video interviews and automated screening.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-white transition-colors">Dashboard</a></li>
                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'reviewer')
                                <li><a href="{{ route('interviews.index') }}" class="text-gray-300 hover:text-white transition-colors">My Interviews</a></li>
                                <li><a href="{{ route('interviews.create') }}" class="text-gray-300 hover:text-white transition-colors">Create Interview</a></li>
                            @endif
                        @else
                            <li><a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors">Sign In</a></li>
                            <li><a href="{{ route('register') }}" class="text-gray-300 hover:text-white transition-colors">Sign Up</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Contact Us</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-300">&copy; {{ date('Y') }} Hireflix. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
