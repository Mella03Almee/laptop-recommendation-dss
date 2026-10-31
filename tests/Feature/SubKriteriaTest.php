<?php

namespace Tests\Feature;

use App\Models\SubKriteria;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubKriteriaTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_auth_user_can_access_sub_kriteria()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard/data-sub-kriteria');

        $response->assertStatus(200);
        $response->assertSee('Data Sub Kriteria');
    }

    public function test_auth_user_can_access_edit_sub_kriteria()
    {
        $user = User::factory()->create();
        $data_sub_kriterium = SubKriteria::first();

        $response = $this->actingAs($user)->get('/dashboard/data-sub-kriteria/' . $data_sub_kriterium->slug . '/edit');

        $response->assertStatus(200);
        $response->assertSee('Ubah Kriteria Harga (C1)', $data_sub_kriterium->harga);
    }

    public function test_auth_user_can_access_update_sub_kriteria()
    {
        $user = User::factory()->create();

        $data_sub_kriterium = SubKriteria::first();

        $this->assertCount(5, SubKriteria::all());
        $response = $this->actingAs($user)->put('/dashboard/data-sub-kriteria/' . $data_sub_kriterium->slug, [
            'user_id' => $user->id,
            'harga' => 'Rp 10 juta - Rp 12,5 juta',
            'slug' => 'c1-subkriteria',
            'nHarga' => 1,
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/dashboard/data-sub-kriteria');
        $this->assertEquals(5, SubKriteria::first()->nHarga);
    }
}
