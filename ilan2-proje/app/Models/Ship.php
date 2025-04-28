<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Ship extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plate_code',
        'ship_name',
        'ship_type',
        'carrying_capacity',
        'load_types',
        'certificates',
    ];

    protected $casts = [
        'load_types' => 'array',
        'certificates' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function gemiRoutes()
{
    return $this->hasMany(GemiRoute::class, 'ship_id');
}

}
