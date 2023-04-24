<?php
// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        dd($request->all());
        $validatedData = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'images.*' => 'required|file|mimes:jpg,jpeg,png',
        ], [
            'images.*.required' => 'Please upload at least one image',
        ]);

        $product = Product::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'quantity' => $validatedData['quantity'],
        ]);

        foreach ($validatedData['images'] as $image) {
            $imagePath = $image->store('public');
            $resizedImage = imagecreatefromstring(file_get_contents(storage_path('app/' . $imagePath)));
            $resizedImage = imagescale($resizedImage, 1000, 1000);
            $resizedImagePath = 'public/' . uniqid() . '.jpg';
            imagejpeg($resizedImage, storage_path('app/' . $resizedImagePath));
            $url = Storage::disk('s3')->putFile('', $resizedImagePath, 'public');
            Image::create([
                'product_id' => $product->id,
                'url' => $url,
            ]);
            Storage::delete($imagePath);
            Storage::delete($resizedImagePath);
        }

        session()->flash('success', 'Product created successfully!');
        return redirect()->route('home');
    }
}
