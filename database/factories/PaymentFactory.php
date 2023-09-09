<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paymentTypes = ['credit_card', 'cash_on_delivery', 'bank_transfer'];
        $selectedType = $this->faker->randomElement($paymentTypes);

        $details = [];

        if ($selectedType === 'credit_card') {
            $details = [
                'holder_name' => $this->faker->name,
                'number' => $this->faker->creditCardNumber,
                'ccv' => $this->faker->numberBetween(100, 999),
                'expire_date' => $this->faker->creditCardExpirationDate,
            ];
        } elseif ($selectedType === 'cash_on_delivery') {
            $details = [
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
                'address' => $this->faker->address,
            ];
        } elseif ($selectedType === 'bank_transfer') {
            $details = [
                'swift' => $this->faker->swiftBicNumber,
                'iban' => $this->faker->iban('NL'),
                'name' => $this->faker->company,
            ];
        }

        return [
            'uuid' => Str::uuid(),
            'type' => $selectedType,
            'details' => json_encode($details),
        ];
    }
}
