@extends('app')
@section('content')

<div class="container py-4 px-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <h2 class="fw-bold mb-0 text-dark">إدارة سجلات الطلاب</h2>
            <p class="text-muted">مراجعة وتعديل بيانات الطلاب الأكاديمية والتحكم بحالة القيد.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-1"></i> العودة للوحة المعلومات
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
            <i class="fa-solid fa-user-graduate me-2"></i>قائمة الطلاب المقيدين
        </h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>الاسم</th>
                        <th>الرقم الأكاديمي</th>
                        <th>القسم</th>
                        <th>الهاتف</th>
                        <th>العنوان</th>
                        <th>حالة القيد</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $student->user->name }}</div>
                                <span class="text-muted small">{{ $student->user->email }}</span>
                            </td>
                            <td class="fw-bold text-success">{{ $student->student_number }}</td>
                            <td>{{ $student->department ?? 'عام' }}</td>
                            <td>{{ $student->phone ?? '-' }}</td>
                            <td>{{ $student->address ?? '-' }}</td>
                            <td>
                                <span class="badge rounded-pill 
                                    {{ $student->status === 'active' ? 'bg-success' : ($student->status === 'suspended' ? 'bg-danger' : 'bg-secondary') }}">
                                    @if($student->status === 'active') مستمر @elseif($student->status === 'suspended') موقوف مؤقتاً @else متخرج @endif
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#editStudentModal-{{ $student->id }}">
                                    <i class="fa-solid fa-pencil me-1"></i> تعديل القيد
                                </button>
                            </td>
                        </tr>

                        <!-- Modal: Edit Student Profile -->
                        <div class="modal fade" id="editStudentModal-{{ $student->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content rounded-4 border-0">
                                    <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <h5 class="modal-title fw-bold">تعديل بيانات ملف الطالب</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body py-4">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">الاسم (للقراءة فقط)</label>
                                                <input type="text" class="form-control bg-light" readonly value="{{ $student->user->name }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">القسم الأكاديمي</label>
                                                <input type="text" name="department" class="form-control" required value="{{ $student->department }}" placeholder="هندسة البرمجيات">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">رقم الهاتف</label>
                                                <input type="text" name="phone" class="form-control" value="{{ $student->phone }}" placeholder="+96650000000">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">العنوان السكني</label>
                                                <input type="text" name="address" class="form-control" value="{{ $student->address }}" placeholder="الرياض - حي الياسمين">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">حالة القيد الأكاديمي</label>
                                                <select name="status" class="form-select" required>
                                                    <option value="active" {{ $student->status === 'active' ? 'selected' : '' }}>مستمر (Active)</option>
                                                    <option value="suspended" {{ $student->status === 'suspended' ? 'selected' : '' }}>موقوف مؤقتاً (Suspended)</option>
                                                    <option value="graduated" {{ $student->status === 'graduated' ? 'selected' : '' }}>متخرج (Graduated)</option>
                                                </select>
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
                            <td colspan="7" class="text-center text-muted py-4">لا يوجد طلاب مسجلين حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
