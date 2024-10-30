<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNguoiDungRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NguoiDungController
{
    public function index(Request $request)
    {
        $nguoi_dung = User::query();
        if($request->filled('search')) {
            $nguoi_dung = User::where('MaNguoiDung', 'like', '%'.$request->search.'%')
                ->orWhere('HoTen', 'like', '%'.$request->search.'%');
        }
        $nguoi_dung = $nguoi_dung->paginate(20);
        return Inertia::render('NguoiDung/Index', compact('nguoi_dung'));
    }

    public function store(StoreNguoiDungRequest $request)
    {
        $validated = $request->validated();

        if($validated['MatKhau']) {
            $validated['MatKhau'] = md5($validated['MatKhau'].$validated['MaNguoiDung']);
        }else{
            unset($validated['MatKhau']);
        }
        $validated['MaLoaiNguoiDung'] = '';
        $validated['TrangThai'] = 'A';

        User::updateOrCreate([
            'ID' => $validated['ID']
        ], $validated);
    }
}
