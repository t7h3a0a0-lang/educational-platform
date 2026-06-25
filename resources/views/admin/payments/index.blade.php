@extends('app')
@section('content')

<div class="container py-4 px-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <h2 class="fw-bold mb-0 text-dark">سجلات المدفوعات والرسوم الدراسية</h2>
            <p class="text-muted">مراقبة المعاملات المالية ورسوم التسجيل الإلكترونية للطلاب.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-1"></i> العودة للوحة المعلومات
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
            <i class="fa-solid fa-wallet text-primary me-2"></i>الفواتير والتحصيلات المستلمة
        </h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>المعاملة</th>
                        <th>الطالب</th>
                        <th>المقرر الدراسي</th>
                        <th>طريقة الدفع</th>
                        <th>المبلغ المسدد</th>
                        <th>تاريخ السداد</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td class="fw-bold text-dark">{{ $payment->transaction_id }}</td>
                            <td>
                                <div class="fw-bold">{{ $payment->student->user->name }}</div>
                                <span class="text-muted small">رقم: {{ $payment->student->student_number }}</span>
                            </td>
                            <td>
                                @if($payment->enrollment && $payment->enrollment->course)
                                    <div class="fw-bold">{{ $payment->enrollment->course->title }}</div>
                                    <span class="text-muted small">رمز: {{ $payment->enrollment->course->course_code }}</span>
                                @else
                                    <span class="text-muted small">رسوم دراسية عامة</span>
                                @endif
                            </td>
                            <td>
                                @if($payment->payment_method === 'credit_card')
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary rounded-pill px-2.5">
                                        <i class="fa-solid fa-credit-card me-1"></i> بطاقة ائتمان
                                    </span>
                                @elseif($payment->payment_method === 'paypal')
                                    <span class="badge bg-info-subtle text-info border border-info rounded-pill px-2.5">
                                        <i class="fa-brands fa-paypal me-1"></i> بايبال
                                    </span>
                                @else
                                    <span class="badge bg-dark-subtle text-dark border border-dark rounded-pill px-2.5">
                                        <i class="fa-solid fa-bank me-1"></i> حوالة بنكية
                                    </span>
                                @endif
                            </td>
                            <td class="fw-bold text-success">{{ number_format($payment->amount, 2) }} ر.س</td>
                            <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d H:i') : '-' }}</td>
                            <td>
                                <span class="badge rounded-pill bg-success px-3">
                                    <i class="fa-solid fa-circle-check me-1"></i> ناجحة
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">لا توجد عمليات سداد تمت حتى الآن.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
