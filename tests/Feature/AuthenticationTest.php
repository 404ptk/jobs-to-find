<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_as_job_seeker()
    {
        $token = Str::random(40);

        $response = $this->withSession(['_token' => $token])->post('/register', [
            '_token' => $token,
            'username' => 'johndoe',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'account_type' => 'job_seeker',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'account_type' => 'job_seeker',
        ]);
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $token = Str::random(40);

        $response = $this->withSession(['_token' => $token])->post('/login', [
            '_token' => $token,
            'login' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_incorrect_password()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $token = Str::random(40);

        $response = $this->withSession(['_token' => $token])->post('/login', [
            '_token' => $token,
            'login' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $token = Str::random(40);

        $response = $this->actingAs($user)->withSession(['_token' => $token])->post(route('logout'), [
            '_token' => $token,
        ]);

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
