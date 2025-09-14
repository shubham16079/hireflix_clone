<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hireflix - Video Interview Platform</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    
    <!-- Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
                        },
                        colors: {
                            'hireflix-red': '#E50914',
                            'hireflix-dark': '#141414',
                            'hireflix-gray': '#333333',
                        }
                    }
                }
            }
        </script>
    @endif
</head>
<body class="bg-hireflix-dark text-white font-sans">
    <!-- Navigation -->
    <nav class="bg-black bg-opacity-90 backdrop-blur-sm fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-hireflix-red">🎥 Hireflix Clone</h1>
                    </div>
                </div>
                
                <!-- Navigation Links -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="#features" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">Features</a>
                        <a href="#how-it-works" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">How it Works</a>
                        <a href="#pricing" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">Pricing</a>
                    </div>
                </div>
                
                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-hireflix-red text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700 transition-colors">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                Sign In
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-hireflix-red text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700 transition-colors">
                                    Get Started
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-20 pb-16 bg-gradient-to-b from-hireflix-dark to-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Revolutionize Your
                    <span class="text-hireflix-red">Hiring Process</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-300 mb-8 max-w-3xl mx-auto">
                    Conduct professional video interviews, review candidates remotely, and make data-driven hiring decisions with our comprehensive video interview platform.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-hireflix-red text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-red-700 transition-colors">
                            Start Free Trial
                        </a>
                    @endif
                    <a href="#demo" class="border border-gray-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:border-white transition-colors">
                        Watch Demo
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Powerful Features for Modern Hiring</h2>
                <p class="text-xl text-gray-400 max-w-2xl mx-auto">
                    Everything you need to conduct professional video interviews and make better hiring decisions.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-hireflix-gray p-6 rounded-lg">
                    <div class="text-4xl mb-4">🎥</div>
                    <h3 class="text-xl font-semibold mb-3">Video Interviews</h3>
                    <p class="text-gray-400">Conduct high-quality video interviews with built-in recording, screen sharing, and real-time collaboration.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-hireflix-gray p-6 rounded-lg">
                    <div class="text-4xl mb-4">👥</div>
                    <h3 class="text-xl font-semibold mb-3">Team Collaboration</h3>
                    <p class="text-gray-400">Invite multiple reviewers, share feedback, and collaborate on candidate evaluations in real-time.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-hireflix-gray p-6 rounded-lg">
                    <div class="text-4xl mb-4">📊</div>
                    <h3 class="text-xl font-semibold mb-3">Analytics & Reports</h3>
                    <p class="text-gray-400">Get detailed insights into your hiring process with comprehensive analytics and performance reports.</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-hireflix-gray p-6 rounded-lg">
                    <div class="text-4xl mb-4">🔒</div>
                    <h3 class="text-xl font-semibold mb-3">Secure & Private</h3>
                    <p class="text-gray-400">Enterprise-grade security with encrypted video storage and secure candidate data management.</p>
                </div>
                
                <!-- Feature 5 -->
                <div class="bg-hireflix-gray p-6 rounded-lg">
                    <div class="text-4xl mb-4">⚡</div>
                    <h3 class="text-xl font-semibold mb-3">Easy Integration</h3>
                    <p class="text-gray-400">Seamlessly integrate with your existing HR tools and applicant tracking systems.</p>
                </div>
                
                <!-- Feature 6 -->
                <div class="bg-hireflix-gray p-6 rounded-lg">
                    <div class="text-4xl mb-4">📱</div>
                    <h3 class="text-xl font-semibold mb-3">Mobile Ready</h3>
                    <p class="text-gray-400">Access interviews and reviews from any device with our responsive mobile-friendly platform.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 bg-hireflix-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">How It Works</h2>
                <p class="text-xl text-gray-400 max-w-2xl mx-auto">
                    Get started with video interviews in just a few simple steps.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="bg-hireflix-red w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold">1</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Create Interview</h3>
                    <p class="text-gray-400">Set up your interview with custom questions, time limits, and reviewer assignments.</p>
                </div>
                
                <!-- Step 2 -->
                <div class="text-center">
                    <div class="bg-hireflix-red w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold">2</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Invite Candidates</h3>
                    <p class="text-gray-400">Send secure interview links to candidates via email with personalized invitations.</p>
                </div>
                
                <!-- Step 3 -->
                <div class="text-center">
                    <div class="bg-hireflix-red w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold">3</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Review & Decide</h3>
                    <p class="text-gray-400">Watch recorded interviews, collaborate with your team, and make informed hiring decisions.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-hireflix-red to-red-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Transform Your Hiring?</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                Join thousands of companies already using Hireflix to find the best talent.
            </p>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="bg-white text-hireflix-red px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-100 transition-colors">
                    Get Started Today
                </a>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Logo -->
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-2xl font-bold text-hireflix-red mb-4">🎥 Hireflix</h3>
                    <p class="text-gray-400 mb-4">
                        The modern video interview platform that helps you find and hire the best talent.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#features" class="text-gray-400 hover:text-white transition-colors">Features</a></li>
                        <li><a href="#how-it-works" class="text-gray-400 hover:text-white transition-colors">How it Works</a></li>
                        <li><a href="#pricing" class="text-gray-400 hover:text-white transition-colors">Pricing</a></li>
                    </ul>
                </div>
                
                <!-- Support -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">&copy; 2024 Hireflix. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Smooth scrolling script -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
