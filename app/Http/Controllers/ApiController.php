<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public static function register(Request $request)
    {
        return \response()->json([
            'status'    =>  true,
            'message'   =>  $_POST['nombre'],
        ], Response::HTTP_OK);
    }
}
