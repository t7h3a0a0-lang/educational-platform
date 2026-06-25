@extends('app')
@section('content')

<div class="container py-4 px-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <h2 class="fw-bold mb-0 text-dark">ورقة رصد الدرجات والتقييم</h2>
            <p class="text-muted">رصد وتحديث درجات الطلاب لاختبار: <strong>{{ $exam->title }}</strong> (الدرجة القصوى: {{ $exam->max_points }} نقطة)</p>
        </div>
        <a href="{{ route('teacher.exams.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-1"></i> العودة لقائمة الاختبارات
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
            <i class="fa-solid fa-graduation-cap text-primary me-2"></i>طلاب مقرر: {{ $exam->course->title }} (رمز: {{ $exam->course->course_code }})
        </h5>
        
        <form action="{{ route('teacher.exams.grades.save', $exam->id) }}" method="POST">
            @csrf
            <div class="table-responsive mb-4">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>الطالب</th>
                            <th>الرقم الأكاديمي</th>
                            <th>الدرجة المستحقة / {{ $exam->max_points }}</th>
                            <th>الملاحظات والملحوظات التدريسية</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $studentCount = 0; @endphp
                        @foreach($exam->course->enrollments as $enrollment)
                            @if($enrollment->status === 'approved')
                                @php 
                                    $studentCount++; 
                                    $sid = $enrollment->student->id;
                                    $score = $existingGrades[$sid] ?? '';
                                    $fb = $feedbacks[$sid] ?? '';
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $enrollment->student->user->name }}</div>
                                        <span class="text-muted small">{{ $enrollment->student->user->email }}</span>
                                    </td>
                                    <td class="text-primary fw-bold">{{ $enrollment->student->student_number }}</td>
                                    <td style="width: 200px;">
                                        <div class="input-group">
                                            <input type="number" name="grades[{{ $sid }}]" class="form-control" 
                                                step="0.01" min="0" max="{{ $exam->max_points }}" value="{{ $score }}" placeholder="الدرجة">
                                            <span class="input-group-text bg-light">/ {{ $exam->max_points }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" name="feedback[{{ $sid }}]" class="form-control" 
                                            value="{{ $fb }}" placeholder="عمل رائع، بحاجة لمزيد من التركيز...">
                                    </td>
                                </tr>
                            @endif
                        @endforeach

                        @if($studentCount === 0)
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="fa-solid fa-users-slash text-muted fs-3 mb-2"></i><br>
                                    لا يوجد طلاب مسجلين وموافق عليهم في هذا المقرر الدراسي لرصد درجاتهم.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            @if($studentCount > 0)
                <button type="submit" class="btn btn-primary rounded-pill px-5 py-2.5 fw-bold shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                    <i class="fa-solid fa-circle-check me-1"></i> حفظ وإرسال النتائج للطلاب
                </button>
            @endif
        </form>
    </div>
</div>

@endsection
