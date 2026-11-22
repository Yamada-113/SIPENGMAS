<?php

namespace Tests\Feature;

use App\Models\JenisKegiatan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenisKegiatanApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_get_active_jenis_kegiatan(): void
    {
        $dosen = User::create([
            'name' => 'Dosen',
            'role' => 'Dosen',
        ]);

        JenisKegiatan::create(['nama' => 'Internal', 'is_active' => true]);
        JenisKegiatan::create(['nama' => 'Eksternal', 'is_active' => false]);

        $this->actingAs($dosen)
            ->getJson(route('api.jenis_kegiatan'))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    ['nama' => 'Internal'],
                ],
            ])
            ->assertJsonMissing(['nama' => 'Eksternal']);
    }

    public function test_guest_is_redirected_to_login_for_jenis_kegiatan_api(): void
    {
        $this->getJson(route('api.jenis_kegiatan'))
            ->assertUnauthorized();
    }

    public function test_api_does_not_expose_inactive_jenis_kegiatan(): void
    {
        $dosen = User::create([
            'name' => 'Dosen',
            'role' => 'Dosen',
        ]);

        $inactive = JenisKegiatan::create([
            'nama' => 'Nonaktif',
            'is_active' => false,
        ]);

        $this->actingAs($dosen)
            ->getJson(route('api.jenis_kegiatan'))
            ->assertOk()
            ->assertJsonMissing(['id' => $inactive->id, 'nama' => 'Nonaktif']);
    }
}
