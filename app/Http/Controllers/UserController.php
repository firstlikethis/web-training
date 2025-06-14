<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * แสดงรายการผู้ใช้ทั้งหมด
     */
    public function index()
    {
        $users = User::orderBy('name')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * แสดงหน้าสร้างผู้ใช้ใหม่
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * บันทึกข้อมูลผู้ใช้ใหม่
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user',
            'is_active' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'สร้างผู้ใช้ ' . $user->name . ' เรียบร้อยแล้ว');
    }

    /**
     * แสดงหน้าแก้ไขข้อมูลผู้ใช้
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * อัปเดตข้อมูลผู้ใช้
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => 'required|in:admin,user',
            'is_active' => 'boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'อัปเดตข้อมูลผู้ใช้ ' . $user->name . ' เรียบร้อยแล้ว');
    }

    /**
     * ลบผู้ใช้
     */
    public function destroy(User $user)
    {
        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'ลบผู้ใช้ ' . $userName . ' เรียบร้อยแล้ว');
    }

    /**
     * รีเซ็ตรหัสผ่านผู้ใช้
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.edit', $user)
            ->with('success', 'รีเซ็ตรหัสผ่านของ ' . $user->name . ' เรียบร้อยแล้ว');
    }
}