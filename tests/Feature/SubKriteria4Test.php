<?php

namespace Tests\Feature;

use App\Models\SubKriteria4;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubKriteria4Test extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_auth_user_can_access_edit_sub_kriteria4()
    {
        $user = User::factory()->create();
        $data_sub_kriteria4 = SubKriteria4::first();

        $response = $this->actingAs($user)->get('/dashboard/data-sub-kriteria4/' . $data_sub_kriteria4->slug . '/edit');

        $response->assertStatus(200);
        $response->assertSee('Ubah Kriteria VGA (C5)', $data_sub_kriteria4->VGA);
    }

    public function test_auth_user_can_access_update_sub_kriteria4()
    {
        $user = User::factory()->create();

        $data_sub_kriteria4 = SubKriteria4::first();

        $this->assertCount(5, SubKriteria4::all());
        $response = $this->actingAs($user)->put('/dashboard/data-sub-kriteria4/' . $data_sub_kriteria4->slug, [
            'user_id' => $user->id,
            'VGA' => '60 Hz - 75 Hz',
            'slug' => 'c5-subkriteria',
            'nVGA' => 5,
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/dashboard/data-sub-kriteria');
        $this->assertEquals(5, SubKriteria4::first()->nVGA);
    }
}
