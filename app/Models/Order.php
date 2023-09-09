<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_status_id',
        'payment_id',
        'uuid',
        'products',
        'address',
        'delivery_fee',
        'amount',
        'shipped_at',
    ];


    /**
     * Get validation rules for orders.
     *
     * @return array
     */
    public static function validation_rules()
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'order_status_uuid' => 'required|string|exists:order_statuses,uuid',
            'payment_uuid' => 'required|string|exists:payments,uuid',
            'uuid' => 'nullable|string|unique:orders,uuid',
            'products' => 'required|json',
            'products.*.product' => 'required|string|exists:products,uuid',
            'address' => 'nullable|json',
            'delivery_fee' => 'nullable|numeric',
            'amount' => 'nullable|numeric',
            'shipped_at' => 'nullable|date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order_status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($row) {

            if ($row->uuid == null)
                $row->uuid = Str::uuid();
        });

        static::saving(function ($row) {
            if ($uuid = request()->get('order_status_uuid')) {
                $row->order_status_id = OrderStatus::where('uuid', $uuid)->firstOrFail()->id;
            }

            if ($uuid = request()->get('payment_uuid')) {
                $row->payment_id = Payment::where('uuid', $uuid)->firstOrFail()->id;
            }

            
            if ($json  = $row->products) {
                $data = json_decode($json, true);
                $totalAmount = 0;
                foreach ($data as $item) {
                    if (isset($item['quantity']) && is_numeric($item['quantity'])) {
                        $quantity = $item['quantity'];
                        $price = Product::where('uuid', $item['product'])->firstOrFail()->price;
                        $totalAmount += $quantity * $price;
                    }
                }
            }
            $row->amount = $totalAmount;
            $row->delivery_fee =  $row->amount > 500 ? 0 : 15;
        });
    }
}
