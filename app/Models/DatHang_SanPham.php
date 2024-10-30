<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatHang_SanPham extends Model
{
    use HasFactory;

    protected $table = "DatHang_SanPham";
    public $timestamps = false;

    protected $fillable = [
       'dathang_id',
       'hang_id',
       'dvt_id',
       'soluong',
       'dongia',
       'chietkhau',
       'thanhtien'
    ];

    public function hang()
    {
        return $this->belongsTo(Hang::class, 'hang_id', 'ID');
    }

    public function don_vi_tinh()
    {
        return $this->belongsTo(DonViTinh::class, 'dvt_id', 'ID');
    }


}
