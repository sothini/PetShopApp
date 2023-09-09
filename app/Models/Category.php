<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'title',
        'slug',
    ];

    public static function validation_rules()
    {
       return [
            'uuid' => 'nullable|string|unique:categories,uuid',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
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
