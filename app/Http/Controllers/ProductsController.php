<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Gallery;
use App\Models\Photo;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class ProductsController extends Controller
{
    private $upload_dir = 'products';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    { 
        $products = Product::with('photos')->orderBy('name', 'asc')->paginate(env('RECORDS_PER_PAGE'), ['*'], 'page', $request->page);
        $collections = Collection::orderBy('name', 'asc')->get();
        return view('dashboard.products.index', compact('products', 'collections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:150',
                'description' => 'nullable|string|max:255',
                'price' => 'required|integer',
                'quantity' => 'required|integer',
                'collection_id' => 'required|integer',
                'image.*' => 'image|mimes:jpeg,jpg,png|max:2048'
            ]);

            $data['slug'] = str_replace(' ', '-', $data['name']);
            
            $product = new Product;
            $product = $product->create($data);

            if ($request->file('image')) {
                foreach ($request->file('image') as $key => $file) {
                    $path = Photo::upload($file, $this->upload_dir, true);

                    Gallery::create([
                        'product_id' => $product->id,
                        'path' => $path
                    ]);

                    if ($key == 0) {
                        $product->image = $path;
                        $product->save();
                    }
                }
            } 

            return URL::backWithSuccess('Product created successfully!');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->photos = DB::select('SELECT * FROM galleries WHERE product_id = '.$product->id); 
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Product $product, Request $request)
    {
        dd($request->all());
        try {
            $data = $request->validate([
                'name' => 'required|string|max:150',
                'description' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            if ($request->file('image'))
                $data['image'] = Photo::upload($request->file('image'), $product->image, false);

            $product->update($data);

            return URL::backWithSuccess('Product updated successfully!');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            if ($product->image != null)
                Photo::remove($product->image);

            $product->delete();
            return URL::backWithSuccess('Product deleted successfully!');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
