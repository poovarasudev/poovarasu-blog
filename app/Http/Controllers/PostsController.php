<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogPost;
use App\Http\Requests\UpdateBlogPost;
use App\Post;
use App\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use mysql_xdevapi\Exception;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostsController extends Controller
{
//    use SoftDeletes;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        try {
            $posts = Post::all();
            return view('index', compact('posts'));
        } catch (\Throwable $exception) {
            return view('errors.500')->with(['url' => route('home')]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(StoreBlogPost $request)
    {
        $post = Post::create([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);

        if ($request->hasFile('image_name')) {
            foreach ($request->image_name as $image_name) {
                $originalfilename = $image_name->getClientOriginalName();
                $image_name->storeAs('public/posts_images', $originalfilename);
                Image::create([
                    'post_id' => $post->id,
                    'image_name' => $originalfilename,
                ]);
            }
        }
        return redirect('/post/' . $post->id);
    }

    /**
     * Display the specified resource.
     *
     * @param Post $post
     * @return Response
     */
    public function show(Post $post)
    {
        $images = Image::wherePostId($post->id)->pluck('image_name');
        return view('show', compact('post', 'images'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Post $post
     * @return Response
     */
    public function edit(Post $post)
    {
        return view('edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Post $post
     * @return Response
     */
    public function update(Post $post, UpdateBlogPost $request)
    {
        try {
            $post->update(['title' => $request->get('title'), 'description' => $request->get('description')]);
            return response()->json(['action' => 'success', 'message' => 'Post updated succesfully']);
        } catch (\Throwable $exception) {
            return view('errors.500')->with(['url' => route('home')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     * @return Response
     */
    public function destroy(Post $post)
    {
        try {
//            Image::wherePostId($post->id)->softDeletes();
            Image::wherePostId($post->id)->delete();
            $post->delete();
            return response()->json(['action' => 'success', 'message' => 'Post deleted succesfully']);
        } catch (\Throwable $exception) {
            return response()->json(['action' => 'error', 'message' => 'Unable to delete post'], 500);
        }
    }

    public function getPosts()
    {
        $posts = Post::select('id', 'title', 'created_at', 'email');
        return DataTables::of($posts)->addColumn('action', function ($post) {
            return '<button type="button" id="view" data-id="' . $post->id . '"
                                                class="btn btn-default btn-circle waves-effect waves-circle waves-float" onclick="redirect(' . $post->id . ')">
                                            <i class="material-icons">visibility</i></button>';
        })
            ->editColumn('created_at', function (Post $post) {
                return $post->created_at->format('d/m/y');
            })->make(true);
    }

}