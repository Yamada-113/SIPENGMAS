<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Services\SecurityAuditLogger;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class LoginRequest extends FormRequest
{
    private const MAX_ATTEMPTS = 5;

    private const DECAY_SECONDS = 600;

    private const INVALID_CREDENTIALS_MESSAGE = 'Username atau password salah';

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
        $password = $this->input('password');
        $throttleKey = $this->throttleKey();
        $lockoutKey = 'login-lockout:' . $throttleKey;

        if (
            RateLimiter::tooManyAttempts($lockoutKey, 1) ||
            RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)
        ) {
            throw new TooManyRequestsHttpException(
                max(
                    RateLimiter::availableIn($lockoutKey),
                    RateLimiter::availableIn($throttleKey)
                ),
                'Terlalu banyak percobaan login. Akun dikunci selama 10 menit.'
            );
        }

        $credentials = [
            'cn' => $username,
            'password' => $password,
            'fallback' => [
                'username' => $username,
                'password' => $password,
            ],
        ];

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
            }

            throw ValidationException::withMessages([
                'username' => self::INVALID_CREDENTIALS_MESSAGE,
            ]);
        }

        RateLimiter::clear($throttleKey);
        RateLimiter::clear($lockoutKey);

        app(SecurityAuditLogger::class)->record($this, 'login', 'success', $username);
    }

    public function throttleKey(): string
    {
        return hash('sha256', Str::transliterate(Str::lower($this->string('username')) . '|' . $this->ip()));
    }
}
