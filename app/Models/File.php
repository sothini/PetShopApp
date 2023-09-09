<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'path',
        'size',
        'type',
     
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            
            if($row->uuid == null)
                $row->uuid = Str::uuid();

           
        });
    }
}
