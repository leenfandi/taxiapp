<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;


class Admin extends Authenticatable implements JWTSubject
{
    use HasApiTokens,HasFactory;
    protected $table ="admins";

    protected $fillable = [
        'name',
        'email',
        'password',

    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
     /*
      * Get all of the comments for the Admin
      *
      * @return \Illuminate\Database\Eloquent\Relations\HasMany
      */
    // public function drivers(): HasMany
    // {
     //   return $this->hasMany(Driver::class);
    // }


}
