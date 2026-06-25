<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Administrator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
//هذي الدالة تقوم بعرض تسجيل الدخول 
{
    public function showLogin()
    {// تفحض هل يوجد مستخدم داخل النظام 
        if (Auth::check()) {
            //اذهب الي لوحة التحكم 
            return $this->redirectUserDashboard(Auth::user());
        }
        return view('login');
    }
     // دالة تسجيل الدخول 
    public function login(Request $request)
    {// تحقق
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        //ابحث في جد,ل  user
        //هل البريد وكلمة المرور صحيحة  اذا نعم يدخل  
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Generate API token if not exists
            if (!$user->api_token) {
                $user->api_token = Str::random(80);
                $user->save();
            }
           //ثماء ارسلة الي لوحة تحكم 
            return $this->redirectUserDashboard($user);
        }
         // اذا فشل رجعة الي نفس الصفحة
        return back()->withErrors([
            'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectUserDashboard(Auth::user());
        }
        return view('register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|in:student,teacher,admin',
            // fields for sub-profiles
            'profile_number' => 'required|string|unique:students,student_number|unique:teachers,employee_number|unique:administrators,employee_number',
            'department' => 'nullable|string|max:100',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'api_token' => Str::random(80),
        ]);

        // Create specific profiles based on selected role
        if ($user->role === 'student') {
            Student::create([
                'user_id' => $user->id,
                'student_number' => $data['profile_number'],
                'department' => $data['department'] ?? 'General',
                'status' => 'active',
            ]);
        } elseif ($user->role === 'teacher') {
            Teacher::create([
                'user_id' => $user->id,
                'employee_number' => $data['profile_number'],
                'department' => $data['department'] ?? 'General',
            ]);
        } elseif ($user->role === 'admin') {
            Administrator::create([
                'user_id' => $user->id,
                'employee_number' => $data['profile_number'],
                'department' => $data['department'] ?? 'IT',
            ]);
        }

        Auth::login($user);

        return $this->redirectUserDashboard($user);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        
        // Simulating sending reset email by putting token in session
        $token = Str::random(64);
        session(['password_reset_email' => $request->email, 'password_reset_token' => $token]);

        return back()->with('status', 'لقد تم إرسال رابط إعادة تعيين كلمة المرور بنجاح. (للتجربة: اضغط على الرابط بالأسفل للذهاب مباشرة للصفحة)')
            ->with('demo_token', $token);
    }

    public function showResetPassword($token)
    {
        $storedToken = session('password_reset_token');
        if ($storedToken !== $token) {
            abort(403, 'الرمز منتهي الصلاحية أو غير صالح.');
        }
        return view('auth.reset-password', ['token' => $token, 'email' => session('password_reset_email')]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $storedToken = session('password_reset_token');
        if ($storedToken !== $request->token || session('password_reset_email') !== $request->email) {
            return back()->withErrors(['email' => 'الرمز غير صالح أو منتهي الصلاحية.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Clear session reset keys
        session()->forget(['password_reset_token', 'password_reset_email']);

        return redirect('/login')->with('success', 'تم إعادة تعيين كلمة المرور بنجاح. يمكنك الآن تسجيل الدخول.');
    }

    protected function redirectUserDashboard($user)
    {
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($user->role === 'teacher') {
            return redirect('/teacher/dashboard');
        } else {
            return redirect('/student/dashboard');
        }
    }
}
