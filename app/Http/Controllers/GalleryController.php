<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Http\Resources\GalleryResource;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $perPage = 10;
    public function index(Request $request)
    {
        $page = $request->input('page') ? $request->input('page') : 1;
        $perPage = $request->input('perPage') ? $request->input('perPage') : $this->perPage;
        $skip = $page * $perPage - $perPage;

        $galleries = Gallery::take($perPage)->skip($skip)->get();
        // $galleries = Gallery::all();
        $metaData = [
            'metadata' => [
                'total' => Gallery::count(),
                'count' => $galleries->count(),
                'perPage' => $perPage,
            ]
        ];

        return GalleryResource::collection($galleries)->additional($metaData);
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
        $validatedData = $request->validated();

        $galleryData = $request->only('name', 'description', 'img_urls');
        $galleryData['author_id'] = 1;

        $gallery = Gallery::create($galleryData);

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
