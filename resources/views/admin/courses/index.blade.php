@extends('app')
@section('content')

<div class="container py-4 px-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <h2 class="fw-bold mb-0 text-dark">إدارة المقررات والشُعب الدراسية</h2>
            <p class="text-muted">إنشاء المقررات وتكليف المدرسين، والموافقة على طلبات التسجيل المعلقة.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-1"></i> العودة للوحة المعلومات
        </a>
    </div>

    <!-- Approvals Alert / section -->
    @php
        $pendingEnrollments = \App\Models\Enrollment::where('status', 'pending')->with(['student.user', 'course'])->get();
    @endphp

    @if($pendingEnrollments->count() > 0)
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="border-right: 5px solid #ff9800 !important; background: #fffcf8;">
            <h5 class="fw-bold mb-3 text-warning"><i class="fa-solid fa-triangle-exclamation me-2"></i>طلبات تسجيل مقررات معلقة للموافقة ({{ $pendingEnrollments->count() }})</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>الطالب</th>
                            <th>المقرر الدراسي</th>
                            <th>تاريخ الطلب</th>
                            <th>حالة الرسوم</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingEnrollments as $pending)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $pending->student->user->name }}</div>
                                    <span class="text-muted small">رقم: {{ $pending->student->student_number }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $pending->course->title }}</div>
                                    <span class="text-muted small">رمز: {{ $pending->course->course_code }}</span>
                                </td>
                                <td>{{ $pending->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <span class="badge {{ $pending->payment_status === 'paid' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $pending->payment_status === 'paid' ? 'مدفوعة' : 'غير مدفوعة' }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.enrollments.approve', $pending->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">
                                            <i class="fa-solid fa-check me-1"></i> موافقة وتأكيد القيد
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Add Course (Col 4) -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-plus me-2"></i>إضافة مقرر دراسي</h5>
                <form action="{{ route('admin.courses.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">رمز المقرر</label>
                        <input type="text" name="course_code" class="form-control" required placeholder="SWE-311">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">عنوان المقرر</label>
                        <input type="text" name="title" class="form-control" required placeholder="هندسة البرمجيات 2">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">عدد الوحدات المعتمدة</label>
                        <input type="number" name="credits" class="form-control" required min="1" max="10" placeholder="3">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">القسم</label>
                        <input type="text" name="department" class="form-control" placeholder="هندسة برمجيات">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">الحد الأقصى للطلاب</label>
                        <input type="number" name="max_students" class="form-control" required min="5" value="50">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">مدرس المقرر الكفء</label>
                        <select name="teacher_id" class="form-select">
                            <option value="">اختر مدرساً للمقرر</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->user->name }} ({{ $teacher->specialization }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">الوصف</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="نبذة مختصرة عن أهداف المنهج الأكاديمي..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="fa-solid fa-save me-1"></i> إنشاء المقرر
                    </button>
                </form>
            </div>
        </div>

        <!-- Courses List (Col 8) -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                    <i class="fa-solid fa-book-open me-2"></i>المقررات الحالية
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>المقرر</th>
                                <th>عدد الوحدات</th>
                                <th>القسم</th>
                                <th>المدرس</th>
                                <th>الشعبة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $course)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $course->title }}</div>
                                        <span class="text-primary small fw-bold">{{ $course->course_code }}</span>
                                    </td>
                                    <td>{{ $course->credits }} وحدات</td>
                                    <td>{{ $course->department ?? 'عام' }}</td>
                                    <td>
                                        @if($course->teacher)
                                            <span class="text-dark">{{ $course->teacher->user->name }}</span>
                                        @else
                                            <span class="text-muted small">غير مسند</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $activeCount = \App\Models\Enrollment::where('course_id', $course->id)->where('status', 'approved')->count();
                                        @endphp
                                        <span class="text-muted small">{{ $activeCount }} / {{ $course->max_students }} طالب</span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <!-- Edit button -->
                                            <button class="btn btn-outline-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#editCourseModal-{{ $course->id }}">
                                                <i class="fa-solid fa-pencil"></i>
                                            </button>
                                            
                                            <!-- Delete button -->
                                            <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المقرر؟ سيتم إلغاء تسجيل جميع الطلاب المرتبطين به.');">
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
                                <div class="modal fade" id="editCourseModal-{{ $course->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content rounded-4 border-0">
                                            <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                <h5 class="modal-title fw-bold">تعديل بيانات المقرر الدراسي</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.courses.update', $course->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body py-4">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">رمز المقرر</label>
                                                        <input type="text" name="course_code" class="form-control" required value="{{ $course->course_code }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">عنوان المقرر</label>
                                                        <input type="text" name="title" class="form-control" required value="{{ $course->title }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">عدد الوحدات المعتمدة</label>
                                                        <input type="number" name="credits" class="form-control" required min="1" max="10" value="{{ $course->credits }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">القسم</label>
                                                        <input type="text" name="department" class="form-control" value="{{ $course->department }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">الحد الأقصى للطلاب</label>
                                                        <input type="number" name="max_students" class="form-control" required min="5" value="{{ $course->max_students }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">المدرس الكفء</label>
                                                        <select name="teacher_id" class="form-select">
                                                            <option value="">اختر مدرساً للمقرر</option>
                                                            @foreach($teachers as $teacher)
                                                                <option value="{{ $teacher->id }}" {{ $course->teacher_id == $teacher->id ? 'selected' : '' }}>{{ $teacher->user->name }}</option>
                                                            @endforeach
                                                        </select>
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
