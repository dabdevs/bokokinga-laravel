<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Aws\S3\S3Client;


class CollectionsController extends Controller
{
    private $upload_dir = 'public/collections';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $collections = Collection::orderBy('name', 'asc')->get();
        return view('dashboard.collections.index', compact('collections'));
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
        $s3 = new S3Client([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ]
        ]);

        try {
            $data = $request->validate([
                'name' => 'required|string|max:150',
                'description' => 'nullable|string|max:255',
                'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
                'banner' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            $data['slug'] = strtolower(str_replace(" ", "-", $request->name));

            if ($request->file('image')) {
                $image_path = Photo::resizeAndUpload($request->file('image'), $this->upload_dir, env('STANDARD_IMAGE_MAX_WIDTH'), env('STANDARD_IMAGE_MAX_HEIGTH'), true);
                $data['image'] = $s3->getObjectUrl(env('S3_BUCKET'), $image_path);
            }

            if ($request->file('banner'))
                $data['banner'] = Photo::resizeAndUpload($request->file('banner'), $this->upload_dir, env('COLLECTION_BANNER_MAX_WIDTH'), env('COLLECTION_BANNER_MAX_HEIGTH'), true);

            $collection = new Collection;
            $collection->create($data);

            return URL::backWithSuccess('Collection created successfully!');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Collection $collection)
    {
        return response()->json($collection);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Collection $collection)
    {
        return view('dashboard.collections.edit')->with([
            'collection' => $collection
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Collection $collection, Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:150',
                'description' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
                'banner' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            if ($request->file('image'))
                $data['image'] = Photo::resizeAndUpload($request->file('image'), $collection->image, env('STANDARD_IMAGE_MAX_WIDTH'), env('STANDARD_IMAGE_MAX_HEIGTH'), false);

            if ($request->file('banner')) {
                $path = $collection->banner == null ? $this->upload_dir : $collection->banner;
                $new = $collection->banner == null ? true : false;

                $data['banner'] = Photo::resizeAndUpload($request->file('banner'), $path, env('COLLECTION_BANNER_MAX_WIDTH'), env('COLLECTION_BANNER_MAX_HEIGTH'), $new);
            }

            $collection->update($data);

            return URL::backWithSuccess('Collection updated successfully!');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection)
    {
        try {
            if ($collection->image != null)
                Photo::remove($collection->image);

            $collection->delete();
            return URL::backWithSuccess('Collection deleted successfully!');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
