<?php

namespace App\Http\Controllers;

use App\Models\BangGia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class BangGiaController
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $bang_gia = DB::table('HangDonViTinh as h')
            ->select([
                'h.id',
                'h.dvt_id',
                DB::raw('ROW_NUMBER() OVER(ORDER BY h.mahang, h.tenhang, h.dvt ASC) as stt'),
                'l.tenloaihang',
                'h.mahang',
                'h.code',
                'h.tenhang',
                'h.dvt',
                'h.GIANHAP',
                'b.gia',
                'b.chietkhau',
                'b.ghichu',
                'b.trangthai',
                'b.ngaysua',
                'n.hoten'
            ])
            ->leftJoin(DB::raw('(SELECT * FROM BANGGIA WHERE loai = 1) as b'), function ($join) {
                $join->on('h.id', '=', 'b.hang_id')
                    ->on('h.dvt_id', '=', 'b.dvt_id');
            })
            ->leftJoin('loaihang as l', 'l.id', '=', 'h.LoaiHang_Id')
            ->leftJoin('NguoiDung as n', 'b.nguoisua', '=', 'n.id')
            ->where('h.id', '>', 0)
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('h.tenhang', 'like', "%{$search}%")
                        ->orWhere('h.mahang', 'like', "%{$search}%");
                });
            })
            ->paginate(20);

        return Inertia::render('BangGia/Index', compact('bang_gia'));
    }

    public function findHangByMaOrTen(Request $request){
        $search = $request->input('search');
        $hang = DB::table('HangDonViTinh as h')
            ->select([
                'h.id',
                'h.dvt_id',
                'l.tenloaihang',
                'h.mahang',
                'h.code',
                'h.tenhang',
                'h.dvt',
                'h.GIANHAP',
                'b.gia',
                'b.chietkhau',
                'b.ghichu',
                'b.trangthai',
                'b.ngaysua',
                'n.hoten'
            ])
            ->leftJoin(DB::raw('(SELECT * FROM BANGGIA WHERE loai = 1) as b'), function ($join) {
                $join->on('h.id', '=', 'b.hang_id')
                    ->on('h.dvt_id', '=', 'b.dvt_id');
            })
            ->leftJoin('loaihang as l', 'l.id', '=', 'h.LoaiHang_Id')
            ->leftJoin('NguoiDung as n', 'b.nguoisua', '=', 'n.id')
            ->where('h.id', '>', 0)
            ->where(function ($query) use ($search) {
                $query->where('h.tenhang', 'like', "%{$search}%")
                    ->orWhere('h.mahang', 'like', "%{$search}%");
            })
            ->get();

        return response()->json($hang);
    }
}
