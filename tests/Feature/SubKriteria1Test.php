<?php

namespace Tests\Feature;

use App\Models\SubKriteria1;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubKriteria1Test extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_auth_user_can_access_edit_sub_kriteria1()
    {
        $user = User::factory()->create();
        $data_sub_kriteria1 = SubKriteria1::first();

        $response = $this->actingAs($user)->get('/dashboard/data-sub-kriteria1/' . $data_sub_kriteria1->slug . '/edit');

        $response->assertStatus(200);
        $response->assertSee('Ubah Kriteria RAM (C2)', $data_sub_kriteria1->RAM);
    }

    public function test_auth_user_can_access_update_sub_kriteria1()
    {
        $user = User::factory()->create();

        $data_sub_kriteria1 = SubKriteria1::first();

        $this->assertCount(5, SubKriteria1::all());
        $response = $this->actingAs($user)->put('/dashboard/data-sub-kriteria1/' . $data_sub_kriteria1->slug, [
            'user_id' => $user->id,
            'RAM' => '18.5 inch - 24.6 inch',
            'slug' => 'c2-subkriteria',
            'nRAM' => 5,
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/dashboard/data-sub-kriteria');
        $this->assertEquals(5, SubKriteria1::first()->nRAM);
    }
}
