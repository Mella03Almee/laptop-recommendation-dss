<?php

namespace Tests\Feature;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataHasilAkhirTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_auth_user_can_access_data_hasil_akhir()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard/data-hasil-akhir');

        $response->assertStatus(200);
        $response->assertSee('Data Hasil Akhir');
    }

    public function test_auth_user_can_access_data_hasil_akhir_print()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/data-hasil-akhir/print');

        $response->assertStatus(200);
        // $response->assertSee('Hasil Akhir Perankingan WP');
    }
}
