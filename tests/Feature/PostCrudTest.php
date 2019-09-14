<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostCrudTest extends TestCase
{
    use DatabaseTransactions;
    public $request, $updateRequest;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::first();
        $this->actingAs($user);
        $this->request = [
            'title' => "myTitle",
            'description' => "This the description for the title",
            'tagInput' => "tag1,tag2,tag3"
        ];
        $this->updateRequest = [
            'title' => "updatedTitle",
            'description' => "This the description for the title updated",
            'tagInput' => "updated"
        ];
    }

    public function testPostIndexSuccess()
    {
        $response = $this->get('/post');
        $response->assertOk()
            ->assertViewIs('index')
            ->assertSee("POST");
    }

    public function testPostCreateSuccess()
    {
        $response = $this->get('/post/create');
        $response->assertOk()
            ->assertViewIs('create')
            ->assertSee("Create your POST")
            ->assertSee("Title")
            ->assertSee("Description")
            ->assertSee("Tags")
            ->assertSee("Upload images(optional)");
    }

    public function testPostStoreSuccess()
    {
        $response = $this->post('/post', [
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'tagInput' => "tag1"
        ]);
        $post = Post::where("title", $this->request['title'])->get()->toArray();
        $this->assertDatabaseHas('posts', [
            'title' => $this->request['title'],
            'description' => $this->request['description']
        ]);
        $response->assertStatus(302)
            ->assertSessionHasNoErrors()
            ->assertRedirect("/post/".$post[0]['id']);
    }

    public function testPostStoreWithImageSuccess()
    {
        $response = $this->post('/post', [
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'image_name' => [UploadedFile::fake()->image('image.jpg')]
        ]);
        $post = Post::where("title", $this->request['title'])->get()->toArray();
        $this->assertDatabaseHas('posts', [
            'title' => $this->request['title'],
            'description' => $this->request['description'],
        ]);
        $response->assertStatus(302)
            ->assertSessionHasNoErrors()
            ->assertRedirect("/post/".$post[0]['id']);
    }

    public function testPostStoreWithImageAsPdfSuccess()
    {
        $response = $this->post('/post', [
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'image_name' => [UploadedFile::fake()->create('doc.pdf', 100)]
        ]);
        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'image_name.0' => 'The image_name.0 must be a file of type: jpeg, jpg, png.'
            ]);
    }

    public function testPostStoreTitleRequiredConditionFailed()
    {
        $response = $this->post('/post', [
            'title' => "",
            'description' => $this->request['description'],
        ]);
        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'title' => 'The title field is required.',
        ]);
    }

    public function testPostStoreDescriptionRequiredConditionFailed()
    {
        $response = $this->post('/post', [
            'title' => $this->request['title'],
            'description' => "",
        ]);
        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'description' => 'The description field is required.'
        ]);
    }

    public function testPostStoreTitleMinConditionFailed()
    {
        $response = $this->post('/post', [
            'title' => "hi",
            'description' => $this->request['description'],
        ]);
        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'title' => 'The title must be at least 5 characters.',
        ]);
    }

    public function testPostStoreDescriptionMinConditionFailed()
    {
        $response = $this->post('/post', [
            'title' => $this->request['title'],
            'description' => "hi",
        ]);
        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'description' => 'The description must be at least 15 characters.'
        ]);
    }

    public function testPostStoreTagMinConditionFailed()
    {
        $response = $this->post('/post', [
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'tagInput' => "a"
        ]);
        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'tagInput' => 'The tag input must be at least 2 characters.'
        ]);
    }

    public function testPostUpdateSuccess()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);

        $response = $this->put("/post/$post->id", [
            'title' => $this->updateRequest['title'],
            'description' => $this->updateRequest['description']
        ]);
        $this->assertDatabaseHas('posts', [
            'title' => $this->updateRequest['title'],
            'description' => $this->updateRequest['description']
        ]);
        $response->assertOk()
            ->assertSessionHasNoErrors();
    }

    public function testPostUpdateTitleRequiredConditionFailed()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);
        $response = $this->put("/post/$post->id", [
            'title' => "",
            'description' => $this->updateRequest['description'],
        ]);
        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'title' => 'The title field is required.',
            ]);
    }

    public function testPostUpdateDescriptionRequiredConditionFailed()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);
        $response = $this->put("/post/$post->id", [
            'title' => $this->updateRequest['title'],
            'description' => "",
        ]);
        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'description' => 'The description field is required.'
            ]);
    }

    public function testPostUpdateTitleMinConditionFailed()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);
        $response = $this->put("/post/$post->id", [
            'title' => "hi",
            'description' => $this->updateRequest['description'],
        ]);
        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'title' => 'The title must be at least 5 characters.',
            ]);
    }

    public function testPostUpdateDescriptionMinConditionFailed()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);
        $response = $this->put("/post/$post->id", [
            'title' => $this->updateRequest['title'],
            'description' => "hi",
        ]);
        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'description' => 'The description must be at least 15 characters.'
            ]);
    }

    public function testPostUpdateTagMinConditionFailed()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);
        $response = $this->put("/post/$post->id", [
            'title' => $this->updateRequest['title'],
            'description' => $this->updateRequest['description'],
            'tagInput' => "h"
        ]);
        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'tagInput' => 'The tag input must be at least 2 characters.'
            ]);
    }

    public function testPostShowSuccess()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);
        $response = $this->get("/post/$post->id");
        $response->assertOk()
            ->assertSee($this->request['title'])
            ->assertSee($this->request['description']);
    }

    public function testPostShowFailed()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);
        $response = $this->get("/post/".($post->id + 1));
        $response->assertStatus(404);
    }

    public function testPostDeleteSuccess()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);
        $response = $this->post("/post/$post->id", [
            '_method' => 'delete'
        ]);
        $this->assertSoftDeleted('posts', [
            'title' => $this->request['title'],
            'description' => $this->request['description']
        ]);
        $response->assertOk();
    }

    public function testPostDeleteFailed()
    {
        $post = Post::create([
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);
        $response = $this->post("/post/".($post->id + 1), [
            '_method' => 'delete'
        ]);
        $response->assertStatus(404);
    }
}
