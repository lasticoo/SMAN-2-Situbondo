<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactMessageRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^\+?[0-9\s\-]+$/'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string'],
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama pengirim wajib diisi.',
            'name.max' => 'Nama pengirim maksimal 100 karakter.',
            'email.required' => 'Email pengirim wajib diisi.',
            'email.email' => 'Format email pengirim tidak valid.',
            'email.max' => 'Email pengirim maksimal 150 karakter.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka, spasi, tanda minus (-), dan awalan (+).',
            'subject.required' => 'Subjek pesan wajib diisi.',
            'subject.max' => 'Subjek pesan maksimal 150 karakter.',
            'message.required' => 'Isi pesan wajib diisi.',
        ];
    }
}
