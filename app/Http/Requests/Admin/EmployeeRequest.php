<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
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
        $employeeId = $this->route('employee')
            ? ($this->route('employee')->id ?? $this->route('employee'))
            : null;

        return [
            'name' => 'required|string|max:150',
            'nip' => [
                'required',
                'string',
                'max:30',
                Rule::unique('employees', 'nip')->ignore($employeeId),
            ],
            'position' => 'required|string|max:100',
            'extra_info' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'photo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama pegawai wajib diisi.',
            'name.max' => 'Nama pegawai maksimal 150 karakter.',
            'nip.required' => 'NIP pegawai wajib diisi.',
            'nip.max' => 'NIP pegawai maksimal 30 karakter.',
            'nip.unique' => 'NIP sudah terdaftar pada sistem (NIP harus unik).',
            'position.required' => 'Jabatan/Posisi pegawai wajib diisi.',
            'position.max' => 'Jabatan/Posisi maksimal 100 karakter.',
            'photo.file' => 'Upload foto harus berupa file gambar.',
            'photo.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WebP.',
            'photo.max' => 'Ukuran foto maksimal 2MB.',
        ];
    }
}
