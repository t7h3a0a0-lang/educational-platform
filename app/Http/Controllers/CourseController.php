<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Teacher;
use App\Models\Enrollment;
use App\Models\Notification;
use App\Http\Requests\CourseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    // List courses (based on roles)
    public function index()
    {
        $user = Auth::user();
        $courses = Course::with('teacher.user')->get();
        $teachers = Teacher::with('user')->get();

        if ($user->role === 'admin') {
            return view('admin.courses.index', compact('courses', 'teachers'));
        } elseif ($user->role === 'teacher') {
            $myCourses = Course::where('teacher_id', $user->teacher->id)->get();
            return view('teacher.courses.index', compact('myCourses'));
        } else {
            // Student: list courses available, indicating registration status
            $student = $user->student;
            $myEnrollments = Enrollment::where('student_id', $student->id)->pluck('status', 'course_id')->toArray();
            return view('student.courses.index', compact('courses', 'myEnrollments'));
        }
    }

    // Create a course
    public function store(CourseRequest $request)
    {
        $data = $request->validated();
        
        // If teacher creates a course, auto-assign teacher_id
        if (Auth::user()->role === 'teacher') {
            $data['teacher_id'] = Auth::user()->teacher->id;
        }

        Course::create($data);

        return redirect()->back()->with('success', 'تم إنشاء المقرر الدراسي بنجاح.');
    }

    // Update course
    public function update(CourseRequest $request, $id)
    {
        $course = Course::findOrFail($id);
        
        // Check authorization if teacher tries to edit another teacher's course
        if (Auth::user()->role === 'teacher' && $course->teacher_id !== Auth::user()->teacher->id) {
            abort(403, 'غير مصرح لك بتعديل هذا المقرر.');
        }

        $course->update($request->validated());

        return redirect()->back()->with('success', 'تم تحديث المقرر الدراسي بنجاح.');
    }

    // Delete course
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        
        if (Auth::user()->role === 'teacher' && $course->teacher_id !== Auth::user()->teacher->id) {
            abort(403, 'غير مصرح لك بحذف هذا المقرر.');
        }

        $course->delete();

        return redirect()->back()->with('success', 'تم حذف المقرر الدراسي بنجاح.');
    }

    // Student enrolls in a course
    public function enroll(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $student = Auth::user()->student;

        // Check if already registered
        $exists = Enrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'أنت مسجل بالفعل في هذا المقرر الدراسي.');
        }

        // Check capacity
        $enrolledCount = Enrollment::where('course_id', $course->id)->where('status', 'approved')->count();
        if ($enrolledCount >= $course->max_students) {
            return redirect()->back()->with('error', 'المقرر الدراسي ممتلئ حالياً.');
        }

        // Create enrollment
        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        // Send Notification
        Notification::create([
            'user_id' => Auth::id(),
            'title' => '📝 طلب تسجيل مقرر',
            'message' => 'تم تقديم طلبك بنجاح للتسجيل في مقرر: ' . $course->title . '. يرجى سداد الرسوم المطلوبة لتأكيد التسجيل.',
            'type' => 'enrollment',
        ]);

        return redirect()->route('student.dashboard')->with('success', 'تم تقديم طلب التسجيل بنجاح. يرجى إتمام عملية الدفع لتأكيد تسجيلك.');
    }

    // Admin approves enrollment
    public function approveEnrollment($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->update(['status' => 'approved']);

        // Send Notification to student
        Notification::create([
            'user_id' => $enrollment->student->user_id,
            'title' => '✅ تم قبول تسجيل المقرر',
            'message' => 'تهانينا! تم قبول طلب تسجيلك في مقرر: ' . $enrollment->course->title,
            'type' => 'enrollment',
        ]);

        return redirect()->back()->with('success', 'تمت الموافقة على طلب التسجيل بنجاح.');
    }
}
