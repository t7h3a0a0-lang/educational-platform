@extends('app')
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header border-0 text-white text-center py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fa-solid fa-user-lock fs-1 mb-2"></i>
                    <h3 class="fw-bold mb-0">تسجيل الدخول</h3>
                    <p class="small text-white-50 mb-0">يرجى إدخال بيانات حسابك الجامعي</p>
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

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">البريد الإلكتروني</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-start-0"><i class="fa-regular fa-envelope text-muted"></i></span>
                                <input type="email" id="email" name="email" class="form-control bg-light" required placeholder="name@university.edu" value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between">
                                <label for="password" class="form-label fw-bold">كلمة المرور</label>
                                <a href="{{ route('password.request') }}" class="text-decoration-none small" style="color: #667eea;">نسيت كلمة المرور؟</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-start-0"><i class="fa-solid fa-key text-muted"></i></span>
                                <input type="password" id="password" name="password" class="form-control bg-light" required placeholder="••••••••">
                            </div>
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label text-muted" for="remember">تذكرني على هذا الجهاز</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                            <i class="fa-solid fa-right-to-bracket me-2"></i> دخول البوابة
                        </button>
                    </form>
                </div>
                <div class="card-footer bg-light text-center py-3 border-0">
                    <span class="text-muted">ليس لديك حساب؟</span> 
                    <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: #667eea;">إنشاء حساب جديد</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection