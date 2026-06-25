@extends('app')
@section('content')

<div class="container-fluid py-4 px-md-5">
    <div class="row">
        <!-- Sidebar Navigation (Column 3) -->
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="text-center py-3 border-bottom mb-3">
                    <div class="user-avatar mx-auto mb-2 fs-3 text-white d-flex align-items-center justify-content-center" style="width: 65px; height: 65px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%;">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                    <h5 class="fw-bold mb-0 text-dark">{{ auth()->user()->name }}</h5>
                    <span class="badge bg-success rounded-pill px-3 mt-1">طالب جامعي</span>
                    <p class="text-muted small mt-2 mb-0">القسم: {{ $student->department }}</p>
                </div>
                <ul class="nav flex-column gap-2 text-end">
                    <li class="nav-item">
                        <a href="{{ route('student.dashboard') }}" class="nav-link active bg-light rounded-3 text-primary p-3 fw-bold">
                            <i class="fa-solid fa-graduation-cap me-2"></i> ملف الطالب الدراسي
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('student.courses.index') }}" class="nav-link text-dark p-3 rounded-3 hover-bg">
                            <i class="fa-solid fa-plus-circle me-2"></i> تسجيل المقررات الجديدة
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('student.grades.index') }}" class="nav-link text-dark p-3 rounded-3 hover-bg">
                            <i class="fa-solid fa-chart-line me-2"></i> سجل كشف الدرجات
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('student.notifications.index') }}" class="nav-link text-dark p-3 rounded-3 hover-bg">
                            <i class="fa-solid fa-bell me-2"></i> الإشعارات والتنبيهات
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content Area (Column 9) -->
        <div class="col-md-9">
            <!-- Header section -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <div>
                    <h2 class="fw-black mb-0 text-dark">البوابة الإلكترونية للطالب</h2>
                    <p class="text-muted">مرحباً بك {{ auth()->user()->name }}، يمكنك متابعة مسيرتك التعليمية وإنجاز معاملاتك الجامعية.</p>
                </div>
                <div class="date-badge bg-white shadow-sm px-4 py-2 rounded-pill text-muted small">
                    <i class="fa-solid fa-id-card me-2"></i> الرقم الجامعي: {{ $student->student_number }}
                </div>
            </div>

            <!-- Stats / Status overview -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center">
                        <i class="fa-solid fa-book text-primary fs-2 mb-2"></i>
                        <h3 class="fw-bold mb-0">{{ $enrollments->count() }}</h3>
                        <span class="text-muted small">المقررات المسجلة</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center">
                        <i class="fa-solid fa-star text-success fs-2 mb-2"></i>
                        @php
                            $avg = $grades->count() ? round($grades->avg('score'), 2) : 0;
                        @endphp
                        <h3 class="fw-bold mb-0">{{ $avg }} %</h3>
                        <span class="text-muted small">متوسط الدرجات العام</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center">
                        <i class="fa-solid fa-credit-card text-warning fs-2 mb-2"></i>
                        @php
                            $unpaidCount = $enrollments->where('payment_status', 'unpaid')->count();
                        @endphp
                        <h3 class="fw-bold mb-0">{{ $unpaidCount }}</h3>
                        <span class="text-muted small">مقررات بانتظار السداد</span>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Registered Courses & Payment Alerts -->
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                            <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-book-open text-primary me-2"></i>المقررات المسجلة هذا الفصل</h5>
                            <a href="{{ route('student.courses.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">تسجيل مقرر</a>
                        </div>
                        <div class="list-group list-group-flush">
                            @forelse($enrollments as $en)
                                <div class="list-group-item px-0 py-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <h6 class="fw-bold mb-0">{{ $en->course->title }}</h6>
                                            <small class="text-muted">{{ $en->course->course_code }} | المدرس: {{ $en->course->teacher->user->name ?? 'غير مسند' }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge rounded-pill px-3 py-1.5 small 
                                                {{ $en->status === 'approved' ? 'bg-success' : ($en->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                                @if($en->status === 'approved') مقبول ومقيد @elseif($en->status === 'pending') معلق @else مرفوض @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-light">
                                        <span class="small text-muted">
                                            حالة الرسوم: 
                                            <strong class="{{ $en->payment_status === 'paid' ? 'text-success' : 'text-danger' }}">
                                                {{ $en->payment_status === 'paid' ? 'مسددة' : 'غير مسددة' }}
                                            </strong>
                                        </span>
                                        @if($en->payment_status === 'unpaid')
                                            <a href="{{ route('student.payment.form', $en->id) }}" class="btn btn-warning btn-xs rounded-pill px-3 fw-bold small text-dark">
                                                <i class="fa-solid fa-wallet me-1"></i> سداد الرسوم الأكاديمية
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted small py-4">
                                    <i class="fa-solid fa-circle-exclamation fs-3 mb-2"></i><br>
                                    لم تقم بتسجيل أي مقررات دراسية بعد.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Payments History -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                        <h5 class="fw-bold mb-3 text-dark border-bottom pb-2"><i class="fa-solid fa-wallet text-success me-2"></i>عمليات الدفع الإلكتروني الأخيرة</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr class="table-light">
                                        <th class="small">رقم المعاملة</th>
                                        <th class="small">المبلغ</th>
                                        <th class="small">التاريخ</th>
                                        <th class="small">الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $p)
                                        <tr>
                                            <td class="small fw-bold text-dark">{{ $p->transaction_id }}</td>
                                            <td class="small fw-bold text-success">{{ number_format($p->amount, 2) }} ر.س</td>
                                            <td class="small text-muted">{{ $p->created_at->format('Y-m-d H:i') }}</td>
                                            <td><span class="badge bg-success rounded-pill px-2.5 small">ناجحة</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted small py-3">لا توجد معاملات سداد في سجل الحساب.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Notifications & Grades Sidebar -->
                <div class="col-md-5">
                    <!-- Notifications -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                            <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-bell text-warning me-2"></i>التنبيهات والإشعارات</h5>
                            <a href="{{ route('student.notifications.index') }}" class="btn btn-sm btn-outline-warning rounded-pill px-3">عرض الكل</a>
                        </div>
                        <div class="list-group list-group-flush">
                            @forelse($notifications as $n)
                                <div class="list-group-item px-0 py-2.5 border-0 {{ !$n->is_read ? 'bg-light p-2 rounded-3 mb-2' : '' }}">
                                    <div class="d-flex justify-content-between">
                                        <strong class="small text-dark">{{ $n->title }}</strong>
                                        <span class="small text-muted" style="font-size:0.75rem;">{{ $n->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="mb-0 text-muted small mt-1">{{ $n->message }}</p>
                                    @if(!$n->is_read)
                                        <button onclick="readNotification(this, {{ $n->id }})" class="btn btn-link btn-xs p-0 text-decoration-none mt-1 small" style="font-size:0.75rem; color: #667eea;">
                                            <i class="fa-solid fa-eye me-1"></i> تعليم كمقروء
                                        </button>
                                    @endif
                                </div>
                            @empty
                                <p class="text-center text-muted small py-3">لا توجد إشعارات جديدة حالياً.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Grades Summary -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                            <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-chart-line text-success me-2"></i>آخر نتائج الاختبارات</h5>
                            <a href="{{ route('student.grades.index') }}" class="btn btn-sm btn-outline-success rounded-pill px-3">كشف الدرجات</a>
                        </div>
                        <div class="list-group list-group-flush">
                            @forelse($grades as $grade)
                                <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold mb-0 small">{{ $grade->exam->title }}</h6>
                                        <small class="text-muted">{{ $grade->exam->course->course_code }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="fw-bold text-success">{{ $grade->score }}</span>
                                        <span class="text-muted small">/ {{ $grade->exam->max_points }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-muted small py-3">لم ترصد أي درجات اختبارات لك بعد.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function readNotification(btn, id) {
        fetch(`/student/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                btn.closest('.list-group-item').classList.remove('bg-light', 'p-2', 'rounded-3', 'mb-2');
                btn.remove();
            }
        });
    }
</script>

@endsection
