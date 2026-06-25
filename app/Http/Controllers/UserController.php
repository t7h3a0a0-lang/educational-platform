<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Administrator;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['student', 'teacher', 'admin', 'permissions'])->get();
        $permissions = Permission::all();
        return view('admin.users.index', compact('users', 'permissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:student,teacher,admin',
            'profile_number' => 'required|string|unique:students,student_number|unique:teachers,employee_number|unique:administrators,employee_number',
            'department' => 'nullable|string|max:100',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        if ($user->role === 'student') {
            Student::create([
                'user_id' => $user->id,
                'student_number' => $data['profile_number'],
                'department' => $data['department'] ?? 'General',
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

        return redirect()->back()->with('success', 'تم إنشاء المستخدم والملف الشخصي المرتبط به بنجاح.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:student,teacher,admin',
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ]);

        // Sync or update permissions if requested
        if ($request->has('permissions')) {
            $user->permissions()->sync($request->input('permissions'));
        } else {
            $user->permissions()->detach();
        }

        return redirect()->back()->with('success', 'تم تحديث بيانات المستخدم بنجاح.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Remove linked profile explicitly (optional if cascade is set, but good practice)
        if ($user->role === 'student' && $user->student) {
            $user->student->delete();
        } elseif ($user->role === 'teacher' && $user->teacher) {
            $user->teacher->delete();
        } elseif ($user->role === 'admin' && $user->admin) {
            $user->admin->delete();
        }

        $user->delete();
        return redirect()->back()->with('success', 'تم حذف المستخدم وملفه الشخصي بنجاح.');
    }

    public function updatePermissions(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $user->permissions()->sync($request->input('permissions', []));

        return redirect()->back()->with('success', 'تم تحديث صلاحيات المستخدم بنجاح.');
    }
}
