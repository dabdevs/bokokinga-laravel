<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Configuration;
use App\Models\Product;
use Illuminate\Http\Request;

class WebController extends Controller
{
   public function index()
   {
        $collections = Collection::orderBy('name')->get(); 
        $configurations = Configuration::all();
        
        return view('web.index', compact('collections', 'configurations'));
   }

    public function login()
    {
        return view('web.login');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $products = Product::search($query)->paginate(env('RECORDS_PER_PAGE')); 

        return view('web.product.search', compact('products'));
    }
}
