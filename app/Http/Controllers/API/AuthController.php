<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use HttpResponses;
    public function login()
    {
        return 'This is my login method';
    }

    public function register()
    {
        return response()->json('This is my register method');
    }

    public function logout()
    {
        return response()->json('This is my register method');
    }
}
