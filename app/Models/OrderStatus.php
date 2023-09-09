<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class OrderStatus extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'title',
    ];

    protected $casts = [
        'uuid' => 'string', // Ensure UUID is cast as a string
    ];

    protected $hidden = [
        'id',
        'deleted_at',
    ];

    public static function validation_rules($id = null)
    {
        return [
            'uuid' => 'nullable|string|unique:order_statuses,uuid,' . $id,
            'title' => 'required|string',
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
