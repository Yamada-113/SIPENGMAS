<?php

namespace Tests\Feature;

use App\Models\Skala;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkalaCrudTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role): User
    {
        return User::create([
            'name' => $role,
            'role' => $role,
        ]);
    }

    public function test_admin_can_open_page_and_add_skala(): void
    {
        $admin = $this->user('Admin');

        $this->actingAs($admin)
            ->get(route('admin.customization.skema'))
            ->assertOk();

        $this->actingAs($admin)
            ->post(route('admin.customization.skala.store'), ['nama' => 'Skala Baru'])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('skalas', [
            'nama' => 'Skala Baru',
            'is_active' => true,
        ]);
    }

    public function test_empty_and_duplicate_skala_names_are_rejected(): void
    {
        $admin = $this->user('Admin');
        Skala::create(['nama' => 'Fakultas', 'is_active' => true]);

        $this->actingAs($admin)
            ->from(route('admin.customization.skema'))
            ->post(route('admin.customization.skala.store'), ['nama' => ''])
            ->assertSessionHasErrorsIn('storeSkala', 'nama');

        $this->actingAs($admin)
            ->from(route('admin.customization.skema'))
            ->post(route('admin.customization.skala.store'), ['nama' => 'Fakultas'])
            ->assertSessionHasErrorsIn('storeSkala', 'nama');

        $this->assertSame(1, Skala::where('nama', 'Fakultas')->count());
    }

    public function test_admin_can_rename_and_deactivate_skala(): void
    {
        $admin = $this->user('Admin');
        $skala = Skala::create(['nama' => 'Prodi', 'is_active' => true]);

        $this->actingAs($admin)
            ->put(route('admin.customization.skala.update', $skala->id), [
                'nama' => 'Prodi Utama',
                'is_active' => 0,
            ])
            ->assertSessionHas('success');

        $skala->refresh();
        $this->assertSame('Prodi Utama', $skala->nama);
        $this->assertFalse($skala->is_active);
    }

    public function test_duplicate_name_is_rejected_when_updating(): void
    {
        $admin = $this->user('Admin');
        $prodi = Skala::create(['nama' => 'Prodi', 'is_active' => true]);
        Skala::create(['nama' => 'Pusat', 'is_active' => true]);

        $this->actingAs($admin)
            ->put(route('admin.customization.skala.update', $prodi->id), [
                'nama' => 'Pusat',
                'is_active' => 1,
            ])
            ->assertSessionHasErrorsIn('updateSkala', 'nama');

        $this->assertSame('Prodi', $prodi->refresh()->nama);
    }

    public function test_non_admin_is_forbidden_and_data_does_not_change(): void
    {
        $dosen = $this->user('Dosen');

        $this->actingAs($dosen)
            ->get(route('admin.customization.skema'))
            ->assertForbidden();

        $this->actingAs($dosen)
            ->post(route('admin.customization.skala.store'), ['nama' => 'Tidak Diizinkan'])
            ->assertForbidden();

        $this->assertDatabaseMissing('skalas', ['nama' => 'Tidak Diizinkan']);
    }

    public function test_inactive_skala_is_excluded_from_active_options(): void
    {
        Skala::create(['nama' => 'Aktif', 'is_active' => true]);
        Skala::create(['nama' => 'Nonaktif', 'is_active' => false]);

        $activeNames = Skala::active()->pluck('nama')->all();

        $this->assertContains('Aktif', $activeNames);
        $this->assertNotContains('Nonaktif', $activeNames);
    }
}
