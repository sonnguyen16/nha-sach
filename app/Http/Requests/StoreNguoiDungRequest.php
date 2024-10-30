<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNguoiDungRequest extends FormRequest
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
            'ID' => 'nullable|integer',
            'MaNguoiDung' => 'required|string|max:255',
            'MatKhau' => 'required_without:ID|max:255',
            'HoTen' => 'required|string|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */

    public function messages(): array{
        return [
            'MaNguoiDung.required' => 'Mã người dùng không được để trống',
            'MatKhau.required' => 'Mật khẩu không được để trống',
            'HoTen.required' => 'Họ tên không được để trống',
        ];
    }
}
