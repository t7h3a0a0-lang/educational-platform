@extends('app')
@section('content')

<div class="container py-4 px-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <h2 class="fw-bold mb-0 text-dark">تسجيل المقررات الدراسية</h2>
            <p class="text-muted">تصفح المقررات الدراسية المتاحة للفصل الدراسي وسجل الشعب فوراً.</p>
        </div>
        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-1"></i> العودة للملف الشخصي
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
            <i class="fa-solid fa-list-check text-primary me-2"></i>المقررات المتاحة للتسجيل حالياً
        </h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>المقرر الدراسي</th>
                        <th>رمز المقرر</th>
                        <th>الوحدات</th>
                        <th>القسم العلمي</th>
                        <th>المدرس</th>
                        <th>الشعبة النشطة</th>
                        <th>حالة التسجيل</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td class="fw-bold text-dark">{{ $course->title }}</td>
                            <td class="text-primary fw-bold">{{ $course->course_code }}</td>
                            <td>{{ $course->credits }} وحدات معتمدة</td>
                            <td>{{ $course->department ?? 'عام' }}</td>
                            <td>{{ $course->teacher->user->name ?? 'غير مسند' }}</td>
                            <td>
                                @php
                                    $activeCount = \App\Models\Enrollment::where('course_id', $course->id)->where('status', 'approved')->count();
                                @endphp
                                <span class="text-muted small">{{ $activeCount }} / {{ $course->max_students }} طالب</span>
                            </td>
                            <td>
                                @if(isset($myEnrollments[$course->id]))
                                    @php $status = $myEnrollments[$course->id]; @endphp
                                    @if($status === 'approved')
                                        <span class="badge bg-success rounded-pill px-3 py-1.5 small"><i class="fa-solid fa-circle-check me-1"></i> مقيد رسمياً</span>
                                    @elseif($status === 'pending')
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 small"><i class="fa-solid fa-clock me-1"></i> معلق (بانتظار الرسوم/القبول)</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill px-3 py-1.5 small"><i class="fa-solid fa-circle-xmark me-1"></i> مرفوض</span>
                                    @endif
                                @else
                                    <form action="{{ route('student.courses.enroll', $course->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                                            <i class="fa-solid fa-plus me-1"></i> تسجيل المقرر
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">لا توجد مقررات دراسية متاحة للتسجيل في الوقت الحالي.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
