<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Administrator;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    // 1. POST /api/login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            
            // Regenerate API token
            $user->api_token = Str::random(80);
            $user->save();

            return response()->json([
                'message' => 'Login successful',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'api_token' => $user->api_token
            ], 200);
        }

        return response()->json(['message' => 'Invalid credentials.'], 401);
    }

    // 2. POST /api/register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|in:student,teacher',
            'profile_number' => 'required|string|unique:students,student_number|unique:teachers,employee_number',
            'department' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'api_token' => Str::random(80),
        ]);

        if ($user->role === 'student') {
            Student::create([
                'user_id' => $user->id,
                'student_number' => $request->profile_number,
                'department' => $request->department ?? 'General',
            ]);
        } else {
            Teacher::create([
                'user_id' => $user->id,
                'employee_number' => $request->profile_number,
                'department' => $request->department ?? 'General',
            ]);
        }

        return response()->json([
            'message' => 'Registration successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'api_token' => $user->api_token
        ], 201);
    }

    // 3. GET /api/users (Admin only)
    public function users()
    {
        $currentUser = Auth::user();
        if ($currentUser->role !== 'admin') {
            return response()->json(['message' => 'Access denied. Administrators only.'], 403);
        }

        $users = User::with(['student', 'teacher', 'admin'])->get();
        return response()->json(['users' => $users], 200);
    }

    // 4. GET /api/courses
    public function courses()
    {
        $courses = Course::with('teacher.user')->get();
        return response()->json(['courses' => $courses], 200);
    }

    // 5. POST /api/enroll (Student only)
    public function enroll(Request $request)
    {
        $currentUser = Auth::user();
        if ($currentUser->role !== 'student') {
            return response()->json(['message' => 'Access denied. Students only.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $student = $currentUser->student;
        $course = Course::find($request->course_id);

        $exists = Enrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'You are already enrolled in this course.'], 400);
        }

        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        // Send alert
        Notification::create([
            'user_id' => $currentUser->id,
            'title' => '📝 طلب تسجيل مقرر (API)',
            'message' => 'تم تسجيلك مبدئياً عبر التطبيق في مقرر: ' . $course->title . '. يرجى سداد الرسوم.',
            'type' => 'enrollment',
        ]);

        return response()->json([
            'message' => 'Enrollment request submitted successfully.',
            'enrollment' => $enrollment
        ], 201);
    }

    // 6. GET /api/grades
    public function grades()
    {
        $currentUser = Auth::user();
        
        if ($currentUser->role === 'student') {
            // Student gets their own grades
            $grades = Grade::with('exam.course')
                ->where('student_id', $currentUser->student->id)
                ->get();
            return response()->json(['grades' => $grades], 200);
        } elseif ($currentUser->role === 'teacher') {
            // Teacher gets grades they gave
            $grades = Grade::with(['student.user', 'exam.course'])
                ->where('graded_by', $currentUser->teacher->id)
                ->get();
            return response()->json(['grades' => $grades], 200);
        } else {
            // Admin gets all grades
            $grades = Grade::with(['student.user', 'exam.course'])->get();
            return response()->json(['grades' => $grades], 200);
        }
    }
}
