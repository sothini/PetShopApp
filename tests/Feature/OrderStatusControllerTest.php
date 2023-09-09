<?php

namespace Tests\Feature;


use App\Models\OrderStatus;
use App\Models\User;
use Tests\TestCase;
use Tests\GenericControllerTestTrait;

class OrderStatusControllerTest extends TestCase
{
    use GenericControllerTestTrait;

    public function getInstance() 
      {
         return 'order-status';
      }

      public function getLoginRoute()
      {
        return "{$this->base_url}/user/login" ; 
      }

      protected function getModel(): string
      {
          return OrderStatus::class;
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
        ];
     }

     public function getIndexRoute(){return "{$this->base_url}/order-statuses" ;}
}
