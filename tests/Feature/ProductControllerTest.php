<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;
use Tests\GenericControllerTestTrait;

class ProductControllerTest extends TestCase
{
    use GenericControllerTestTrait;

    public function getInstance() 
      {
         return 'product';
      }

      public function getLoginRoute()
      {
        return "{$this->base_url}/user/login" ; 
      }

      protected function getModel(): string
      {
          return Product::class;
      }

     public function getUser() 
     {
        return [
         'email' => User::where('is_admin',0)->first()->email,
         'password' => 'userpassword',
        ];
     }

     public function getObject() 
     {
         return [
          
            'category_uuid' => Category::inRandomOrder()->first()->uuid,
            'title' => fake()->sentence(3),
            'price' => fake()->randomFloat(2, 10, 1000),
            'description' => fake()->paragraph,
            'metadata' => json_encode([
                'brand' => fake()->uuid,
                'image' => fake()->uuid,
            ]),
        ];
     }

    
}
