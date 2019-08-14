<?php

namespace App\Http\Controllers;

use App\Events\PostCreated;
use App\Events\PostDeleted;
use App\Events\PostUpdated;
use App\Http\Requests\Api\StoreBlogPost;
use App\Http\Requests\Api\UpdateBlogPost;
use App\Http\Resources\ApiPostShowResponse;
use App\Image;
use App\Tag;
use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('JWTAuthentication', ['only' => ['store', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Post::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return ApiPostShowResponse
     */
    public function store(StoreBlogPost $request)
    {
        info($request);
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
                info($originalfilename);
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
        event(new PostCreated($post, auth()->user()->name));
        $current_post = Post::with("tags", "images")->find($post->id);
        return new ApiPostShowResponse($current_post);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return ApiPostShowResponse
     */
    public function show($id)
    {
        try {
            $result = Post::with("tags", "images")->find($id);
            if (is_null($result)){
                throw new Exception();
            }
            return (new ApiPostShowResponse($result));
        } catch (\Throwable $exception) {
            $error = [
                "status" => 'unknown post',
                "controller_error" => [
                    "Post not found"
                ]
            ];
            return(response()->json($error, 404));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return ApiPostShowResponse
     */
    public function update(UpdateBlogPost $request, $id)
    {
        try {
            $post = Post::get()->find($id);
            if (is_null($post)){
            throw new Exception();
            }
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
        event(new PostUpdated($post, auth()->user()->name));

            $current_post = Post::with("tags", "images")->find($post->id);
            return new ApiPostShowResponse($current_post);
        } catch (\Throwable $exception) {
            $error = [
                "status" => 'validation failed',
                "controller_error" => [
                    "Post not found"
                ]
            ];
            return(response()->json($error, 404));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $post = Post::get()->find($id);
            if (is_null($post)){
                throw new Exception();
            }
            Image::wherePostId($post->id)->delete();
        event(new PostDeleted($post, auth()->user()->name));
            $post->delete();
        if (Cache::has('tags')){
            Cache::forget('tags');
        }
            return(response()->json(["Post Deleted Successfully"], 200));
        } catch (\Throwable $exception) {
            $error = [
                "status" => 'unknown post',
                "controller_error" => [
                    "Post not found"
                ]
            ];
            return(response()->json($error, 404));
        }
    }
}
