<?php

namespace App\Http\Requests\Admin;

use App\Services\YoutubeUrlParser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateVideoRequest extends FormRequest
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
            'youtube_url' => [
                'required',
                'url',
                function ($attribute, $value, $fail) {
                    if (! YoutubeUrlParser::isValidYoutubeUrl($value)) {
                        $fail('URL harus berupa tautan YouTube yang sah (domain youtube.com atau youtu.be).');
                    }
                },
            ],
            'youtube_id' => 'required|string|max:30',
            'title' => 'required|string|max:150',
            'thumbnail' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'thumbnail_url' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order' => 'required|integer|min:0',
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     */
    public function messages(): array
    {
        return [
            'youtube_url.required' => 'URL YouTube wajib diisi.',
            'youtube_url.url' => 'Format URL YouTube tidak valid.',
            'youtube_id.required' => 'YouTube ID wajib diisi.',
            'youtube_id.max' => 'YouTube ID maksimal 30 karakter.',
            'title.required' => 'Judul video wajib diisi.',
            'title.string' => 'Judul video harus berupa teks.',
            'title.max' => 'Judul video maksimal 150 karakter.',
            'thumbnail.file' => 'Thumbnail harus berupa file gambar yang valid.',
            'thumbnail.mimes' => 'Format thumbnail harus berupa JPG, JPEG, PNG, atau WebP.',
            'thumbnail.max' => 'Ukuran file thumbnail maksimal adalah 2MB.',
            'thumbnail_url.file' => 'Thumbnail harus berupa file gambar yang valid.',
            'thumbnail_url.mimes' => 'Format thumbnail harus berupa JPG, JPEG, PNG, atau WebP.',
            'thumbnail_url.max' => 'Ukuran file thumbnail maksimal adalah 2MB.',
            'sort_order.required' => 'Urutan tampil wajib diisi.',
            'sort_order.integer' => 'Urutan tampil harus berupa angka.',
            'sort_order.min' => 'Urutan tampil tidak boleh bernilai negatif.',
        ];
    }
}
