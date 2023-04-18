<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;


class CollectionsController extends Controller
{
    private $upload_dir = 'collections';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $collections = Collection::orderBy('name', 'asc')->paginate(env('RECORDS_PER_PAGE'), ['*'], 'page', $request->page);
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
        try {
            $data = $request->validate([
                'name' => 'required|string|max:150',
                'description' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            if ($request->file('image') != null)
                $data['image'] = Photo::upload($request->file('image'), $this->upload_dir, true);

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
    public function edit(string $id)
    {
        //
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
            ]);

            if ($request->file('image') != null) 
                $data['image'] = Photo::upload($request->file('image'), $collection->image, false);

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
 