<?php

namespace Tests\Feature;


use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Str;
use Tests\GenericControllerTestTrait;

class OrderControllerTest extends TestCase
{
    use GenericControllerTestTrait;

    public function getInstance()
    {
        return 'order';
    }

    public function getLoginRoute()
    {
        return "{$this->base_url}/user/login";
    }

    protected function getModel(): string
    {
        return Order::class;
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

        $products = [];

        for ($i=0; $i < rand(1,10); $i++) { 
            $products[] = [
                'product' => Product::inRandomOrder()->first()->uuid,
                'quantity' => fake()->numberBetween(1, 5),
            ];
        }


        return [
            'order_status_uuid' =>  OrderStatus::inRandomOrder()->first()->uuid, // Adjust the range as needed
            'payment_uuid' => Payment::inRandomOrder()->first()->uuid, // Adjust the range as needed
            'products' => json_encode($products),
            'address' => json_encode([
                'billing' => fake()->streetAddress,
                'shipping' => fake()->streetAddress,
            ]),
        ];
    }
}
