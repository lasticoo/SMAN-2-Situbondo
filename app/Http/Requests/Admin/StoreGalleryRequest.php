<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreGalleryRequest extends FormRequest
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
            'photo' => 'required_without:photo_url|nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'photo_url' => 'required_without:photo|nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'activity_name' => 'required|string|max:150',
            'activity_date' => 'required|date',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     */
    public function messages(): array
    {
        return [
            'photo.required_without' => 'Foto galeri wajib diisi.',
            'photo.file' => 'Foto galeri harus berupa file gambar yang valid.',
            'photo.mimes' => 'Format foto harus berupa JPG, JPEG, PNG, atau WebP.',
            'photo.max' => 'Ukuran file foto maksimal adalah 2MB.',
            'photo_url.required_without' => 'Foto galeri wajib diisi.',
            'photo_url.file' => 'Foto galeri harus berupa file gambar yang valid.',
            'photo_url.mimes' => 'Format foto harus berupa JPG, JPEG, PNG, atau WebP.',
            'photo_url.max' => 'Ukuran file foto maksimal adalah 2MB.',
            'activity_name.required' => 'Nama kegiatan wajib diisi.',
            'activity_name.string' => 'Nama kegiatan harus berupa teks.',
            'activity_name.max' => 'Nama kegiatan maksimal 150 karakter.',
            'activity_date.required' => 'Tanggal kegiatan wajib diisi.',
            'activity_date.date' => 'Format tanggal kegiatan tidak valid.',
            'sort_order.integer' => 'Urutan tampil harus berupa angka.',
            'sort_order.min' => 'Urutan tampil tidak boleh bernilai negatif.',
        ];
    }
}
