<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Gallery;
use App\Models\Image;
use App\Models\Photo;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ProductsController extends Controller
{
    private $upload_dir = 'products';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //$products = Product::with('photos')->orderBy('name', 'asc')->paginate(env('RECORDS_PER_PAGE'), ['*'], 'page', $request->page)
        $products = Product::orderBy('name', 'asc')->get();
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
        dd($request->all());
        try {
            $data = $request->validate([
                'name' => 'required|string|max:150',
                'description' => 'nullable|string|max:255',
                'price' => 'required|numeric|gt:0',
                'quantity' => 'required|integer',
                'collection_id' => 'required|integer',
                'images.*' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
            ]);

            $data['slug'] = str_replace(' ', '-', $data['name']);
            
            $product = new Product;
            $product = $product->create($data);
            $product->searchable(); 

            if ($request->file('images')) {
                foreach ($request->file('images') as $key => $file) {
                    $path = Photo::resizeAndUpload($file, $this->upload_dir, true);

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
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
       return view('dashboard.products.edit')->with([
            'product' => $product,
            'collections' => Collection::orderBy('name', 'asc')->get()
       ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Product $product, Request $request)
    {
        try {
            DB::beginTransaction(); 

            $data = $request->validate([
                'name' => 'required|string|max:150',
                'description' => 'nullable|string|max:255',
                'price' => 'required|numeric|gt:0',
                'quantity' => 'required|integer',
                'collection_id' => 'required|integer',
                'images.*' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
            ]);

            if ($request->file('images')) {
                foreach ($request->file('images') as $key => $file) {
                    $path = Photo::resizeAndUpload($file, $this->upload_dir, true);

                    $image = [
                        'product_id' => $product->id,
                        'path' => $path
                    ];

                    // If uploaded image is set as primary
                    if (str_contains($request->primaryImage, $file->getClientOriginalName())) {
                        $primaryImage = $product->primaryImage;
                        $primaryImage->is_primary = 0;
                        $primaryImage->save();
                        $image['is_primary'] = 1;
                    }
                    
                    $product->images()->create($image);
                }
            }

            // If primary image is an already uploaded image
            if (is_numeric($request->primaryImage)) {
                $primaryImage = $product->primaryImage;
                $primaryImage->is_primary = 0;
                $primaryImage->save();

                $image = Image::findOrFail($request->primaryImage);
                $image->is_primary = 1;
                $image->save();
            }

            $product->update($data);
            $product->searchable(); 

            // Delete images
            if ($request->delete_images) {
                $photo_ids_to_delete = explode("-", $request->delete_images);
                
                foreach ($photo_ids_to_delete as $photo_id) {
                    $image = Image::find($photo_id); 

                    // Delete image from Amazon S3 storage
                    Storage::disk('s3')->delete($image->path);
                    $image->delete();
                }
            }

            DB::commit();
            
            return URL::backWithSuccess('Product updated successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
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
