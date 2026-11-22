<?php

namespace Tests\Feature;

use App\Models\JenisKegiatan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenisKegiatanCrudTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role): User
    {
        return User::create([
            'name' => $role,
            'role' => $role,
        ]);
    }

    public function test_admin_can_open_master_page_and_add_item(): void
    {
        $admin = $this->user('Admin');

        $this->actingAs($admin)
            ->get(route('admin.customization.jenis_kegiatan'))
            ->assertOk();

        $this->actingAs($admin)
            ->post(route('admin.customization.jenis_kegiatan.store'), ['nama' => 'Kegiatan Baru'])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('jenis_kegiatans', [
            'nama' => 'Kegiatan Baru',
            'is_active' => true,
        ]);
    }

    public function test_empty_and_duplicate_names_are_rejected(): void
    {
        $admin = $this->user('Admin');
        JenisKegiatan::create(['nama' => 'Internal', 'is_active' => true]);

        $this->actingAs($admin)
            ->from(route('admin.customization.jenis_kegiatan'))
            ->post(route('admin.customization.jenis_kegiatan.store'), ['nama' => ''])
            ->assertSessionHasErrors('nama');

        $this->actingAs($admin)
            ->from(route('admin.customization.jenis_kegiatan'))
            ->post(route('admin.customization.jenis_kegiatan.store'), ['nama' => 'Internal'])
            ->assertSessionHasErrors('nama');

        $this->assertSame(1, JenisKegiatan::where('nama', 'Internal')->count());
    }

    public function test_admin_can_rename_and_deactivate_item(): void
    {
        $admin = $this->user('Admin');
        $jenis = JenisKegiatan::create(['nama' => 'Internal', 'is_active' => true]);

        $this->actingAs($admin)
            ->put(route('admin.customization.jenis_kegiatan.update', $jenis), [
                'nama' => 'Internal Utama',
                'is_active' => 0,
            ])
            ->assertSessionHas('success');

        $jenis->refresh();
        $this->assertSame('Internal Utama', $jenis->nama);
        $this->assertFalse($jenis->is_active);
    }

    public function test_non_admin_cannot_open_or_change_master_data(): void
    {
        $dosen = $this->user('Dosen');

        $this->actingAs($dosen)
            ->get(route('admin.customization.jenis_kegiatan'))
            ->assertForbidden();

        $this->actingAs($dosen)
            ->post(route('admin.customization.jenis_kegiatan.store'), ['nama' => 'Tidak Diizinkan'])
            ->assertForbidden();

        $this->assertDatabaseMissing('jenis_kegiatans', ['nama' => 'Tidak Diizinkan']);
    }

    public function test_duplicate_name_is_rejected_when_updating(): void
    {
        $admin = $this->user('Admin');
        $internal = JenisKegiatan::create(['nama' => 'Internal', 'is_active' => true]);
        JenisKegiatan::create(['nama' => 'Eksternal', 'is_active' => true]);

        $this->actingAs($admin)
            ->put(route('admin.customization.jenis_kegiatan.update', $internal), [
                'nama' => 'Eksternal',
                'is_active' => 1,
            ])
            ->assertSessionHasErrors('nama');

        $this->assertSame('Internal', $internal->refresh()->nama);
    }

    public function test_inactive_item_is_excluded_from_active_options(): void
    {
        JenisKegiatan::create(['nama' => 'Aktif', 'is_active' => true]);
        JenisKegiatan::create(['nama' => 'Nonaktif', 'is_active' => false]);

        $activeNames = JenisKegiatan::active()->pluck('nama')->all();

        $this->assertContains('Aktif', $activeNames);
        $this->assertNotContains('Nonaktif', $activeNames);
    }
}
