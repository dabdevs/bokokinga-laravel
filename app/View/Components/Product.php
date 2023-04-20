<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Product extends Component
{
    public $image;
    public $name;
    public $description;
    public $price;

    /**
     * Create a new component instance.
     */
    public function __construct($image, $name, $description, $price)
    {
        $this->image = $image;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('web.components.product');
    }
}
