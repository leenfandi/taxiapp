<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;


class Mainuser extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table ="mainusers";
    protected $fillable = [
        'name',
        'email',
        'password',
        'number',
    ];
    protected $hidden = [
        'password',
    ];


    //public function drivers(){
     // return  $this->hasMany(Driver::class);
    //}
   // public function admins(){
      //  return $this->hasMany(Admin::class);
   // }
    //public function customers(){
     // return  $this->hasMany(User::class);
   // }

}
