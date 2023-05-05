<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;

class ShowCollectionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($id, $name)
    {
        $collection = Collection::with('products')
                                ->where('id', $id)
                                ->first(); 

        return view('web.collection.show', compact('collection'));
    }
}
