<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BannerRequest extends FormRequest
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
        // Saat update (PUT/PATCH), gambar tidak wajib diisi
        $imageRule = $this->isMethod('post')
            ? 'required|file|mimes:jpg,jpeg,png,webp|max:2048'
            : 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048';

        return [
            'title'      => 'nullable|string|max:150',
            'description'=> 'nullable|string',
            'image'      => $imageRule,
            'is_active'  => 'boolean',
            'sort_order' => 'required|integer|min:0',
        ];
    }

    /**
     * Pesan validasi dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'title.required'      => 'Judul banner wajib diisi.',
            'title.max'           => 'Judul banner maksimal 150 karakter.',
            'image.required'      => 'Gambar banner wajib diupload.',
            'image.file'          => 'Upload harus berupa file gambar.',
            'image.mimes'         => 'Format gambar harus JPG, JPEG, PNG, atau WebP.',
            'image.max'           => 'Ukuran gambar maksimal 2MB.',
            'sort_order.required' => 'Urutan tampil wajib diisi.',
            'sort_order.integer'  => 'Urutan tampil harus berupa angka.',
            'sort_order.min'      => 'Urutan tampil minimal 0.',
        ];
    }
}
