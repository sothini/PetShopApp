<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


/**
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="User resource",
 *     required={"first_name","last_name","email","password","avatar","address","phone_number","password_confirmation"},
 *     @OA\Property(property="first_name", type="string", example="John"),
 *     @OA\Property(property="last_name", type="string", example="Doe"),
 *     @OA\Property(property="email", type="string", example="john@example.com"),
 *     @OA\Property(property="password", type="string", example="********"),
 *     @OA\Property(property="password_confirmation", type="string", example="********"),
 *     @OA\Property(property="avatar", type="string", nullable=true),
 *     @OA\Property(property="address", type="string", example="123 Main St"),
 *     @OA\Property(property="phone_number", type="string", example="+123456789"),
 *     @OA\Property(property="is_marketing", type="boolean", example=false),
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'is_admin',
        'email',
        'email_verified_at',
        'password',
        'avatar',
        'address',
        'phone_number',
        'is_marketing',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'id',
        'is_admin',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
 

    public static function validation_rules()
    {
       // dd(request()->route('uuid'));
        return [
            'uuid' => 'nullable|uuid',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'is_admin' => 'boolean|nullable',
            'email' => ['required', 'email', 
            Rule::unique('users')->ignore(request()->route('uuid')?? request()->input('uuid'), 'uuid')],
            'email_verified_at' => 'nullable|date',
            'password' => 'required|confirmed|min:8',
            'avatar' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'is_marketing' => 'nullable',
            'last_login_at' => 'nullable|date',
        ];
    }

    public function jwt_tokens()
    {
        return $this->hasMany(JwtToken::class);
    }



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            
            if($user->uuid == null)
                $user->uuid = Str::uuid();

            
            $user->is_marketing = boolval($user->is_marketing);

                if($user->is_marketing == null)
                $user->is_marketing = 0;
        });
    }
    
}
