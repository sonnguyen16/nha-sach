<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatHangRequest;
use App\Models\DatHang;
use App\Models\DatHang_SanPham;
use App\Models\DonViTinh;
use App\Models\Hang;
use App\Models\KhachHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DatHangController
{
    public function index(Request $request)
    {
        $dat_hang = DatHang::query();
        $don_vi_tinh = DonViTinh::all();
        $khach_hang = KhachHang::all();
        if($request->filled('search')) {
            $search = $request->input('search');
            $dat_hang = $dat_hang->whereHas('khach_hang', function($query) use ($search) {
                $query->where('TenKhachHang', 'like', "%$search%")
                    ->orWhere('MaKhachHang', 'like', "%$search%");
            })->orWhereHas('dathang_sanpham', function($query) use ($search) {
                $query->whereHas('hang', function($query) use ($search) {
                    $query->where('TenHang', 'like', "%$search%")
                        ->orWhere('MaHang', 'like', "%$search%");
                });
            });
        }
        $dat_hang = $dat_hang->with('khach_hang', 'dathang_sanpham.hang', 'dathang_sanpham.don_vi_tinh')->paginate(20);
        return Inertia::render('DatHang/Index', compact('dat_hang', 'don_vi_tinh', 'khach_hang'));
    }

    public function store(DatHangRequest $request)
    {
        $validated = $request->validated();
        unset($validated['dathang_sanpham']);
        $validated['nguoi_tao'] = Auth::id();
        $validated['nguoi_sua'] = Auth::id();
        $dathang = DatHang::updateOrCreate([
            'id' => $validated['id']
        ], $validated);

        $dathang->dathang_sanpham()->delete();

        foreach($request->dathang_sanpham as $item) {
            unset($item['dvt']);
            unset($item['tenhang']);
            $dathang->dathang_sanpham()->updateOrCreate([
                'hang_id' => $item['hang_id'],
            ], $item);
        }
    }

    public function delete(Request $request)
    {
        $dathang_sanpham = DatHang_SanPham::find($request->id);
        $dathang_sanpham->delete();
    }
}
