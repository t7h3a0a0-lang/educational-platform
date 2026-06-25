<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    // Admin list of teachers
    public function index()
    {
        $teachers = Teacher::with('user')->get();
        return view('admin.teachers.index', compact('teachers'));
    }

    // Admin updates teacher status
    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $request->validate([
            'specialization' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'office_location' => 'nullable|string|max:100',
        ]);

        $teacher->update([
            'specialization' => $request->specialization,
            'department' => $request->department,
            'phone' => $request->phone,
            'office_location' => $request->office_location,
        ]);

        return redirect()->back()->with('success', 'تم تحديث معلومات المدرس بنجاح.');
    }

    // Teacher Dashboard view
    public function dashboard()
    {
        $user = Auth::user();
        if (!$user->teacher) {
            return redirect('/login')->withErrors(['email' => 'لم يتم العثور على ملف مدرس لهذا الحساب.']);
        }

        $teacher = $user->teacher;
        $courses = Course::where('teacher_id', $teacher->id)->get();
        $courseIds = $courses->pluck('id');

        $exams = Exam::whereIn('course_id', $courseIds)->with('course')->get();
        
        // Count total unique students enrolled in teacher's courses
        $studentsCount = Course::withCount('enrollments')
            ->where('teacher_id', $teacher->id)
            ->get()
            ->sum('enrollments_count');

        $recentGrades = Grade::with(['student.user', 'exam.course'])
            ->where('graded_by', $teacher->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('teacher.dashboard', compact('teacher', 'courses', 'exams', 'studentsCount', 'recentGrades'));
    }

    // Teacher view students registered in their courses
    public function students()
    {
        $teacher = Auth::user()->teacher;
        $courses = Course::with(['enrollments.student.user'])->where('teacher_id', $teacher->id)->get();
        
        return view('teacher.students', compact('courses'));
    }
}
