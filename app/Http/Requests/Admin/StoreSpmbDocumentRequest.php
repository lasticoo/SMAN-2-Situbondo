<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSpmbDocumentRequest extends FormRequest
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
            'title' => 'required|string|max:150',
            'file_url' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul dokumen wajib diisi.',
            'title.max' => 'Judul dokumen maksimal 150 karakter.',
            'file_url.required' => 'File dokumen wajib diunggah.',
            'file_url.file' => 'Upload dokumen harus berupa file valid.',
            'file_url.mimes' => 'Format file dokumen harus PDF, DOC, atau DOCX.',
            'file_url.max' => 'Ukuran file dokumen maksimal 10MB.',
        ];
    }
}
