<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{

    protected $data = [
        [
            'title' => 'open',
        ],
        [
            'title' => 'pending payment',
        ],
        [
            'title' => 'paid',
        ],
        [
            'title' => 'shipped',
        ],
        [
            'title' => 'cancelled',
        ],

    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->data as $data) {
            OrderStatus::firstOrCreate($data);
        }
    }
}
