<?php

namespace Tests\Feature;

use App\Models\Alternatif;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlternatifTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_auth_user_can_access_alternatif()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard/data-alternatif');

        $response->assertStatus(200);
        $response->assertSee('Data Alternatif');
    }

    public function test_auth_user_can_access_create_alternatif()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard/data-alternatif/create');

        $response->assertStatus(200);
        $response->assertSee('Tambah Alternatif');
    }

    public function test_auth_user_can_access_store_alternatif()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/dashboard/data-alternatif', [
            'user_id' => $user->id,
            'kodeAlternatif' => 'A1',
            'slug' => 'a-1',
            'namaAlternatif' => 'MSI OPTIX MAG274QRF'
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertCount(1, Alternatif::all());
        $this->assertDatabaseHas('alternatifs', [
            'user_id' => $user->id,
            'kodeAlternatif' => 'A1',
            'slug' => 'a-1',
            'namaAlternatif' => 'MSI OPTIX MAG274QRF'
        ]);
    }

    public function test_auth_user_can_access_edit_alternatif()
    {
        $user = User::factory()->create();
        $data_alternatif = Alternatif::create([
            'user_id' => $user->id,
            'kodeAlternatif' => 'A1',
            'slug' => 'a-1',
            'namaAlternatif' => 'MSI OPTIX MAG274QRF'
        ]);

        $response = $this->actingAs($user)->get('/dashboard/data-alternatif/' . $data_alternatif->slug . '/edit');

        $response->assertStatus(200);
        $response->assertSee($data_alternatif->namaAlternatif);
    }

    public function test_auth_user_can_access_update_kriteria()
    {
        $user = User::factory()->create();
        Alternatif::create([
            'user_id' => $user->id,
            'kodeAlternatif' => 'A1',
            'slug' => 'a-1',
            'namaAlternatif' => 'MSI OPTIX MAG274QRF'
        ]);

        $data_alternatif = Alternatif::first();

        $this->assertCount(1, Alternatif::all());
        $response = $this->actingAs($user)->put('/dashboard/data-alternatif/' . $data_alternatif->slug, [
            'user_id' => $user->id,
            'kodeAlternatif' => 'A1',
            'slug' => 'a-1',
            'namaAlternatif' => 'MSI OPTIX'
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/dashboard/data-alternatif');
        $this->assertEquals('A1', Alternatif::first()->kodeAlternatif);
        $this->assertEquals('MSI OPTIX', Alternatif::first()->namaAlternatif);
    }

    public function test_auth_user_can_access_delete_alternatif()
    {
        $user = User::factory()->create();
        Alternatif::create([
            'user_id' => $user->id,
            'kodeAlternatif' => 'A1',
            'slug' => 'a-1',
            'namaAlternatif' => 'MSI OPTIX MAG274QRF'
        ]);

        $response = $this->actingAs($user)->get('/dashboard/data-alternatif');

        $response->assertStatus(200);
        $response->assertSee('Hapus');
    }
}
