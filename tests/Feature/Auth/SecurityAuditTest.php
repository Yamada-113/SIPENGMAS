<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class SecurityAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_forbidden_response_is_generic_and_recorded(): void
    {
        Route::get('/_security-audit-test/forbidden', fn () => response()->json([
            'message' => 'Internal authorization detail',
        ], 403));
        Log::spy();

        $response = $this->getJson('/_security-audit-test/forbidden')->assertForbidden();
        $incidentId = $response->json('incident_id');

        $this->assertMatchesRegularExpression('/^ERR-\d{8}-[A-Z0-9]{8}$/', $incidentId);
        $this->assertSame(
            'Akses ditolak. ID kejadian: '.$incidentId,
            $response->json('message'),
        );
        $this->assertStringNotContainsString('Internal authorization detail', $response->getContent());

        Log::shouldHaveReceived('notice')
            ->once()
            ->withArgs(function (string $message, array $context): bool {
                $this->assertSame('Security audit event', $message);
                $this->assertSame('access_denied', $context['action']);
                $this->assertSame('denied', $context['result']);
                $this->assertNotEmpty($context['ip']);

                return true;
            });
    }

    public function test_role_change_is_recorded_with_actor_and_target(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'username' => 'admin-npm',
            'role' => 'Admin',
        ]);
        $target = User::create([
            'name' => 'Dosen',
            'username' => 'target-npm',
            'role' => 'Dosen',
        ]);
        Log::spy();

        $this->actingAs($admin)
            ->put(route('admin.manajemen_user.update', $target), [
                'name' => $target->name,
                'username' => $target->username,
                'role' => 'Admin',
            ])
            ->assertRedirect();

        Log::shouldHaveReceived('notice')
            ->once()
            ->withArgs(function (string $message, array $context): bool {
                $this->assertSame('Security audit event', $message);
                $this->assertSame('admin-npm', $context['npm']);
                $this->assertSame('target-npm', $context['target_npm']);
                $this->assertSame('Dosen', $context['role_before']);
                $this->assertSame('Admin', $context['role_after']);
                $this->assertSame('role_changed', $context['action']);
                $this->assertSame('success', $context['result']);

                return true;
            });
    }

}
