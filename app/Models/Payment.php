<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'type',
        'details',
       
    ];

 

    public static function validation_rules()
    {
        return [
            'uuid' => 'nullable|uuid',
            'type' => 'required|in:credit_card,cash_on_delivery,bank_transfer',
            'details' => 'required|json',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            if($row->uuid == null)
                $row->uuid = str::uuid();
        });

        static::saving(function ($row) {
            //  $row->details = json_encode($row->details);
        });
    }
}
