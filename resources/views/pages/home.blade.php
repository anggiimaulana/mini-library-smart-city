@extends('layouts.app')

@section('content')
    <div id="homePage" class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="text-center mb-12 animate-slide-up">
                <h1 id="welcomeTitle" class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Welcome to the Smart City Mini Library!
                </h1>
                <p id="welcomeSubtitle" class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Learn the concept of Indonesia's Smart City through 6 main pillars with reliable academic references.
                </p>
            </div>

            <!-- Progress Section (Hidden initially, shown after login) -->
            <div id="progressSection" class="bg-white rounded-2xl shadow-lg p-8 mb-12 hidden">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-chart-line text-primary-500 mr-3"></i>
                        Learning Progress
                    </h3>
                    <span id="progressPercentage" class="text-2xl font-bold text-primary-500">0%</span>
                </div>

                <div class="w-full bg-gray-200 rounded-full h-4 mb-4">
                    <div id="progressBar"
                        class="bg-gradient-primary h-4 rounded-full transition-all duration-500 ease-out relative overflow-hidden"
                        style="width: 0%">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-20 animate-pulse">
                        </div>
                    </div>
                </div>

                <p id="progressText" class="text-gray-600 text-center">
                    0 out of 0 references have been read
                </p>
            </div>

            <!-- Certificate Claim Section (Hidden initially) -->
            <div id="certificateSection"
                class="bg-gradient-to-r from-green-50 to-blue-50 border-2 border-green-200 rounded-2xl p-8 mb-12 text-center hidden animate-slide-up">
                <div class="text-6xl mb-4">🏆</div>
                <h3 class="text-2xl font-bold text-green-800 mb-4">Congratulations! Learning Completed!</h3>
                <p class="text-green-700 mb-6">You have completed all the Smart City learning. Claim your certificate now!
                </p>
                <button onclick="app.showCertificateModal()"
                    class="bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-lg font-bold text-lg transition-all transform hover:scale-105">
                    <i class="fas fa-certificate mr-2"></i>Claim Certificate
                </button>
            </div>

            <!-- Statistics Cards -->
            <div id="statsSection" class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12 hidden">
                <!-- Stats will be populated dynamically -->
            </div>

            <!-- Smart City Description -->
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-8 mb-12 relative overflow-hidden">
                <div class="absolute top-4 right-4 text-6xl opacity-10">🏙️</div>
                <div class="relative z-10">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6 text-gradient">What is a Smart City?</h2>
                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                        <p class="mb-6">
                            <strong>Smart City</strong> is a concept of urban development and management
                            that integrates information and communication technology (ICT) to improve the quality of public
                            services,
                            operational efficiency, and citizens' quality of life in a sustainable way.
                        </p>
                        <p class="mb-6">
                            This concept aims to create an urban ecosystem that is responsive, adaptive, and sustainable
                            through the use of data, the Internet of Things (IoT), artificial intelligence,
                            and other digital technologies to optimize various aspects of city life.
                        </p>
                        <p>
                            Indonesia has developed a Smart City framework consisting of 6 main pillars that are
                            interconnected
                            to achieve a comprehensive digital urban transformation.
                        </p>
                    </div>
                </div>
            </div>

            <!-- 6 Pillars Section -->
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4 text-gradient">
                    6 Pillars of Indonesia’s Smart City
                </h2>
                <p class="text-xl text-gray-600">
                    Explore each pillar to understand the Smart City concept comprehensively
                </p>
            </div>

            <div id="pillarsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Pillars will be loaded dynamically -->
            </div>

            <!-- Call to Action -->
            <div class="text-center mt-16 py-12 bg-gradient-primary rounded-2xl text-white">
                <h3 class="text-3xl font-bold mb-4">Ready to Start Your Smart City Journey?</h3>
                <p class="text-xl mb-8 opacity-90">
                    Register now and start exploring the concept of Indonesia’s Smart City
                </p>
                <div id="ctaButtons" class="space-x-4">
                    <button onclick="app.navigateToPage('references')"
                        class="bg-white text-primary-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition-colors transform hover:scale-105">
                        <i class="fas fa-book mr-2"></i>See All References
                    </button>
                    <button onclick="app.navigateToPage('quiz')"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-8 py-4 rounded-lg font-bold text-lg transition-colors transform hover:scale-105">
                        <i class="fas fa-question-circle mr-2"></i>Take Quiz
                    </button>
                    <button onclick="app.logout()"
                        class="border-2 border-white text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-white hover:text-primary-600 transition-colors">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
