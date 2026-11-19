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
            ->times(8)
            ->andReturn(null);
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

    }

    public function test_ldap_backend_error_shows_generic_message_and_incident_id(): void
    {
        $ldapProvider = Mockery::mock(UserProvider::class);
        $ldapProvider
            ->shouldReceive('retrieveByCredentials')
            ->once()
            ->andThrow(new \RuntimeException('LDAP diagnostic response must not be shown'));
        Auth::guard()->setProvider($ldapProvider);
        Log::spy();

        $response = $this->postJson('/login', [
            'username' => 'npm-123',
            'password' => 'test-password',
        ])->assertStatus(503);

        $incidentId = $response->json('incident_id');
        $this->assertMatchesRegularExpression('/^ERR-\d{8}-[A-Z0-9]{8}$/', $incidentId);
        $this->assertSame(
            'Terjadi kesalahan. Silakan coba lagi. ID kejadian: ' . $incidentId,
            $response->json('message'),
        );
        $this->assertStringNotContainsString('LDAP diagnostic response', $response->getContent());
        $this->assertGuest();

        Log::shouldHaveReceived('error')
            ->once()
            ->withArgs(function (string $message, array $context) use ($incidentId): bool {
                $this->assertSame('Application request failed', $message);
                $this->assertSame($incidentId, $context['incident_id']);
                $this->assertStringNotContainsString('LDAP diagnostic response', json_encode($context));

                return true;
            });
    }

    public function test_failed_login_is_audited_with_npm_without_password(): void
    {
        $ldapProvider = Mockery::mock(UserProvider::class);
        $ldapProvider
            ->shouldReceive('retrieveByCredentials')
            ->once()
            ->andReturn(null);
        Auth::guard()->setProvider($ldapProvider);
        Log::spy();
        $password = 'wrong-password-that-must-not-be-logged';

        $this->postJson('/login', [
            'username' => 'npm-123',
            'password' => $password,
        ])->assertUnprocessable();

        Log::shouldHaveReceived('notice')
            ->once()
            ->withArgs(function (string $message, array $context) use ($password): bool {
                $this->assertSame('Security audit event', $message);
                $this->assertSame('npm-123', $context['npm']);
                $this->assertSame('login', $context['action']);
                $this->assertSame('failure', $context['result']);
                $this->assertNotEmpty($context['occurred_at']);
                $this->assertNotEmpty($context['ip']);
                $this->assertStringNotContainsString($password, json_encode([$message, $context]));

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

        Log::shouldHaveReceived('notice')
            ->once()
            ->withArgs(function (string $message, array $context) use ($password): bool {
                $this->assertSame('Security audit event', $message);
                $this->assertArrayNotHasKey('password', $context);
                $this->assertStringNotContainsString($password, $message . json_encode($context));

                return true;
            });
    }
}
