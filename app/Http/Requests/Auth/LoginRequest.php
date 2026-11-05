<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class LoginRequest extends FormRequest
{
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
            'username' => ['required', 'string'],
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
            $username = $this->input('username');
            $user = User::where('username', $username)->first();

            if (
                $user &&
                $user->locked_until &&
                now()->lt($user->locked_until)
            ) {

                $remainingMinutes = ceil(
                    now()->diffInSeconds($user->locked_until) / 60
                );

                throw ValidationException::withMessages([
                    'username' => "Akun terkunci. Coba lagi dalam {$remainingMinutes} menit.",
                ]);
            }

            $credentials = [
                'cn' => $username,
                'password' => $this->input('password'),
                'fallback' => [
                    'username' => $username,
                    'password' => $this->input('password'),
                ]
            ];

            if (! Auth::attempt($credentials, $this->boolean('remember'))) {
                if ($user) {

                    $user->increment('failed_login_attempts');

                    Log::warning('Failed login', [
                        'username' => $username,
                        'attempts' => $user->failed_login_attempts,
                        'ip' => $this->ip(),
                    ]);

                if ($user->failed_login_attempts >= 5) {

                    $user->update([
                        'locked_until' => now()->addMinutes(10),
                    ]);
                    Log::critical('Account locked after 5 failed attempts', [
                        'username' => $username,
                        'ip' => $this->ip(),
                    ]);
                    throw ValidationException::withMessages([
                        'username' => 'Akun terkunci selama 10 menit karena 5 kali gagal login.',
                    ]);
                }

                }

        throw ValidationException::withMessages([
            'username' => 'Username atau Password salah. Silakan coba lagi.',
        ]);
    }

    // reset kalau login berhasil
    if ($user) {
        $user->update([
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ]);
    }

    Log::info('Login success', [
        'username' => $username,
        'ip' => $this->ip(),
    ]);
}

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('username')) . '|' . $this->ip());
    }
}
