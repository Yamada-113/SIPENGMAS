<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
<<<<<<< Updated upstream
=======
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Testing\TestResponse;
use Mockery;
>>>>>>> Stashed changes
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

<<<<<<< Updated upstream
        $this->assertGuest();
        $response->assertRedirect('/');
=======
        $response
            ->assertOk()
            ->assertSee('value="&lt;script&gt;alert(1)&lt;/script&gt;"', false)
            ->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_logout_expires_cookie_and_rejects_the_old_session_cookie(): void
    {
        $user = $this->makeLdapUser();
        $loginResponse = $this->loginAs($user);
        $cookieName = config('session.cookie');
        $oldSessionCookie = $loginResponse->getCookie($cookieName)->getValue();

        Log::spy();
        $logoutResponse = $this->withCookie($cookieName, $oldSessionCookie)->post('/logout');

        $logoutResponse->assertRedirect(route('login'));
        Log::shouldHaveReceived('notice')
            ->once()
            ->withArgs(function (string $message, array $context) use ($user): bool {
                $this->assertSame('Security audit event', $message);
                $this->assertSame($user->username, $context['npm']);
                $this->assertSame('logout', $context['action']);
                $this->assertSame('success', $context['result']);

                return true;
            });
        $this->assertLessThanOrEqual(
            now()->timestamp,
            $logoutResponse->getCookie($cookieName, false)->getExpiresTime()
        );

        $this->withCookie($cookieName, $oldSessionCookie)
            ->get('/profile')
            ->assertRedirect(route('login'));
    }

    public function test_absolute_session_lifetime_expires_after_twelve_hours(): void
    {
        $loginResponse = $this->loginAs($this->makeLdapUser());
        $cookieName = config('session.cookie');
        $sessionCookie = $loginResponse->getCookie($cookieName)->getValue();

        $this->travel(12)->hours();

        $response = $this->withCookie($cookieName, $sessionCookie)
            ->get('/profile')
            ->assertRedirect(route('login'));
        $this->assertLessThanOrEqual(
            now()->timestamp,
            $response->getCookie($cookieName, false)->getExpiresTime()
        );
    }

    private function loginAs(User $user): TestResponse
    {
        $password = 'ldap-test-password';
        $cookieName = config('session.cookie');
        $initialCookie = $this->get('/login')->getCookie($cookieName)->getValue();
        $this->mockLdapProvider($user, $password);

        return $this->withCookie($cookieName, $initialCookie)->post('/login', [
            'username' => $user->username,
            'password' => $password,
        ]);
    }

    private function makeLdapUser(): User
    {
        return User::create([
            'name' => 'LDAP Test User',
            'username' => 'ldap-test-user',
            'role' => 'Dosen',
        ]);
    }

    private function mockLdapProvider(?User $user, string $password = 'wrong-password'): void
    {
        $ldapProvider = Mockery::mock(UserProvider::class);
        $ldapProvider
            ->shouldReceive('retrieveByCredentials')
            ->once()
            ->andReturn($user);

        if ($user !== null) {
            $ldapProvider
                ->shouldReceive('validateCredentials')
                ->once()
                ->with($user, Mockery::on(
                    fn (array $credentials): bool => $credentials['password'] === $password
                ))
                ->andReturnTrue();
            $ldapProvider
                ->shouldReceive('rehashPasswordIfRequired')
                ->once()
                ->andReturnNull();
        }

        Auth::guard('web')->setProvider($ldapProvider);
>>>>>>> Stashed changes
    }
}
