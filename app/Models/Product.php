<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_uuid',
        'title',
        'price',
        'description',
        'metadata',
    ];

 

    public static function validation_rules()
    {
        return [
            'category_uuid' => 'nullable|uuid', 
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'metadata' => 'required|json',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            if($row->uuid == null)
                $row->uuid = Str::uuid();
        });

        static::saving(function ($row) {
           // $row->metadata = json_encode($row->metadata);
        });
    }
}
