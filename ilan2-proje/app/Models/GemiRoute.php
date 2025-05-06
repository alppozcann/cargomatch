<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GemiRoute extends Model
{
	use HasFactory;

	protected $fillable = [
	'user_id',
	'title',
	'start_location',
	'end_location',
	'way_points',
	'available_capacity',
	'price',
	'departure_date',
	'arrival_date',
	'description',
	'status',
	'weight_type',
	'currency_type',
	'ship_id',
	];

	protected $casts = [
	'way_points' => 'array',
	'departure_date' => 'datetime',
	'arrival_date' => 'datetime',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
	public function matchedYukler()
	{
		return $this->hasMany(Yuk::class, 'matched_gemi_route_id');
	}
	public function ship()
	{
    	return $this->belongsTo(Ship::class, 'ship_id');
	}
	public function startPort()
	{
    	return $this->belongsTo(Port::class, 'start_location');
	}

	public function endPort()
	{
    	return $this->belongsTo(Port::class, 'end_location');
	}

}

