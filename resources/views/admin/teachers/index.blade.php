@extends('app')
@section('content')

<div class="container py-4 px-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <h2 class="fw-bold mb-0 text-dark">إدارة أعضاء هيئة التدريس</h2>
            <p class="text-muted">عرض وتحديث البيانات الأكاديمية والوظيفية للمدرسين.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-1"></i> العودة للوحة المعلومات
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
            <i class="fa-solid fa-chalkboard-user me-2"></i>قائمة المدرسين المقيدين
        </h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>الاسم</th>
                        <th>الرقم الوظيفي</th>
                        <th>التخصص</th>
                        <th>القسم</th>
                        <th>الهاتف</th>
                        <th>المكتب</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $teacher->user->name }}</div>
                                <span class="text-muted small">{{ $teacher->user->email }}</span>
                            </td>
                            <td class="fw-bold text-primary">{{ $teacher->employee_number }}</td>
                            <td>{{ $teacher->specialization ?? 'لم يحدد' }}</td>
                            <td>{{ $teacher->department ?? 'عام' }}</td>
                            <td>{{ $teacher->phone ?? '-' }}</td>
                            <td>{{ $teacher->office_location ?? '-' }}</td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#editTeacherModal-{{ $teacher->id }}">
                                    <i class="fa-solid fa-pencil me-1"></i> تعديل البيانات
                                </button>
                            </td>
                        </tr>

                        <!-- Modal: Edit Teacher Profile -->
                        <div class="modal fade" id="editTeacherModal-{{ $teacher->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content rounded-4 border-0">
                                    <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <h5 class="modal-title fw-bold">تعديل بيانات المدرس</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.teachers.update', $teacher->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body py-4">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">الاسم (للقراءة فقط)</label>
                                                <input type="text" class="form-control bg-light" readonly value="{{ $teacher->user->name }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">التخصص الدقيق</label>
                                                <input type="text" name="specialization" class="form-control" required value="{{ $teacher->specialization }}" placeholder="ذكاء اصطناعي">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">القسم الأكاديمي</label>
                                                <input type="text" name="department" class="form-control" required value="{{ $teacher->department }}" placeholder="علوم الحاسوب">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">رقم الهاتف</label>
                                                <input type="text" name="phone" class="form-control" value="{{ $teacher->phone }}" placeholder="+96650000000">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">موقع المكتب</label>
                                                <input type="text" name="office_location" class="form-control" value="{{ $teacher->office_location }}" placeholder="مبنى الحاسب - مكتب 102">
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
                            <td colspan="7" class="text-center text-muted py-4">لا يوجد مدرسين مسجلين حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
