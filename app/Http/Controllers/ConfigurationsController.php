<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ConfigurationsController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $configurations = Configuration::orderBy('name', 'asc')->get();
        return view('dashboard.configurations.index', compact('configurations'));
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
                'name' => 'required|string',
                'value' => 'required|string'
            ]);

            $configuration = new Configuration;
            $configuration->create($data); 

            return URL::backWithSuccess('Configuration created successfully!');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Configuration $configuration)
    {
        return response()->json($configuration);
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
    public function update(Configuration $configuration, Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'value' => 'required|string'
            ]);

            $configuration->update($data);

            return URL::backWithSuccess('Configuration updated successfully!');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Configuration $configuration)
    {
        try {
            $configuration->delete();
            return URL::backWithSuccess('Configuration deleted successfully!');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
