<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CyberBuddy - Digital Safety Partner</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-white">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.955 11.955 0 003 12c0 3.491 1.497 6.63 3.876 8.797A11.955 11.955 0 0012 21a11.955 11.955 0 005.124-1.203A11.955 11.955 0 0021 12c0-2.168-.575-4.2-1.578-5.953A11.955 11.955 0 0012 2.964z" />
                        </svg>
                    </div>
                    <span class="text-blue-500 font-bold text-base">CyberBuddy</span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Login</a>
                    <a href="{{ route('register') }}" class="text-sm font-semibold bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Get Started</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-b from-blue-50 to-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div>
                    <span class="inline-block text-blue-500 text-sm font-semibold mb-4">Cybersecurity for the Next Generation</span>
                    <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
                        Level Up Your
                        <span class="text-blue-500">Digital Safety</span>
                        Skills.
                    </h1>
                    <p class="text-lg text-gray-600 mb-8">
                        CyberBuddy makes learning online safety fun for kids, manageable for parents, and easy for teachers. Join the adventure today!
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 mb-12">
                        <a href="{{ route('register') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold px-8 py-3 rounded-lg transition">
                            Get Started (for kids) →
                        </a>
                        <a href="{{ route('register') }}" class="inline-block border-2 border-gray-300 text-gray-700 font-semibold px-8 py-3 rounded-lg hover:border-gray-400 transition">
                            For Parents / Teachers
                        </a>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex -space-x-2">
                            <img src="https://i.pravatar.cc/32?img=1" alt="" class="w-8 h-8 rounded-full border-2 border-white">
                            <img src="https://i.pravatar.cc/32?img=2" alt="" class="w-8 h-8 rounded-full border-2 border-white">
                            <img src="https://i.pravatar.cc/32?img=3" alt="" class="w-8 h-8 rounded-full border-2 border-white">
                            <img src="https://i.pravatar.cc/32?img=4" alt="" class="w-8 h-8 rounded-full border-2 border-white">
                        </div>
                        <span class="text-sm text-gray-600">Loved by <strong>15,000+</strong> young explorers</span>
                    </div>
                </div>

                <!-- Right Image -->
                <div class="flex justify-center lg:justify-center">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-blue-600 rounded-3xl blur-2xl opacity-20"></div>
                        <img src="https://png.pngtree.com/png-clipart/20250207/original/pngtree-a-futuristic-robot-with-blue-eyes-holding-shield-white-bug-icon-png-image_20376551.png" alt="CyberBuddy Robot" class="relative w-full max-w-md h-auto">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="text-center">
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Gamified Learning</h3>
                    <p class="text-gray-600">Kids earn badges, climb leaderboards, and compete against peers while learning crucial digital safety skills.</p>
                </div>

                <!-- Feature 2 -->
                <div class="text-center">
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Real-world Scenarios</h3>
                    <p class="text-gray-600">Interactive activities built from real-world situations help kids develop critical thinking and practical safety skills.</p>
                </div>

                <!-- Feature 3 -->
                <div class="text-center">
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">All-Teacher Tools</h3>
                    <p class="text-gray-600">Teachers can personalize learning paths and monitor progress easily using our comprehensive dashboard.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">How it Works</h2>
                <p class="text-lg text-gray-600">Mastering the internet is a journey. We break it down into simple, fun, and manageable steps.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="flex justify-center mb-6">
                        <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-lg">01</div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Join a Team</h3>
                    <p class="text-gray-600">Sign up with a quick, secure password to get your personalized learning journey started.</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center">
                    <div class="flex justify-center mb-6">
                        <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-lg">02</div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Explore & Play</h3>
                    <p class="text-gray-600">Dive into interactive modules that make learning about digital safety engaging and fun.</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center">
                    <div class="flex justify-center mb-6">
                        <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-lg">03</div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Stay Protected</h3>
                    <p class="text-gray-600">Apply your new knowledge in real situations and stay safe online with confidence.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Portal Selection -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Choose Your Portal</h2>
                <p class="text-lg text-gray-600">Tailored experiences for every member of the digital community</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Kid Portal -->
                <div class="bg-blue-100 rounded-2xl p-8">
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2.293-2.293a1 1 0 00-1.414 0l-2.293 2.293m5 5l-3 3m0 0l-3-3m3 3V7m0 10l-2 1m0 0l-2-1m2 1v2.5" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2 text-center">I'm a Kid</h3>
                    <p class="text-sm text-gray-600 text-center mb-6">Unlock cybersecurity skills, gain real-world knowledge, and have fun along the way.</p>

                    <div class="space-y-3 mb-8">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            <span class="text-sm text-gray-700">Gamified Modules</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            <span class="text-sm text-gray-700">Earn Badges</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            <span class="text-sm text-gray-700">Leaderboard Fun</span>
                        </div>
                    </div>

                    <button class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-lg transition">
                        Start Portal
                    </button>
                </div>

                <!-- Parent Portal -->
                <div class="bg-cyan-100 rounded-2xl p-8">
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 bg-cyan-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-cyan-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2 text-center">I'm a Parent</h3>
                    <p class="text-sm text-gray-600 text-center mb-6">Monitor progress, set safety rules, and have informed conversations about digital wellness.</p>

                    <div class="space-y-3 mb-8">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-cyan-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            <span class="text-sm text-gray-700">Progress Tracking</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-cyan-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            <span class="text-sm text-gray-700">Safety Insights</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-cyan-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            <span class="text-sm text-gray-700">Family Guides</span>
                        </div>
                    </div>

                    <button class="w-full bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-3 rounded-lg transition">
                        Learn More
                    </button>
                </div>

                <!-- Teacher Portal -->
                <div class="bg-purple-100 rounded-2xl p-8">
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 bg-purple-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2 text-center">I'm a Teacher</h3>
                    <p class="text-sm text-gray-600 text-center mb-6">All essential classroom support tools to help students master cybersecurity with ease.</p>

                    <div class="space-y-3 mb-8">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            <span class="text-sm text-gray-700">AI-Powered Resources</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            <span class="text-sm text-gray-700">Bulk User Upload</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            <span class="text-sm text-gray-700">Detailed Reports</span>
                        </div>
                    </div>

                    <button class="w-full bg-purple-500 hover:bg-purple-600 text-white font-semibold py-3 rounded-lg transition">
                        Manage Classroom
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-gradient-to-r from-blue-500 to-blue-600 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-4">Ready to become a Cyber Superhero?</h2>
            <p class="text-xl text-blue-100 mb-8">
                Join thousands of families and schools worldwide. It takes only 5 minutes to create an account and start learning!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="inline-block bg-white text-blue-600 font-semibold px-8 py-3 rounded-lg hover:bg-gray-100 transition">
                    Create Free Account
                </a>
                <a href="#" class="inline-block border-2 border-white text-white font-semibold px-8 py-3 rounded-lg hover:bg-blue-700 transition">
                    Learn More
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- Brand -->
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-6 h-6 rounded-lg bg-blue-500 flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.955 11.955 0 003 12c0 3.491 1.497 6.63 3.876 8.797A11.955 11.955 0 0012 21a11.955 11.955 0 005.124-1.203A11.955 11.955 0 0021 12c0-2.168-.575-4.2-1.578-5.953A11.955 11.955 0 0012 2.964z" />
                            </svg>
                        </div>
                        <span class="text-white font-bold">CyberBuddy</span>
                    </div>
                    <p class="text-sm">Empowering the next generation with the skills to navigate the digital world safely and confidently.</p>
                </div>

                <!-- Links 1 -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Product</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">For Kids</a></li>
                        <li><a href="#" class="hover:text-white transition">For Parents</a></li>
                        <li><a href="#" class="hover:text-white transition">For Teachers</a></li>
                    </ul>
                </div>

                <!-- Links 2 -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Resources</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">Safety Guide</a></li>
                        <li><a href="#" class="hover:text-white transition">Learning Path</a></li>
                        <li><a href="#" class="hover:text-white transition">Blog</a></li>
                    </ul>
                </div>

                <!-- Links 3 -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Company</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <p class="text-center text-sm text-gray-400">
                    © 2026 CyberBuddy. Your digital safety partner.
                </p>
            </div>
        </div>
    </footer>
</div>
</body>
</html>
