<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAnnouncementRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'category' => ['required', 'string', 'max:100'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'thumbnail_url' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'summary' => ['required', 'string', 'max:300'],
            'content' => ['required', 'string'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'published_at' => ['required', 'date'],
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul pengumuman wajib diisi.',
            'title.max' => 'Judul pengumuman maksimal 200 karakter.',
            'category.required' => 'Kategori pengumuman wajib diisi.',
            'category.max' => 'Kategori pengumuman maksimal 100 karakter.',
            'thumbnail.image' => 'File thumbnail harus berupa gambar.',
            'thumbnail.mimes' => 'Format thumbnail harus JPG, JPEG, PNG, atau WEBP.',
            'thumbnail.max' => 'Ukuran file thumbnail maksimal 5MB.',
            'thumbnail_url.image' => 'File thumbnail harus berupa gambar.',
            'thumbnail_url.mimes' => 'Format thumbnail harus JPG, JPEG, PNG, atau WEBP.',
            'thumbnail_url.max' => 'Ukuran file thumbnail maksimal 5MB.',
            'summary.required' => 'Ringkasan pengumuman wajib diisi.',
            'summary.max' => 'Ringkasan pengumuman maksimal 300 karakter.',
            'content.required' => 'Konten pengumuman wajib diisi.',
            'status.required' => 'Status pengumuman wajib dipilih.',
            'status.in' => 'Status pengumuman meharus berupa draft atau published.',
            'published_at.required' => 'Waktu publikasi pengumuman wajib diisi.',
            'published_at.date' => 'Waktu publikasi harus berupa tanggal dan jam yang valid.',
        ];
    }
}
