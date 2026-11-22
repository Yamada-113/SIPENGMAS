<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_central_dashboard_redirects_admin_to_admin_dashboard(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'role' => 'Admin',
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_central_dashboard_redirects_dosen_to_dosen_dashboard(): void
    {
        $user = User::create([
            'name' => 'Dosen',
            'role' => 'Dosen',
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertRedirect(route('dosen.dashboard'));
    }

    public function test_central_dashboard_rejects_an_unknown_role(): void
    {
        $user = User::create([
            'name' => 'Role Tidak Dikenal',
            'role' => 'Role Tidak Dikenal',
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertForbidden();
    }

    public function test_dashboard_rejects_an_authenticated_user_with_a_different_role(): void
    {
        $user = User::create([
            'name' => 'Dosen Pengusul',
            'role' => 'Dosen',
        ]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_dashboard_redirects_a_guest_to_login(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_dashboard_allows_the_matching_role(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'role' => 'Admin',
        ]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }
}
