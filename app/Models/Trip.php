<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;
    protected $table = "_trips";
    protected $fillable = [
        'user_id',
        'driver_id',
        'start_time',
        'end_time',
        'first_location',
        'end_location',
        'note',
        'status',
    ];
    /**
     * Get all of the user for the Trip
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    /**
     * Get the user that owns the Trip
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

}

