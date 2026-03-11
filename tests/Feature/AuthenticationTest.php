<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthenticationTest extends TestCase
{
  use RefreshDatabase;

  public function test_user_can_register_as_job_seeker()
  {
    $response = $this->post('/register', [
      'username' => 'johndoe',
      'first_name' => 'John',
      'last_name' => 'Doe',
      'email' => 'john@example.com',
      'password' => 'password123',
      'password_confirmation' => 'password123',
      'account_type' => 'job_seeker'
    ]);

    $response->assertRedirect('/');
    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
      'email' => 'john@example.com',
      'account_type' => 'job_seeker'
    ]);
  }

  public function test_user_can_login_with_correct_credentials()
  {
    $user = User::factory()->create([
      'email' => 'test@example.com',
      'password' => Hash::make('password123')
    ]);

    $response = $this->post('/login', [
      'login' => 'test@example.com',
      'password' => 'password123'
    ]);

    $response->assertRedirect('/');
    $this->assertAuthenticatedAs($user);
  }

  public function test_user_cannot_login_with_incorrect_password()
  {
    $user = User::factory()->create([
      'email' => 'test@example.com',
      'password' => Hash::make('password123')
    ]);

    $response = $this->post('/login', [
      'login' => 'test@example.com',
      'password' => 'wrongpassword'
    ]);

    $response->assertSessionHasErrors();
    $this->assertGuest();
  }

  public function test_user_can_logout()
  {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect('/');
    $this->assertGuest();
  }
}
