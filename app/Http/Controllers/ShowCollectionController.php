<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;

class ShowCollectionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $slug)
    {
        $collection = Collection::with('products')
                                ->where('slug', $slug)
                                ->first(); 

        return view('web.collection.show', compact('collection'));
    }
}
