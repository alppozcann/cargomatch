<?php

use App\Models\Ship;

Route::get('/ships', function () {
    return Ship::select('id', 'name', 'current_latitude', 'current_longitude', 'status')->get();
});
