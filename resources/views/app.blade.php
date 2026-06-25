<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>البوابة الإلكترونية | نظام إدارة الخدمات الجامعية</title>
    <!-- Bootstrap 5 CSS (RTL support) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <!-- FontAwesome for Premium Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Tajawal:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- Custom CSS styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body {
            font-family: 'Tajawal', 'Outfit', sans-serif;
            background: #f8f9fa;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .navbar-custom .navbar-brand, 
        .navbar-custom .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
        }
        .navbar-custom .nav-link:hover {
            color: #fff !important;
        }
        .footer-custom {
            background: #2d3748;
            color: #cbd5e0;
            padding: 3rem 0;
            margin-top: 5rem;
        }
        .footer-custom a {
            color: #fff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-3">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="/">
                    <i class="fa-solid fa-graduation-cap me-2 fs-3"></i>
                    <span>منصة الخدمات الأكاديمية</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-2">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="/{{ auth()->user()->role }}/dashboard">
                                    <i class="fa-solid fa-gauge me-1"></i> لوحة التحكم
                                </a>
                            </li>
                            <li class="nav-item">
                                <span class="nav-link text-white-50">
                                    مرحباً، {{ auth()->user()->name }} ({{ __(auth()->user()->role) }})
                                </span>
                            </li>
                            <li class="nav-item">
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-outline-light rounded-pill btn-sm px-3 mt-1" type="submit">
                                        <i class="fa-solid fa-right-from-bracket"></i> خروج
                                    </button>
                                </form>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="fa-solid fa-lock me-1"></i> دخول
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-white rounded-pill px-4" href="{{ route('register') }}" style="background: white; color: #667eea; font-weight: 600;">
                                    <i class="fa-solid fa-user-plus me-1"></i> حساب جديد
                                </a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    @if(session('success'))
        <div class="container mt-4">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mt-4">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="footer-custom text-center text-md-start">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h5>منصة الخدمات الجامعية الإلكترونية</h5>
                    <p class="text-muted">نظام متكامل لتسجيل المقررات، إدارة الامتحانات والدرجات، والمدفوعات الإلكترونية.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; 2026 جميع الحقوق محفوظة لجامعة المستقبل</p>
                    <p class="text-muted small">تم التطوير باستخدام Laravel 12 + MySQL</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
