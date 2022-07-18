<?php

namespace App\Http\Controllers;
use App\Models\user;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{

public function autocomplete($paramiter){

    $datas = Region::select("area")
    ->where("area","LIKE","%{$paramiter}%")->get();
return response()->json([
    'messege'=> 'Get Area  Succesfuly ',
    'Areas' => $datas,

]);
}
}
