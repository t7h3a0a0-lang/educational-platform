@extends('app')
@section('content')

    <main>
        <div class="container">
            <h1 class="page-title">📰 جميع المنشورات</h1>
            <div class="posts-grid">
                <!-- منشور وهمي 1 -->
                <article class="post-card">
                    <h2><a href="post-details.html">مقدمة في Laravel - لماذا تختاره لتطوير تطبيقات الويب؟</a></h2>
                    <div class="post-meta">
                        <span>📅 15 يناير 2026</span>
                        <span>✍️ بواسطة: أحمد محمد</span>
                    </div>
                    <p>Laravel هو إطار عمل PHP مفتوح المصدر يتبع نمط MVC، ويتميز ببساطته وقوته. في هذا المقال نتعرف على أهم مميزاته ولماذا يفضله المطورون...</p>
                    <a href="post-details.html" class="read-more">اقرأ المزيد ←</a>
                </article>

                <!-- منشور وهمي 2 -->
                <article class="post-card">
                    <h2><a href="post-details.html">فهم نظام Routing في Laravel خطوة بخطوة</a></h2>
                    <div class="post-meta">
                        <span>📅 10 يناير 2026</span>
                        <span>✍️ بواسطة: سارة خالد</span>
                    </div>
                    <p>نظام التوجيه (Routing) هو أحد أهم مكونات Laravel، حيث يسمح لك بتعريف المسارات والتعامل مع طلبات HTTP بطريقة أنيقة وسهلة...</p>
                    <a href="post-details.html" class="read-more">اقرأ المزيد ←</a>
                </article>

                <!-- منشور وهمي 3 -->
                <article class="post-card">
                    <h2><a href="post-details.html">التعامل مع قواعد البيانات باستخدام Eloquent ORM</a></h2>
                    <div class="post-meta">
                        <span>📅 5 يناير 2026</span>
                        <span>✍️ بواسطة: محمد علي</span>
                    </div>
                    <p>Eloquent ORM يوفر طريقة سهلة ومبسطة للتعامل مع قاعدة البيانات، مع دعم كامل للعلاقات بين الجداول والعمليات المختلفة...</p>
                    <a href="post-details.html" class="read-more">اقرأ المزيد ←</a>
                </article>

                <!-- منشور وهمي 4 -->
                <article class="post-card">
                    <h2><a href="post-details.html">إنشاء نظام مصادقة كامل في Laravel باستخدام Jetstream</a></h2>
                    <div class="post-meta">
                        <span>📅 1 يناير 2026</span>
                        <span>✍️ بواسطة: نورة أحمد</span>
                    </div>
                    <p>Jetstream يوفر نظام مصادقة متكامل يدعم تسجيل الدخول، التسجيل، التحقق من البريد الإلكتروني، المصادقة الثنائية، وإدارة الفرق...</p>
                    <a href="post-details.html" class="read-more">اقرأ المزيد ←</a>
                </article>

                <!-- منشور وهمي 5 -->
                <article class="post-card">
                    <h2><a href="post-details.html">إدارة الملفات والتخزين في Laravel باستخدام Filesystem</a></h2>
                    <div class="post-meta">
                        <span>📅 28 ديسمبر 2025</span>
                        <span>✍️ بواسطة: عمر خالد</span>
                    </div>
                    <p>نظام الملفات في Laravel يوفر واجهة بسيطة للتعامل مع التخزين المحلي أو السحابي مثل Amazon S3 و Dropbox...</p>
                    <a href="post-details.html" class="read-more">اقرأ المزيد ←</a>
                </article>

                <!-- منشور وهمي 6 -->
                <article class="post-card">
                    <h2><a href="post-details.html">اختبارات الوحدة والتكامل في Laravel باستخدام PHPUnit</a></h2>
                    <div class="post-meta">
                        <span>📅 20 ديسمبر 2025</span>
                        <span>✍️ بواسطة: ليلى أحمد</span>
                    </div>
                    <p>Laravel يأتي مع دعم مدمج لاختبارات PHPUnit، مما يسهل عليك كتابة اختبارات الوحدة والتكامل لتطبيقاتك...</p>
                    <a href="post-details.html" class="read-more">اقرأ المزيد ←</a>
                </article>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <a href="#" class="disabled">السابق</a>
                <a href="#" class="active">1</a>
                <a href="#">2</a>
                <a href="#">3</a>
                <a href="#">4</a>
                <a href="#">التالي</a>
            </div>
        </div>
    </main>

    <footer>
</body>
</html>
@endsection