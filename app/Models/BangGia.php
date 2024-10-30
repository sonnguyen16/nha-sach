<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BangGia extends Model
{
    use HasFactory;

    protected $table = 'BANGGIA';

    public function don_vi_tinh()
    {
        return $this->belongsTo(DonViTinh::class, 'dvt_id', 'ID');
    }

    public function hang()
    {
        return $this->belongsTo(Hang::class, 'hang_id', 'ID');
    }
}
