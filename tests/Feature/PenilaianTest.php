<?php

namespace Tests\Feature;

use App\Models\Penilaian;
use App\Models\User;
use App\Models\Alternatif;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenilaianTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_auth_user_can_access_penilaian()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard/data-penilaian');

        $response->assertStatus(200);
        $response->assertSee('Data Penilaian');
    }

    public function test_auth_user_can_access_create_penilaian()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard/data-penilaian/create');

        $response->assertStatus(200);
        $response->assertSee('Tambah Penilaian');
    }

    public function test_auth_user_can_access_store_penilaian()
    {
        $user = User::factory()->create();
        $alternatif = Alternatif::create([
            'user_id' => $user->id,
            'kodeAlternatif' => 'A1',
            'slug' => 'a-1',
            'namaAlternatif' => 'MSI OPTIX MAG274QRF'
        ]);
        $response = $this->actingAs($user)->post('/dashboard/data-penilaian', [
            'user_id' => $user->id,
            'alternatif_id' => $alternatif->id,
            'slug' => 'aboggoba',
            'C1x' => 3,
            'C2x' => 2,
            'C3x' => 4,
            'C4x' => 5,
            'C5x' => 2,
            'C6x' => 4,
            'C7x' => 4,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertCount(1, Penilaian::all());
        $this->assertDatabaseHas('penilaians', [
            'user_id' => $user->id,
            'alternatif_id' => $alternatif->id,
            'slug' => 'aboggoba',
            'C1x' => 3,
            'C2x' => 2,
            'C3x' => 4,
            'C4x' => 5,
            'C5x' => 2,
            'C6x' => 4,
            'C7x' => 4,
        ]);
    }

    public function test_auth_user_can_access_edit_penilaian()
    {
        $user = User::factory()->create();
        $alternatif = Alternatif::create([
            'user_id' => $user->id,
            'kodeAlternatif' => 'A1',
            'slug' => 'a-1',
            'namaAlternatif' => 'MSI OPTIX MAG274QRF'
        ]);
        $data_penilaian = Penilaian::create([
            'user_id' => $user->id,
            'alternatif_id' => $alternatif->id,
            'slug' => 'aboggoba',
            'C1x' => 3,
            'C2x' => 2,
            'C3x' => 4,
            'C4x' => 5,
            'C5x' => 2,
            'C6x' => 4,
            'C7x' => 4,
        ]);

        $response = $this->actingAs($user)->get('/dashboard/data-penilaian/' . $data_penilaian->slug . '/edit');

        $response->assertStatus(200);
        $response->assertSee($data_penilaian->slug);
    }

    public function test_auth_user_can_access_update_penilaian()
    {
        $user = User::factory()->create();
        $alternatif = Alternatif::create([
            'user_id' => $user->id,
            'kodeAlternatif' => 'A1',
            'slug' => 'a-1',
            'namaAlternatif' => 'MSI OPTIX MAG274QRF'
        ]);
        Penilaian::create([
            'user_id' => $user->id,
            'alternatif_id' => $alternatif->id,
            'slug' => 'aboggoba',
            'C1x' => 3,
            'C2x' => 2,
            'C3x' => 4,
            'C4x' => 5,
            'C5x' => 2,
            'C6x' => 4,
            'C7x' => 4,
        ]);

        $data_penilaian = Penilaian::first();

        $this->assertCount(1, Penilaian::all());
        $response = $this->actingAs($user)->put('/dashboard/data-penilaian/' . $data_penilaian->slug, [
            'user_id' => $user->id,
            'alternatif_id' => $alternatif->id,
            'slug' => 'aboggoba',
            'C1x' => 3,
            'C2x' => 2,
            'C3x' => 4,
            'C4x' => 5,
            'C5x' => 2,
            'C6x' => 5,
            'C7x' => 5,
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/dashboard/data-penilaian');
        $this->assertEquals(5, Penilaian::first()->C6x);
        $this->assertEquals(5, Penilaian::first()->C7x);
    }
}
