<?php

namespace Tests\Feature;

use App\Http\Resources\ApiPostShowResponse;
use App\Post;
use App\User;
use http\Env\Response;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Tymon\JWTAuth\Facades\JWTAuth;


class ApiPostCrudTest extends TestCase
{
    use DatabaseTransactions;
    public $token, $request, $updateRequest;

    public function setUp(): void
    {
        parent::setUp();

        $user = ['email' => 'abc@gmail.com', 'password' => '12345678'];

        $this->token = auth()->attempt($user);
        $this->request = [
            'title' => "Title",
            'description' => "This the description for the title",
            'tagInput' => "tag1,tag2,tag3"
        ];
        $this->updateRequest = [
            'title' => "new title",
            'description' => "This the description for the title updated",
            'tagInput' => "updated"
        ];
    }

    public function test_index_success()
    {
        $response = $this->get('/api/v1/post');

        $data = Post::all()->toArray();

        $response
            ->assertStatus(200)
            ->assertJson($data);
    }

    public function test_create_post_success()
    {
        $response = $this->post('/api/v1/post/create', [
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'tagInput' => $this->request['tagInput']
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $post = Post::with("tags", "images")->find(json_decode($response->getContent())->data->id);
        $data = (new ApiPostShowResponse($post))->response()->getData(true);
        $response->assertOk()->assertJson($data);
    }

    public function test_create_post_without_title()
    {
        $response = $this->json('POST', '/api/v1/post/create', [
            'title' => "",
            'description' => $this->request['description'],
            'tagInput' => $this->request['tagInput']
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $error = [
            "status" => "validation failed",
            "error" => [config('code.create_post_validation.title_required')]
        ];

        $response->assertStatus(422)->assertJson($error);
    }


    public function test_create_post_with_title_minimum_condition()
    {
        $response = $this->json('POST', '/api/v1/post/create', [
            'title' => "hai",
            'description' => $this->request['description'],
            'tagInput' => $this->request['tagInput']
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $error = [
            "status" => "validation failed",
            "error" => [config('code.create_post_validation.title_min')]
        ];

        $response->assertStatus(422)->assertJson($error);
    }

    public function test_create_post_with_title_maximum_condition()
    {
        $response = $this->json('POST', '/api/v1/post/create', [
            'title' => "haihaihaihai",
            'description' => $this->request['description'],
            'tagInput' => $this->request['tagInput']
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $error = [
            "status" => "validation failed",
            "error" => [config('code.create_post_validation.title_max')]
        ];

        $response->assertStatus(422)->assertJson($error);
    }

    public function test_create_post_without_description()
    {
        $response = $this->json('POST', '/api/v1/post/create', [
            'title' => $this->request['title'],
            'description' => "",
            'tagInput' => $this->request['tagInput']
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $error = [
            "status" => "validation failed",
            "error" => [config('code.create_post_validation.description_required')]
        ];

        $response->assertStatus(422)->assertJson($error);
    }

    public function test_create_post_with_description_minimum_condition()
    {
        $response = $this->json('POST', '/api/v1/post/create', [
            'title' => $this->request['title'],
            'description' => "description",
            'tagInput' => $this->request['tagInput']
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $error = [
            "status" => "validation failed",
            "error" => [config('code.create_post_validation.description_min')]
        ];

        $response->assertStatus(422)->assertJson($error);
    }

    public function test_create_post_without_tag()
    {
        $response = $this->post('/api/v1/post/create', [
            'title' => $this->request['title'],
            'description' => $this->request['description']
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $post = Post::with("tags", "images")->find(json_decode($response->getContent())->data->id);
        $data = (new ApiPostShowResponse($post))->response()->getData(true);
        $response->assertOk()->assertJson($data);
    }

    public function test_create_post_with_tag_minimum_condition()
    {
        $response = $this->json('POST', '/api/v1/post/create', [
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'tagInput' => "s"
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $error = [
            "status" => "validation failed",
            "error" => [config('code.create_post_validation.tagInput_min')]
        ];

        $response->assertStatus(422)->assertJson($error);
    }

    public function test_create_post_without_image()
    {
        $response = $this->post('/api/v1/post/create', [
            'title' => $this->request['title'],
            'description' => $this->request['description']
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $post = Post::with("tags", "images")->find(json_decode($response->getContent())->data->id);
        $data = (new ApiPostShowResponse($post))->response()->getData(true);
        $response->assertOk()->assertJson($data);
    }

    public function test_create_post_with_image()
    {
        $response = $this->json('POST', '/api/v1/post/create', [
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'tagInput' => $this->request['tagInput'],
            'image_name' => [UploadedFile::fake()->image('image.jpg')],
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $post = Post::with("tags", "images")->find(json_decode($response->getContent())->data->id);
        $data = (new ApiPostShowResponse($post))->response()->getData(true);
        $response->assertOk()->assertJson($data);
    }

    public function test_create_post_with_image_as_pdf()
    {
        $response = $this->json('POST', '/api/v1/post/create', [
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'tagInput' => $this->request['tagInput'],
            'image_name' => [UploadedFile::fake()->create('doc.pdf', 100)]
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $error = [
            "status" => "validation failed",
            "error" => [config('code.create_post_validation.image_mimes')]
        ];


        $response->assertStatus(422)->assertJson($error);

    }

    public function test_update_post_success()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);

        $response = $this->put("/api/v1/post/" . $post->id . "/update", [
            'title' => $this->updateRequest['title'],
            'description' => $this->updateRequest['description'],
            'tagInput' => $this->updateRequest['tagInput']
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $post = Post::with("tags", "images")->find(json_decode($response->getContent())->data->id);
        $data = (new ApiPostShowResponse($post))->response()->getData(true);
        $response->assertOk()->assertJson($data);
    }

    public function test_update_post_without_title()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);

        $response = $this->json('PUT', "/api/v1/post/" . $post->id . "/update", [
            'title' => "",
            'description' => $this->updateRequest['description'],
            'tagInput' => $this->updateRequest['tagInput']
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $error = [
            "status" => "validation failed",
            "error" => [config('code.update_post_validation.title_required')]
        ];

        $response->assertStatus(422)->assertJson($error);
    }

    public function test_update_post_with_title_minimum_condition()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);

        $response = $this->json('PUT', "/api/v1/post/" . $post->id . "/update", [
            'title' => "hai",
            'description' => $this->updateRequest['description'],
            'tagInput' => $this->updateRequest['tagInput']
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $error = [
            "status" => "validation failed",
            "error" => [config('code.update_post_validation.title_min')]
        ];

        $response->assertStatus(422)->assertJson($error);
    }

    public function test_update_post_with_title_maximum_condition()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);

        $response = $this->json('PUT', "/api/v1/post/" . $post->id . "/update", [
            'title' => "haihaihaihai",
            'description' => $this->updateRequest['description'],
            'tagInput' => $this->updateRequest['tagInput']
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $error = [
            "status" => "validation failed",
            "error" => [config('code.update_post_validation.title_max')]
        ];

        $response->assertStatus(422)->assertJson($error);
    }

    public function test_update_post_without_description()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);

        $response = $this->json('PUT', "/api/v1/post/" . $post->id . "/update", [
            'title' => $this->updateRequest['title'],
            'description' => "",
            'tagInput' => $this->updateRequest['tagInput']
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $error = [
            "status" => "validation failed",
            "error" => [config('code.update_post_validation.description_required')]
        ];

        $response->assertStatus(422)->assertJson($error);
    }

    public function test_update_post_with_description_minimum_condition()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);

        $response = $this->json('PUT', "/api/v1/post/" . $post->id . "/update", [
            'title' => $this->updateRequest['title'],
            'description' => "description",
            'tagInput' => $this->updateRequest['tagInput']
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $error = [
            "status" => "validation failed",
            "error" => [config('code.update_post_validation.description_min')]
        ];

        $response->assertStatus(422)->assertJson($error);
    }

    public function test_update_post_without_tag()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);

        $response = $this->put("/api/v1/post/" . $post->id . "/update", [
            'title' => $this->updateRequest['title'],
            'description' => $this->updateRequest['description']
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $post = Post::with("tags", "images")->find(json_decode($response->getContent())->data->id);
        $data = (new ApiPostShowResponse($post))->response()->getData(true);
        $response->assertOk()->assertJson($data);
    }

    public function test_update_post_with_tag_minimum_condition()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);

        $response = $this->json('PUT', "/api/v1/post/" . $post->id . "/update", [
            'title' => $this->updateRequest['title'],
            'description' => $this->updateRequest['description'],
            'tagInput' => "s"
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $error = [
            "status" => "validation failed",
            "error" => [config('code.update_post_validation.tagInput_min')]
        ];

        $response->assertStatus(422)->assertJson($error);
    }

    public function test_update_post_without_image()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);

        $response = $this->put("/api/v1/post/" . $post->id . "/update", [
            'title' => $this->updateRequest['title'],
            'description' => $this->updateRequest['description']
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $post = Post::with("tags", "images")->find(json_decode($response->getContent())->data->id);
        $data = (new ApiPostShowResponse($post))->response()->getData(true);
        $response->assertOk()->assertJson($data);
    }

    public function test_update_post_with_image()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);

        $response = $this->json('PUT', "/api/v1/post/" . $post->id . "/update", [
            'title' => $this->updateRequest['title'],
            'description' => $this->updateRequest['description'],
            'tagInput' => $this->updateRequest['tagInput'],
            'image_name[]' => [UploadedFile::fake()->image('image.jpg')]
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $post = Post::with("tags", "images")->find(json_decode($response->getContent())->data->id);
        $data = (new ApiPostShowResponse($post))->response()->getData(true);
        $response->assertOk()->assertJson($data);
    }

    public function test_update_post_with_image_as_pdf()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);

        $response = $this->json('PUT', "/api/v1/post/" . $post->id . "/update", [
            'title' => $this->updateRequest['title'],
            'description' => $this->updateRequest['description'],
            'tagInput' => $this->updateRequest['tagInput'],
            'image_name' => [UploadedFile::fake()->create('doc.pdf', 100)]
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $error = [
            "status" => "validation failed",
            "error" => [config('code.update_post_validation.image_mimes')]
        ];

        $response->assertStatus(422)->assertJson($error);

    }

    public function test_update_post_with_invalid_id()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);

        $response = $this->put("/api/v1/post/" . ($post->id + 1) . "/update", [
            'title' => $this->updateRequest['title'],
            'description' => $this->updateRequest['description'],
            'tagInput' => $this->updateRequest['tagInput']
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $error = [
            "status" => "unknown post",
            "controller_error" => config('code.update_post.invalid_id')
        ];

        $response->assertStatus(404)->assertJson($error);
    }

    public function test_post_show_success()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);

        $response = $this->get("/api/v1/post/" . $post->id);

        $post = Post::with("tags", "images")->find(json_decode($response->getContent())->data->id);
        $data = (new ApiPostShowResponse($post))->response()->getData(true);
        $response->assertOk()->assertJson($data);
    }

    public function test_post_show_failed()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);

        $response = $this->get("/api/v1/post/" . ($post->id + 1));

        $error = [
            "status" => "unknown post",
            "controller_error" => config('code.show_post.invalid_id')
        ];

        $response->assertStatus(404)->assertJson($error);
    }

    public function test_post_delete_success()
    {
        $user = User::get()->first();

        $headers = [
            'Accept' => 'application/json',
            'AUTHORIZATION' => 'Bearer ' . $this->token
        ];

        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);

        $response = $this->post("/api/v1/post/" . $post->id . "/delete", [
            '_method' => "delete"
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $response->assertOk()->assertJson(["Post Deleted Successfully"]);
    }

    public function test_post_delete_failed()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);

        $response = $this->post("/api/v1/post/" . ($post->id + 1) . "/delete", [
            '_method' => "delete"
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);

        $error = [
            "status" => "unknown post",
            "controller_error" => config('code.delete_post.invalid_id')
        ];

        $response->assertStatus(404)->assertJson($error);
    }
}
