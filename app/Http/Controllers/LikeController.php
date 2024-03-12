<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Http\Requests\StoreLikeRequest;
use App\Http\Requests\UpdateLikeRequest;
use App\Models\Gallery;

class LikeController extends Controller
{
    public function likeGallery(StoreLikeRequest $request)
    {
        if (!$request->user()) {
            return response("You are not logged in!", 401);
        }
        $user = $request->user();
        $gallery = Gallery::find($request->gallery_id);
        $existing_like = Like::where('author_id', $user->id)
            ->where('gallery_id', $gallery->id)
            ->first();
        if (!$existing_like) {
            $like = new Like();
            $like->author_id = $user->id;
            $like->gallery_id = $gallery->id;
            $like->liked = true;
            $like->save();
            return response('Liked');
        } else {
            if ($existing_like->liked) {
                $existing_like->delete();
                return response('Like removed');
            } else {
                $existing_like->liked = true;
                $existing_like->save();
                return response('Changed to like');
            }
        }
    }

    public function dislikeGallery(StoreLikeRequest $request)
    {
        if (!$request->user()) {
            return response("You are not logged in!", 401);
        }
        $user = $request->user();
        $gallery = Gallery::find($request->gallery_id);
        $existing_like = Like::where('author_id', $user->id)
            ->where('gallery_id', $gallery->id)
            ->first();
        if (!$existing_like) {
            $like = new Like();
            $like->author_id = $user->id;
            $like->gallery_id = $gallery->id;
            $like->liked = false;
            $like->save();
            return response('Disliked');
        } else {
            if ($existing_like->liked === 0) {
                $existing_like->delete();
                return response('Dislike removed');
            } else {
                $existing_like->liked = false;
                $existing_like->save();
                return response('Changed to dislike.');
            }
        }
    }
}
