@extends('layouts.app')

@section('content')
    <div
        class="min-h-screen bg-gradient-primary flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60"
                viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg
                fill="%23ffffff" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"
                /%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>

        <div class="max-w-md w-full space-y-8 relative z-10">
            <div class="bg-white rounded-2xl shadow-2xl p-8 animate-slide-up">
                <div class="text-center mb-8">
                    <div class="text-4xl mb-4">🏙️</div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">
                        Create a New Account
                    </h2>
                    <p class="text-gray-600">
                        Join the Smart City Mini Library<br>
                        Politeknik Negeri Indramayu
                    </p>
                </div>

                <form id="registerForm" class="space-y-6">
                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="name" name="name" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors pl-12"
                                placeholder="Enter your full name">
                            <i class="fas fa-user absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <div id="nameError" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- NIM Field -->
                    <div>
                        <label for="nim" class="block text-sm font-medium text-gray-700 mb-2">
                            Student ID (NIM) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="nim" name="nim" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors pl-12"
                                placeholder="Enter your Student ID">
                            <i class="fas fa-id-card absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <div id="nimError" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Major Field -->
                    <div>
                        <label for="major_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Major <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="major_id" name="major_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors pl-12 appearance-none bg-white">
                                <option value="">Select Major</option>
                            </select>
                            <i
                                class="fas fa-graduation-cap absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <i
                                class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                        <div id="major_idError" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Study Program Field -->
                    <div>
                        <label for="study_program_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Study Program <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="study_program_id" name="study_program_id" required disabled
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors pl-12 appearance-none bg-white disabled:bg-gray-100">
                                <option value="">Select Study Program</option>
                            </select>
                            <i class="fas fa-book absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <i
                                class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                        <div id="study_program_idError" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-gradient-primary text-white py-4 px-6 rounded-lg font-bold text-lg hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200 focus:ring-4 focus:ring-primary-300">
                            <i class="fas fa-user-plus mr-2"></i>
                            Register Now
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center pt-4 border-t border-gray-200">
                        <p class="text-gray-600">
                            Already have an account?
                            <a href="#" data-page="login" class="text-primary-500 hover:text-primary-600 font-medium">
                                Login here
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Success Modal (Will be shown after successful registration) -->
            <div id="successModal" class="hidden">
                <div class="bg-white rounded-2xl shadow-2xl p-8 text-center animate-slide-up">
                    <div class="text-6xl text-green-500 mb-4">✅</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Registration Successful!</h3>

                    <div class="bg-gradient-to-r from-green-50 to-blue-50 border-2 border-green-200 rounded-xl p-6 mb-6">
                        <p class="text-gray-700 mb-2 font-medium">Your Unique Code:</p>
                        <div id="uniqueCode"
                            class="text-3xl font-bold text-green-600 letter-spacing-wide font-mono bg-white px-4 py-2 rounded-lg inline-block mb-4">
                        </div>
                        <div
                            class="flex items-center justify-center text-amber-600 bg-amber-50 border border-amber-200 rounded-lg p-3">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span class="text-sm font-medium">Save this code carefully for login</span>
                        </div>
                    </div>

                    <button onclick="app.navigateToPage('login')"
                        class="bg-primary-500 hover:bg-primary-600 text-white px-8 py-3 rounded-lg font-bold transition-colors">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Continue to Login
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
