<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\PostCreate;
use App\Events\PostDelete;
use App\Events\PostUpdate;
use App\Http\Requests\Api\v1\StoreBlogPost;
use App\Http\Requests\Api\v1\UpdateBlogPost;
use App\Http\Resources\ApiPostShowResponse;
use App\Http\Controllers\Controller;
use App\Image;
use App\Tag;
use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use mysql_xdevapi\Exception;

class PostController extends Controller
{
    public function __construct()
    {
        //
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
        $post = Post::create([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);
        $this->syncTag($request, $post);
        $this->syncImage($request, $post);
        $this->forgetCache();
        event(new PostCreate($post, auth()->user()->name));
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
            $route_name = str_replace(' ', '_', strtoupper(Route::currentRouteName()));
            $error = [
                "status" => 'unknown post',
                "controller_error" => [
                    "code" => $route_name . '-' . "POST" . '-' . "NOT_FOUND",
                    "message" => "Post not found."
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
            $this->syncTag($request, $post);
            $this->forgetCache();
            event(new PostUpdate($post, auth()->user()->name));
            $current_post = Post::with("tags", "images")->find($post->id);
            return new ApiPostShowResponse($current_post);
        } catch (\Throwable $exception) {
            $route_name = str_replace(' ', '_', strtoupper(Route::currentRouteName()));
            $error = [
                "status" => 'unknown post',
                "controller_error" => [
                    "code" => $route_name . '-' . "POST" . '-' . "NOT_FOUND",
                    "message" => "Post not found."
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
        event(new PostDelete($post, auth()->user()->name));
            $post->delete();
            $this->forgetCache();
            return(response()->json(["Post Deleted Successfully"], 200));
        } catch (\Throwable $exception) {
            $route_name = str_replace(' ', '_', strtoupper(Route::currentRouteName()));
            $error = [
                "status" => 'unknown post',
                "controller_error" => [
                    "code" => $route_name . '-' . "POST" . '-' . "NOT_FOUND",
                    "message" => "Post not found."
                ]
            ];
            return(response()->json($error, 404));
        }
    }

    public function forgetCache(): void
    {
        if (Cache::has('tags')) {
            Cache::forget('tags');
        }
    }

    /**
     * @param $request
     * @param $post
     */
    public function syncTag($request, $post): void
    {
        $input_tag = explode(",", $request->tagInput);
        $old_tags = Tag::all()->pluck('tag_name');
        $new_tags = array_diff($input_tag, $old_tags->toArray());
        if (!empty($new_tags)) {
            foreach ($new_tags as $new_tag) {
                if ($new_tag != "") {
                    Tag::create(['tag_name' => $new_tag]);
                }
            }
        }
        $input_tag_id = Tag::all()->whereIn('tag_name', $input_tag)->pluck('id')->toArray();
        $post->tags()->sync($input_tag_id);
    }

    /**
     * @param $request
     * @param $post
     */
    public function syncImage($request, $post): void
    {
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
    }
}
