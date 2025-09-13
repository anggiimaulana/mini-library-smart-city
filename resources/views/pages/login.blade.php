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
                    <div class="text-4xl mb-4 animate-bounce-slow">🔐</div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">
                        Login to the Library
                    </h2>
                    <p class="text-gray-600">
                        Enter your unique code to access<br>
                        Smart City Mini Library
                    </p>
                </div>

                <form id="loginForm" class="space-y-6">
                    <!-- Secret Code Field -->
                    <div>
                        <label for="secret_code" class="block text-sm font-medium text-gray-700 mb-2">
                            Unique Code <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="secret_code" name="secret_code" required maxlength="6"
                                class="w-full px-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors text-center text-2xl font-bold font-mono uppercase tracking-widest bg-gray-50"
                                placeholder="A1B2C3" style="letter-spacing: 0.5em;">
                            <div class="absolute inset-0 pointer-events-none">
                                <div class="flex justify-center items-center h-full">
                                    <div class="flex space-x-2 opacity-20">
                                        <div class="w-8 h-8 border-2 border-gray-300 rounded"></div>
                                        <div class="w-8 h-8 border-2 border-gray-300 rounded"></div>
                                        <div class="w-8 h-8 border-2 border-gray-300 rounded"></div>
                                        <div class="w-8 h-8 border-2 border-gray-300 rounded"></div>
                                        <div class="w-8 h-8 border-2 border-gray-300 rounded"></div>
                                        <div class="w-8 h-8 border-2 border-gray-300 rounded"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="secret_codeError" class="text-red-500 text-sm mt-1 hidden"></div>
                        <div class="mt-3 text-center">
                            <p class="text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Example format: <span class="font-mono font-bold text-primary-500">A1B2C3</span>
                            </p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-gradient-primary text-white py-4 px-6 rounded-lg font-bold text-lg hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200 focus:ring-4 focus:ring-primary-300">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Enter the Library
                        </button>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center pt-4 border-t border-gray-200">
                        <p class="text-gray-600">
                            Don’t have an account?
                            <a href="#" data-page="register"
                                class="text-primary-500 hover:text-primary-600 font-medium">
                                Register here
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Tips Card -->
            <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl p-6 text-center">
                <div class="text-2xl mb-3">💡</div>
                <h3 class="font-bold text-gray-800 mb-2">Login Tips</h3>
                <p class="text-sm text-gray-600">
                    The unique code consists of 6 characters, a mix of letters and numbers.
                    If you forget your code, please re-register using the same data.
                </p>
            </div>
        </div>
    </div>
@endsection
