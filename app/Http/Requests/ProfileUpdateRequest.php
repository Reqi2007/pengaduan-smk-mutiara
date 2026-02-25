<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            // Aturan validasi tambahan untuk foto profil
            'avatar' => [
                'nullable', // Boleh kosong (tidak wajib diisi)
                'image',    // Harus berupa file gambar
                'mimes:jpeg,png,jpg,gif', // Ekstensi yang diizinkan
                'max:2048', // Ukuran maksimal 2048 KB (2 MB)
            ],
        ];
    }
}