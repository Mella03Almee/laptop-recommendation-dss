<?php

namespace Tests\Feature;

use App\Models\Kriteria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KriteriaTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_auth_user_can_access_kriteria()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard/data-kriteria');

        $response->assertStatus(200);
        $response->assertSee('Data Kriteria');
    }

    public function test_auth_user_can_access_edit_kriteria()
    {
        $user = User::factory()->create();

        $data_kriterium = Kriteria::first();

        $response = $this->actingAs($user)->get('/dashboard/data-kriteria/' . $data_kriterium->slug . '/edit');

        $response->assertStatus(200);
        $response->assertSee($data_kriterium->namaKriteria);
    }

    public function test_auth_user_can_access_update_kriteria()
    {
        $user = User::factory()->create();

        $data_kriterium = Kriteria::first();

        $this->assertCount(7, Kriteria::all());
        $response = $this->actingAs($user)->put('/dashboard/data-kriteria/' . $data_kriterium->slug, [
            'kodeKriteria' => 'C1',
            'slug' => 'c1',
            'namaKriteria' => 'Harga',
            'bobot' => 5,
            'jenis' => 'benefit'
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/dashboard/data-kriteria');
        $this->assertEquals('benefit', Kriteria::first()->jenis);
        $this->assertEquals(5, Kriteria::first()->bobot);
    }
}
