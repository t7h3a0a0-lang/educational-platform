@extends('app')
@section('content')

    <main>
        <div class="container">
            <article class="post-detail">
                <h1>مقدمة في Laravel - لماذا تختاره لتطوير تطبيقات الويب؟</h1>
                <div class="post-meta">
                    <span>📅 15 يناير 2026</span>
                    <span>✍️ بواسطة: أحمد محمد</span>
                    <span>🏷️ التصنيف: Laravel, PHP, Backend</span>
                </div>
                <div class="post-content">
                    <p>Laravel هو إطار عمل PHP مفتوح المصدر يتبع نمط MVC (Model-View-Controller). تم إنشاؤه بواسطة Taylor Otwell في عام 2011 بهدف توفير بديل متقدم لإطار CodeIgniter.</p>
                    
                    <h3>لماذا Laravel؟</h3>
                    <p>يتميز Laravel بالعديد من المميزات التي تجعله الخيار الأول لمطوري PHP حول العالم:</p>
                    <ul>
                        <li>بناء جملة أنيق وسهل القراءة</li>
                        <li>نظام توجيه (Routing) قوي ومرن</li>
                        <li>Eloquent ORM للتعامل مع قواعد البيانات بطريقة مبسطة</li>
                        <li>نظام قوالب Blade القوي</li>
                        <li>مجتمع كبير ونشط</li>
                        <li>أدوات مدمجة مثل المصادقة والتحقق من الصلاحيات</li>
                    </ul>

                    <h3>مشاريع حقيقية باستخدام Laravel</h3>
                    <p>يُستخدم Laravel في بناء العديد من المواقع والتطبيقات الكبيرة مثل:</p>
                    <ul>
                        <li>منصات التجارة الإلكترونية</li>
                        <li>أنظمة إدارة المحتوى (CMS)</li>
                        <li>لوحات التحكم الإدارية</li>
                        <li>واجهات برمجة التطبيقات (APIs)</li>
                    </ul>

                    <h3>بداية سريعة مع Laravel</h3>
                    <p>لتثبيت Laravel، تحتاج أولاً إلى تثبيت Composer (مدير حزم PHP). ثم يمكنك إنشاء مشروع جديد باستخدام الأمر التالي:</p>
                    <pre style="background: #f4f4f4; padding: 15px; border-radius: 8px; overflow-x: auto; direction: ltr; text-align: left;">
composer create-project laravel/laravel blog
                    </pre>

                    <p>بعد التثبيت، يمكنك تشغيل الخادم المحلي باستخدام الأمر:</p>
                    <pre style="background: #f4f4f4; padding: 15px; border-radius: 8px; overflow-x: auto; direction: ltr; text-align: left;">
php artisan serve
                    </pre>

                    <p>في دورتنا هذه، سنقوم ببناء نظام مدونة متكامل باستخدام Laravel، وستتعلم خطوة بخطوة كيفية بناء تطبيقات ويب احترافية.</p>
                    
                    <p>تابع معنا الدروس القادمة لتتعرف على المزيد من مفاهيم Laravel المتقدمة مثل Middleware، Service Providers، و Event System.</p>
                </div>
            </article>
        </div>
    </main>

    
</body>
</html>
@endsection