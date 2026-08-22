<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateSpmbInfoRequest extends FormRequest
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
            'banner_url' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'schedule_info' => 'required|string',
            'requirements_info' => 'required|string',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     */
    public function messages(): array
    {
        return [
            'banner_url.file' => 'Upload banner harus berupa file gambar.',
            'banner_url.mimes' => 'Format banner harus JPG, JPEG, PNG, atau WebP.',
            'banner_url.max' => 'Ukuran file banner maksimal 2MB.',
            'schedule_info.required' => 'Informasi jadwal SPMB wajib diisi.',
            'requirements_info.required' => 'Informasi persyaratan SPMB wajib diisi.',
            'period_start.required' => 'Tanggal periode mulai wajib diisi.',
            'period_start.date' => 'Format tanggal periode mulai tidak valid.',
            'period_end.required' => 'Tanggal periode berakhir wajib diisi.',
            'period_end.date' => 'Format tanggal periode berakhir tidak valid.',
            'period_end.after_or_equal' => 'Tanggal periode berakhir harus sama atau setelah tanggal periode mulai.',
        ];
    }
}
