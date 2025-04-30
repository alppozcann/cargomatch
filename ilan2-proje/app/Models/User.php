<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
	    'is_admin',
	    'user_type',
	    'description',
        'phone_number',
        'company_name',
        'company_address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    public function isGemici()
	{
		return $this->user_type === 'gemici';
	}
    public function isYukVeren()
	{
		return $this->user_type === 'yukveren';
	}

    public function gemiRoutes()
	{
		return $this->hasMany(GemiRoute::class);
	}

   public function yukler()
	{
		return $this->hasMany(Yuk::class);
	}
    public function gemiler()
    {
        return $this->hasMany(Ship::class);
    }

}

