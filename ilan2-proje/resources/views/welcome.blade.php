<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CargoOptima</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        /* Custom Styles */
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #1a3c6d;
            --accent-color: #4dabf7;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            position: relative;
        }
        
        .navbar {
            transition: all 0.3s ease;
        }
        
        .navbar.scrolled {
            background-color: var(--secondary-color) !important;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        }
        
        .hero-section {
            height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://source.unsplash.com/1600x900/?ship,cargo') no-repeat center center;
            background-size: cover;
            color: white;
            display: flex;
            align-items: center;
            position: relative;
        }
        
        .section {
            padding: 100px 0;
        }
        
        .section-title {
            position: relative;
            margin-bottom: 50px;
            font-weight: 700;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--primary-color);
        }
        
        .feature-card {
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: var(--primary-color);
        }
        
        .service-card {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .service-img {
            transition: all 0.5s ease;
        }
        
        .service-card:hover .service-img {
            transform: scale(1.1);
        }
        
        .service-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            color: white;
        }
        
        .cta-section {
            background: linear-gradient(rgba(13, 110, 253, 0.8), rgba(13, 110, 253, 0.8)), url('https://source.unsplash.com/1600x900/?ocean,logistics') no-repeat center center;
            background-size: cover;
            color: white;
        }
        
        .testimonial-card {
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        .testimonial-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }
        
        .contact-info {
            margin-bottom: 30px;
        }
        
        .contact-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .footer {
            background-color: var(--secondary-color);
            color: white;
            padding: 50px 0 20px;
        }
        
        .footer-title {
            font-weight: 700;
            margin-bottom: 30px;
            position: relative;
        }
        
        .footer-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 30px;
            height: 2px;
            background-color: var(--accent-color);
        }
        
        .footer-links li {
            margin-bottom: 15px;
        }
        
        .footer-links a {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer-links a:hover {
            color: var(--accent-color);
            padding-left: 5px;
        }
        
        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            text-align: center;
            line-height: 40px;
            border-radius: 50%;
            color: white;
            margin-right: 10px;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background-color: var(--accent-color);
            transform: translateY(-3px);
        }
        
        .back-to-top {
            position: fixed;
            right: 30px;
            bottom: 30px;
            width: 50px;
            height: 50px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 999;
        }
        
        .back-to-top.active {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            background-color: var(--secondary-color);
            color: white;
        }
        
        /* CSS Animations */
        .animate-up {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s ease;
        }
        
        .animate-up.active {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .hero-section {
                height: 80vh;
            }
            .section {
                padding: 70px 0;
            }
        }
        
        @media (max-width: 768px) {
            .hero-section {
                height: 60vh;
            }
            .section {
                padding: 50px 0;
            }
            .section-title {
                margin-bottom: 30px;
            }
            .feature-card, .testimonial-card {
                margin-bottom: 30px;
            }
        }
    </style>
</head>
<body data-bs-spy="scroll" data-bs-target="#navbarMain" data-bs-offset="100">
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center overflow-hidden" href="#home">
                <img src="{{ asset('logo2.png') }}" alt="Logo" height="80" class="d-inline-block align-text-top me-2">
                CargoOptima
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Ana Sayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Hakkımızda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Özellikler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Hizmetler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Müşteri Yorumları</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">İletişim</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="nav-link btn btn-primary px-4 text-white" href="{{ route('login') }}">Giriş Yap</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-3 fw-bold mb-4">Deniz Taşımacılığında Yeni Nesil Çözüm</h1>
                    <p class="lead mb-5">Küçük ve orta büyüklükteki gemilerde kargo taşımacılığı için gemiciler ve yük verenler arasında kolay eşleştirme platformu.</p>
                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5 py-3">Hemen Başla</a>
                        <a href="#features" class="btn btn-outline-light btn-lg px-5 py-3">Daha Fazla Bilgi</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 animate-up">
                    <h2 class="section-title">Hakkımızda</h2>
                    <p class="lead mb-4">Deniz taşımacılığı sektöründe yenilikçi bir platform sunuyoruz.</p>
                    <p class="mb-4">Platformumuz, gemiciler ile yük sahiplerini bir araya getirerek, boş kapasitelerin daha verimli kullanılmasını sağlıyor. Böylece hem gemiciler hem de yük sahipleri için kazançlı bir ortaklık oluşturuyoruz.</p>
                    <p class="mb-4">Amacımız, deniz taşımacılığını daha erişilebilir, daha ekonomik ve daha sürdürülebilir hale getirmektir. Modern teknolojiler kullanarak, geleneksel taşımacılık süreçlerini optimize ediyoruz.</p>
                    <a href="#contact" class="btn btn-primary">Bizimle İletişime Geçin</a>
                </div>
                <div class="col-lg-6 animate-up" style="animation-delay: 0.3s;">
                    <div class="ratio ratio-16x9 overflow-hidden rounded shadow">
                        <img src="https://source.unsplash.com/random/800x600/?shipping,cargo" class="img-fluid" alt="About Us">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title mx-auto">Platformumuzun Özellikleri</h2>
                    <p class="lead">Deniz taşımacılığında yeni bir dönem başlatıyoruz</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 mb-4 animate-up">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <h4>Kolay Rota Yönetimi</h4>
                        <p>Gemiciler için başlangıç, ara durak ve varış noktalarını kolayca belirleyebilme imkanı.</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4 animate-up" style="animation-delay: 0.3s;">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h4>Yük Takibi</h4>
                        <p>Yük sahipleri için kargolarının gerçek zamanlı takibi ve güvenli teslimat süreçleri.</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4 animate-up" style="animation-delay: 0.6s;">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <h4>Akıllı Eşleştirme</h4>
                        <p>Gelişmiş algoritma ile en uygun gemici ve yük eşleştirmelerini otomatik olarak yapabilme.</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4 animate-up" style="animation-delay: 0.9s;">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4>Güvenli Taşıma</h4>
                        <p>Tüm taşıma süreçlerinde maksimum güvenlik ve sigorta imkanları.</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4 animate-up" style="animation-delay: 1.2s;">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <h4>Ekonomik Fiyatlandırma</h4>
                        <p>Hem gemiciler hem de yük sahipleri için ekonomik ve şeffaf fiyatlandırma modeli.</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4 animate-up" style="animation-delay: 1.5s;">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="bi bi-star"></i>
                        </div>
                        <h4>Derecelendirme Sistemi</h4>
                        <p>Kullanıcı deneyimini artırmak için puanlama ve yorumlama sistemi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-9 animate-up">
                    <h3 class="mb-2">Hazır mısınız?</h3>
                    <p class="mb-0">Hemen üye olun ve deniz taşımacılığının avantajlarından yararlanmaya başlayın.</p>
                </div>
                <div class="col-lg-3 text-lg-end mt-4 mt-lg-0 animate-up" style="animation-delay: 0.3s;">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg">Şimdi Üye Ol</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="section bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title mx-auto">Hizmetlerimiz</h2>
                    <p class="lead">Deniz taşımacılığında sunduğumuz çözümler</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 animate-up">
                    <div class="service-card">
                        <img src="https://source.unsplash.com/random/600x400/?cargo,ship" class="img-fluid service-img" alt="Service 1">
                        <div class="service-content">
                            <h4>Gemici Rota Yönetimi</h4>
                            <p>Gemiciler için özelleştirilmiş rota yönetimi ve kargo eşleştirme hizmetleri.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 animate-up" style="animation-delay: 0.3s;">
                    <div class="service-card">
                        <img src="https://source.unsplash.com/random/600x400/?shipping,boat" class="img-fluid service-img" alt="Service 2">
                        <div class="service-content">
                            <h4>Yük Taşıma</h4>
                            <p>Yük sahipleri için güvenli ve ekonomik taşıma çözümleri.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 animate-up" style="animation-delay: 0.6s;">
                    <div class="service-card">
                        <img src="https://source.unsplash.com/random/600x400/?logistics,marine" class="img-fluid service-img" alt="Service 3">
                        <div class="service-content">
                            <h4>Lojistik Danışmanlık</h4>
                            <p>Deniz taşımacılığında optimizasyon ve verimlilik için danışmanlık hizmetleri.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="section">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title mx-auto">Müşteri Yorumları</h2>
                    <p class="lead">Kullanıcılarımızın deneyimleri</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 animate-up">
                    <div class="testimonial-card text-center">
                        <img src="https://source.unsplash.com/random/100x100/?man,portrait" class="testimonial-img" alt="Testimonial 1">
                        <h5>Mehmet Yılmaz</h5>
                        <p class="text-muted">Gemici</p>
                        <p>"Bu platform sayesinde boş kapasitemi değerlendirerek ek gelir elde ediyorum. Kullanımı çok kolay ve müşteri hizmetleri her zaman yardımcı oluyor."</p>
                        <div class="text-warning">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 animate-up" style="animation-delay: 0.3s;">
                    <div class="testimonial-card text-center">
                        <img src="https://source.unsplash.com/random/100x100/?woman,portrait" class="testimonial-img" alt="Testimonial 2">
                        <h5>Ayşe Demir</h5>
                        <p class="text-muted">Yük Sahibi</p>
                        <p>"Kargolarımı taşıtmak için artık saatlerce arama yapmama gerek kalmıyor. Hızlı eşleştirme ve uygun fiyatlar için teşekkürler."</p>
                        <div class="text-warning">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 animate-up" style="animation-delay: 0.6s;">
                    <div class="testimonial-card text-center">
                        <img src="https://source.unsplash.com/random/100x100/?man,face" class="testimonial-img" alt="Testimonial 3">
                        <h5>Ali Kaya</h5>
                        <p class="text-muted">Lojistik Firma Sahibi</p>
                        <p>"Şirketimizin deniz taşımacılığı operasyonlarını bu platform üzerinden yönetiyoruz. Verimlilik ve karlılık konusunda büyük fark yarattı."</p>
                        <div class="text-warning">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title mx-auto">İletişim</h2>
                    <p class="lead">Bize ulaşın, sorularınızı yanıtlayalım</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 animate-up">
                    <div class="contact-info">
                        <div class="contact-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <h5>Adres</h5>
                        <p>Liman Caddesi No: 123, Kadıköy/İstanbul</p>
                    </div>
                    <div class="contact-info">
                        <div class="contact-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <h5>E-posta</h5>
                        <p>info@deniztasima.com</p>
                    </div>
                    <div class="contact-info">
                        <div class="contact-icon">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <h5>Telefon</h5>
                        <p>+90 (212) 123 45 67</p>
                    </div>
                    <div class="social-links mt-4">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-7 animate-up" style="animation-delay: 0.3s;">
                    <div class="card border-0 shadow">
                        <div class="card-body p-5">
                            <form>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Adınız</label>
                                        <input type="text" class="form-control" id="name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">E-posta</label>
                                        <input type="email" class="form-control" id="email" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="subject" class="form-label">Konu</label>
                                        <input type="text" class="form-control" id="subject" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="message" class="form-label">Mesaj</label>
                                        <textarea class="form-control" id="message" rows="5" required></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary w-100 py-3">Mesaj Gönder</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <h4 class="footer-title">CargoOptima</h4>
                    <p>Küçük ve orta büyüklükteki gemilerde kargo taşımacılığı için eşleştirme platformu.</p>
                    <div class="social-links mt-4">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-5 mb-md-0">
                    <h4 class="footer-title">Hızlı Linkler</h4>
                    <ul class="list-unstyled footer-links">
                        <li><a href="#home">Ana Sayfa</a></li>
                        <li><a href="#about">Hakkımızda</a></li>
                        <li><a href="#features">Özellikler</a></li>
                        <li><a href="#services">Hizmetler</a></li>
                        <li><a href="#contact">İletişim</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-5 mb-md-0">
                    <h4 class="footer-title">Hizmetler</h4>
                    <ul class="list-unstyled footer-links">
                        <li><a href="#">Gemi Rota Planlaması</a></li>
                        <li><a href="#">Yük Taşıma</a></li>
                        <li><a href="#">Lojistik Danışmanlık</a></li>
                        <li><a href="#">Kapasite Optimizasyonu</a></li>
                        <li><a href="#">Taşıma Sigortası</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h4 class="footer-title">İletişim</h4>
                    <p><i class="bi bi-geo-alt me-2"></i> Liman Caddesi No: 123, Kadıköy/İstanbul</p>
                    <p><i class="bi bi-envelope me-2"></i> info@deniztasima.com</p>
                    <p><i class="bi bi-telephone me-2"></i> +90 (212) 123 45 67</p>
                    <div class="mt-4">
                        <form class="row
                        <div class="mt-4">
                        <form class="row g-2">
                            <div class="col-8">
                                <input type="email" class="form-control" placeholder="E-posta adresiniz" required>
                            </div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary w-100">Abone Ol</button>
                            </div>
                        </form>
                        <small class="text-muted mt-2 d-block">Güncellemelerden haberdar olmak için abone olun.</small>
                    </div>
                </div>
            </div>
            <hr class="my-5 border-light">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-md-0">&copy; {{ date('Y') }} CargoOptima. Tüm hakları saklıdır.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">
                        <a href="#" class="text-white text-decoration-none">Gizlilik Politikası</a> |
                        <a href="#" class="text-white text-decoration-none">Kullanım Şartları</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top" id="backToTop">
        <i class="bi bi-arrow-up"></i>
    </a>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Navbar scroll effect
            const navbar = document.getElementById('mainNav');
            
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });
            
            // Animate on scroll
            const animateUpElements = document.querySelectorAll('.animate-up');
            
            function checkPosition() {
                for (let i = 0; i < animateUpElements.length; i++) {
                    const element = animateUpElements[i];
                    const positionFromTop = element.getBoundingClientRect().top;
                    
                    if (positionFromTop - window.innerHeight <= -100) {
                        element.classList.add('active');
                    }
                }
            }
            
            // Initial check
            checkPosition();
            
            // Check on scroll
            window.addEventListener('scroll', checkPosition);
            
            // Back to top button
            const backToTopButton = document.getElementById('backToTop');
            
            window.addEventListener('scroll', function() {
                if (window.scrollY > 500) {
                    backToTopButton.classList.add('active');
                } else {
                    backToTopButton.classList.remove('active');
                }
            });
            
            backToTopButton.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
            
            // Smooth scroll for navigation links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    
                    const targetId = this.getAttribute('href');
                    
                    if (targetId === '#') return;
                    
                    const targetElement = document.querySelector(targetId);
                    
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth'
                        });
                        
                        // Update active nav item
                        document.querySelectorAll('.nav-link').forEach(navLink => {
                            navLink.classList.remove('active');
                        });
                        
                        this.classList.add('active');
                        
                        // Close navbar collapse on mobile
                        const navbarCollapse = document.getElementById('navbarMain');
                        if (navbarCollapse.classList.contains('show')) {
                            const bsCollapse = new bootstrap.Collapse(navbarCollapse);
                            bsCollapse.hide();
                        }
                    }
                });
            });
        });
    </script>
<style>
.hero-section {
    background: url('{{ asset('startpage.jpg') }}') no-repeat center center;
    background-size: cover;
    background-attachment: fixed;
    background-position: center;
    padding: 120px 0; /* İçerikleri biraz aşağı çeker */
    color: white; /* Yazılar beyaz olsun */
    text-shadow: 0 2px 4px rgba(0,0,0,0.5); /* Yazılara hafif gölge verir okunurluğu artırır */
}
</style>
</body>


</html>