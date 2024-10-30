<?php

namespace App\Http\Controllers;

use App\Models\KhachHang;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BanDoController
{
    public function index()
    {
        $khach_hang = KhachHang::query()->where('lat', '!=', null)->where('lng', '!=', null)->get();
        return Inertia::render('Map/Index', compact('khach_hang'));
    }
}
