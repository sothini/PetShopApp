<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\GenericControllerTestTrait;
use Tests\TestCase;

class AdminUserControllerTest extends TestCase
{ 
    use GenericControllerTestTrait;
   
     
      public function getInstance() 
      {
         return 'user';
      }

      public function getLoginRoute()
      {
        return "{$this->base_url}/admin/login" ; 
      }

      protected function getModel(): string
      {
          return User::class;
      }

     public function getUser() 
     {
        return [
         'email' => 'admin@buckhill.co.uk',
         'password' => 'admin',
        ];
     }

     public function getObject() 
     {
         return [
            
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'is_admin' =>fake()->boolean,
            'email' => fake()->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => 'userpassword', // Change this to the desired default password
            'password_confirmation' => 'userpassword',
            'avatar' => null, // Change this based on your image logic
            'address' => fake()->address,
            'phone_number' => fake()->phoneNumber,
            'is_marketing' => fake()->boolean,
   
        ];
     }

     public function test_update(): void
     {
         $object = $this->getObject();
 
         $row = $this->getModel()::where('is_admin',0)->inRandomOrder()->first();
 
         $route = $this->getUpdateRoute() ? $this->getUpdateRoute() : $this->base_url."/{$this->getInstance()}";
 
         $response = $this->withHeaders(['Authorization'=>'Bearer '.$this->getToken()])
                     ->put("{$route}/{$row->uuid}",$object);
 
         $response->assertStatus(200);
 
     }

      /**
     * @test
     */
     public function test_show()
     {
      $this->markTestSkipped('Not necessary for this controller');
     }

     public function test_delete(): void
     {
       
        $row = $this->getModel()::where('is_admin',0)->inRandomOrder()->first();

        $route = $this->getDeleteRoute() ? $this->getDeleteRoute() : $this->base_url."/{$this->getInstance()}";
        
        $response = $this->withHeaders(['Authorization'=>'Bearer '.$this->getToken()])
                    ->delete("{$route}/{$row->uuid}");

        $response->assertStatus(204);

    }

     public function getIndexRoute(){return "{$this->base_url}/admin/user-listing" ;}

     public function getStoreRoute(){return "{$this->base_url}/admin/create" ;}

     public function getUpdateRoute(){return "{$this->base_url}/admin/user-edit";}
   
     public function getDeleteRoute(){return "{$this->base_url}/admin/user-delete";}


}
