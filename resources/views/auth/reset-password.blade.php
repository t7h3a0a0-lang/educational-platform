@extends('app')
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header border-0 text-white text-center py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fa-solid fa-key fs-1 mb-2"></i>
                    <h3 class="fw-bold mb-0">تعيين كلمة المرور الجديدة</h3>
                    <p class="small text-white-50 mb-0">يرجى إدخال كلمة المرور الجديدة لحسابك الأكاديمي</p>
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

                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">البريد الإلكتروني</label>
                            <input type="email" id="email" name="email" class="form-control bg-light" required readonly value="{{ $email }}">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">كلمة المرور الجديدة</label>
                            <input type="password" id="password" name="password" class="form-control bg-light" required placeholder="••••••••">
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control bg-light" required placeholder="••••••••">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                            تحديث كلمة المرور
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
