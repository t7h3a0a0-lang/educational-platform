<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Notification;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    // Admin list of students
    public function index()
    {
        $students = Student::with('user')->get();
        return view('admin.students.index', compact('students'));
    }

    // Admin updates student status
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $request->validate([
            'department' => 'required|string|max:100',
            'status' => 'required|string|in:active,suspended,graduated',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $student->update([
            'department' => $request->department,
            'status' => $request->status,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->back()->with('success', 'تم تحديث معلومات الطالب بنجاح.');
    }

    // Student Dashboard view
    public function dashboard()
    {
        $user = Auth::user();
        if (!$user->student) {
            return redirect('/login')->withErrors(['email' => 'لم يتم العثور على ملف طالب لهذا الحساب.']);
        }

        $student = $user->student;
        $enrollments = Enrollment::with('course.teacher.user')
            ->where('student_id', $student->id)
            ->get();
        
        $payments = Payment::where('student_id', $student->id)->orderBy('created_at', 'desc')->get();
        
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $grades = Grade::with('exam.course')
            ->where('student_id', $student->id)
            ->get();

        return view('student.dashboard', compact('student', 'enrollments', 'payments', 'notifications', 'grades'));
    }

    // Student view grades
    public function grades()
    {
        $student = Auth::user()->student;
        $grades = Grade::with(['exam.course', 'grader.user'])
            ->where('student_id', $student->id)
            ->get();
        return view('student.grades', compact('grades'));
    }

    // Student view notifications
    public function notifications()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('student.notifications', compact('notifications'));
    }

    // Student mark notification as read
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }
}
