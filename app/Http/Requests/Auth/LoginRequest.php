<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Services\SecurityAuditLogger;
use Illuminate\Validation\ValidationException;
<<<<<<< Updated upstream
use App\Models\User;
use Illuminate\Support\Facades\Log;
=======
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;
>>>>>>> Stashed changes

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

<<<<<<< Updated upstream
                throw ValidationException::withMessages([
                    'username' => "Akun terkunci. Coba lagi dalam {$remainingMinutes} menit.",
                ]);
=======
        try {
            $authenticated = Auth::attempt($credentials, $this->boolean('remember'));
        } catch (Throwable $exception) {
            app(SecurityAuditLogger::class)->record(
                $this,
                'login',
                'backend_error',
                $username,
            );

            throw new ServiceUnavailableHttpException(0, 'Authentication service unavailable', $exception);
        }

        if (! $authenticated) {
            RateLimiter::hit($throttleKey, self::DECAY_SECONDS);

            app(SecurityAuditLogger::class)->record(
                $this,
                'login',
                'failure',
                $username,
                ['attempt' => RateLimiter::attempts($throttleKey)],
            );

            if (RateLimiter::attempts($throttleKey) >= self::MAX_ATTEMPTS) {
                RateLimiter::hit($lockoutKey, self::DECAY_SECONDS);
>>>>>>> Stashed changes
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

<<<<<<< Updated upstream
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
=======
        app(SecurityAuditLogger::class)->record($this, 'login', 'success', $username);
>>>>>>> Stashed changes
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
