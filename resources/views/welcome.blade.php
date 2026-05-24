<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Informasi Skripsi - Fakultas Psikologi UNS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" />

    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #22004C;
            --primary-light: #3d0a6b;
            --primary-dark: #190038;
            --accent-color: #6c2d9e;
            --text-dark: #2d3748;
            --text-light: #718096;
            --bg-light: #f7fafc;
            --white: #ffffff;
        }

        body {
            font-family: 'Figtree', sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
        }

        /* Header */
        .header {
            background-color: var(--primary-color);
            padding: 1rem 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: var(--white);
            text-decoration: none;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: var(--white);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--primary-color);
            font-weight: 700;
        }

        .logo-text {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .nav-menu {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-menu a {
            color: var(--white);
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s;
        }

        .nav-menu a:hover {
            opacity: 0.8;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.625rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            display: inline-block;
        }

        .btn-primary {
            background-color: var(--white);
            color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #f0f0f0;
            transform: translateY(-2px);
        }

        .btn-outline {
            background-color: transparent;
            color: var(--white);
            border: 2px solid var(--white);
        }

        .btn-outline:hover {
            background-color: var(--white);
            color: var(--primary-color);
        }

        /* Hero Slider Section */
        .hero-slider {
            position: relative;
            width: 100%;
            height: 600px;
            overflow: hidden;
        }

        .slider-container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .slider-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .slide.active {
            opacity: 1;
            z-index: 1;
        }

        .slide-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .slide-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(34, 0, 76, 0.85) 0%, rgba(108, 45, 158, 0.75) 100%);
        }

        .slide-content {
            position: relative;
            z-index: 2;
            color: var(--white);
            text-align: center;
            padding: 2rem;
            width: 100%;
        }

        .hero-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .slide-content h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            animation: fadeInUp 1s ease-out;
        }

        .slide-content h2 {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
            opacity: 0.95;
            animation: fadeInUp 1s ease-out 0.2s both;
        }

        .slide-content p {
            font-size: 1.125rem;
            opacity: 0.9;
            margin-bottom: 1rem;
            animation: fadeInUp 1s ease-out 0.4s both;
        }

        .slide-content .btn {
            font-size: 1.125rem;
            padding: 1rem 2.5rem;
            animation: fadeInUp 1s ease-out 0.6s both;
        }

        /* Slider Navigation Buttons */
        .slider-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.2);
            color: var(--white);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 1.25rem;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s;
            backdrop-filter: blur(10px);
        }

        .slider-nav:hover {
            background: rgba(255, 255, 255, 0.4);
            transform: translateY(-50%) scale(1.1);
        }

        .slider-nav.prev {
            left: 2rem;
        }

        .slider-nav.next {
            right: 2rem;
        }

        /* Slider Indicators */
        .slider-indicators {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 0.75rem;
            z-index: 10;
        }

        .indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s;
        }

        .indicator:hover {
            background: rgba(255, 255, 255, 0.8);
            transform: scale(1.2);
        }

        .indicator.active {
            background: var(--white);
            width: 40px;
            border-radius: 6px;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Features Section */
        .features {
            padding: 5rem 2rem;
            background-color: var(--white);
        }

        .section-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .section-title p {
            font-size: 1.125rem;
            color: var(--text-light);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: var(--white);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            transition: all 0.3s;
            text-align: center;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(34, 0, 76, 0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: var(--white);
            font-size: 2rem;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: var(--text-light);
            line-height: 1.8;
        }

        /* Process Section */
        .process {
            padding: 5rem 2rem;
            background: linear-gradient(to bottom, var(--bg-light), var(--white));
        }

        .process-steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .process-step {
            background: var(--white);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            text-align: center;
            position: relative;
        }

        .step-number {
            width: 50px;
            height: 50px;
            background: var(--primary-color);
            color: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 auto 1.5rem;
        }

        .process-step h3 {
            font-size: 1.25rem;
            color: var(--primary-color);
            margin-bottom: 0.75rem;
        }

        .process-step p {
            color: var(--text-light);
            font-size: 0.95rem;
        }

        /* FAQ Section */
        .faq {
            padding: 5rem 2rem;
            background: var(--white);
        }

        .faq-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .faq-item {
            background: var(--bg-light);
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--primary-color);
        }

        .faq-item h3 {
            color: var(--primary-color);
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .faq-item h3 i {
            font-size: 1rem;
        }

        .faq-item p {
            color: var(--text-light);
            line-height: 1.8;
        }

        /* Footer */
        .footer {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 3rem 2rem 1.5rem;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }

        .footer-section p,
        .footer-section a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
        }

        .footer-section a:hover {
            color: var(--white);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.7);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .social-links a:hover {
            background: var(--white);
            color: var(--primary-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-menu {
                flex-direction: column;
                gap: 0.5rem;
                text-align: center;
            }

            .hero-slider {
                height: 500px;
            }

            .slide-content h1 {
                font-size: 2rem;
            }

            .slide-content h2 {
                font-size: 1.5rem;
            }

            .slide-content p {
                font-size: 1rem;
            }

            .slider-nav {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .slider-nav.prev {
                left: 1rem;
            }

            .slider-nav.next {
                right: 1rem;
            }

            .features-grid,
            .process-steps {
                grid-template-columns: 1fr;
            }

            .section-title h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a href="{{ url('/') }}" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">SIMSKRIPSI</div>
            </a>
            
            @if (Route::has('login'))
                <div class="auth-buttons">
                    @auth
                        <a href="{{ url('/home') }}" class="btn btn-primary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </header>

    <!-- Hero Section with Slider -->
    <section class="hero-slider">
        <div class="slider-container">
            <div class="slider-wrapper">
                <!-- Slide 1 -->
                <div class="slide active">
                    <div class="slide-image" style="background-image: url('https://picsum.photos/1920/800?random=1')"></div>
                    <div class="slide-overlay"></div>
                    <div class="slide-content">
                        <div class="hero-container">
                            <h1>Selamat Datang!</h1>
                            <h2>Sistem Informasi Manajemen Skripsi</h2>
                            <p>Fakultas Psikologi Universitas Sebelas Maret</p>
                            <p>Platform terintegrasi untuk pengelolaan proses bimbingan, seminar proposal, sidang skripsi, dan penilaian mahasiswa dengan mudah dan efisien.</p>
                            @guest
                                <a href="{{ route('login') }}" class="btn btn-primary">Mulai Sekarang</a>
                            @endguest
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="slide">
                    <div class="slide-image" style="background-image: url('https://picsum.photos/1920/800?random=2')"></div>
                    <div class="slide-overlay"></div>
                    <div class="slide-content">
                        <div class="hero-container">
                            <h1>Proses Bimbingan yang Terstruktur</h1>
                            <h2>Kelola Bimbingan dengan Mudah</h2>
                            <p>Sistem terintegrasi untuk memudahkan proses bimbingan skripsi Anda</p>
                            <p>Dokumentasi lengkap dan terorganisir untuk setiap sesi bimbingan dengan dosen pembimbing.</p>
                            @guest
                                <a href="{{ route('login') }}" class="btn btn-primary">Mulai Sekarang</a>
                            @endguest
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="slide">
                    <div class="slide-image" style="background-image: url('https://picsum.photos/1920/800?random=3')"></div>
                    <div class="slide-overlay"></div>
                    <div class="slide-content">
                        <div class="hero-container">
                            <h1>Seminar & Sidang Online</h1>
                            <h2>Pendaftaran Cepat & Praktis</h2>
                            <p>Daftarkan seminar proposal dan sidang skripsi secara online</p>
                            <p>Jadwal terorganisir dengan notifikasi otomatis untuk setiap tahapan penting.</p>
                            @guest
                                <a href="{{ route('login') }}" class="btn btn-primary">Mulai Sekarang</a>
                            @endguest
                        </div>
                    </div>
                </div>

                <!-- Slide 4 -->
                <div class="slide">
                    <div class="slide-image" style="background-image: url('https://picsum.photos/1920/800?random=4')"></div>
                    <div class="slide-overlay"></div>
                    <div class="slide-content">
                        <div class="hero-container">
                            <h1>Penilaian Transparan</h1>
                            <h2>Sistem Penilaian Terintegrasi</h2>
                            <p>Penilaian yang objektif dan transparan</p>
                            <p>Konversi grade otomatis dan akses hasil penilaian secara real-time.</p>
                            @guest
                                <a href="{{ route('login') }}" class="btn btn-primary">Mulai Sekarang</a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slider Navigation -->
            <button class="slider-nav prev" onclick="changeSlide(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="slider-nav next" onclick="changeSlide(1)">
                <i class="fas fa-chevron-right"></i>
            </button>

            <!-- Slider Indicators -->
            <div class="slider-indicators">
                <span class="indicator active" onclick="goToSlide(0)"></span>
                <span class="indicator" onclick="goToSlide(1)"></span>
                <span class="indicator" onclick="goToSlide(2)"></span>
                <span class="indicator" onclick="goToSlide(3)"></span>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="section-container">
            <div class="section-title">
                <h2>Layanan Sistem</h2>
                <p>Berbagai fitur untuk mendukung proses akademik skripsi Anda</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h3>Pendaftaran Online</h3>
                    <p>Daftarkan diri Anda untuk mengikuti seminar proposal dan sidang skripsi secara online dengan mudah dan cepat.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Bimbingan Skripsi</h3>
                    <p>Kelola proses bimbingan skripsi dengan dosen pembimbing Anda secara terorganisir dan terdokumentasi dengan baik.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3>Seminar Proposal</h3>
                    <p>Daftarkan dan kelola jadwal seminar proposal Anda dengan sistem yang terintegrasi dan transparan.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3>Sidang Skripsi</h3>
                    <p>Proses pengajuan dan pelaksanaan sidang skripsi yang terstruktur dengan penilaian yang objektif.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Penilaian Terintegrasi</h3>
                    <p>Sistem penilaian yang transparan dengan konversi grade otomatis untuk memudahkan evaluasi.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3>Dokumentasi Lengkap</h3>
                    <p>Semua dokumen dan berkas terkait skripsi tersimpan dengan aman dan dapat diakses kapan saja.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="process">
        <div class="section-container">
            <div class="section-title">
                <h2>Alur Proses Skripsi</h2>
                <p>Tahapan yang perlu Anda lalui dalam menyelesaikan skripsi</p>
            </div>
            
            <div class="process-steps">
                <div class="process-step">
                    <div class="step-number">1</div>
                    <h3>Pendaftaran</h3>
                    <p>Daftarkan diri Anda ke sistem dan lengkapi data profil mahasiswa</p>
                </div>

                <div class="process-step">
                    <div class="step-number">2</div>
                    <h3>Bimbingan</h3>
                    <p>Lakukan bimbingan dengan dosen pembimbing secara berkala</p>
                </div>

                <div class="process-step">
                    <div class="step-number">3</div>
                    <h3>Seminar Proposal</h3>
                    <p>Daftarkan dan presentasikan proposal skripsi Anda</p>
                </div>

                <div class="process-step">
                    <div class="step-number">4</div>
                    <h3>Sidang Skripsi</h3>
                    <p>Ajukan sidang skripsi setelah penelitian selesai</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq">
        <div class="faq-container">
            <div class="section-title">
                <h2>Tanya Jawab</h2>
                <p>Pertanyaan yang sering diajukan</p>
            </div>

            <div class="faq-item">
                <h3><i class="fas fa-question-circle"></i> Apa itu SIMSKRIPSI?</h3>
                <p>SIMSKRIPSI adalah Sistem Informasi Manajemen Skripsi yang dibuat untuk memudahkan mahasiswa dalam mengelola proses skripsi mulai dari bimbingan, seminar proposal, hingga sidang skripsi.</p>
            </div>

            <div class="faq-item">
                <h3><i class="fas fa-question-circle"></i> Siapa yang dapat menggunakan sistem ini?</h3>
                <p>Sistem ini dapat digunakan oleh mahasiswa Fakultas Psikologi UNS yang sedang menempuh skripsi, dosen pembimbing, dan penguji.</p>
            </div>

            <div class="faq-item">
                <h3><i class="fas fa-question-circle"></i> Bagaimana cara mendaftar?</h3>
                <p>Anda dapat mendaftar dengan mengklik tombol "Daftar" di pojok kanan atas halaman ini, kemudian lengkapi formulir pendaftaran dengan data yang valid.</p>
            </div>

            <div class="faq-item">
                <h3><i class="fas fa-question-circle"></i> Apa saja fitur yang tersedia?</h3>
                <p>Sistem ini menyediakan fitur pendaftaran online, manajemen bimbingan, pendaftaran seminar proposal, pendaftaran sidang skripsi, dan sistem penilaian terintegrasi.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Sistem Informasi Skripsi</h3>
                    <p>Fakultas Psikologi</p>
                    <p>Universitas Sebelas Maret Surakarta</p>
                    <p>Jalan Ir. Sutami Nomor 36A</p>
                    <p>Kentingan, Surakarta 57126</p>
                </div>

                <div class="footer-section">
                    <h3>Hubungi Kami</h3>
                    <p><i class="fas fa-phone"></i> (0271) 646994</p>
                    <p><i class="fas fa-fax"></i> (0271) 646994</p>
                    <p><i class="fas fa-envelope"></i> psikologi@staff.uns.ac.id</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

                <div class="footer-section">
                    <h3>Tautan Cepat</h3>
                    <a href="{{ url('/') }}">Beranda</a>
                    <a href="{{ route('login') }}">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">Daftar</a>
                    @endif
                    <a href="https://psikologi.uns.ac.id" target="_blank">Website Fakultas</a>
                </div>
            </div>

            <div class="footer-bottom">
                <p>Copyright © {{ date('Y') }} Fakultas Psikologi UNS. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Slider JavaScript -->
    <script>
        let currentSlide = 0;
        let slideInterval;

        function showSlide(index) {
            const slides = document.querySelectorAll('.slide');
            const indicators = document.querySelectorAll('.indicator');
            
            // Remove active class from all slides and indicators
            slides.forEach(slide => slide.classList.remove('active'));
            indicators.forEach(indicator => indicator.classList.remove('active'));

            // Wrap around if index is out of bounds
            if (index >= slides.length) {
                currentSlide = 0;
            } else if (index < 0) {
                currentSlide = slides.length - 1;
            } else {
                currentSlide = index;
            }

            // Add active class to current slide and indicator
            slides[currentSlide].classList.add('active');
            indicators[currentSlide].classList.add('active');
        }

        function changeSlide(direction) {
            showSlide(currentSlide + direction);
            resetInterval();
        }

        function goToSlide(index) {
            showSlide(index);
            resetInterval();
        }

        function autoSlide() {
            showSlide(currentSlide + 1);
        }

        function resetInterval() {
            clearInterval(slideInterval);
            slideInterval = setInterval(autoSlide, 5000);
        }

        // Initialize slider when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            showSlide(0);
            slideInterval = setInterval(autoSlide, 5000);

            // Pause on hover
            const sliderContainer = document.querySelector('.slider-container');
            if (sliderContainer) {
                sliderContainer.addEventListener('mouseenter', function() {
                    clearInterval(slideInterval);
                });

                sliderContainer.addEventListener('mouseleave', function() {
                    slideInterval = setInterval(autoSlide, 5000);
                });
            }
        });
    </script>
</body>
</html>
