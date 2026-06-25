<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Notification;
use App\Http\Requests\ExamRequest;
use App\Http\Requests\GradeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    // List all exams for a teacher's courses
    public function index()
    {
        $teacher = Auth::user()->teacher;
        $courses = Course::where('teacher_id', $teacher->id)->get();
        $courseIds = $courses->pluck('id');

        $exams = Exam::whereIn('course_id', $courseIds)->with('course')->get();
        return view('teacher.exams.index', compact('exams', 'courses'));
    }

    // Create exam
    public function store(ExamRequest $request)
    {
        Exam::create($request->validated());
        return redirect()->back()->with('success', 'تم إنشاء الاختبار وجدولته بنجاح.');
    }

    // Update exam
    public function update(ExamRequest $request, $id)
    {
        $exam = Exam::findOrFail($id);
        $exam->update($request->validated());
        return redirect()->back()->with('success', 'تم تحديث بيانات الاختبار بنجاح.');
    }

    // Delete exam
    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();
        return redirect()->back()->with('success', 'تم حذف الاختبار بنجاح.');
    }

    // Show grade input page for an exam
    public function showGrades($exam_id)
    {
        $exam = Exam::with('course.enrollments.student.user')->findOrFail($exam_id);
        $teacher = Auth::user()->teacher;

        // Ensure teacher owns this course/exam
        if ($exam->course->teacher_id !== $teacher->id) {
            abort(403, 'غير مصرح لك برصد درجات هذا الاختبار.');
        }

        // Get already entered grades for this exam
        $existingGrades = Grade::where('exam_id', $exam->id)->pluck('score', 'student_id')->toArray();
        $feedbacks = Grade::where('exam_id', $exam->id)->pluck('feedback', 'student_id')->toArray();

        return view('teacher.exams.grades', compact('exam', 'existingGrades', 'feedbacks'));
    }

    // Save grades
    public function saveGrades(Request $request, $exam_id)
    {
        $exam = Exam::with('course')->findOrFail($exam_id);
        $teacher = Auth::user()->teacher;

        if ($exam->course->teacher_id !== $teacher->id) {
            abort(403);
        }

        $request->validate([
            'grades' => 'required|array',
            'grades.*' => 'nullable|numeric|min:0|max:' . $exam->max_points,
            'feedback' => 'nullable|array',
        ]);

        foreach ($request->grades as $studentId => $score) {
            if ($score === null) continue;

            $feedback = $request->feedback[$studentId] ?? null;

            // Check if grade already exists, then update or create
            $grade = Grade::updateOrCreate(
                ['student_id' => $studentId, 'exam_id' => $exam->id],
                [
                    'score' => $score,
                    'feedback' => $feedback,
                    'graded_by' => $teacher->id
                ]
            );

            // Send notification to student
            $student = Student::find($studentId);
            Notification::updateOrCreate([
                'user_id' => $student->user_id,
                'title' => '📊 رصد درجة اختبار جديدة',
                'message' => 'تم رصد درجتك في اختبار "' . $exam->title . '" لمقرر "' . $exam->course->title . '". الدرجة الحاصل عليها: ' . $score . ' / ' . $exam->max_points,
                'type' => 'grade',
            ]);
        }

        return redirect()->route('teacher.exams.index')->with('success', 'تم رصد وتحديث الدرجات بنجاح وإرسال الإشعارات للطلاب.');
    }
}
