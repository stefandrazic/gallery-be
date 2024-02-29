<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Http\Resources\GalleryResource;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galleries = Gallery::all();
        return GalleryResource::collection($galleries);
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
    public function store(StoreGalleryRequest $request)
    {
        $request->validated();
        $gallery = Gallery::create(
            $request->only('name', 'description', 'img_urls')
        );
        return new GalleryResource($gallery);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $gallery = Gallery::find($id);
        if (!$gallery) {
            return response('Gallery not found', 404);
        }
        return new GalleryResource($gallery);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGalleryRequest $request, $id)
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response('Gallery not found', 404);
        }
        $request->validated();
        $gallery->update($request->only('name', 'description', 'img_urls'));
        return new GalleryResource($gallery);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $gallery = Gallery::find($id);
        if (!$gallery) {
            return response('Gallery not found', 404);
        }
        $gallery->delete();
        return response('Gallery deleted successfully.');
    }
}
