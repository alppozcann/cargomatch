<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ship extends Model
{
    protected $fillable = ['name', 'current_latitude', 'current_longitude', 'status'];
}
