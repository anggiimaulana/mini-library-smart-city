@extends('layouts.app')

@section('content')
    <div id="pillarPage" class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-8">
                <button onclick="app.navigateToPage('home')"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Home
                </button>
            </div>

            <!-- Loading State -->
            <div id="pillarLoading" class="text-center py-12">
                <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-primary-500 mx-auto mb-4"></div>
                <p class="text-gray-600">Loading pillar data...</p>
            </div>

            <!-- Pillar Content -->
            <div id="pillarContent" class="hidden">
                <!-- Pillar Header -->
                <div class="bg-gradient-primary rounded-2xl text-white overflow-hidden mb-8">
                    <div class="relative">
                        <div id="pillarImage" class="h-64 bg-cover bg-center relative">
                            <!-- Image will be set via JavaScript -->
                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                                <h1 id="pillarTitle" class="text-4xl md:text-5xl font-bold text-center px-4">
                                    <!-- Title will be loaded -->
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pillar Details -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                    <!-- Description -->
                    <div class="lg:col-span-2 space-y-8">
                        <div class="bg-white rounded-2xl shadow-lg p-8">
                            <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-info-circle text-primary-500 mr-3"></i>
                                Description
                            </h3>
                            <div id="pillarDescription" class="text-gray-700 leading-relaxed">
                                <!-- Description will be loaded -->
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-lg p-8">
                            <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-exclamation-triangle text-orange-500 mr-3"></i>
                                Challenges & Solutions
                            </h3>
                            <div id="pillarChallenges" class="text-gray-700 leading-relaxed">
                                <!-- Challenges will be loaded -->
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-lg p-8">
                            <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-cogs text-blue-500 mr-3"></i>
                                Technology
                            </h3>
                            <div id="pillarTechnology" class="text-gray-700 leading-relaxed">
                                <!-- Technology will be loaded -->
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-lg p-8">
                            <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-star text-green-500 mr-3"></i>
                                Example of Implementation
                            </h3>
                            <div id="pillarImplementation" class="text-gray-700 leading-relaxed">
                                <!-- Implementation will be loaded -->
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats Sidebar -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h4 class="text-lg font-bold text-gray-800 mb-4">Quick Stats</h4>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Total References</span>
                                    <span id="totalReferences" class="text-2xl font-bold text-primary-500">0</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Completed</span>
                                    <span id="completedReferences" class="text-2xl font-bold text-green-500">0</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div id="pillarProgress"
                                        class="bg-gradient-to-r from-primary-500 to-green-500 h-3 rounded-full transition-all duration-500"
                                        style="width: 0%"></div>
                                </div>
                                <div class="text-center">
                                    <span id="pillarProgressText" class="text-sm font-medium text-gray-600">0%
                                        Complete</span>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation -->
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h4 class="text-lg font-bold text-gray-800 mb-4">Navigation</h4>
                            <div class="space-y-2">
                                <button onclick="app.navigateToPage('home')"
                                    class="w-full text-left px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors flex items-center">
                                    <i class="fas fa-home mr-3 text-primary-500"></i>
                                    <span>Back to Home</span>
                                </button>
                                <button onclick="app.navigateToPage('references')"
                                    class="w-full text-left px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors flex items-center">
                                    <i class="fas fa-book mr-3 text-primary-500"></i>
                                    <span>All References</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- References Section -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-primary-50 to-purple-50 px-8 py-6 border-b">
                        <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-book text-primary-500 mr-3"></i>
                            References <span id="referenceCount" class="text-sm font-normal text-gray-600 ml-2">(0)</span>
                        </h3>
                    </div>

                    <div class="p-8">
                        <div id="referencesLoading" class="text-center py-8">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500 mx-auto mb-4">
                            </div>
                            <p class="text-gray-600">Loading references...</p>
                        </div>

                        <div id="referencesContainer" class="hidden space-y-4">
                            <!-- References will be loaded dynamically -->
                        </div>

                        <div id="noReferences" class="hidden text-center py-12 text-gray-500">
                            <div class="text-6xl mb-4">📚</div>
                            <p class="text-xl">No references available for this pillar.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
