<?php

namespace Tests\Feature;

use App\Models\SubKriteria3;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubKriteria3Test extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_auth_user_can_access_edit_sub_kriteria3()
    {
        $user = User::factory()->create();
        $data_sub_kriteria3 = SubKriteria3::first();

        $response = $this->actingAs($user)->get('/dashboard/data-sub-kriteria3/' . $data_sub_kriteria3->slug . '/edit');

        $response->assertStatus(200);
        $response->assertSee('Ubah Kriteria PROCESSOR (C4)', $data_sub_kriteria3->Processor);
    }

    public function test_auth_user_can_access_update_sub_kriteria3()
    {
        $user = User::factory()->create();

        $data_sub_kriteria3 = SubKriteria3::first();

        $this->assertCount(5, SubKriteria3::all());
        $response = $this->actingAs($user)->put('/dashboard/data-sub-kriteria3/' . $data_sub_kriteria3->slug, [
            'user_id' => $user->id,
            'Processor' => 'TN (Twisted Nematic)',
            'slug' => 'c4-subkriteria',
            'nProcessor' => 5,
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/dashboard/data-sub-kriteria');
        $this->assertEquals(5, SubKriteria3::first()->nProcessor);
    }
}
