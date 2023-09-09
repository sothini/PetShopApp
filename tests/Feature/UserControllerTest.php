<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use Tests\GenericControllerTestTrait;

class UserControllerTest extends TestCase
{
  use GenericControllerTestTrait;

  public function getInstance()
  {
    return 'user';
  }

  public function getLoginRoute()
  {
    return "{$this->base_url}/user/login";
  }

  protected function getModel(): string
  {
    return User::class;
  }

  public function getUser()
  {
    return [
      'email' => User::where('is_admin', 0)->first()->email,
      'password' => 'userpassword',
    ];
  }

  public function getObject()
  {
    return [

      'first_name' => fake()->firstName,
      'last_name' => fake()->lastName,
      'is_admin' => fake()->boolean,
      'email' => fake()->unique()->safeEmail,
      'email_verified_at' => now(),
      'password' => 'userpassword',
      'password_confirmation' => 'userpassword',
      'avatar' => null,
      'address' => fake()->address,
      'phone_number' => fake()->phoneNumber,
      'is_marketing' => fake()->boolean,

    ];
  }


  public function test_index()
  {
    $this->markTestSkipped('Not necessary for this controller');
  }

  public function test_update(): void
  {
    $object = $this->getObject();

    $route = $this->getUpdateRoute() ? $this->getUpdateRoute() : $this->base_url . "/{$this->getInstance()}";
    $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken()])
      ->put("{$route}", $object);
    $response->assertStatus(200);
  }


  public function test_show(): void
  {
    $route = $this->getShowRoute() ? $this->getShowRoute() : $this->base_url . "/{$this->getInstance()}";

    $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken()])
      ->get("{$route}");

    $response->assertStatus(200);
  }


  public function test_delete()
  {
    $this->markTestSkipped('Not necessary for this controller');
  }


  public function getUpdateRoute()
  {
    return "{$this->base_url}/user/edit";
  }
}
