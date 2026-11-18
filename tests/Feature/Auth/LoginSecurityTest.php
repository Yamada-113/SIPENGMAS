<?php

namespace Tests\Feature\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class LoginSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_sixth_login_attempt_is_rate_limited_before_ldap_authentication(): void
    {
        $ldapProvider = Mockery::mock(UserProvider::class);
        $ldapProvider
            ->shouldReceive('retrieveByCredentials')
            ->times(9)
            ->andReturnUsing(function (array $credentials): null {
                if ($credentials['cn'] === 'ldap-backend-error') {
                    throw new \RuntimeException('LDAP diagnostic includes backend-test-password');
                }

                return null;
            });
        Auth::guard()->setProvider($ldapProvider);

        $originalIp = '192.0.2.10';
        $this->withServerVariables(['REMOTE_ADDR' => $originalIp]);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->postJson('/login', [
                'username' => 'npm-123',
                'password' => 'test-password',
            ])
                ->assertUnprocessable()
                ->assertJsonPath('errors.username.0', 'Username atau password salah');
        }

        $this->postJson('/login', [
            'username' => 'npm-123',
            'password' => 'test-password',
        ])->assertStatus(429);

        $this->postJson('/login', [
            'username' => 'npm-456',
            'password' => 'test-password',
        ])->assertUnprocessable();

        $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.11']);
        $this->postJson('/login', [
            'username' => 'npm-123',
            'password' => 'test-password',
        ])->assertUnprocessable();

        $this->travel(9)->minutes();
        $this->withServerVariables(['REMOTE_ADDR' => $originalIp]);
        $this->postJson('/login', [
            'username' => 'npm-123',
            'password' => 'test-password',
        ])->assertStatus(429);

        $this->travel(2)->minutes();
        $this->postJson('/login', [
            'username' => 'npm-123',
            'password' => 'test-password',
        ])->assertUnprocessable();

        Log::spy();
        $this->postJson('/login', [
            'username' => 'ldap-backend-error',
            'password' => 'backend-test-password',
        ])
            ->assertUnprocessable()
            ->assertJsonPath('errors.username.0', 'Username atau password salah');

        Log::shouldHaveReceived('error')
            ->once()
            ->withArgs(function (string $message, array $context): bool {
                $this->assertSame('Login authentication backend error', $message);
                $this->assertSame('LDAP diagnostic includes [REDACTED]', $context['details']);
                $this->assertStringNotContainsString('backend-test-password', json_encode([$message, $context]));

                return true;
            });
    }

    public function test_successful_login_does_not_log_the_password(): void
    {
        $user = User::create([
            'name' => 'LDAP Test User',
            'username' => 'ldap-test-user',
        ]);
        $password = 'test-password-that-must-not-be-logged';

        $ldapProvider = Mockery::mock(UserProvider::class);
        $ldapProvider
            ->shouldReceive('retrieveByCredentials')
            ->once()
            ->andReturn($user);
        $ldapProvider
            ->shouldReceive('validateCredentials')
            ->once()
            ->with($user, Mockery::on(fn (array $credentials): bool => $credentials['password'] === $password))
            ->andReturnTrue();
        $ldapProvider
            ->shouldReceive('rehashPasswordIfRequired')
            ->once()
            ->andReturnNull();
        Auth::guard()->setProvider($ldapProvider);
        Log::spy();

        $request = LoginRequest::create('/login', 'POST', [
            'username' => $user->username,
            'password' => $password,
        ]);
        $request->authenticate();

        Log::shouldHaveReceived('info')
            ->once()
            ->withArgs(function (string $message, array $context) use ($password): bool {
                $this->assertArrayNotHasKey('password', $context);
                $this->assertStringNotContainsString($password, $message . json_encode($context));

                return true;
            });
    }
}
