@extends('app')
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header border-0 text-white text-center py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fa-solid fa-user-plus fs-1 mb-2"></i>
                    <h3 class="fw-bold mb-0">إنشاء حساب جديد</h3>
                    <p class="small text-white-50 mb-0">انضم للمنصة الإلكترونية للخدمات الجامعية</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-bold">الاسم الكامل</label>
                                <input type="text" id="name" name="name" class="form-control bg-light" required placeholder="أحمد محمد" value="{{ old('name') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold">البريد الإلكتروني</label>
                                <input type="email" id="email" name="email" class="form-control bg-light" required placeholder="example@university.edu" value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-bold">كلمة المرور</label>
                                <input type="password" id="password" name="password" class="form-control bg-light" required placeholder="••••••••">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-bold">تأكيد كلمة المرور</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control bg-light" required placeholder="••••••••">
                            </div>
                        </div>

                        <hr class="my-4 text-muted">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label fw-bold">نوع الحساب</label>
                                <select id="role" name="role" class="form-select bg-light" required>
                                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>طالب (Student)</option>
                                    <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>مدرس (Teacher)</option>
                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>مسؤول (Admin)</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label id="number_label" for="profile_number" class="form-label fw-bold">الرقم الأكاديمي / الوظيفي</label>
                                <input type="text" id="profile_number" name="profile_number" class="form-control bg-light" required placeholder="20261005" value="{{ old('profile_number') }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="department" class="form-label fw-bold">القسم العلمي / التخصص</label>
                            <input type="text" id="department" name="department" class="form-control bg-light" placeholder="هندسة البرمجيات / علوم الحاسوب" value="{{ old('department') }}">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                            <i class="fa-solid fa-user-check me-2"></i> إنشاء الحساب وتفعيل العضوية
                        </button>
                    </form>
                </div>
                <div class="card-footer bg-light text-center py-3 border-0">
                    <span class="text-muted">لديك حساب بالفعل؟</span> 
                    <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: #667eea;">تسجيل الدخول</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const roleSelect = document.getElementById('role');
    const label = document.getElementById('number_label');
    roleSelect.addEventListener('change', function() {
        if(roleSelect.value === 'student') {
            label.textContent = 'الرقم الأكاديمي للطالب';
        } else if(roleSelect.value === 'teacher') {
            label.textContent = 'الرقم الوظيفي للمدرس';
        } else {
            label.textContent = 'الرقم الوظيفي للمسؤول';
        }
    });
</script>

@endsection