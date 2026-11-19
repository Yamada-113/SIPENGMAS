<?php

namespace Tests\Feature\Dosen;

use App\Models\Proposal;
use App\Models\ProposalCoreAnggotaMahasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LdapRecord\Container;
use LdapRecord\Testing\ConnectionFake;
use Tests\TestCase;

class DosenCekAnggotaProposalTest extends TestCase
{
    use RefreshDatabase;

    private ?Container $originalLdapContainer = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalLdapContainer = Container::getInstance();
        Container::setInstance(new Container);
    }

    protected function tearDown(): void
    {
        Container::setInstance($this->originalLdapContainer);

        parent::tearDown();
    }

    public function test_non_numeric_student_npm_is_rejected_before_ldap_search_or_proposal_save(): void
    {
        $connection = ConnectionFake::make([
            'hosts' => ['localhost'],
            'base_dn' => 'dc=example,dc=com',
        ])->shouldBeConnected();
        Container::addConnection($connection, 'default');

        $user = User::create([
            'name' => 'Dosen Test',
            'username' => 'dosen-test',
            'role' => 'Dosen',
        ]);
        $invalidNpm = "12345'";
        $proposalsBefore = Proposal::count();
        $studentsBefore = ProposalCoreAnggotaMahasiswa::count();

        $this->actingAs($user)
            ->getJson(route('api.ldap_check', ['id' => $invalidNpm]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('id');

        $this->actingAs($user)
            ->postJson(route('dosen.tesproposal.store'), [
                'anggota_mhs' => [
                    [
                        'npm' => $invalidNpm,
                        'nama' => 'Mahasiswa Test',
                        'prodi' => 'Informatika',
                        'peran' => 'Anggota Mahasiswa 1',
                    ],
                ],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('anggota_mhs.0.npm');

        $this->assertSame($proposalsBefore, Proposal::count());
        $this->assertSame($studentsBefore, ProposalCoreAnggotaMahasiswa::count());
    }
}
