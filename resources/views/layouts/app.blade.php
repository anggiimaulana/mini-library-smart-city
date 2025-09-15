<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Smart City Mini Library - Politeknik Negeri Indramayu' }}</title>
    {{-- @vite('resources/js/app.js') --}}

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f4ff',
                            100: '#e0e7ff',
                            500: '#667eea',
                            600: '#5a67d8',
                            700: '#4c51bf',
                            900: '#312e81'
                        },
                        secondary: {
                            500: '#764ba2',
                            600: '#6b46c1'
                        }
                    },
                    animation: {
                        'bounce-slow': 'bounce 3s infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'slide-in': 'slideIn 0.5s ease-out'
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Styles -->
    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateX(-30px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans antialiased">
    <!-- Loading Overlay -->
    <div id="loadingOverlay"
        class="fixed inset-0 bg-gray-900 bg-opacity-80 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="text-center text-white">
            <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-white mx-auto mb-4"></div>
            <p class="text-lg font-medium">Loading...</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="text-2xl animate-bounce-slow">🏙️</div>
                        <span class="ml-2 text-xl font-bold text-gradient">Smart City Mini Library</span>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="#" class="nav-link" data-page="home">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <a href="#" class="nav-link" data-page="references">
                        <i class="fas fa-book mr-2"></i>References
                    </a>
                    <a href="#" class="nav-link-mobile" data-page="quiz">
                        <i class="fas fa-question-circle mr-2"></i>Quiz
                    </a>
                    <div id="navUserSection" class="flex items-center space-x-4">
                        <!-- User info will be injected here -->
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button id="navToggle" class="text-gray-600 hover:text-gray-900 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="navMenu" class="md:hidden bg-white border-t hidden">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="#" class="nav-link-mobile" data-page="home">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
                <a href="#" class="nav-link-mobile" data-page="references">
                    <i class="fas fa-book mr-2"></i>References
                </a>
                <!-- FIX: Perbaiki link quiz di mobile -->
                <a href="#" class="nav-link-mobile" data-page="quiz">
                    <i class="fas fa-question-circle mr-2"></i>Quiz
                </a>
                <div id="navUserSectionMobile">
                    <!-- Mobile user info will be injected here -->
                </div>
            </div>
        </div>
    </nav>

    <!-- GANTI bagian Journey Progress dengan yang lebih responsive -->
    <div id="journeyProgress" class="bg-white shadow-sm border-b hidden">
        <div class="max-w-7xl mx-auto px-4 py-8 md:py-12">
            <div class="relative">
                <!-- Journey Steps - Responsive -->
                <div class="flex justify-between items-center overflow-x-auto pb-2">
                    <!-- Step 1: Register/Login -->
                    <div class="journey-step flex flex-col items-center min-w-0 flex-shrink-0" data-step="auth">
                        <div class="w-8 md:w-10 h-8 md:h-10 rounded-full bg-gray-300 flex items-center justify-center text-white font-bold mb-1 md:mb-2 transition-colors text-xs md:text-sm"
                            id="step-auth">
                            1
                        </div>
                        <span class="text-xs text-gray-600 text-center leading-tight">Signup/<br>Signin</span>
                    </div>

                    <!-- Step 2: Smart City Overview -->
                    <div class="journey-step flex flex-col items-center min-w-0 flex-shrink-0" data-step="overview">
                        <div class="w-8 md:w-10 h-8 md:h-10 rounded-full bg-gray-300 flex items-center justify-center text-white font-bold mb-1 md:mb-2 transition-colors text-xs md:text-sm"
                            id="step-overview">
                            2
                        </div>
                        <span class="text-xs text-gray-600 text-center leading-tight">Smart<br>City?</span>
                    </div>

                    <!-- Step 3: 6 Pillars -->
                    <div class="journey-step flex flex-col items-center min-w-0 flex-shrink-0" data-step="pillars">
                        <div class="w-8 md:w-10 h-8 md:h-10 rounded-full bg-gray-300 flex items-center justify-center text-white font-bold mb-1 md:mb-2 transition-colors text-xs md:text-sm"
                            id="step-pillars">
                            3
                        </div>
                        <span class="text-xs text-gray-600 text-center leading-tight">6 Pillar<br>Smart CityC</span>
                    </div>

                    <!-- Step 4: Indramayu Example -->
                    <div class="journey-step flex flex-col items-center min-w-0 flex-shrink-0" data-step="example">
                        <div class="w-8 md:w-10 h-8 md:h-10 rounded-full bg-gray-300 flex items-center justify-center text-white font-bold mb-1 md:mb-2 transition-colors text-xs md:text-sm"
                            id="step-example">
                            4
                        </div>
                        <span class="text-xs text-gray-600 text-center leading-tight">Contoh<br>IDY</span>
                    </div>

                    <!-- Step 5: All References -->
                    <div class="journey-step flex flex-col items-center min-w-0 flex-shrink-0" data-step="references">
                        <div class="w-8 md:w-10 h-8 md:h-10 rounded-full bg-gray-300 flex items-center justify-center text-white font-bold mb-1 md:mb-2 transition-colors text-xs md:text-sm"
                            id="step-references">
                            5
                        </div>
                        <span class="text-xs text-gray-600 text-center leading-tight">Semua<br>Ref</span>
                    </div>

                    <!-- Step 6: Quiz -->
                    <div class="journey-step flex flex-col items-center min-w-0 flex-shrink-0" data-step="quiz">
                        <div class="w-8 md:w-10 h-8 md:h-10 rounded-full bg-gray-300 flex items-center justify-center text-white font-bold mb-1 md:mb-2 transition-colors text-xs md:text-sm"
                            id="step-quiz">
                            6
                        </div>
                        <span class="text-xs text-gray-600 text-center leading-tight">Kuis<br>SC</span>
                    </div>

                    <!-- Step 7: Certificate -->
                    <div class="journey-step flex flex-col items-center min-w-0 flex-shrink-0" data-step="certificate">
                        <div class="w-8 md:w-10 h-8 md:h-10 rounded-full bg-gray-300 flex items-center justify-center text-white font-bold mb-1 md:mb-2 transition-colors text-xs md:text-sm"
                            id="step-certificate">
                            7
                        </div>
                        <span class="text-xs text-gray-600 text-center leading-tight">Klaim<br>Sertifikat</span>
                    </div>
                </div>

                <!-- Progress Line -->
                <div class="absolute top-4 md:top-5 left-0 w-full h-0.5 bg-gray-200 -z-10">
                    <div id="journeyProgressLine" class="h-full bg-gradient-primary transition-all duration-500"
                        style="width: 0%"></div>
                </div>
            </div>

            <!-- Journey Description -->
            <div class="text-center mt-4">
                <p id="journeyDescription" class="text-sm text-gray-600">
                    Selesaikan perjalanan pembelajaran Smart City Anda langkah demi langkah
                </p>
            </div>
        </div>
    </div>

    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateX(-30px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* TAMBAH: Journey responsive styles */
        .journey-step {
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 50px;
            /* Minimum width untuk mobile */
        }

        .journey-step:hover {
            transform: translateY(-2px);
        }

        .journey-step.active .w-8,
        .journey-step.active .md\\:w-10 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transform: scale(1.1);
        }

        .journey-step.completed .w-8,
        .journey-step.completed .md\\:w-10 {
            background: #10b981;
        }

        .journey-step.locked {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .journey-step.locked:hover {
            transform: none;
        }

        /* Mobile navigation improvements */
        @media (max-width: 768px) {
            .journey-step {
                margin: 0 2px;
            }

            #journeyProgress {
                padding-left: 8px;
                padding-right: 8px;
            }
        }
    </style>

    <!-- Main Content -->
    <main id="mainContent" class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-20">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-4">
                    <h3 class="text-xl font-bold">Smart City Mini Library</h3>
                    <p class="text-gray-300">
                        Learning Center for Indonesia’s Smart City Concepts for students of
                        Politeknik Negeri Indramayu
                    </p>
                </div>

                <div class="space-y-4">
                    <h4 class="text-lg font-semibold">Quick Links</h4>
                    <div class="space-y-2">
                        <a href="#" data-page="home"
                            class="block text-gray-300 hover:text-white transition-colors">Home</a>
                        <a href="#" data-page="references"
                            class="block text-gray-300 hover:text-white transition-colors">References</a>
                        <a href="#" class="nav-link" data-page="quiz">
                            <i class="fas fa-question-circle mr-2"></i>Quiz
                        </a>
                        <a href="https://polindra.ac.id" target="_blank"
                            class="block text-gray-300 hover:text-white transition-colors">Politeknik Negeri
                            Indramayu</a>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="text-lg font-semibold">Contact</h4>
                    <div class="space-y-2 text-gray-300">
                        <p class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Jl. Lohbener Lama No.08, Indramayu
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-phone mr-2"></i>
                            (0234) 5746464
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-envelope mr-2"></i>
                            info@polindra.ac.id
                        </p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 Smart City Mini Library - Politeknik Negeri Indramayu. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Certificate Modal -->
    <div id="certificateModal"
        class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto relative">
            <button
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition-colors"
                onclick="app.closeModal()">
                &times;
            </button>

            <div class="p-8">
                <div id="certificatePreview" class="mb-6">
                    <!-- Certificate content will be generated here -->
                </div>

                <div class="flex justify-center space-x-4">
                    <button
                        class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-medium transition-colors"
                        onclick="downloadCertificate()">
                        <i class="fas fa-download mr-2"></i>Download PNG
                    </button>
                    <button
                        class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-medium transition-colors"
                        onclick="generatePDF()">
                        <i class="fas fa-file-pdf mr-2"></i>Download PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2">
        <!-- Toast notifications will appear here -->
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        // Base URL for API calls
        const API_BASE_URL = '{{ url('/api') }}';
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // FIX: Mobile Navigation
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle fix
            const navToggle = document.getElementById('navToggle');
            const navMenu = document.getElementById('navMenu');

            if (navToggle && navMenu) {
                navToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    navMenu.classList.toggle('hidden');
                });

                // Close menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
                        navMenu.classList.add('hidden');
                    }
                });
            }

            // Navigation styles
            const style = document.createElement('style');
            style.textContent = `
            .nav-link {
                @apply flex items-center px-4 py-2 text-gray-600 hover:text-primary-600 font-medium transition-colors rounded-lg hover:bg-gray-100;
            }
            .nav-link.active {
                @apply text-primary-600 bg-primary-50;
            }
            .nav-link-mobile {
                @apply block px-3 py-2 text-gray-600 hover:text-primary-600 font-medium transition-colors rounded-md hover:bg-gray-100;
            }
            .nav-link-mobile.active {
                @apply text-primary-600 bg-primary-50;
            }
        `;
            document.head.appendChild(style);
        });
    </script>
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>

</html>
