@extends('app')
@section('content')

<div class="container py-4 px-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <h2 class="fw-bold mb-0 text-dark">إدارة حسابات المستخدمين</h2>
            <p class="text-muted">عرض وتعديل صلاحيات الطلاب، المدرسين، والمسؤولين في النظام.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-1"></i> العودة للوحة المعلومات
        </a>
    </div>

    <div class="row">
        <!-- Add New User Card (Col 4) -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-user-plus me-2"></i>إضافة حساب جديد</h5>
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">الاسم الكامل</label>
                        <input type="text" name="name" class="form-control" required placeholder="أحمد علي">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" required placeholder="name@university.edu">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">كلمة المرور</label>
                        <input type="password" name="password" class="form-control" required placeholder="••••••••">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">نوع الحساب</label>
                        <select name="role" class="form-select" required>
                            <option value="student">طالب (Student)</option>
                            <option value="teacher">مدرس (Teacher)</option>
                            <option value="admin">مسؤول (Admin)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">الرقم الأكاديمي / الوظيفي</label>
                        <input type="text" name="profile_number" class="form-control" required placeholder="20260012">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">القسم / التخصص</label>
                        <input type="text" name="department" class="form-control" placeholder="هندسة برمجيات">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="fa-solid fa-save me-1"></i> حفظ الحساب
                    </button>
                </form>
            </div>
        </div>

        <!-- Users List (Col 8) -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                    <i class="fa-solid fa-list-ul me-2"></i>قائمة الحسابات المقيدة
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>الاسم</th>
                                <th>البريد</th>
                                <th>الدور</th>
                                <th>رقم الملف</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <span class="text-muted small">ID: {{ $user->id }}</span>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge rounded-pill 
                                            {{ $user->role === 'admin' ? 'bg-danger' : ($user->role === 'teacher' ? 'bg-warning' : 'bg-success') }}">
                                            {{ __($user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->role === 'student' && $user->student)
                                            {{ $user->student->student_number }}
                                        @elseif($user->role === 'teacher' && $user->teacher)
                                            {{ $user->teacher->employee_number }}
                                        @elseif($user->role === 'admin' && $user->admin)
                                            {{ $user->admin->employee_number }}
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <!-- Permissions Trigger -->
                                            <button class="btn btn-outline-info btn-sm rounded-pill px-2.5" data-bs-toggle="modal" data-bs-target="#permsModal-{{ $user->id }}">
                                                <i class="fa-solid fa-shield-halved"></i> صلاحيات
                                            </button>
                                            
                                            <!-- Edit Trigger -->
                                            <button class="btn btn-outline-primary btn-sm rounded-pill px-2.5" data-bs-toggle="modal" data-bs-target="#editModal-{{ $user->id }}">
                                                <i class="fa-solid fa-pencil"></i>
                                            </button>

                                            <!-- Delete Trigger -->
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الحساب؟ ستفقد كافة الملفات المرتبطة به.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-2.5">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal: Edit User -->
                                <div class="modal fade" id="editModal-{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content rounded-4 border-0">
                                            <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                <h5 class="modal-title fw-bold">تعديل بيانات الحساب</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body py-4">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">الاسم الكامل</label>
                                                        <input type="text" name="name" class="form-control" required value="{{ $user->name }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">البريد الإلكتروني</label>
                                                        <input type="email" name="email" class="form-control" required value="{{ $user->email }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">نوع الحساب</label>
                                                        <select name="role" class="form-select" required>
                                                            <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>طالب (Student)</option>
                                                            <option value="teacher" {{ $user->role === 'teacher' ? 'selected' : '' }}>مدرس (Teacher)</option>
                                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>مسؤول (Admin)</option>
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

                                <!-- Modal: Sync Permissions -->
                                <div class="modal fade" id="permsModal-{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content rounded-4 border-0">
                                            <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                <h5 class="modal-title fw-bold">إسناد الصلاحيات المخصصة</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.users.permissions', $user->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body py-4">
                                                    <p class="small text-muted mb-3">حدد الصلاحيات الإضافية التي ترغب بمنحها لهذا المستخدم خارج نطاق دوره الأساسي:</p>
                                                    @foreach($permissions as $perm)
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="perm-{{ $user->id }}-{{ $perm->id }}"
                                                                {{ $user->hasPermission($perm->slug) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="perm-{{ $user->id }}-{{ $perm->id }}">
                                                                <strong>{{ $perm->name }}</strong> - <span class="text-muted small">{{ $perm->description }}</span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">إلغاء</button>
                                                    <button type="submit" class="btn btn-primary rounded-pill px-4">تحديث الصلاحيات</button>
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
