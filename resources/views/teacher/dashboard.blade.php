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
                    <span class="badge bg-warning text-dark rounded-pill px-3 mt-1">عضو هيئة التدريس</span>
                    <p class="text-muted small mt-2 mb-0">{{ $teacher->department }} | {{ $teacher->specialization }}</p>
                </div>
                <ul class="nav flex-column gap-2 text-end">
                    <li class="nav-item">
                        <a href="{{ route('teacher.dashboard') }}" class="nav-link active bg-light rounded-3 text-primary p-3 fw-bold">
                            <i class="fa-solid fa-gauge me-2"></i> لوحة تحكم المعلم
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('teacher.courses.index') }}" class="nav-link text-dark p-3 rounded-3 hover-bg">
                            <i class="fa-solid fa-book me-2"></i> مقرراتي الدراسية ({{ $courses->count() }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('teacher.students.index') }}" class="nav-link text-dark p-3 rounded-3 hover-bg">
                            <i class="fa-solid fa-graduation-cap me-2"></i> قائمة الطلاب والتحضير
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('teacher.exams.index') }}" class="nav-link text-dark p-3 rounded-3 hover-bg">
                            <i class="fa-solid fa-file-pen me-2"></i> إدارة الاختبارات والدرجات
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
                    <h2 class="fw-black mb-0 text-dark">بوابة المعلم الأكاديمية</h2>
                    <p class="text-muted">مرحباً بك د. {{ auth()->user()->name }}، يمكنك إدارة شُعبك الدراسية ورصد الدرجات هنا.</p>
                </div>
                <div class="date-badge bg-white shadow-sm px-4 py-2 rounded-pill text-muted small">
                    <i class="fa-solid fa-id-card me-2"></i> رقم الموظف: {{ $teacher->employee_number }}
                </div>
            </div>

            <!-- Stats Metric Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center">
                        <i class="fa-solid fa-book-open text-primary fs-2 mb-2"></i>
                        <h3 class="fw-bold mb-0">{{ $courses->count() }}</h3>
                        <span class="text-muted small">المقررات التي تدرسها</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center">
                        <i class="fa-solid fa-users text-success fs-2 mb-2"></i>
                        <h3 class="fw-bold mb-0">{{ $studentsCount }}</h3>
                        <span class="text-muted small">إجمالي الطلاب بشعبك</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center">
                        <i class="fa-solid fa-file-signature text-warning fs-2 mb-2"></i>
                        <h3 class="fw-bold mb-0">{{ $exams->count() }}</h3>
                        <span class="text-muted small">الاختبارات المجدولة</span>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Courses list overview -->
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                            <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-book-open text-primary me-2"></i>الشعب الدراسية النشطة</h5>
                            <a href="{{ route('teacher.courses.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">عرض الكل</a>
                        </div>
                        <div class="list-group list-group-flush">
                            @forelse($courses as $course)
                                <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold mb-0">{{ $course->title }}</h6>
                                        <small class="text-muted">{{ $course->course_code }} | {{ $course->credits }} وحدات معتمدة</small>
                                    </div>
                                    @php
                                        $count = \App\Models\Enrollment::where('course_id', $course->id)->where('status', 'approved')->count();
                                    @endphp
                                    <span class="badge bg-success-subtle text-success border border-success rounded-pill px-3">
                                        {{ $count }} طالب مقيد
                                    </span>
                                </div>
                            @empty
                                <p class="text-center text-muted small py-3">لم يتم إسناد أي شُعب دراسية لك حتى الآن.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Grades -->
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                        <h5 class="fw-bold mb-3 text-dark border-bottom pb-2"><i class="fa-solid fa-circle-check text-success me-2"></i>آخر الدرجات المرصودة</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr class="table-light">
                                        <th class="small">الطالب</th>
                                        <th class="small">المقرر</th>
                                        <th class="small">الدرجة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentGrades as $rg)
                                        <tr>
                                            <td class="small fw-bold">{{ $rg->student->user->name }}</td>
                                            <td class="small text-muted">{{ $rg->exam->course->course_code }}</td>
                                            <td class="small fw-bold text-success">{{ $rg->score }} / {{ $rg->exam->max_points }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted small py-3">لا توجد درجات مرصودة مؤخراً.</td>
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

@endsection
