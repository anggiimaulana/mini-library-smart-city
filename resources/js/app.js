import './bootstrap';

// Smart City Mini Library - Main Application
class SmartCityApp {
    constructor() {
        this.currentUser = null;
        this.currentPage = window.location.pathname === '/' ? 'home' : 
                          window.location.pathname.replace('/', '').split('/')[0];
        this.readReferences = new Set();
        this.baseURL = API_BASE_URL;
        
        this.init();
    }

    async init() {
        this.setupEventListeners();
        await this.checkAuthStatus();
        await this.loadInitialPage();
        await this.loadDropdownData();
    }

    setupEventListeners() {
        // Navigation clicks
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-page]') || e.target.closest('[data-page]')) {
                e.preventDefault();
                const target = e.target.matches('[data-page]') ? e.target : e.target.closest('[data-page]');
                this.navigateToPage(target.dataset.page);
            }
        });

        // Mobile navigation toggle
        const navToggle = document.getElementById('navToggle');
        const navMenu = document.getElementById('navMenu');
        
        navToggle?.addEventListener('click', () => {
            const isOpen = navMenu.classList.contains('hidden');
            if (isOpen) {
                navMenu.classList.remove('hidden');
                navMenu.classList.add('animate-slide-in');
            } else {
                navMenu.classList.add('hidden');
                navMenu.classList.remove('animate-slide-in');
            }
        });

        // Form submissions
        document.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleFormSubmit(e);
        });

        // Filter changes
        document.addEventListener('change', (e) => {
            if (e.target.matches('#pillarFilter, #categoryFilter, #statusFilter')) {
                this.filterReferences();
            }
            if (e.target.matches('#major_id')) {
                this.loadStudyPrograms(e.target.value);
            }
        });

        // Search input
        document.addEventListener('input', (e) => {
            if (e.target.matches('#searchInput')) {
                this.debounce(() => this.filterReferences(), 300)();
            }
        });

        // Clear filters
        document.addEventListener('click', (e) => {
            if (e.target.matches('#clearFilters')) {
                this.clearFilters();
            }
        });

        // Auto-format secret code input
        document.addEventListener('input', (e) => {
            if (e.target.matches('#secret_code')) {
                e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            }
        });
    }

    async checkAuthStatus() {
        const token = this.getStoredAuth();
        if (token) {
            try {
                const response = await this.apiCall('/auth/verify', 'POST', { token });
                if (response.success) {
                    this.currentUser = response.user;
                    this.readReferences = new Set(response.progress.map(id => id.toString()));
                    this.updateNavigation();
                    return true;
                }
            } catch (error) {
                this.clearAuth();
                console.warn('Auth verification failed:', error);
            }
        }
        return false;
    }

    async loadInitialPage() {
        // Determine current page from URL
        const path = window.location.pathname;
        
        if (path === '/register') {
            this.currentPage = 'register';
        } else if (path === '/login') {
            this.currentPage = 'login';
        } else if (path.startsWith('/pilar/')) {
            this.currentPage = 'pillar';
            this.currentPillarSlug = path.split('/')[2];
        } else if (path === '/references') {
            this.currentPage = 'references';
        } else {
            this.currentPage = 'home';
        }

        // Check if user needs to be authenticated
        const protectedPages = ['home', 'pillar', 'references'];
        if (protectedPages.includes(this.currentPage) && !this.currentUser) {
            this.navigateToPage('login');
            return;
        }

        await this.renderCurrentPage();
    }

    async renderCurrentPage() {
        this.showLoading();
        
        try {
            switch (this.currentPage) {
                case 'home':
                    await this.renderHomePage();
                    break;
                case 'pillar':
                    await this.renderPillarPage();
                    break;
                case 'references':
                    await this.renderReferencesPage();
                    break;
                case 'register':
                case 'login':
                    // These pages are rendered server-side
                    break;
            }
            this.updateNavigation();
        } catch (error) {
            this.showToast('Terjadi kesalahan saat memuat halaman', 'error');
            console.error('Render error:', error);
        }
        
        this.hideLoading();
    }

    async navigateToPage(page, params = {}) {
        this.currentPage = page;
        Object.assign(this, params);
        
        // Update URL
        let newURL = '/';
        switch (page) {
            case 'register':
                newURL = '/register';
                break;
            case 'login':
                newURL = '/login';
                break;
            case 'pillar':
                newURL = `/pilar/${params.slug || this.currentPillarSlug}`;
                break;
            case 'references':
                newURL = '/references';
                break;
        }
        
        // Check authentication for protected pages
        const protectedPages = ['home', 'pillar', 'references'];
        if (protectedPages.includes(page) && !this.currentUser) {
            window.location.href = '/login';
            return;
        }
        
        // For server-rendered pages, redirect
        if (['register', 'login', 'pillar', 'references'].includes(page)) {
            window.location.href = newURL;
            return;
        }
        
        history.pushState({ page, params }, '', newURL);
        await this.renderCurrentPage();
    }

    async renderHomePage() {
        if (!this.currentUser) {
            window.location.href = '/login';
            return;
        }

        // Update welcome message
        const welcomeTitle = document.getElementById('welcomeTitle');
        const welcomeSubtitle = document.getElementById('welcomeSubtitle');
        
        if (welcomeTitle) {
            welcomeTitle.textContent = `Selamat Datang, ${this.currentUser.name}!`;
        }
        if (welcomeSubtitle) {
            welcomeSubtitle.innerHTML = `
                <span class="block">${this.currentUser.major.name} - ${this.currentUser.study_program.name}</span>
                <span class="text-base text-gray-500 mt-2 block">NIM: ${this.currentUser.nim}</span>
            `;
        }

        // Show progress section
        const progressSection = document.getElementById('progressSection');
        if (progressSection) {
            progressSection.classList.remove('hidden');
        }

        // Load and display data
        await Promise.all([
            this.loadProgressData(),
            this.loadPillarsForHome(),
            this.loadStatsForHome()
        ]);

        // Update CTA buttons for logged-in user
        const ctaButtons = document.getElementById('ctaButtons');
        if (ctaButtons) {
            ctaButtons.innerHTML = `
                <button onclick="app.navigateToPage('references')" class="bg-white text-primary-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition-colors transform hover:scale-105">
                    <i class="fas fa-book mr-2"></i>Lihat Semua Referensi
                </button>
                <button onclick="app.logout()" class="border-2 border-white text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-white hover:text-primary-600 transition-colors">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </button>
            `;
        }
    }

    async renderPillarPage() {
        if (!this.currentPillarSlug) return;

        const pillarContent = document.getElementById('pillarContent');
        const pillarLoading = document.getElementById('pillarLoading');
        
        try {
            // Show loading
            pillarLoading?.classList.remove('hidden');
            pillarContent?.classList.add('hidden');

            const pillarData = await this.apiCall(`/contents/${this.currentPillarSlug}`);
            
            // Update pillar info
            document.getElementById('pillarTitle').textContent = pillarData.title;
            document.getElementById('pillarImage').style.backgroundImage = url('${pillarData.image_url}');
            document.getElementById('pillarDescription').innerHTML = pillarData.description;
            document.getElementById('pillarChallenges').innerHTML = pillarData.challenge_solution || 'Tidak ada data';
            document.getElementById('pillarTechnology').innerHTML = pillarData.technology || 'Tidak ada data';
            document.getElementById('pillarImplementation').innerHTML = pillarData.implementation || 'Tidak ada data';

            // Update references
            await this.loadPillarReferences(pillarData.resources || []);

            // Show content
            pillarLoading?.classList.add('hidden');
            pillarContent?.classList.remove('hidden');

        } catch (error) {
            this.showToast('Gagal memuat data pilar', 'error');
            console.error('Pillar load error:', error);
        }
    }

    async renderReferencesPage() {
        try {
            const referencesData = await this.apiCall('/resources');
            
            // Update statistics
            this.updateReferencesStats(referencesData);
            
            // Populate filters
            this.populateFilters(referencesData);
            
            // Display references
            this.displayReferences(referencesData);
            
            // Hide loading, show content
            document.getElementById('referencesLoading')?.classList.add('hidden');
            document.getElementById('referencesContainer')?.classList.remove('hidden');
            
        } catch (error) {
            this.showToast('Gagal memuat data referensi', 'error');
            console.error('References load error:', error);
        }
    }

    // Data Loading Methods
    async loadProgressData() {
        try {
            const progressData = await this.apiCall('/progress');
            
            const progressBar = document.getElementById('progressBar');
            const progressPercentage = document.getElementById('progressPercentage');
            const progressText = document.getElementById('progressText');
            
            if (progressBar) progressBar.style.width = `${progressData.percentage}%`;
            if (progressPercentage) progressPercentage.textContent = `${progressData.percentage}%`;
            if (progressText) {
                progressText.textContent = `${progressData.completed_count} dari ${progressData.total_references} referensi telah dibaca`;
            }
            
            // Show certificate section if completed
            if (progressData.percentage === 100) {
                const certificateSection = document.getElementById('certificateSection');
                if (certificateSection) {
                    certificateSection.classList.remove('hidden');
                }
            }
            
        } catch (error) {
            console.error('Progress load error:', error);
        }
    }

    async loadPillarsForHome() {
        try {
            const pillarsData = await this.apiCall('/contents');
            const pillarsGrid = document.getElementById('pillarsGrid');
            
            if (pillarsGrid) {
                pillarsGrid.innerHTML = pillarsData.map(pillar => `
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 cursor-pointer group" onclick="app.navigateToPage('pillar', {slug: '${pillar.slug}'})">
                        <div class="h-48 bg-gradient-primary relative overflow-hidden">
                            <img src="${pillar.image_url}" alt="${pillar.title}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" onerror="this.style.display='none'">
                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                                <div class="text-white text-center">
                                    <div class="text-3xl mb-2">${this.getPillarIcon(pillar.slug)}</div>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-primary-600 transition-colors">
                                ${pillar.title}
                            </h3>
                            <p class="text-gray-600 leading-relaxed mb-4">
                                ${pillar.description.substring(0, 120)}...
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800">
                                    <i class="fas fa-book mr-1"></i>
                                    ${pillar.resources_count || 0} Referensi
                                </span>
                                <i class="fas fa-arrow-right text-primary-500 group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
            
        } catch (error) {
            console.error('Pillars load error:', error);
        }
    }

    async loadStatsForHome() {
        try {
            const progressData = await this.apiCall('/progress');
            const pillarsData = await this.apiCall('/contents');
            
            const statsSection = document.getElementById('statsSection');
            if (statsSection) {
                statsSection.innerHTML = `
                    <div class="bg-white rounded-2xl shadow-lg p-6 text-center border-l-4 border-primary-500">
                        <div class="text-3xl font-bold text-primary-500 mb-2">${pillarsData.length}</div>
                        <div class="text-sm text-gray-600 uppercase tracking-wide">Pilar Smart City</div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-lg p-6 text-center border-l-4 border-blue-500">
                        <div class="text-3xl font-bold text-blue-500 mb-2">${progressData.total_references}</div>
                        <div class="text-sm text-gray-600 uppercase tracking-wide">Total Referensi</div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-lg p-6 text-center border-l-4 border-green-500">
                        <div class="text-3xl font-bold text-green-500 mb-2">${progressData.completed_count}</div>
                        <div class="text-sm text-gray-600 uppercase tracking-wide">Telah Dibaca</div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-lg p-6 text-center border-l-4 border-purple-500">
                        <div class="text-3xl font-bold text-purple-500 mb-2">${progressData.percentage}%</div>
                        <div class="text-sm text-gray-600 uppercase tracking-wide">Progress</div>
                    </div>
                `;
                statsSection.classList.remove('hidden');
            }
            
        } catch (error) {
            console.error('Stats load error:', error);
        }
    }

    async loadPillarReferences(resources) {
        const referencesContainer = document.getElementById('referencesContainer');
        const referenceCount = document.getElementById('referenceCount');
        const noReferences = document.getElementById('noReferences');
        const referencesLoading = document.getElementById('referencesLoading');
        
        // Update counts
        if (referenceCount) referenceCount.textContent = (`${resources.length}`);
        
        // Update stats
        const totalReferences = document.getElementById('totalReferences');
        const completedReferences = document.getElementById('completedReferences');
        const pillarProgress = document.getElementById('pillarProgress');
        const pillarProgressText = document.getElementById('pillarProgressText');
        
        const completedCount = resources.filter(r => this.readReferences.has(r.id.toString())).length;
        const percentage = resources.length > 0 ? Math.round((completedCount / resources.length) * 100) : 0;
        
        if (totalReferences) totalReferences.textContent = resources.length;
        if (completedReferences) completedReferences.textContent = completedCount;
        if (pillarProgress) pillarProgress.style.width = `${percentage}%`;
        if (pillarProgressText) pillarProgressText.textContent = `${percentage}% Complete`;
        
        referencesLoading?.classList.add('hidden');
        
        if (resources.length === 0) {
            noReferences?.classList.remove('hidden');
            referencesContainer?.classList.add('hidden');
            return;
        }
        
        if (referencesContainer) {
            referencesContainer.innerHTML = resources.map(resource => this.createReferenceItem(resource)).join('');
            referencesContainer.classList.remove('hidden');
        }
    }

    async loadDropdownData() {
        // Load majors for register page
        try {
            const majorSelect = document.getElementById('major_id');
            if (majorSelect) {
                const majorsResponse = await this.apiCall('/majors');
                if (majorsResponse.success) {
                    majorSelect.innerHTML = '<option value="">Pilih Jurusan</option>' +
                        majorsResponse.data.map(major => 
                            <option value="${major.id}">${major.name}</option>
                        ).join('');
                }
            }
        } catch (error) {
            console.error('Failed to load majors:', error);
        }
    }

    async loadStudyPrograms(majorId) {
        const studyProgramSelect = document.getElementById('study_program_id');
        if (!studyProgramSelect) return;
        
        if (!majorId) {
            studyProgramSelect.innerHTML = '<option value="">Pilih Program Studi</option>';
            studyProgramSelect.disabled = true;
            return;
        }
        
        try {
            const response = await this.apiCall(`/study-programs/${majorId}`);
            if (response.success) {
                studyProgramSelect.innerHTML = '<option value="">Pilih Program Studi</option>' +
                    response.data.map(program => 
                        <option value="${program.id}">${program.name}</option>
                    ).join('');
                studyProgramSelect.disabled = false;
            }
        } catch (error) {
            console.error('Failed to load study programs:', error);
            this.showToast('Gagal memuat program studi', 'error');
        }
    }

    // Form Handling
    async handleFormSubmit(e) {
        const form = e.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            if (form.id === 'registerForm') {
                await this.handleRegister(data);
            } else if (form.id === 'loginForm') {
                await this.handleLogin(data);
            }
        } catch (error) {
            this.showToast(error.message || 'Terjadi kesalahan', 'error');
        }
    }

    async handleRegister(data) {
        this.clearFormErrors();
        
        try {
            const response = await this.apiCall('/auth/register', 'POST', data);
            
            if (response.success) {
                // Show success modal
                const form = document.getElementById('registerForm').parentElement;
                const successModal = document.getElementById('successModal');
                const uniqueCode = document.getElementById('uniqueCode');
                
                if (uniqueCode) uniqueCode.textContent = response.secret_code;
                
                form.style.display = 'none';
                successModal.classList.remove('hidden');
                
                this.showToast('Pendaftaran berhasil! Simpan kode unik Anda.', 'success');
            }
        } catch (error) {
            if (error.errors) {
                this.displayFormErrors(error.errors);
            } else {
                throw error;
            }
        }
    }

    async handleLogin(data) {
        this.clearFormErrors();
        
        try {
            const response = await this.apiCall('/auth/login', 'POST', data);
            
            if (response.success) {
                this.currentUser = response.user;
                this.readReferences = new Set(response.progress.map(id => id.toString()));
                this.storeAuth(response.token);
                
                this.showToast(`Selamat datang, ${response.user.name}!`, 'success');
                
                // Redirect to home
                window.location.href = '/';
            }
        } catch (error) {
            if (error.errors) {
                this.displayFormErrors(error.errors);
            } else {
                throw error;
            }
        }
    }

    // Reference Methods
    viewReference(url) {
        if (!url || url === '#') {
            this.showToast('Link referensi tidak tersedia', 'warning');
            return;
        }
        window.open(url, '_blank');
    }

    async toggleReadStatus(resourceId, button) {
        try {
            const isCurrentlyRead = this.readReferences.has(resourceId.toString());
            
            const response = await this.apiCall('/progress/toggle', 'POST', {
                resource_id: resourceId,
                is_completed: !isCurrentlyRead
            });

            if (response.success) {
                if (isCurrentlyRead) {
                    this.readReferences.delete(resourceId.toString());
                    button.className = 'px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-medium transition-colors text-sm';
                    button.innerHTML = '<i class="fas fa-bookmark mr-1"></i>Tandai';
                } else {
                    this.readReferences.add(resourceId.toString());
                    button.className = 'px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition-colors text-sm';
                    button.innerHTML = '<i class="fas fa-check mr-1"></i>Dibaca';
                }

                // Update reference item styling
                const referenceItem = button.closest('[data-resource-id], tr');
                if (referenceItem) {
                    if (this.readReferences.has(resourceId.toString())) {
                        referenceItem.classList.add('bg-green-50', 'border-l-4', 'border-green-400');
                        referenceItem.dataset.status = 'read';
                    } else {
                        referenceItem.classList.remove('bg-green-50', 'border-l-4', 'border-green-400');
                        referenceItem.dataset.status = 'unread';
                    }
                }

                // Update progress
                await this.updateProgressDisplay();
                
                this.showToast(
                    isCurrentlyRead ? 'Referensi ditandai belum dibaca' : 'Referensi ditandai sudah dibaca',
                    'success'
                );
            }
        } catch (error) {
            this.showToast('Gagal mengubah status referensi', 'error');
            console.error('Toggle read error:', error);
        }
    }

    async updateProgressDisplay() {
        try {
            const progressData = await this.apiCall('/progress');
            
            // Update progress bar on current page
            const progressBar = document.getElementById('progressBar');
            const progressPercentage = document.getElementById('progressPercentage');
            const progressText = document.getElementById('progressText');
            
            if (progressBar) progressBar.style.width = `${progressData.percentage}%`;
            if (progressPercentage) progressPercentage.textContent = `${progressData.percentage}%`;
            if (progressText) {
                progressText.textContent = `${progressData.completed_count} dari ${progressData.total_references} referensi telah dibaca`;
            }

            // Update pillar progress if on pillar page
            const pillarProgress = document.getElementById('pillarProgress');
            const completedReferences = document.getElementById('completedReferences');
            
            if (pillarProgress && completedReferences && this.currentPage === 'pillar') {
                // Recalculate pillar-specific progress
                const pillarRefs = document.querySelectorAll('[data-resource-id]');
                const pillarCompleted = Array.from(pillarRefs).filter(ref => 
                    this.readReferences.has(ref.dataset.resourceId)
                ).length;
                const pillarPercentage = pillarRefs.length > 0 ? Math.round((pillarCompleted / pillarRefs.length) * 100) : 0;
                
                pillarProgress.style.width = `${pillarPercentage}%`;
                completedReferences.textContent = pillarCompleted;
                document.getElementById('pillarProgressText').textContent = `${pillarPercentage}% Complete`;
            }

            // Show certificate if 100%
            if (progressData.percentage === 100) {
                const certificateSection = document.getElementById('certificateSection');
                if (certificateSection && certificateSection.classList.contains('hidden')) {
                    certificateSection.classList.remove('hidden');
                    this.showToast('🎉 Selamat! Anda telah menyelesaikan semua pembelajaran!', 'success');
                }
            }
        } catch (error) {
            console.error('Update progress error:', error);
        }
    }

    // Certificate Methods
    async showCertificateModal() {
        if (!this.currentUser) return;

        try {
            const certificateData = await this.apiCall('/certificate');
            
            if (certificateData.success) {
                const modal = document.getElementById('certificateModal');
                const preview = document.getElementById('certificatePreview');
                
                preview.innerHTML = `
                    <div class="certificate-preview bg-white p-12 border-4 border-primary-500 rounded-2xl text-center relative">
                        <div class="absolute top-4 right-4 text-4xl opacity-20">🏆</div>
                        <div class="text-3xl font-bold text-primary-600 mb-2">SERTIFIKAT PENYELESAIAN</div>
                        <div class="text-xl text-secondary-500 mb-8">Smart City Mini Library</div>
                        <div class="text-gray-600 mb-4">Diberikan kepada:</div>
                        <div class="text-2xl font-bold text-gray-800 mb-6 border-b-2 border-primary-500 inline-block pb-2">
                            ${certificateData.certificate_data.name}
                        </div>
                        <div class="text-gray-700 leading-relaxed mb-8">
                            Telah berhasil menyelesaikan pembelajaran<br>
                            <strong>6 Pilar Smart City Indonesia</strong><br>
                            dengan membaca seluruh referensi akademik<br><br>
                            <em>${certificateData.certificate_data.major} - ${certificateData.certificate_data.study_program}</em><br>
                            <em>NIM: ${certificateData.certificate_data.nim}</em>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <div>
                                <div>Tanggal:</div>
                                <div>${new Date().toLocaleDateString('id-ID')}</div>
                            </div>
                            <div>
                                <div>Smart City Library</div>
                                <div>Politeknik Negeri Indramayu</div>
                            </div>
                        </div>
                    </div>
                `;
                
                modal.classList.remove('hidden');
            }
        } catch (error) {
            this.showToast('Gagal memuat data sertifikat', 'error');
            console.error('Certificate error:', error);
        }
    }

    closeModal() {
        document.getElementById('certificateModal').classList.add('hidden');
    }

    // Filter and Search Methods
    updateReferencesStats(references) {
        const totalCount = document.getElementById('totalReferencesCount');
        const completedCount = document.getElementById('completedReferencesCount');
        const journalCount = document.getElementById('journalCount');
        const bookCount = document.getElementById('bookCount');
        
        if (totalCount) totalCount.textContent = references.length;
        if (completedCount) {
            completedCount.textContent = references.filter(r => 
                this.readReferences.has(r.id.toString())
            ).length;
        }
        if (journalCount) {
            journalCount.textContent = references.filter(r => 
                r.source_category.name === 'Jurnal'
            ).length;
        }
        if (bookCount) {
            bookCount.textContent = references.filter(r => 
                r.source_category.name === 'Buku'
            ).length;
        }
    }

    populateFilters(references) {
        // Pillar filter
        const pillarFilter = document.getElementById('pillarFilter');
        if (pillarFilter) {
            const pillars = [...new Set(references.map(r => r.content.title))];
            pillarFilter.innerHTML = '<option value="">Semua Pilar</option>' +
                pillars.map(pillar => <option value="${pillar}">${pillar}</option>).join('');
        }
        
        // Category filter
        const categoryFilter = document.getElementById('categoryFilter');
        if (categoryFilter) {
            const categories = [...new Set(references.map(r => r.source_category.name))];
            categoryFilter.innerHTML = '<option value="">Semua Kategori</option>' +
                categories.map(category => <option value="${category}">${category}</option>).join('');
        }
    }

    displayReferences(references) {
        const tbody = document.getElementById('referencesTableBody');
        if (!tbody) return;
        
        tbody.innerHTML = references.map(resource => `
            <tr data-pillar="${resource.content.title}" 
                data-category="${resource.source_category.name}"
                data-status="${this.readReferences.has(resource.id.toString()) ? 'read' : 'unread'}"
                data-resource-id="${resource.id}"
                class="hover:bg-gray-50 ${this.readReferences.has(resource.id.toString()) ? 'bg-green-50' : ''}">
                <td class="px-6 py-4">
                    <div class="font-medium text-gray-900">${resource.title}</div>
                    <div class="text-sm text-gray-500">oleh ${resource.author}</div>
                </td>
                <td class="px-6 py-4 text-gray-900">${resource.year}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                        ${resource.source_category.name === 'Jurnal' ? 'bg-blue-100 text-blue-800' : 
                          resource.source_category.name === 'Buku' ? 'bg-green-100 text-green-800' : 
                          'bg-gray-100 text-gray-800'}">
                        ${resource.source_category.name}
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-900">${resource.content.title}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                        ${this.readReferences.has(resource.id.toString()) ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                        ${this.readReferences.has(resource.id.toString()) ? 'Dibaca' : 'Belum Dibaca'}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex space-x-2">
                        <button onclick="app.viewReference('${resource.link}')" 
                                class="px-3 py-1 bg-primary-500 hover:bg-primary-600 text-white rounded text-sm transition-colors">
                            <i class="fas fa-external-link-alt"></i>
                        </button>
                        <button onclick="app.toggleReadStatus(${resource.id}, this)"
                                class="px-3 py-1 ${this.readReferences.has(resource.id.toString()) ? 'bg-green-500 hover:bg-green-600' : 'bg-yellow-500 hover:bg-yellow-600'} text-white rounded text-sm transition-colors">
                            <i class="fas ${this.readReferences.has(resource.id.toString()) ? 'fa-check' : 'fa-bookmark'}"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    filterReferences() {
        const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
        const pillarFilter = document.getElementById('pillarFilter')?.value || '';
        const categoryFilter = document.getElementById('categoryFilter')?.value || '';
        const statusFilter = document.getElementById('statusFilter')?.value || '';
        
        const rows = document.querySelectorAll('#referencesTableBody tr');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const title = row.querySelector('.font-medium')?.textContent.toLowerCase() || '';
            const author = row.querySelector('.text-sm')?.textContent.toLowerCase() || '';
            const pillar = row.dataset.pillar || '';
            const category = row.dataset.category || '';
            const status = row.dataset.status || '';
            
            const matchesSearch = !searchTerm || title.includes(searchTerm) || author.includes(searchTerm);
            const matchesPillar = !pillarFilter || pillar === pillarFilter;
            const matchesCategory = !categoryFilter || category === categoryFilter;
            const matchesStatus = !statusFilter || status === statusFilter;
            
            if (matchesSearch && matchesPillar && matchesCategory && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show/hide no results message
        const noResults = document.getElementById('noResults');
        const referencesContainer = document.getElementById('referencesContainer');
        
        if (visibleCount === 0) {
            noResults?.classList.remove('hidden');
            referencesContainer?.classList.add('hidden');
        } else {
            noResults?.classList.add('hidden');
            referencesContainer?.classList.remove('hidden');
        }
    }

    clearFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('pillarFilter').value = '';
        document.getElementById('categoryFilter').value = '';
        document.getElementById('statusFilter').value = '';
        this.filterReferences();
    }

    // Helper Methods
    createReferenceItem(resource) {
        const isRead = this.readReferences.has(resource.id.toString());
        return `
            <div class="flex items-start justify-between p-6 bg-gray-50 hover:bg-white rounded-xl border hover:border-primary-200 transition-all ${isRead ? 'bg-green-50 border-l-4 border-green-400' : ''}" data-resource-id="${resource.id}">
                <div class="flex-1">
                    <h4 class="font-semibold text-gray-800 mb-2">${resource.title}</h4>
                    <div class="text-sm text-gray-600 space-x-4">
                        <span><i class="fas fa-user mr-1"></i>${resource.author}</span>
                        <span><i class="fas fa-calendar mr-1"></i>${resource.year}</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            ${resource.source_category.name === 'Jurnal' ? 'bg-blue-100 text-blue-800' : 
                              resource.source_category.name === 'Buku' ? 'bg-green-100 text-green-800' : 
                              'bg-gray-100 text-gray-800'}">
                            ${resource.source_category.name}
                        </span>
                        ${isRead ? '<i class="fas fa-check-circle text-green-500"></i>' : ''}
                    </div>
                </div>
                <div class="flex space-x-2 ml-4">
                    <button onclick="app.viewReference('${resource.link}')" 
                            class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg font-medium transition-colors text-sm">
                        <i class="fas fa-external-link-alt mr-1"></i>Lihat
                    </button>
                    <button onclick="app.toggleReadStatus(${resource.id}, this)"
                            class="px-4 py-2 ${isRead ? 'bg-green-500 hover:bg-green-600' : 'bg-yellow-500 hover:bg-yellow-600'} text-white rounded-lg font-medium transition-colors text-sm">
                        <i class="fas ${isRead ? 'fa-check' : 'fa-bookmark'} mr-1"></i>${isRead ? 'Dibaca' : 'Tandai'}
                    </button>
                </div>
            </div>
        `;
    }

    getPillarIcon(slug) {
        const icons = {
            'smart-governance': '🏛',
            'smart-branding': '🌟',
            'smart-economy': '💰',
            'smart-living': '🏠',
            'smart-society': '👥',
            'smart-environment': '🌱'
        };
        return icons[slug] || '🏙';
    }

    // API Methods
    async apiCall(endpoint, method = 'GET', data = null) {
        const token = this.getStoredAuth();
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        };
        
        if (token) {
            headers['Authorization'] = `Bearer ${token}`;
        }

        const config = {
            method,
            headers
        };

        if (data && method !== 'GET') {
            config.body = JSON.stringify(data);
        }

        const response = await fetch(`${this.baseURL}${endpoint}, config`);
        const result = await response.json();

        if (!response.ok) {
            throw result;
        }

        return result;
    }

    // Authentication Methods
    storeAuth(token) {
        localStorage.setItem('smart_city_token', token);
    }

    getStoredAuth() {
        return localStorage.getItem('smart_city_token');
    }

    clearAuth() {
        localStorage.removeItem('smart_city_token');
        this.currentUser = null;
        this.readReferences.clear();
    }

    logout() {
        this.clearAuth();
        this.showToast('Anda telah logout', 'info');
        window.location.href = '/login';
    }

   // Continuation of SmartCityApp class methods

    // Helper Methods (continued)
    updateNavigation() {
        const navUserSection = document.getElementById('navUserSection');
        const navUserSectionMobile = document.getElementById('navUserSectionMobile');
        
        const userContent = this.currentUser ? `
            <div class="flex items-center space-x-3 px-3 py-2 bg-gray-100 rounded-lg">
                <div class="w-8 h-8 bg-gradient-primary rounded-full flex items-center justify-center text-white font-bold text-sm">
                    ${this.currentUser.name.charAt(0).toUpperCase()}
                </div>
                <div class="text-sm">
                    <div class="font-medium text-gray-900">${this.currentUser.name}</div>
                    <div class="text-gray-500">${this.currentUser.nim}</div>
                </div>
            </div>
            <button onclick="app.logout()" class="text-gray-600 hover:text-red-600 px-3 py-2 rounded-lg transition-colors">
                <i class="fas fa-sign-out-alt mr-1"></i>Logout
            </button>
        ` : `
            <a href="#" data-page="login" class="nav-link">
                <i class="fas fa-sign-in-alt mr-2"></i>Login
            </a>
            <a href="#" data-page="register" class="nav-link">
                <i class="fas fa-user-plus mr-2"></i>Register
            </a>
        `;
        
        const mobileUserContent = this.currentUser ? `
            <div class="px-3 py-2 border-t border-gray-200">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-gradient-primary rounded-full flex items-center justify-center text-white font-bold">
                        ${this.currentUser.name.charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <div class="font-medium text-gray-900">${this.currentUser.name}</div>
                        <div class="text-sm text-gray-500">${this.currentUser.nim}</div>
                    </div>
                </div>
                <button onclick="app.logout()" class="w-full text-left px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </button>
            </div>
        ` : `
            <a href="#" data-page="login" class="nav-link-mobile">
                <i class="fas fa-sign-in-alt mr-2"></i>Login
            </a>
            <a href="#" data-page="register" class="nav-link-mobile">
                <i class="fas fa-user-plus mr-2"></i>Register
            </a>
        `;
        
        if (navUserSection) navUserSection.innerHTML = userContent;
        if (navUserSectionMobile) navUserSectionMobile.innerHTML = mobileUserContent;
        
        // Update active navigation links
        const currentPageLinks = document.querySelectorAll(`[data-page="${this.currentPage}"]`);
        document.querySelectorAll('.nav-link, .nav-link-mobile').forEach(link => {
            link.classList.remove('active');
        });
        currentPageLinks.forEach(link => {
            if (link.classList.contains('nav-link') || link.classList.contains('nav-link-mobile')) {
                link.classList.add('active');
            }
        });
    }

    showLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) overlay.classList.remove('hidden');
    }

    hideLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) overlay.classList.add('hidden');
    }

    showToast(message, type = 'info') {
        const container = document.getElementById('toastContainer');
        if (!container) return;

        const toastId = 'toast_' + Date.now();
        const iconMap = {
            success: 'fas fa-check-circle text-green-500',
            error: 'fas fa-times-circle text-red-500',
            warning: 'fas fa-exclamation-triangle text-yellow-500',
            info: 'fas fa-info-circle text-blue-500'
        };

        const colorMap = {
            success: 'bg-green-50 border-green-200',
            error: 'bg-red-50 border-red-200',
            warning: 'bg-yellow-50 border-yellow-200',
            info: 'bg-blue-50 border-blue-200'
        };

        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = `${colorMap[type]} border-l-4 p-4 rounded-lg shadow-lg max-w-sm animate-slide-in`;
        toast.innerHTML = `
            <div class="flex items-center">
                <i class="${iconMap[type]} mr-3"></i>
                <div class="text-sm font-medium text-gray-800 flex-1">${message}</div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-gray-500 hover:text-gray-700 ml-2">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        container.appendChild(toast);

        // Auto remove after 5 seconds
        setTimeout(() => {
            const toastElement = document.getElementById(toastId);
            if (toastElement) {
                toastElement.remove();
            }
        }, 5000);
    }

    // Form Helper Methods
    clearFormErrors() {
        document.querySelectorAll('[id$="Error"]').forEach(errorElement => {
            errorElement.classList.add('hidden');
            errorElement.textContent = '';
        });
        
        document.querySelectorAll('.border-red-500').forEach(input => {
            input.classList.remove('border-red-500');
            input.classList.add('border-gray-300');
        });
    }

    displayFormErrors(errors) {
        Object.keys(errors).forEach(field => {
            const errorElement = document.getElementById(`${field}Error`);
            const inputElement = document.getElementById(field);
            
            if (errorElement) {
                errorElement.textContent = errors[field][0];
                errorElement.classList.remove('hidden');
            }
            
            if (inputElement) {
                inputElement.classList.add('border-red-500');
                inputElement.classList.remove('border-gray-300');
            }
        });
    }

    // Utility Methods
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    truncateText(text, maxLength = 120) {
        if (text.length <= maxLength) return text;
        return text.substring(0, maxLength) + '...';
    }
}

// Certificate Functions (outside the class for global access)
function downloadCertificate() {
    const certificateElement = document.querySelector('.certificate-preview');
    if (!certificateElement) return;

    html2canvas(certificateElement, {
        scale: 2,
        backgroundColor: '#ffffff',
        width: certificateElement.scrollWidth,
        height: certificateElement.scrollHeight
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = `sertifikat-smart-city-${app.currentUser.nim}.png`;
        link.href = canvas.toDataURL();
        link.click();
    }).catch(error => {
        console.error('Error generating certificate image:', error);
        app.showToast('Gagal mengunduh sertifikat', 'error');
    });
}

function generatePDF() {
    const certificateElement = document.querySelector('.certificate-preview');
    if (!certificateElement) return;

    html2canvas(certificateElement, {
        scale: 2,
        backgroundColor: '#ffffff'
    }).then(canvas => {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('landscape', 'mm', 'a4');
        
        const imgData = canvas.toDataURL('image/png');
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = pdf.internal.pageSize.getHeight();
        
        // Calculate dimensions to fit the page while maintaining aspect ratio
        const canvasAspectRatio = canvas.width / canvas.height;
        const pdfAspectRatio = pdfWidth / pdfHeight;
        
        let finalWidth, finalHeight, xOffset, yOffset;
        
        if (canvasAspectRatio > pdfAspectRatio) {
            finalWidth = pdfWidth - 20; // 10mm margin on each side
            finalHeight = finalWidth / canvasAspectRatio;
            xOffset = 10;
            yOffset = (pdfHeight - finalHeight) / 2;
        } else {
            finalHeight = pdfHeight - 20; // 10mm margin on top and bottom
            finalWidth = finalHeight * canvasAspectRatio;
            xOffset = (pdfWidth - finalWidth) / 2;
            yOffset = 10;
        }
        
        pdf.addImage(imgData, 'PNG', xOffset, yOffset, finalWidth, finalHeight);
        pdf.save(`sertifikat-smart-city-${app.currentUser.nim}.pdf`);
    }).catch(error => {
        console.error('Error generating PDF:', error);
        app.showToast('Gagal mengunduh PDF', 'error');
    });
}

// Initialize the app when DOM is ready
let app;
document.addEventListener('DOMContentLoaded', function() {
    app = new SmartCityApp();
    
    // Handle browser back/forward buttons
    window.addEventListener('popstate', function(event) {
        if (event.state && event.state.page) {
            app.currentPage = event.state.page;
            Object.assign(app, event.state.params || {});
            app.renderCurrentPage();
        }
    });
    
    // Add some custom animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }
        
        .certificate-preview {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .hover-scale {
            transition: transform 0.2s ease-in-out;
        }
        
        .hover-scale:hover {
            transform: scale(1.05);
        }
        
        /* Loading animation improvements */
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }
        
        /* Progress bar animation */
        .progress-bar-animated {
            background-image: linear-gradient(
                45deg,
                rgba(255, 255, 255, 0.15) 25%,
                transparent 25%,
                transparent 50%,
                rgba(255, 255, 255, 0.15) 50%,
                rgba(255, 255, 255, 0.15) 75%,
                transparent 75%,
                transparent
            );
            background-size: 1rem 1rem;
            animation: progress-bar-stripes 1s linear infinite;
        }
        
        @keyframes progress-bar-stripes {
            0% {
                background-position: 1rem 0;
            }
            100% {
                background-position: 0 0;
            }
        }
    `;
    document.head.appendChild(style);
});

// Global error handler
window.addEventListener('error', function(event) {
    console.error('Global error:', event.error);
    if (window.app) {
        app.showToast('Terjadi kesalahan aplikasi', 'error');
    }
});

// Handle unhandled promise rejections
window.addEventListener('unhandledrejection', function(event) {
    console.error('Unhandled promise rejection:', event.reason);
    if (window.app) {
        app.showToast('Terjadi kesalahan jaringan', 'error');
    }
    event.preventDefault();
});

window.app = new SmartCityApp();