<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;

class ProductsList extends Component
{
    protected $listeners = [
        'productAdded' => '$refresh'
    ];

    public function render()
    {
        $products = Product::all();
        return view('livewire.products-list', compact('products'));
    }
}
