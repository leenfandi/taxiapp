<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;
    protected $table = 'trips';
    protected $fillable = [
        'user_id',
        'driver_id',
        'distance',
        'price',

    ];
    /**
     * Get all of the user for the Trip
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(){
        return $this->hasMany(User::class);
    }
    /**
     * Get all of the driver for the Trip
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }
}

