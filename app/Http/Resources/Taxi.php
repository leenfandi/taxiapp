<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Taxi extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
       // return parent::toArray($request);
       return [
           'id'=> $this->id,
           'driver_id'=>$this->id,
           'gender'=>$this->gender,
           'name'=> $this->name,
           'number'=> $this->number,
           'typeofcar'=> $this->typeofcar,
           'email'=> $this->email,
           'image'=>$this->image,
           'like'=>$this->like,
           'comment'=>$this->comment,

       ];
    }
}
