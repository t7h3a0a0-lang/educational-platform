@extends('app')
@section('content')

<div class="container py-4 px-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <h2 class="fw-bold mb-0 text-dark">شُعب وقوائم الطلاب</h2>
            <p class="text-muted">عرض الطلاب المسجلين رسمياً في مقرراتك الدراسية النشطة.</p>
        </div>
        <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-1"></i> العودة للوحة المعلومات
        </a>
    </div>

    @forelse($courses as $course)
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
            <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                <i class="fa-solid fa-book-open text-primary me-2"></i>{{ $course->title }} (رمز: {{ $course->course_code }})
            </h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>الاسم</th>
                            <th>الرقم الأكاديمي</th>
                            <th>القسم الأكاديمي</th>
                            <th>حالة الحساب</th>
                            <th>البريد الإلكتروني</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $approvedCount = 0; @endphp
                        @forelse($course->enrollments as $enrollment)
                            @if($enrollment->status === 'approved')
                                @php $approvedCount++; @endphp
                                <tr>
                                    <td class="fw-bold text-dark">{{ $enrollment->student->user->name }}</td>
                                    <td class="text-success fw-bold">{{ $enrollment->student->student_number }}</td>
                                    <td>{{ $enrollment->student->department ?? '-' }}</td>
                                    <td>
                                        <span class="badge rounded-pill bg-success-subtle text-success border border-success px-3">نشط</span>
                                    </td>
                                    <td>{{ $enrollment->student->user->email }}</td>
                                </tr>
                            @endif
                        @empty
                            <!-- Empty block handled by the counter below -->
                        @endforelse

                        @if($approvedCount === 0)
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">لا يوجد طلاب مسجلين ومقبولين في هذه الشعبة بعد.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
            <i class="fa-solid fa-users-slash text-muted fs-1 mb-3"></i>
            <h5 class="text-muted">لم يتم إيجاد أي طلاب أو شعب دراسية مسندة إليك حالياً.</h5>
        </div>
    @endforelse
</div>

@endsection
