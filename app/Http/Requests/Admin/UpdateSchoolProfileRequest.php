<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateSchoolProfileRequest extends FormRequest
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
            'vision' => 'required|string',
            'mission' => 'required|string',
            'goals' => 'required|string',
            'history' => 'required|string',
            'about_us' => 'required|string',
            'structure_image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     */
    public function messages(): array
    {
        return [
            'vision.required' => 'Visi sekolah wajib diisi.',
            'vision.string' => 'Visi sekolah harus berupa teks.',
            'mission.required' => 'Misi sekolah wajib diisi.',
            'mission.string' => 'Misi sekolah harus berupa teks.',
            'goals.required' => 'Tujuan sekolah wajib diisi.',
            'goals.string' => 'Tujuan sekolah harus berupa teks.',
            'history.required' => 'Sejarah sekolah wajib diisi.',
            'history.string' => 'Sejarah sekolah harus berupa teks.',
            'about_us.required' => 'Informasi tentang sekolah wajib diisi.',
            'about_us.string' => 'Informasi tentang sekolah harus berupa teks.',
            'structure_image.file' => 'Upload gambar struktur harus berupa file.',
            'structure_image.mimes' => 'Format gambar struktur harus JPG, JPEG, PNG, atau WebP.',
            'structure_image.max' => 'Ukuran gambar struktur maksimal 2MB.',
        ];
    }
}
