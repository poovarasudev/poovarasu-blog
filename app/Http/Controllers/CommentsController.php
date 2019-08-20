<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\Comment\StoreComment;
use App\Http\Requests\Comment\UpdateComment;
use App\Post;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    /**
     * Instantiate a new PostController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-comment', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-comment',   ['only' => ['edit', 'update']]);
        $this->middleware('permission:view-comment',   ['only' => ['show']]);
        $this->middleware('permission:delete-comment',   ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreComment $request)
    {
        try {
            $post = Post::with('comments')->find($request->post_id);
            $post->comments()->create([
                'comment' => $request->comment,
            ]);
            $post->refresh();
            return view('comments.show', compact('post'));
        } catch (\Throwable $exception) {
            return response()->json(['action' => 'error', 'message' => 'Unable to create comment'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Comment $comment, UpdateComment $request)
    {
        try {
            $comment->update(['comment' => $request->comment]);
            return response()->json(['action' => 'success', 'message' => 'Comment updated succesfully']);
        } catch (\Throwable $exception) {
            return response()->json(['action' => 'error', 'message' => 'Unable to update the comment'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        try {
            $comment->delete();
            return response()->json(['action' => 'success', 'message' => 'Comment deleted succesfully']);
        } catch (\Throwable $exception) {
            return response()->json(['action' => 'error', 'message' => 'Unable to delete the comment'], 500);
        }
    }
}
