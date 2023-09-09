<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'slug',
    ];


    // Static method to define validation rules
    public static function validation_rules()
    {
        return [
            'uuid' => 'nullable|uuid', // Assuming you have a custom 'uuid' validation rule
            'title' => 'required|string',
            'slug' => 'required|string|unique:brands,slug', // Unique constraint for 'slug' in the 'brands' table
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            if($row->uuid == null)
                $row->uuid = Str::uuid();
        });
    }
}
