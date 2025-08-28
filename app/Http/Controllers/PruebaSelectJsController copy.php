<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class PruebaSelectJsController extends Controller
{
    public function index(Request $request)
    {
        return view('pruebaselectjs.index');
    }
}
