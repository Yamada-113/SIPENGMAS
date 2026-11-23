<?php

namespace Tests\Feature;

use App\Models\Skala;
use App\Models\Skema;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkemaCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create([
            'name' => 'Admin',
            'role' => 'Admin',
        ]);
    }

    public function test_admin_cannot_create_a_duplicate_skema_name(): void
    {
        $admin = $this->admin();
        $skala = Skala::create(['nama' => 'Prodi', 'is_active' => true]);
        Skema::create([
            'skala_id' => $skala->id,
            'label_dropdown' => 'Fakultas A',
            'nama' => 'Skema yang sama',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->from(route('admin.customization.skema'))
            ->post(route('admin.customization.skema.store'), [
                'skala_id' => $skala->id,
                'label_dropdown' => 'Fakultas B',
                'nama' => 'Skema yang sama',
            ])
            ->assertSessionHasErrorsIn('storeSkema', 'nama');

        $this->assertSame(1, Skema::where('nama', 'Skema yang sama')->count());
    }

    public function test_admin_cannot_rename_skema_to_an_existing_name(): void
    {
        $admin = $this->admin();
        $skala = Skala::create(['nama' => 'Prodi', 'is_active' => true]);
        $first = Skema::create([
            'skala_id' => $skala->id,
            'label_dropdown' => 'Fakultas A',
            'nama' => 'Skema pertama',
            'is_active' => true,
        ]);
        Skema::create([
            'skala_id' => $skala->id,
            'label_dropdown' => 'Fakultas B',
            'nama' => 'Skema kedua',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->from(route('admin.customization.skema'))
            ->put(route('admin.customization.skema.update', $first->id), [
                'label_dropdown' => 'Fakultas A',
                'nama' => 'Skema kedua',
                'is_active' => 1,
                'editing_skema_id' => $first->id,
            ])
            ->assertSessionHasErrorsIn('updateSkema', 'nama');

        $this->assertSame('Skema pertama', $first->refresh()->nama);
    }

    public function test_admin_can_deactivate_a_skema(): void
    {
        $admin = $this->admin();
        $skala = Skala::create(['nama' => 'Prodi', 'is_active' => true]);
        $skema = Skema::create([
            'skala_id' => $skala->id,
            'label_dropdown' => 'Fakultas A',
            'nama' => 'Skema aktif',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.customization.skema.update', $skema->id), [
                'label_dropdown' => 'Fakultas A',
                'nama' => 'Skema aktif',
                'is_active' => 0,
                'editing_skema_id' => $skema->id,
            ])
            ->assertSessionHas('success');

        $this->assertFalse($skema->refresh()->is_active);
    }

    public function test_admin_receives_success_notifications_after_skema_changes(): void
    {
        $admin = $this->admin();
        $skala = Skala::create(['nama' => 'Prodi', 'is_active' => true]);

        $this->actingAs($admin)
            ->from(route('admin.customization.skema'))
            ->followingRedirects()
            ->post(route('admin.customization.skema.store'), [
                'skala_id' => $skala->id,
                'label_dropdown' => 'Fakultas A',
                'nama' => 'Skema baru',
            ])
            ->assertOk()
            ->assertSee('Skema Berhasil Ditambahkan');

        $skema = Skema::where('nama', 'Skema baru')->firstOrFail();
        $this->actingAs($admin)
            ->from(route('admin.customization.skema'))
            ->followingRedirects()
            ->delete(route('admin.customization.skema.delete', $skema->id))
            ->assertOk()
            ->assertSee('Skema berhasil dihapus');
    }

    public function test_form_displays_validation_error_for_duplicate_skema_name(): void
    {
        $admin = $this->admin();
        $skala = Skala::create(['nama' => 'Prodi', 'is_active' => true]);
        Skema::create([
            'skala_id' => $skala->id,
            'label_dropdown' => 'Fakultas A',
            'nama' => 'Skema sama',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->from(route('admin.customization.skema'))
            ->followingRedirects()
            ->post(route('admin.customization.skema.store'), [
                'skala_id' => $skala->id,
                'label_dropdown' => 'Fakultas B',
                'nama' => 'Skema sama',
            ])
            ->assertOk()
            ->assertSee('Nama skema ini sudah ada.');
    }
}
