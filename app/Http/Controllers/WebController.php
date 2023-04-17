<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;

class WebController extends Controller
{
   public function index()
   {
        $collections = Collection::all();
        return view('web.index', compact('collections'));
   }

    public function login()
    {
        return view('web.login');
    }
}
