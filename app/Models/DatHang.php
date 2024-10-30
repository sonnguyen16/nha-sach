<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatHang extends Model
{
    use HasFactory;

    protected $table = 'DatHang';

    public const CREATED_AT = 'ngaytao';
    public const UPDATED_AT = 'ngaysua';

    protected $fillable = [
        'ngay',
        'khachhang_id',
        'tongtien',
        'vat',
        'tongvat',
        'ghichu',
        'nguoitao',
        'nguoisua',
    ];

    public function khach_hang()
    {
        return $this->belongsTo(KhachHang::class, 'khachhang_id', 'ID');
    }

    public function dathang_sanpham()
    {
        return $this->hasMany(DatHang_SanPham::class, 'dathang_id', 'id');
    }
}
