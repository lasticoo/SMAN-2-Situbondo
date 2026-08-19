<?php

namespace App\Http\Requests\Admin;

use App\Rules\HexColor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateColorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'primary_color' => ['required', 'string', 'max:50', new HexColor],
            'secondary_color' => ['required', 'string', 'max:50', new HexColor],
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     */
    public function messages(): array
    {
        return [
            'primary_color.required' => 'Warna utama (Primary Color) wajib diisi.',
            'primary_color.string' => 'Kode warna utama harus berupa teks.',
            'primary_color.max' => 'Kode warna utama maksimal 50 karakter.',
            'secondary_color.required' => 'Warna sekunder (Secondary Color) wajib diisi.',
            'secondary_color.string' => 'Kode warna sekunder harus berupa teks.',
            'secondary_color.max' => 'Kode warna sekunder maksimal 50 karakter.',
        ];
    }
}
