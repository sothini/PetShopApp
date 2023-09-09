<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;
use Tests\GenericControllerTestTrait;

class PaymentControllerTest extends TestCase
{
    use GenericControllerTestTrait;

    public function getInstance() 
      {
         return 'payment';
      }

      public function getLoginRoute()
      {
        return "{$this->base_url}/user/login" ; 
      }

      protected function getModel(): string
      {
          return Payment::class;
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
        $paymentTypes = ['credit_card', 'cash_on_delivery', 'bank_transfer'];
        $selectedType = fake()->randomElement($paymentTypes);

        $details = [];

        if ($selectedType === 'credit_card') {
            $details = [
                'holder_name' => fake()->name,
                'number' => fake()->creditCardNumber,
                'ccv' => fake()->numberBetween(100, 999),
                'expire_date' => fake()->creditCardExpirationDate,
            ];
        } elseif ($selectedType === 'cash_on_delivery') {
            $details = [
                'first_name' => fake()->firstName,
                'last_name' => fake()->lastName,
                'address' => fake()->address,
            ];
        } elseif ($selectedType === 'bank_transfer') {
            $details = [
                'swift' => fake()->swiftBicNumber,
                'iban' => fake()->iban('NL'),
                'name' => fake()->company,
            ];
        }

        return [
        
            'type' => $selectedType,
            'details' => json_encode($details),
        ];
     }

    
}
