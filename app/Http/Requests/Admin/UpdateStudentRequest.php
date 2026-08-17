<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check();
    }

    public function rules(): array
    {
        $studentId = $this->route('student')
            ? ($this->route('student')->id ?? $this->route('student'))
            : null;

        return [
            'nisn' => [
                'required',
                'string',
                'max:20',
                Rule::unique('students', 'nisn')->ignore($studentId),
            ],
            'name' => 'required|string|max:150',
            'class' => 'required|string|max:20',
            'extra_info' => 'nullable|string',
            'is_public' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nisn.required' => 'NISN siswa wajib diisi.',
            'nisn.max' => 'NISN siswa maksimal 20 karakter.',
            'nisn.unique' => 'NISN sudah terdaftar pada sistem (NISN harus unik).',
            'name.required' => 'Nama siswa wajib diisi.',
            'name.max' => 'Nama siswa maksimal 150 karakter.',
            'class.required' => 'Kelas siswa wajib diisi.',
            'class.max' => 'Kelas siswa maksimal 20 karakter.',
        ];
    }
}
