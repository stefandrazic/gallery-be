<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Http\Resources\GalleryResource;
use App\Http\Resources\ShortGalleryResource;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $perPage = 10;
    public function index(Request $request)
    {
        $request->has('page') ?
            $page = $request->input('page') :
            $page = 1;
        $perPage = $request->input('perPage') ? $request->input('perPage') : $this->perPage;
        $skip = $page * $perPage - $perPage;

        // $galleries = Gallery::orderBy('id', 'desc')->take($perPage)->skip($skip)->get();
        // $galleries = Gallery::all();

        $query = Gallery::query();
        if ($request->has('userId')) {
            $userId = $request->input('userId');
            $query->where('author_id', $userId); // Filter galleries by author_id
        }

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%$searchTerm%")
                    ->orWhere('description', 'like', "%$searchTerm%")
                    ->orWhereHas('author', function ($q) use ($searchTerm) {
                        $q->where('first_name', 'like', "%$searchTerm%")
                            ->orWhere('last_name', 'like', "%$searchTerm%");
                    });
            });
        }
        $queryCount = $query->count();
        $galleries = $query->orderBy('id', 'desc')->take($perPage)->skip($skip)->get();

        $metaData = [
            'metadata' => [
                'total' => $queryCount,
                'count' => $galleries->count(),
                'perPage' => $perPage,
            ]
        ];

        return ShortGalleryResource::collection($galleries)->additional($metaData);
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
    // public function store(StoreGalleryRequest $request)
    // {
    //     $validatedData = $request->validated();
    //     if (!$request->user()) {
    //         return response("You are not logged in!", 404);
    //     }


    //     $galleryData = $request->only('name', 'description', 'img_urls');
    //     $galleryData['author_id'] = $request->user()->id;

    //     $gallery = Gallery::create($galleryData);

    //     return new GalleryResource($gallery);
    // }
    public function store(StoreGalleryRequest $request)
    {
        // Check if the user is authenticated
        if (!$request->user()) {
            return response("You are not logged in!", 401);
        }

        // Extract validated data from the request
        $request->validated();

        // Create gallery data array
        $galleryData = $request->only('name', 'description', 'img_urls');
        $galleryData['author_id'] = $request->user()->id;

        // Create a new gallery instance
        $gallery = Gallery::create($galleryData);

        // Return the newly created gallery as a resource
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

        if ($request->user()->id !== $gallery->author->id) {
            return response()->json(['error' => 'You have no rights to edit this post.']);
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
        if (auth()->user()->id !== $gallery->author->id) {
            return response('Unauthorised', 401);
        }

        $gallery->delete();
        return response('Gallery deleted successfully.');
    }
}
