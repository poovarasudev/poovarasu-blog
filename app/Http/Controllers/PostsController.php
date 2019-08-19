<?php

namespace App\Http\Controllers;

use App\Events\PostCreate;
use App\Events\PostDelete;
use App\Events\PostUpdate;
use App\Http\Requests\Post\StoreBlogPost;
use App\Http\Requests\Post\UpdateBlogPost;
use App\Post;
use App\Image;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use mysql_xdevapi\Exception;
use Yajra\DataTables\Facades\DataTables;

class PostsController extends Controller
{
    /**
     * Instantiate a new PostController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create post', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit post',   ['only' => ['edit', 'update']]);
        $this->middleware('permission:view post',   ['only' => ['show']]);
        $this->middleware('permission:delete post',   ['only' => ['destroy']]);
    }

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
        $input_tag = explode(",", $request->tagInput);
        $old_tags = DB::table('tags')->pluck('tag_name');
        $new_tags = array_diff($input_tag, $old_tags->toArray());
        if (!empty($new_tags)) {
            foreach ($new_tags as $new_tag) {
                if ($new_tag != ""){
                Tag::create(['tag_name' => $new_tag]);
                }
            }
        }
        $input_tag_id = DB::table('tags')->whereIn('tag_name', $input_tag)->pluck('id')->toArray();
        $post = Post::create([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);
        $post->tags()->sync($input_tag_id);
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
        if (Cache::has('tags')){
            Cache::forget('tags');
        }
        event(new PostCreate($post, auth()->user()->name));
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
        return view('show', compact('post'));
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
            $input_tag = explode(",", $request->tagInput);
            $old_tags = DB::table('tags')->pluck('tag_name');
            $new_tags = array_diff($input_tag, $old_tags->toArray());
            if (!empty($new_tags)) {
                foreach ($new_tags as $new_tag) {
                    if ($new_tag != ''){
                    Tag::create(['tag_name' => $new_tag]);
                    }
                }
            }
            $input_tag_id = DB::table('tags')->whereIn('tag_name', $input_tag)->pluck('id')->toArray();
            $post->tags()->sync($input_tag_id);
            if (Cache::has('tags')){
                Cache::forget('tags');
            }
            event(new PostUpdate($post, auth()->user()->name));
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
            Image::wherePostId($post->id)->delete();
            event(new PostDelete($post, auth()->user()->name));
            $post->delete();
            if (Cache::has('tags')){
                Cache::forget('tags');
            }
            return response()->json(['action' => 'success', 'message' => 'Post deleted succesfully']);
        } catch (\Throwable $exception) {
            return response()->json(['action' => 'error', 'message' => 'Unable to delete post'], 500);
        }
    }

    public function getPosts()
    {
        $posts = Post::select('id', 'title', 'created_at', 'email');
        return DataTables::of($posts)->addColumn('action', function ($post) {
            if (auth()->user()->hasPermissionTo('view post')){
                return '<button type="button" id="view" data-id="' . $post->id . '"
                                                    class="btn btn-default btn-circle waves-effect waves-circle waves-float" onclick="redirect(' . $post->id . ')">
                                                <i class="material-icons">visibility</i></button>';
            } else{
                return '<i class="material-icons">remove</i>';
            }
        })
            ->editColumn('created_at', function (Post $post) {
                return $post->created_at->format('d/m/y');
            })->make(true);
    }

}
