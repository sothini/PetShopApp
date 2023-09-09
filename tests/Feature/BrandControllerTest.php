<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\User;
use Tests\TestCase;
use Tests\GenericControllerTestTrait;

class BrandControllerTest extends TestCase
{
   use GenericControllerTestTrait;

   public function getInstance()
   {
      return 'brand';
   }

   protected function getModel(): string
   {
      return Brand::class;
   }

   public function getLoginRoute()
   {
      return "{$this->base_url}/user/login";
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
         'title' => fake()->word,
         'slug' => fake()->slug,
      ];
   }
}
