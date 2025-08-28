<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class PruebaFetchyAxiosController extends Controller
{
    public function index(Request $request)
    {
        return view('pruebafetchyaxios.index');
    }
}
