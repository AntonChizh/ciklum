<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrentWeather extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['city_id' , 'city_name', 'country_code', 'lon', 'lat', 'dt', 'weather'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'dt' => 'datetime',
        'weather' => 'array',
    ];
}
