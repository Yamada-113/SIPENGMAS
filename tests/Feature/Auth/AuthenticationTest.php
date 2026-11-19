<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\TestResponse;
use Mockery;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['session.driver' => 'database']);
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_ldap_login_regenerates_session_and_does_not_store_credentials(): void
    {
        $user = $this->makeLdapUser();
        $password = 'ldap-test-password';
        $cookieName = config('session.cookie');
        $initialCookie = $this->get('/login')->getCookie($cookieName)->getValue();
        $this->mockLdapProvider($user, $password);

        $response = $this->withCookie($cookieName, $initialCookie)->post('/login', [
            'username' => $user->username,
            'password' => $password,
        ]);

        $response->assertRedirect(route('dosen.dashboard'));
        $sessionCookie = $response->getCookie($cookieName, false);
        $newSessionId = $response->getCookie($cookieName)->getValue();
        $this->assertNotSame($initialCookie, $newSessionId);
        $this->assertTrue($sessionCookie->isHttpOnly());
        $this->assertSame('lax', $sessionCookie->getSameSite());
        $this->assertAuthenticatedAs($user);

        $sessionData = $this->app['session.store']->all();
        $this->assertSame(now()->timestamp, $sessionData['auth']['login_at']);
        $this->assertStringNotContainsString($password, serialize($sessionData));
        $this->assertStringNotContainsString($user->username, serialize($sessionData));
        $this->assertStringNotContainsString($user->name, serialize($sessionData));
        $this->assertStringNotContainsString($user->role, serialize($sessionData));
    }

    public function test_invalid_ldap_credentials_do_not_authenticate(): void
    {
        $this->mockLdapProvider(null);

        $this->post('/login', [
            'username' => 'npm-123',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_script_tag_in_user_name_is_rendered_as_text(): void
    {
        $user = User::create([
            'name' => '<script>alert(1)</script>',
            'username' => 'ldap-test-user',
            'role' => 'Dosen',
        ]);

        $response = $this->actingAs($user)->get('/profile');

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

        $logoutResponse = $this->withCookie($cookieName, $oldSessionCookie)->post('/logout');

        $logoutResponse->assertRedirect(route('login'));
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
    }
}
