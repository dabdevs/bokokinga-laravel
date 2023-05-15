<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Image;
use App\Models\Photo;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Aws\S3\S3Client;

class ProductsController extends Controller
{
    private $upload_dir = 'public/products';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::with('collection')->orderBy('id', 'desc')->get();
        $collections = Collection::orderBy('name', 'asc')->get();
        return view('dashboard.products.index', compact('products', 'collections'));
    }

    /**
     * Show the product
     */
    public function show(Product $product)
    {
        return view('web.products.show', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $s3 = new S3Client([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ]
        ]);

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

            $data['slug'] = strtolower(str_replace(" ", "-", $request->name));

            $product = new Product;

            $product->fill($data);
            $product->save();

            if ($request->file('images')) {
                foreach ($request->file('images') as $file) {
                    $image_path = Photo::resizeAndUpload($file, $this->upload_dir, env('STANDARD_IMAGE_MAX_WIDTH'), env('STANDARD_IMAGE_MAX_HEIGTH'), true);
                    $path = $s3->getObjectUrl(env('S3_BUCKET'), $image_path);

                    $image = [
                        'product_id' => $product->id,
                        'path' => $path
                    ];

                    // If uploaded image is set as primary
                    if (str_contains($request->primaryImage, $file->getClientOriginalName())) {
                        $image['is_primary'] = 1;
                    }

                    $product->images()->create($image);
                }
            }


            $product->searchable();

            DB::commit();

            return URL::backWithSuccess('Product created successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
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
        $s3 = new S3Client([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ]
        ]);
        
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
                foreach ($request->file('images') as $file) {
                    $image_path = Photo::resizeAndUpload($file, $this->upload_dir, env('STANDARD_IMAGE_MAX_WIDTH'), env('STANDARD_IMAGE_MAX_HEIGTH'), true);
                    $path = $s3->getObjectUrl(env('S3_BUCKET'), $image_path);

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
