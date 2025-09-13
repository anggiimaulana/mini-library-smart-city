@extends('layouts.app')

@section('content')
    <div id="referencesPage" class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-book text-primary-500 mr-3"></i>
                    All References
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    A complete collection of academic references for Smart City learning in Indonesia
                </p>
            </div>

            <!-- Statistics Cards -->
            <div id="statsSection" class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-lg p-6 text-center border-l-4 border-primary-500">
                    <div id="totalReferencesCount" class="text-3xl font-bold text-primary-500 mb-2">0</div>
                    <div class="text-sm text-gray-600 uppercase tracking-wide">Total References</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6 text-center border-l-4 border-green-500">
                    <div id="completedReferencesCount" class="text-3xl font-bold text-green-500 mb-2">0</div>
                    <div class="text-sm text-gray-600 uppercase tracking-wide">Read</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6 text-center border-l-4 border-blue-500">
                    <div id="journalCount" class="text-3xl font-bold text-blue-500 mb-2">0</div>
                    <div class="text-sm text-gray-600 uppercase tracking-wide">Journals</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6 text-center border-l-4 border-orange-500">
                    <div id="bookCount" class="text-3xl font-bold text-orange-500 mb-2">0</div>
                    <div class="text-sm text-gray-600 uppercase tracking-wide">Books</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-filter text-primary-500 mr-2"></i>
                    Reference Filters
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Search title, author..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Pillar Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pillar</label>
                        <select id="pillarFilter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">All Pillars</option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select id="categoryFilter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">All Categories</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="statusFilter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">All Status</option>
                            <option value="read">Read</option>
                            <option value="unread">Unread</option>
                        </select>
                    </div>
                </div>

                <!-- Clear Filters -->
                <div class="mt-4 text-right">
                    <button id="clearFilters" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                        <i class="fas fa-times mr-1"></i>
                        Clear Filters
                    </button>
                </div>
            </div>

            <!-- Loading State -->
            <div id="referencesLoading" class="text-center py-12">
                <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-primary-500 mx-auto mb-4"></div>
                <p class="text-gray-600">Loading references...</p>
            </div>

            <!-- References Table -->
            <div id="referencesContainer" class="hidden bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Title & Author
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Year
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Category
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pillar
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody id="referencesTableBody" class="bg-white divide-y divide-gray-200">
                            <!-- References will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- No Results -->
            <div id="noResults" class="hidden text-center py-16 text-gray-500">
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-xl font-medium mb-2">No references found</h3>
                <p>Try changing filters or search keywords</p>
            </div>

            <!-- Pagination -->
            <div id="paginationContainer" class="hidden mt-8 flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing <span id="showingStart">1</span> - <span id="showingEnd">10</span> of <span
                        id="totalItems">0</span> references
                </div>
                <div class="flex space-x-2" id="paginationButtons">
                    <!-- Pagination buttons will be generated here -->
                </div>
            </div>
        </div>
    </div>
@endsection
