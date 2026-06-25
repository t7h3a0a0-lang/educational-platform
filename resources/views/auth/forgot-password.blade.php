@extends('app')
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header border-0 text-white text-center py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fa-solid fa-unlock-keyhole fs-1 mb-2"></i>
                    <h3 class="fw-bold mb-0">استعادة كلمة المرور</h3>
                    <p class="small text-white-50 mb-0">أدخل بريدك الإلكتروني للحصول على رابط الاستعادة</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                        @if(session('demo_token'))
                            <div class="alert alert-warning text-center mt-3">
                                <strong>رابط إعادة التعيين التجريبي السريع:</strong><br>
                                <a href="/reset-password/{{ session('demo_token') }}" class="btn btn-sm btn-dark mt-2">
                                    اضغط هنا لإعادة تعيين كلمة المرور مباشرة
                                </a>
                            </div>
                        @endif
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">البريد الإلكتروني المسجل</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-start-0"><i class="fa-regular fa-envelope text-muted"></i></span>
                                <input type="email" id="email" name="email" class="form-control bg-light" required placeholder="name@university.edu" value="{{ old('email') }}">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                            إرسال رابط إعادة التعيين
                        </button>
                    </form>
                </div>
                <div class="card-footer bg-light text-center py-3 border-0">
                    <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: #667eea;">العودة لصفحة الدخول</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
