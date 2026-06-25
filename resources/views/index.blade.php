@extends('app')
@section('content')

    <main>
        <section class="hero">
            <div class="container">
                <h1>مرحباً بك في مدونة لارافيل</h1>
                <p>منصة تعليمية متكاملة لتعلم Laravel من الصفر إلى الاحتراف</p>
                <div class="hero-buttons">
                    <a href="register.html" class="btn btn-primary">📝 إنشاء حساب جديد</a>
                    <a href="login.html" class="btn btn-secondary">🔐 تسجيل الدخول</a>
                </div>
            </div>
        </section>

        <section class="features">
            <div class="container">
                <h2>ماذا تقدم لك المنصة؟</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">📚</div>
                        <h3>دروس متكاملة</h3>
                        <p>تعلم Laravel خطوة بخطوة مع أمثلة عملية ومشاريع حقيقية</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">💡</div>
                        <h3>مشاريع تطبيقية</h3>
                        <p>قم ببناء نظام مدونة متكامل وتعلم أساسيات التطوير</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">👥</div>
                        <h3>مجتمع تفاعلي</h3>
                        <p>تواصل مع المطورين الآخرين وشارك تجاربك</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    
</body>
</html>
@endsection