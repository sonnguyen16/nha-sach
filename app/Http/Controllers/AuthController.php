<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AuthController
{
    public function index()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('bang-gia.index');
        }
        return Inertia::render('Auth/Login');
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        $validated['MatKhau'] = md5($validated['MatKhau'].$validated['MaNguoiDung']);

        $user = User::query()
            ->where('MaNguoiDung', $validated['MaNguoiDung'])
            ->where('MatKhau', $validated['MatKhau'])
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'MaNguoiDung' => 'Tài khoản hoặc mật khẩu không chính xác',
            ]);
        }

        Auth::loginUsingId($user->ID);

        $request->session()->regenerate();

        return redirect()->route('bang-gia.index');
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
