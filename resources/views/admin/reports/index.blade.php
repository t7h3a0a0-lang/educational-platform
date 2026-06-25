@extends('app')
@section('content')

<div class="container py-4 px-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <h2 class="fw-bold mb-0 text-dark">التقارير الإحصائية والتحليلات</h2>
            <p class="text-muted">توليد ومراجعة تقارير النظام الأكاديمية والمالية الشاملة.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-1"></i> العودة للوحة المعلومات
        </a>
    </div>

    <div class="row">
        <!-- Generate Report Form (Col 4) -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-file-signature me-2"></i>توليد تقرير إحصائي جديد</h5>
                <form action="{{ route('admin.reports.generate') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">عنوان التقرير</label>
                        <input type="text" name="title" class="form-control" required placeholder="تقرير الأداء الأكاديمي لفصل الخريف">
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">نوع وموضوع التقرير</label>
                        <select name="type" class="form-select" required>
                            <option value="financial">التقارير المالية والرسوم (Financial)</option>
                            <option value="academic">الأداء الأكاديمي والدرجات (Academic)</option>
                            <option value="enrollments">إحصاءات القبول والتسجيل (Enrollments)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="fa-solid fa-gears me-1"></i> توليد وحفظ التقرير
                    </button>
                </form>
            </div>
        </div>

        <!-- Generated Reports List (Col 8) -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                    <i class="fa-solid fa-file-lines me-2"></i>سجل التقارير المصدرة
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>عنوان التقرير</th>
                                <th>الموضوع</th>
                                <th>الملخص الإحصائي المستخرج</th>
                                <th>المصدر</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $report->title }}</div>
                                        <span class="text-muted small">ID: {{ $report->id }}</span>
                                    </td>
                                    <td>
                                        @if($report->type === 'financial')
                                            <span class="badge bg-success-subtle text-success border border-success rounded-pill px-2.5">مالي</span>
                                        @elseif($report->type === 'academic')
                                            <span class="badge bg-primary-subtle text-primary border border-primary rounded-pill px-2.5">أكاديمي</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border border-warning rounded-pill px-2.5">قبول وتسجيل</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small text-dark bg-light p-2 rounded-3">
                                            @if($report->type === 'financial')
                                                <i class="fa-solid fa-coins text-success me-1"></i> إجمالي الإيرادات: <strong>{{ $report->parameters['total_revenue'] ?? 0 }} ر.س</strong><br>
                                                <i class="fa-solid fa-receipt text-muted me-1"></i> الفواتير المدفوعة: {{ $report->parameters['payment_count'] ?? 0 }}
                                            @elseif($report->type === 'academic')
                                                <i class="fa-solid fa-chart-bar text-primary me-1"></i> متوسط درجات الطلاب: <strong>{{ $report->parameters['average_grade'] ?? 0 }} %</strong><br>
                                                <i class="fa-solid fa-award text-warning me-1"></i> أعلى درجة تم رصدها: {{ $report->parameters['highest_grade'] ?? 0 }}
                                            @else
                                                <i class="fa-solid fa-book-open text-warning me-1"></i> إجمالي المقررات: {{ $report->parameters['courses_count'] ?? 0 }}<br>
                                                <i class="fa-solid fa-file-contract text-muted me-1"></i> إجمالي التسجيلات: {{ $report->parameters['enrollments_count'] ?? 0 }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="small">{{ $report->generator->name }}</td>
                                    <td class="small text-muted">{{ $report->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">لا توجد تقارير إحصائية مصدرة في سجل النظام.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
