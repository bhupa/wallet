<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    

    public function index(){
        $user = auth()->user();

        return response(['user'=>$user],200);
    }
}
