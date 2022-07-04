<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;



class Admin extends Authenticatable
{
    use HasApiTokens,HasFactory;
    protected $table ="admins";
    protected $guard = 'admin';
    protected $fillable = [
        'name',
        'email',
        'password',

    ];


     /*
      * Get all of the comments for the Admin
      *
      * @return \Illuminate\Database\Eloquent\Relations\HasMany
      */
     public function drivers(): HasMany
     {
         return $this->hasMany(Driver::class);
     }


}
