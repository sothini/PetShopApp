<?php

namespace Database\Factories;

use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = [];

        for ($i=0; $i < rand(1,10); $i++) { 
            $products[] = [
                'product' => Product::inRandomOrder()->first()->uuid,
                'quantity' => $this->faker->numberBetween(1, 5),
            ];
        }

        $status = OrderStatus::inRandomOrder()->first();

        return [
            'user_id' => User::where('is_admin',0)->inRandomOrder()->first()->id, // Adjust the range as needed
            'order_status_id' =>    $status->id, // Adjust the range as needed
            'payment_id' => in_array($status->title,['paid','shipped']) ? Payment::inRandomOrder()->first()->id : null, // Adjust the range as needed
            'uuid' => $this->faker->uuid,
            'products' => json_encode($products),
            'address' => json_encode([
                'billing' => $this->faker->streetAddress,
                'shipping' => $this->faker->streetAddress,
            ]),
            'delivery_fee' => $this->faker->randomFloat(2, 0, 1000),
            'amount' => $this->faker->randomFloat(2, 10, 500),
            'shipped_at' => $this->faker->optional(0.7)->dateTimeThisDecade,

        ];
    }
}
