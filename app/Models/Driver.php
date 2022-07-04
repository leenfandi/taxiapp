<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class Driver extends Authenticatable
{
    use HasApiTokens, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table ="_drivers";
    protected $guard = 'driver';
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'typeofcar',
        'number',
         'admin_id',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',

    ];
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

    public function admin(){
       return  $this->belongsTo(Admin::class,'admin_id');
       }
}
