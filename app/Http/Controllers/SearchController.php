<?php

namespace App\Http\Controllers;
use App\Models\user;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search($paramiter)
    {

$region = Region::where('area',$paramiter)->get();
return $region;
}
}
