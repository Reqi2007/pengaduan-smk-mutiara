<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'], // Validasi menggunakan 'name' (Username)
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // 1. Cek apakah kombinasi Name dan Password benar
        if (! Auth::attempt($this->only('name', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'name' => trans('auth.failed'),
            ]);
        }

        // 2. CEK STATUS AKTIF: Jika password benar tapi akun dinonaktifkan
        if (! Auth::user()->is_active) {
            
            // Paksa akun tersebut logout kembali secara instan
            Auth::logout(); 

            // Munculkan pesan error di halaman login
            throw ValidationException::withMessages([
                'name' => 'Akun Anda telah dinonaktifkan. Silakan hubungi Admin Sekolah.',
            ]);
        }

        // 3. Jika aman, bersihkan catatan rate limiter
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        // [BUG FIX]: Variabel diubah dari 'email' menjadi 'name'
        throw ValidationException::withMessages([
            'name' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        // [BUG FIX]: Pembacaan string diubah dari 'email' menjadi 'name'
        return Str::transliterate(Str::lower($this->string('name')).'|'.$this->ip());
    }
}