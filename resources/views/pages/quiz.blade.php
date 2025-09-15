@extends('layouts.app')

@section('content')
    <div id="quizPage" class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-8">
                <button onclick="app.navigateToPage('home')"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Home
                </button>
            </div>

            <!-- Quiz Header -->
            <div class="text-center mb-8">
                <div class="text-6xl mb-4">📝</div>
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Smart City Quiz</h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Test your knowledge about Smart City Indonesia concepts. You need to score at least 70% to pass.
                </p>
            </div>

            <!-- Loading State -->
            <div id="quizLoading" class="text-center py-12">
                <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-primary-500 mx-auto mb-4"></div>
                <p class="text-gray-600">Loading quiz...</p>
            </div>

            <!-- Quiz Info -->
            <div id="quizInfo" class="hidden bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600" id="totalQuestions">10</div>
                        <div class="text-sm text-gray-600">Questions</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">70%</div>
                        <div class="text-sm text-gray-600">Passing Score</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-orange-600" id="userAttempts">0</div>
                        <div class="text-sm text-gray-600">Your Attempts</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600" id="userScore">0</div>
                        <div class="text-sm text-gray-600">Best Score</div>
                    </div>
                </div>
            </div>

            <!-- Quiz Form -->
            <div id="quizForm" class="hidden">
                <form id="quizAnswerForm">
                    <div id="questionsContainer" class="space-y-6">
                        <!-- Questions will be loaded here -->
                    </div>

                    <div class="mt-8 text-center">
                        <button type="submit"
                            class="bg-gradient-primary text-white px-12 py-4 rounded-lg font-bold text-lg hover:shadow-lg transform hover:scale-[1.02] transition-all">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Submit Quiz
                        </button>
                    </div>
                </form>
            </div>

            <!-- Quiz Results -->
            <div id="quizResults" class="hidden">
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
                    <div id="resultsIcon" class="text-6xl mb-4">
                        <!-- Will be set based on pass/fail -->
                    </div>
                    <h2 id="resultsTitle" class="text-3xl font-bold mb-4">
                        <!-- Title will be set -->
                    </h2>
                    <div id="scoreDisplay" class="text-6xl font-bold mb-6">
                        <!-- Score will be displayed -->
                    </div>
                    <div id="resultsSummary" class="text-lg text-gray-600 mb-8">
                        <!-- Summary will be displayed -->
                    </div>

                    <div id="resultsActions" class="space-x-4">
                        <!-- Action buttons will be added -->
                    </div>
                </div>

                <!-- Detailed Results -->
                <div id="detailedResults" class="mt-8 space-y-4">
                    <!-- Detailed results will be shown here -->
                </div>
            </div>
        </div>
    </div>
@endsection
