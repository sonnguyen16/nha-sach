<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DatHangRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => '',
            'ngay' => 'required|date',
            'khachhang_id' => 'required',
            'tongtien' => 'required',
            'vat' => 'required',
            'tongvat' => 'required',
            'ghichu' => '',
            'dathang_sanpham' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */

    public function messages(): array{
        return [
            'ngay.required' => 'Ngày không được để trống',
            'ngay.date' => 'Ngày không đúng định dạng',
            'khachhang_id.required' => 'Khách hàng không được để trống',
            'tongtien.required' => 'Tổng tiền không được để trống',
            'vat.required' => 'VAT không được để trống',
            'tongvat.required' => 'Tổng VAT không được để trống',
            'dathang_sanpham.required' => 'Danh sách sản phẩm không được để trống',
        ];
    }
}
