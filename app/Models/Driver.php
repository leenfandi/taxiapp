<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
class Driver extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory ,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table ="_drivers";

    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'gender',
        'typeofcar',
        'number',
        'address',
        'status',


    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',

    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
  // public function profile()
   // {
     //   return $this->hasOne(Profile::class);
    //}
    /**
     * Get all of the comments for the Driver
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

 /**
  * Get all of the comments for the Driver
  *
  * @return \Illuminate\Database\Eloquent\Relations\HasMany
  */
 public function trips()
 {
     return $this->hasMany(Trip::class);
 }
 public function pins()
 {
     return $this->hasMany(Pin::class);
 }

}
