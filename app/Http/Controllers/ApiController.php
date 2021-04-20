<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\API;

class ApiController extends Controller
{
    public function getData(){
        return API::all();
    }
}
