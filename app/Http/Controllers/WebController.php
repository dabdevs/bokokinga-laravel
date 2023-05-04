<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\Request;

class WebController extends Controller
{
   public function index()
   {
        $collections = Collection::orderBy('name', 'asc')->get(); 
        $configurations = [];
        return view('web.index', compact('collections', 'configurations'));
   }

    public function login()
    {
        return view('web.login');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $results = Product::search($query)->get(); 

        return view('web.search', compact('results'));
    }
}
