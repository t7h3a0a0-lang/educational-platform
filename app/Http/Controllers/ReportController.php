<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Grade;
use App\Models\Report;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // Admin Dashboard / Analytics center
    public function adminDashboard()
    {
        $usersCount = User::count();
        $studentsCount = Student::count();
        $teachersCount = Teacher::count();
        $coursesCount = Course::count();

        // Financial report totals
        $totalPayments = Payment::where('status', 'completed')->sum('amount');
        
        // Enrollment stats
        $pendingEnrollments = Enrollment::where('status', 'pending')->count();
        $approvedEnrollments = Enrollment::where('status', 'approved')->count();

        // Recent users
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();

        // Chart 1: Registrations per month (simulated aggregate)
        $enrollmentChart = Enrollment::select(DB::raw('count(id) as count'), DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Chart 2: Revenue per payment method
        $paymentMethodsChart = Payment::select(DB::raw('sum(amount) as total'), 'payment_method')
            ->groupBy('payment_method')
            ->get();

        // Load system settings
        $settings = SystemSetting::all();

        return view('admin.dashboard', compact(
            'usersCount',
            'studentsCount',
            'teachersCount',
            'coursesCount',
            'totalPayments',
            'pendingEnrollments',
            'approvedEnrollments',
            'recentUsers',
            'enrollmentChart',
            'paymentMethodsChart',
            'settings'
        ));
    }

    // List and generate reports
    public function index()
    {
        $reports = Report::with('generator')->orderBy('created_at', 'desc')->get();
        return view('admin.reports.index', compact('reports'));
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'type' => 'required|string|in:financial,academic,enrollments',
        ]);

        // Gather report details
        $parameters = [];
        if ($request->type === 'financial') {
            $total = Payment::where('status', 'completed')->sum('amount');
            $count = Payment::count();
            $parameters = ['total_revenue' => $total, 'payment_count' => $count];
        } elseif ($request->type === 'academic') {
            $avgGrade = Grade::avg('score');
            $highest = Grade::max('score');
            $parameters = ['average_grade' => round($avgGrade ?? 0, 2), 'highest_grade' => $highest];
        } else {
            $totalCourses = Course::count();
            $totalEnrollments = Enrollment::count();
            $parameters = ['courses_count' => $totalCourses, 'enrollments_count' => $totalEnrollments];
        }

        Report::create([
            'title' => $request->title,
            'type' => $request->type,
            'parameters' => $parameters,
            'generated_by' => Auth::id(),
            'file_path' => null, // In production, this would link to a generated PDF
        ]);

        return redirect()->back()->with('success', 'تم إنشاء التقرير الإحصائي وتوثيقه بنجاح في النظام.');
    }

    // Save system settings
    public function saveSettings(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'تم حفظ إعدادات النظام بنجاح.');
    }
}
