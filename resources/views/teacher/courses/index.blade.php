@extends('app')
@section('content')

<div class="container py-4 px-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <h2 class="fw-bold mb-0 text-dark">مقرراتي الدراسية المسندة</h2>
            <p class="text-muted">عرض وتعديل محتوى وتفاصيل المناهج التي تقوم بتدريسها.</p>
        </div>
        <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-1"></i> العودة للوحة المعلومات
        </a>
    </div>

    <div class="row">
        <!-- Add New Course directly (Optional/Alternative flow) -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-plus me-2"></i>إنشاء مقرر دراسي جديد</h5>
                <form action="{{ route('teacher.courses.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">رمز المقرر</label>
                        <input type="text" name="course_code" class="form-control" required placeholder="CS-101">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">عنوان المقرر الدراسي</label>
                        <input type="text" name="title" class="form-control" required placeholder="مبادئ شبكات الحاسب">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">عدد الوحدات المعتمدة</label>
                        <input type="number" name="credits" class="form-control" required min="1" max="10" placeholder="3">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">القسم الأكاديمي</label>
                        <input type="text" name="department" class="form-control" placeholder="علوم الحاسوب">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">السعة الاستيعابية للطلاب</label>
                        <input type="number" name="max_students" class="form-control" required min="5" value="40">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">وصف المقرر وأهدافه</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="مقدمة في بروتوكولات الاتصال والشبكات اللاسلكية..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="fa-solid fa-save me-1"></i> حفظ وإنشاء المقرر
                    </button>
                </form>
            </div>
        </div>

        <!-- My Courses list -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                    <i class="fa-solid fa-book-open me-2"></i>قائمة المناهج الدراسية الخاصة بي
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>المقرر</th>
                                <th>عدد الوحدات</th>
                                <th>القسم العلمي</th>
                                <th>الشعبة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myCourses as $course)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $course->title }}</div>
                                        <span class="text-primary small fw-bold">{{ $course->course_code }}</span>
                                    </td>
                                    <td>{{ $course->credits }} وحدات معتمدة</td>
                                    <td>{{ $course->department ?? 'عام' }}</td>
                                    <td>
                                        @php
                                            $activeCount = \App\Models\Enrollment::where('course_id', $course->id)->where('status', 'approved')->count();
                                        @endphp
                                        <span class="badge bg-light text-dark">{{ $activeCount }} / {{ $course->max_students }} طالب</span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-outline-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#editMyCourseModal-{{ $course->id }}">
                                                <i class="fa-solid fa-pencil"></i> تعديل
                                            </button>
                                            
                                            <form action="{{ route('teacher.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المقرر؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal: Edit Course -->
                                <div class="modal fade" id="editMyCourseModal-{{ $course->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content rounded-4 border-0">
                                            <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                <h5 class="modal-title fw-bold">تعديل بيانات المقرر</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('teacher.courses.update', $course->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body py-4">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">رمز المقرر</label>
                                                        <input type="text" name="course_code" class="form-control" required value="{{ $course->course_code }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">عنوان المقرر الدراسي</label>
                                                        <input type="text" name="title" class="form-control" required value="{{ $course->title }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">عدد الوحدات المعتمدة</label>
                                                        <input type="number" name="credits" class="form-control" required min="1" max="10" value="{{ $course->credits }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">القسم الأكاديمي</label>
                                                        <input type="text" name="department" class="form-control" value="{{ $course->department }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">الحد الأقصى للطلاب</label>
                                                        <input type="number" name="max_students" class="form-control" required min="5" value="{{ $course->max_students }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">الوصف</label>
                                                        <textarea name="description" class="form-control" rows="3">{{ $course->description }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">إلغاء</button>
                                                    <button type="submit" class="btn btn-primary rounded-pill px-4">حفظ التغييرات</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">لم تضف أو تسند لك أي مقررات دراسية بعد.</td>
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
