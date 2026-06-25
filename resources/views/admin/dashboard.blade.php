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
                    <span class="badge bg-danger rounded-pill px-3 mt-1">مسؤول النظام</span>
                </div>
                <ul class="nav flex-column gap-2 text-end">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link active bg-light rounded-3 text-primary p-3 fw-bold">
                            <i class="fa-solid fa-chart-line me-2"></i> لوحة المعلومات العامة
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link text-dark p-3 rounded-3 hover-bg">
                            <i class="fa-solid fa-users me-2"></i> إدارة المستخدمين
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.teachers.index') }}" class="nav-link text-dark p-3 rounded-3 hover-bg">
                            <i class="fa-solid fa-chalkboard-user me-2"></i> إدارة المدرسين
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.students.index') }}" class="nav-link text-dark p-3 rounded-3 hover-bg">
                            <i class="fa-solid fa-user-graduate me-2"></i> إدارة الطلاب
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.courses.index') }}" class="nav-link text-dark p-3 rounded-3 hover-bg">
                            <i class="fa-solid fa-book-open me-2"></i> إدارة المقررات والتسجيل
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.payments.index') }}" class="nav-link text-dark p-3 rounded-3 hover-bg">
                            <i class="fa-solid fa-wallet me-2"></i> المدفوعات والرسوم
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.reports.index') }}" class="nav-link text-dark p-3 rounded-3 hover-bg">
                            <i class="fa-solid fa-file-invoice-dollar me-2"></i> التقارير والتحليلات
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
                    <h2 class="fw-black mb-0 text-dark">لوحة المعلومات والإحصائيات</h2>
                    <p class="text-muted">مرحباً بك في وحدة التحكم الإدارية بالبوابة الإلكترونية.</p>
                </div>
                <div class="date-badge bg-white shadow-sm px-4 py-2 rounded-pill text-muted small">
                    <i class="fa-regular fa-calendar-days me-2"></i> {{ date('Y-m-d') }}
                </div>
            </div>

            <!-- Stats Metric Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
                        <i class="fa-solid fa-users text-primary fs-2 mb-2"></i>
                        <h3 class="fw-bold mb-0">{{ $usersCount }}</h3>
                        <span class="text-muted small">المستخدمين</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
                        <i class="fa-solid fa-user-graduate text-success fs-2 mb-2"></i>
                        <h3 class="fw-bold mb-0">{{ $studentsCount }}</h3>
                        <span class="text-muted small">الطلاب المقيدين</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
                        <i class="fa-solid fa-chalkboard-user text-warning fs-2 mb-2"></i>
                        <h3 class="fw-bold mb-0">{{ $teachersCount }}</h3>
                        <span class="text-muted small">أعضاء هيئة التدريس</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
                        <i class="fa-solid fa-wallet text-info fs-2 mb-2"></i>
                        <h3 class="fw-bold mb-0">{{ number_format($totalPayments, 2) }} ر.س</h3>
                        <span class="text-muted small">إجمالي التحصيلات</span>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row g-4 mb-4">
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                        <h5 class="fw-bold mb-3"><i class="fa-solid fa-chart-area text-primary me-2"></i>معدلات تسجيل المقررات شهرياً</h5>
                        <div style="position: relative; height:280px;">
                            <canvas id="enrollmentChartCanvas"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                        <h5 class="fw-bold mb-3"><i class="fa-solid fa-chart-pie text-success me-2"></i>توزيع وسائل الدفع</h5>
                        <div style="position: relative; height:280px;">
                            <canvas id="paymentMethodChartCanvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings & Recent Activity -->
            <div class="row g-4">
                <!-- System Settings Manager -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                            <i class="fa-solid fa-sliders text-danger me-2"></i>إعدادات النظام الجامعي
                        </h5>
                        <form action="{{ route('admin.settings.save') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold">اسم المنصة / البوابة</label>
                                <input type="text" name="settings[site_name]" class="form-control" 
                                    value="{{ $settings->where('key', 'site_name')->first()->value ?? 'البوابة الأكاديمية' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">حالة التسجيل الفصلي</label>
                                <select name="settings[registration_status]" class="form-select">
                                    <option value="open" {{ ($settings->where('key', 'registration_status')->first()->value ?? 'open') === 'open' ? 'selected' : '' }}>مفتوح للتسجيل</option>
                                    <option value="closed" {{ ($settings->where('key', 'registration_status')->first()->value ?? '') === 'closed' ? 'selected' : '' }}>مغلق مؤقتاً</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">الفصل الدراسي الحالي</label>
                                <input type="text" name="settings[semester]" class="form-control" 
                                    value="{{ $settings->where('key', 'semester')->first()->value ?? 'خريف 2026' }}">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 mt-2">
                                <i class="fa-solid fa-floppy-disk me-1"></i> حفظ إعدادات النظام
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Recent Registered Users -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                            <i class="fa-solid fa-clock-rotate-left text-warning me-2"></i>أحدث الحسابات المسجلة
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr class="table-light">
                                        <th class="small">الاسم</th>
                                        <th class="small">البريد</th>
                                        <th class="small">الدور</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentUsers as $ru)
                                        <tr>
                                            <td class="small fw-bold">{{ $ru->name }}</td>
                                            <td class="small text-muted">{{ $ru->email }}</td>
                                            <td>
                                                <span class="badge rounded-pill small 
                                                    {{ $ru->role === 'admin' ? 'bg-danger' : ($ru->role === 'teacher' ? 'bg-warning' : 'bg-success') }}">
                                                    {{ __($ru->role) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted small py-3">لا توجد بيانات متاحة حالياً.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN for graphics -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Enrollment stats chart setup
    const enrollmentCtx = document.getElementById('enrollmentChartCanvas').getContext('2d');
    
    // PHP variables mapped to JSON
    const months = {!! json_encode($enrollmentChart->pluck('month')) !!};
    const counts = {!! json_encode($enrollmentChart->pluck('count')) !!};

    const displayMonths = months.length ? months : ['يناير', 'فبراير', 'مارس', 'أبريل'];
    const displayCounts = counts.length ? counts : [0, 0, 0, 0];

    new Chart(enrollmentCtx, {
        type: 'line',
        data: {
            labels: displayMonths,
            datasets: [{
                label: 'عدد المقررات المسجلة',
                data: displayCounts,
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderColor: 'rgba(102, 126, 234, 1)',
                borderWidth: 3,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // Payment methods chart setup
    const paymentCtx = document.getElementById('paymentMethodChartCanvas').getContext('2d');
    const methods = {!! json_encode($paymentMethodsChart->pluck('payment_method')) !!};
    const totals = {!! json_encode($paymentMethodsChart->pluck('total')) !!};

    const displayMethods = methods.length ? methods.map(m => {
        if(m === 'credit_card') return 'بطاقة ائتمان';
        if(m === 'paypal') return 'بايبال';
        if(m === 'bank_transfer') return 'حوالة بنكية';
        return m;
    }) : ['بطاقة ائتمان', 'بايبال', 'حوالة بنكية'];
    
    const displayTotals = totals.length ? totals : [0, 0, 0];

    new Chart(paymentCtx, {
        type: 'doughnut',
        data: {
            labels: displayMethods,
            datasets: [{
                data: displayTotals,
                backgroundColor: [
                    '#4caf50',
                    '#2196f3',
                    '#ff9800'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>

@endsection
