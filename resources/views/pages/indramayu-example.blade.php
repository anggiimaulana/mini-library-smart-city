@extends('layouts.app')

@section('content')
    <div id="indramayuExamplePage" class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-8">
                <button onclick="app.navigateToPage('home')"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Beranda
                </button>
            </div>

            <!-- Header -->
            <div class="text-center mb-12">
                <div class="text-6xl mb-4">🏙️</div>
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    Contoh Implementasi Smart City di Indramayu
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Melihat penerapan konsep Smart City dalam konteks nyata di Kabupaten Indramayu
                </p>
            </div>

            <!-- Introduction -->
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-8 mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Tentang Smart City Indramayu</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Kabupaten Indramayu sebagai salah satu daerah di Jawa Barat telah mulai mengimplementasikan
                    konsep Smart City untuk meningkatkan pelayanan publik dan kesejahteraan masyarakat.
                    Implementasi ini mengacu pada 6 pilar Smart City Indonesia.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Berikut adalah contoh-contoh implementasi nyata dari masing-masing pilar Smart City
                    yang telah atau sedang dikembangkan di Kabupaten Indramayu.
                </p>
            </div>

            <!-- Smart City Pillars Implementation -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">

                <!-- Smart Governance -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border-l-4 border-blue-500">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-university text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-blue-800">Smart Governance</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-blue-800 mb-2">Sistem Pelayanan Publik Online</h4>
                            <p class="text-blue-700 text-sm">Portal online untuk berbagai layanan administrasi seperti
                                perizinan, surat keterangan, dan layanan publik lainnya.</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-blue-800 mb-2">E-Government Platform</h4>
                            <p class="text-blue-700 text-sm">Website resmi pemerintah daerah dengan informasi transparan
                                tentang program, anggaran, dan kebijakan.</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-blue-800 mb-2">Digital Complaint System</h4>
                            <p class="text-blue-700 text-sm">Sistem pengaduan masyarakat secara digital untuk meningkatkan
                                responsivitas pemerintah.</p>
                        </div>
                    </div>
                </div>

                <!-- Smart Branding -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border-l-4 border-yellow-500">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-star text-yellow-600 text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-yellow-800">Smart Branding</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-yellow-800 mb-2">Branding "Indramayu Berseri"</h4>
                            <p class="text-yellow-700 text-sm">Pengembangan identitas kota dengan tagline dan logo yang
                                mencerminkan potensi daerah.</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-yellow-800 mb-2">Promosi Wisata Digital</h4>
                            <p class="text-yellow-700 text-sm">Platform digital untuk mempromosikan destinasi wisata seperti
                                Pantai Karangsong dan wisata kuliner.</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-yellow-800 mb-2">Media Sosial Terintegrasi</h4>
                            <p class="text-yellow-700 text-sm">Penggunaan media sosial untuk komunikasi dan branding daerah
                                secara konsisten.</p>
                        </div>
                    </div>
                </div>

                <!-- Smart Economy -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border-l-4 border-green-500">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-coins text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-green-800">Smart Economy</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-green-800 mb-2">Platform UMKM Digital</h4>
                            <p class="text-green-700 text-sm">Marketplace lokal untuk mendukung UMKM dalam pemasaran produk
                                secara online.</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-green-800 mb-2">E-Commerce Produk Lokal</h4>
                            <p class="text-green-700 text-sm">Platform khusus untuk produk unggulan Indramayu seperti mangga
                                dan kerajinan lokal.</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-green-800 mb-2">Digital Payment Integration</h4>
                            <p class="text-green-700 text-sm">Integrasi sistem pembayaran digital dalam transaksi pemerintah
                                dan komersial.</p>
                        </div>
                    </div>
                </div>

                <!-- Smart Living -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border-l-4 border-purple-500">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-home text-purple-600 text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-purple-800">Smart Living</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-purple-800 mb-2">Smart Healthcare System</h4>
                            <p class="text-purple-700 text-sm">Sistem informasi kesehatan terintegrasi untuk pelayanan
                                kesehatan yang lebih baik.</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-purple-800 mb-2">Digital Education Platform</h4>
                            <p class="text-purple-700 text-sm">Platform pembelajaran digital untuk mendukung pendidikan di
                                era teknologi.</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-purple-800 mb-2">Public Safety Monitoring</h4>
                            <p class="text-purple-700 text-sm">Sistem monitoring keamanan publik menggunakan teknologi CCTV
                                dan aplikasi darurat.</p>
                        </div>
                    </div>
                </div>

                <!-- Smart Society -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border-l-4 border-red-500">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-users text-red-600 text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-red-800">Smart Society</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-red-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-red-800 mb-2">Community Engagement Platform</h4>
                            <p class="text-red-700 text-sm">Platform untuk partisipasi masyarakat dalam perencanaan
                                pembangunan daerah.</p>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-red-800 mb-2">Digital Literacy Program</h4>
                            <p class="text-red-700 text-sm">Program pelatihan literasi digital untuk meningkatkan kemampuan
                                masyarakat.</p>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-red-800 mb-2">Social Media Integration</h4>
                            <p class="text-red-700 text-sm">Pemanfaatan media sosial untuk komunikasi dua arah antara
                                pemerintah dan masyarakat.</p>
                        </div>
                    </div>
                </div>

                <!-- Smart Environment -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border-l-4 border-teal-500">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-leaf text-teal-600 text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-teal-800">Smart Environment</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-teal-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-teal-800 mb-2">Air Quality Monitoring</h4>
                            <p class="text-teal-700 text-sm">Sistem monitoring kualitas udara menggunakan sensor IoT untuk
                                memantau polusi.</p>
                        </div>
                        <div class="bg-teal-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-teal-800 mb-2">Smart Waste Management</h4>
                            <p class="text-teal-700 text-sm">Sistem pengelolaan sampah pintar dengan tracking dan optimasi
                                rute pengangkutan.</p>
                        </div>
                        <div class="bg-teal-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-teal-800 mb-2">Water Quality Sensors</h4>
                            <p class="text-teal-700 text-sm">Monitoring kualitas air sungai dan air bersih menggunakan
                                sensor digital.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Challenges and Future Development -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-road text-primary-500 mr-3"></i>
                    Tantangan dan Pengembangan Selanjutnya
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 text-red-600">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Tantangan
                        </h3>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <i class="fas fa-circle text-red-400 text-xs mt-2 mr-3"></i>
                                <span class="text-gray-700">Keterbatasan infrastruktur teknologi di beberapa wilayah</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-circle text-red-400 text-xs mt-2 mr-3"></i>
                                <span class="text-gray-700">Peningkatan literasi digital masyarakat</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-circle text-red-400 text-xs mt-2 mr-3"></i>
                                <span class="text-gray-700">Kebutuhan SDM yang kompeten di bidang teknologi</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-circle text-red-400 text-xs mt-2 mr-3"></i>
                                <span class="text-gray-700">Integrasi sistem antar instansi pemerintah</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 text-green-600">
                            <i class="fas fa-lightbulb mr-2"></i>Pengembangan Selanjutnya
                        </h3>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <i class="fas fa-circle text-green-400 text-xs mt-2 mr-3"></i>
                                <span class="text-gray-700">Implementasi IoT untuk monitoring infrastruktur kota</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-circle text-green-400 text-xs mt-2 mr-3"></i>
                                <span class="text-gray-700">Pengembangan aplikasi mobile terintegrasi</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-circle text-green-400 text-xs mt-2 mr-3"></i>
                                <span class="text-gray-700">Implementasi big data dan AI untuk analisis kota</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-circle text-green-400 text-xs mt-2 mr-3"></i>
                                <span class="text-gray-700">Peningkatan layanan digital berbasis citizen-centric</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="text-center bg-gradient-primary rounded-2xl p-8 text-white">
                <h3 class="text-2xl font-bold mb-4">Lanjutkan Pembelajaran Anda</h3>
                <p class="text-lg mb-6 opacity-90">
                    Sudah memahami implementasi Smart City di Indramayu? Mari lanjutkan dengan mempelajari referensi
                    lengkap!
                </p>
                <div class="space-x-4">
                    <button onclick="app.navigateToPage('references')"
                        class="bg-white text-primary-600 px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition-colors">
                        <i class="fas fa-book mr-2"></i>Lihat Semua Referensi
                    </button>
                    <button onclick="app.navigateToPage('quiz')"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-8 py-3 rounded-lg font-bold transition-colors">
                        <i class="fas fa-question-circle mr-2"></i>Ikuti Kuis
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection