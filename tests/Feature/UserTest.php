<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_registration_form()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Registration Form', 'Register');
    }

    public function test_login_form()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Login');
    }

    public function test_login_redirect_to_dashboard_successfully()
    {
        User::factory()->create([
            'username' => 'testlogin',
            'password' => bcrypt('00000000')
        ]);

        $response = $this->post('/login', [
            'username' => 'testlogin',
            'password' => '00000000'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
    }

    public function test_auth_user_can_access_dashboard()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_unauth_user_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_user_has_name_attribute()
    {
        $user = User::factory()->create([
            'name' => 'testlogin',
            'username' => '00000000',
            'password' => bcrypt('00000000')
        ]);

        $this->assertEquals('testlogin', $user->name);
    }

    public function test_registration_stores_user_data()
    {
        $response = $this->post('/register', [
            'name' => 'testlogin',
            'username' => 'testlogin',
            'password' => '00000000',
            'password_confirmation' => '00000000',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_data_user()
    {
        $admin = User::factory()->create(['is_admin' => 1]);

        $response = $this->actingAs($admin)->get('/dashboard/data-user');

        $response->assertStatus(200);
        $response->assertSee('Data User');
    }

    public function test_user_cannot_access_data_user()
    {
        $user = User::factory()->create(['is_admin' => 0]);

        $response = $this->actingAs($user)->get('/dashboard/data-user');

        $response->assertStatus(403); //* Forbidden for user, only admin can access
        $response->assertDontSee('Data User');
    }

    public function test_logout_user()
    {
        // Create a user
        $user = User::factory()->create();

        // Log in the user
        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'password'
        ]);

        // Access protected route
        $response = $this->get('/dashboard');
        $response->assertStatus(200);

        // Log out the user
        $response = $this->post('/dashboard/logout');

        // Check user state
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }
}
