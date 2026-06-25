@extends('app')
@section('content')

<div class="container py-4 px-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <h2 class="fw-bold mb-0 text-dark">إدارة وجدولة الاختبارات</h2>
            <p class="text-muted">جدولة اختبارات جديدة، تحديد المعايير والدرجات، ورصد النتائج للطلاب.</p>
        </div>
        <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-1"></i> العودة للوحة المعلومات
        </a>
    </div>

    <div class="row">
        <!-- Add Exam Form (Col 4) -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-calendar-plus me-2"></i>جدولة اختبار جديد</h5>
                <form action="{{ route('teacher.exams.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">المقرر الدراسي</label>
                        <select name="course_id" class="form-select" required>
                            <option value="">اختر المقرر للشعبة</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }} ({{ $course->course_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">عنوان الاختبار</label>
                        <input type="text" name="title" class="form-control" required placeholder="الاختبار النصفي الأول">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">نوع التقييم</label>
                        <select name="type" class="form-select" required>
                            <option value="quiz">اختبار قصير (Quiz)</option>
                            <option value="midterm">اختبار نصفي (Midterm)</option>
                            <option value="final">اختبار نهائي (Final)</option>
                            <option value="assignment">واجب / مشروع (Assignment)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">الدرجة القصوى (النقاط)</label>
                        <input type="number" name="max_points" class="form-control" required min="1" max="1000" value="100">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">تاريخ ووقت الاختبار</label>
                        <input type="datetime-local" name="exam_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">توجيهات أو وصف</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="توجيهات حل الأسئلة أو تفاصيل حول الفصول المطلوبة..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="fa-solid fa-save me-1"></i> جدولة وحفظ
                    </button>
                </form>
            </div>
        </div>

        <!-- Exams list (Col 8) -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                    <i class="fa-solid fa-file-pen me-2"></i>الاختبارات النشطة والمرتقبة
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>الاختبار</th>
                                <th>المقرر الدراسي</th>
                                <th>نوع التقييم</th>
                                <th>الدرجة القصوى</th>
                                <th>التاريخ</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($exams as $exam)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $exam->title }}</div>
                                        <span class="text-muted small">ID: {{ $exam->id }}</span>
                                    </td>
                                    <td>{{ $exam->course->title }}</td>
                                    <td>
                                        <span class="badge rounded-pill bg-light text-dark">
                                            @if($exam->type === 'quiz') اختبار قصير @elseif($exam->type === 'midterm') نصفي @elseif($exam->type === 'final') نهائي @else واجب / مشروع @endif
                                        </span>
                                    </td>
                                    <td class="fw-bold text-primary">{{ $exam->max_points }} نقطة</td>
                                    <td>{{ $exam->exam_date->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <!-- Grades rasing -->
                                            <a href="{{ route('teacher.exams.grades', $exam->id) }}" class="btn btn-success btn-sm rounded-pill px-2.5">
                                                <i class="fa-solid fa-star me-1"></i> رصد الدرجات
                                            </a>
                                            
                                            <!-- Delete -->
                                            <form action="{{ route('teacher.exams.destroy', $exam->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الاختبار؟ سيتم حذف جميع درجات الطلاب المرتبطة به.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">لم تقم بجدولة أي اختبارات بعد.</td>
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
