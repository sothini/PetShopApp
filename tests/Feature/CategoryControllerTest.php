<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;
use Tests\GenericControllerTestTrait;

class CategoryControllerTest extends TestCase
{
    use GenericControllerTestTrait;

    public function getInstance() 
      {
         return 'category';
      }

      public function getLoginRoute()
      {
        return "{$this->base_url}/user/login" ; 
      }

      protected function getModel(): string
      {
          return Category::class;
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
            'title' => fake()->word,
            'slug' => fake()->slug,
        ];
     }

     public function getIndexRoute(){return "{$this->base_url}/categories" ;}
}
