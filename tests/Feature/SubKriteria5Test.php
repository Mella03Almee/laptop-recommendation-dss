<?php

namespace Tests\Feature;

use App\Models\SubKriteria5;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubKriteria5Test extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_auth_user_can_access_edit_sub_kriteria5()
    {
        $user = User::factory()->create();
        $data_sub_kriteria5 = SubKriteria5::first();

        $response = $this->actingAs($user)->get('/dashboard/data-sub-kriteria5/' . $data_sub_kriteria5->slug . '/edit');

        $response->assertStatus(200);
        $response->assertSee('Ubah Kriteria DISPLAY PANEL (C6)', $data_sub_kriteria5->DisplayPanel);
    }

    public function test_auth_user_can_access_update_sub_kriteria5()
    {
        $user = User::factory()->create();

        $data_sub_kriteria5 = SubKriteria5::first();

        $this->assertCount(5, SubKriteria5::all());
        $response = $this->actingAs($user)->put('/dashboard/data-sub-kriteria5/' . $data_sub_kriteria5->slug, [
            'user_id' => $user->id,
            'DisplayPanel' => '6 ms - 5 ms',
            'slug' => 'c6-subkriteria',
            'nDisplayPanel' => 5,
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/dashboard/data-sub-kriteria');
        $this->assertEquals(5, SubKriteria5::first()->nDisplayPanel);
    }
}
