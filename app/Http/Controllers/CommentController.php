<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreCommentRequest $request)
    {
        if (!$request->user()) {
            return response("You are not logged in!", 401);
        }

        // $request->validated();
        $commentData = $request->only('content', 'gallery_id');
        $commentData['author_id'] = $request->user()->id;

        $comment = Comment::create($commentData);
        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response('Movie not found', 404);
        }
        if (auth()->user()->id !== $comment->author->id) {
            return response('Unauthorised', 401);
        }
        $comment->delete();
        return response('Comment deleted successfully');
    }
}
