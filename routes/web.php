<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MapController;




// Index page redirecting to login or appropriate dashboard
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        return redirect("/{$role}/dashboard");
    }
    return redirect('/login');
});

// Authentication Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Forgot / Reset Password
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // 1. Admin Role Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [ReportController::class, 'adminDashboard'])->name('dashboard');
        
        // Manage Users
        Route::resource('users', UserController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::post('users/{id}/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions');

        // Manage Teachers
        Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
        Route::put('/teachers/{id}', [TeacherController::class, 'update'])->name('teachers.update');

        // Manage Students
        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');

        // Manage Courses
        Route::resource('courses', CourseController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::post('enrollments/{id}/approve', [CourseController::class, 'approveEnrollment'])->name('enrollments.approve');

        // View Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('/reports/generate', [ReportController::class, 'generateReport'])->name('reports.generate');

        // Manage Payments log
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');

        // System Settings
        Route::post('/settings', [ReportController::class, 'saveSettings'])->name('settings.save');
    });

    // 2. Teacher Role Routes
    Route::middleware('role:teacher')->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
        
        // Manage Courses
        Route::resource('courses', CourseController::class)->only(['index', 'store', 'update', 'destroy']);
        
        // Manage Students
        Route::get('/students', [TeacherController::class, 'students'])->name('students.index');

        // Manage Exams
        Route::resource('exams', ExamController::class)->only(['index', 'store', 'update', 'destroy']);
        
        // Manage Grades
        Route::get('exams/{id}/grades', [ExamController::class, 'showGrades'])->name('exams.grades');
        Route::post('exams/{id}/grades', [ExamController::class, 'saveGrades'])->name('exams.grades.save');
    });

    // 3. Student Role Routes
    Route::middleware('role:student')->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        
        // View Courses and Enroll
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
        Route::post('/courses/{id}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');

        // View Grades
        Route::get('/grades', [StudentController::class, 'grades'])->name('grades.index');

        // View Notifications
        Route::get('/notifications', [StudentController::class, 'notifications'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [StudentController::class, 'markAsRead'])->name('notifications.read');

        // Electronic Payments Simulator
        Route::get('/payment/{enrollment_id}', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
        Route::post('/payment/{enrollment_id}', [PaymentController::class, 'processPayment'])->name('payment.process');
    });
});





