<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $table = "_profiles";
    protected $fillable =[
         'name',
         'email',
         'gender',
        'driver_id',
        'image',
        'typeofcar',
        'number',
    ];

        /**
         * Get the user that owns the Profile
         *
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
      /*  public function driver()
        {
            return $this->belongsTo(Driver::class, 'driver_id','id');
        }*/
}
