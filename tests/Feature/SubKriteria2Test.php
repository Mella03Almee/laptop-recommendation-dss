<?php

namespace Tests\Feature;

use App\Models\SubKriteria2;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubKriteria2Test extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_auth_user_can_access_edit_sub_kriteria2()
    {
        $user = User::factory()->create();
        $data_sub_kriteria2 = SubKriteria2::first();

        $response = $this->actingAs($user)->get('/dashboard/data-sub-kriteria2/' . $data_sub_kriteria2->slug . '/edit');

        $response->assertStatus(200);
        $response->assertSee('Ubah Kriteria SSD (C3)', $data_sub_kriteria2->RAM);
    }

    public function test_auth_user_can_access_update_sub_kriteria2()
    {
        $user = User::factory()->create();

        $data_sub_kriteria2 = SubKriteria2::first();

        $this->assertCount(5, SubKriteria2::all());
        $response = $this->actingAs($user)->put('/dashboard/data-sub-kriteria2/' . $data_sub_kriteria2->slug, [
            'user_id' => $user->id,
            'SSD' => '720p/768p (HD)',
            'slug' => 'c3-subkriteria',
            'nSSD' => 5,
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/dashboard/data-sub-kriteria');
        $this->assertEquals(5, SubKriteria2::first()->nSSD);
    }
}
