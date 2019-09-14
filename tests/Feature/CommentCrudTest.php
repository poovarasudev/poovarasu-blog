<?php

namespace Tests\Feature;

use App\Comment;
use App\Post;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentCrudTest extends TestCase
{
    use DatabaseTransactions;

    public $comment, $updateComment, $post;
    
    public function setUp(): void
    {
        parent::setUp();

        $user = User::first();
        $this->actingAs($user);
        $this->post = Post::create([
            'title' => "MyTitle",
            'description' => "this is the description for my post",
            'email' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);
        $this->comment = "Very Good Post";
        $this->updateComment = "Very Good Post Updated";
    }
    public function testCommentStoreSuccess()
    {
        $response = $this->post('/comment', [
            'post_id' => $this->post->id,
            'comment' => $this->comment
        ]);
        $this->assertDatabaseHas('comments', [
            'post_id' => $this->post->id,
            'comment' => $this->comment
        ]);
        $response->assertStatus(200)
            ->assertViewIs('comments.show');
    }

    public function testCommentStoreCommentRequiredConditionFailed()
    {
        $response = $this->post('/comment', [
            'post_id' => $this->post->id,
            'comment' => ""
        ]);
        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'comment' => 'The comment field is required.',
            ]);
    }

    public function testCommentStoreCommentMinConditionFailed()
    {
        $response = $this->post('/comment', [
            'post_id' => $this->post->id,
            'comment' => "abc"
        ]);
        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'comment' => 'The comment must be at least 5 characters.',
            ]);
    }

    public function testCommentStoreCommentMaxConditionFailed()
    {
        $response = $this->post('/comment', [
            'post_id' => $this->post->id,
            'comment' => "safffdsbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbkdhbskkkkkkkkkkkdvkkkkkkkkkkkkgg"
        ]);
        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'comment' => 'The comment may not be greater than 100 characters.',
            ]);
    }

    public function testCommentUpdateSuccess()
    {
        $comment = Comment::create([
            'post_id' => $this->post->id,
            'comment' => $this->comment,
        ]);
        $response = $this->put("/comment/$comment->id", [
            'comment' => $this->updateComment
        ]);
        $this->assertDatabaseHas('comments', [
            'post_id' => $this->post->id,
            'comment' => $this->updateComment
        ]);
        $response->assertStatus(200);
    }

    public function testCommentUpdateCommentRequiredConditionFailed()
    {
        $comment = Comment::create([
            'post_id' => $this->post->id,
            'comment' => $this->comment,
        ]);
        $response = $this->put("/comment/$comment->id", [
            'comment' => ""
        ]);
        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'comment' => 'The comment field is required.',
            ]);
    }

    public function testCommentUpdateCommentMinConditionFailed()
    {
        $comment = Comment::create([
            'post_id' => $this->post->id,
            'comment' => $this->comment,
        ]);
        $response = $this->put("/comment/$comment->id", [
            'comment' => "abc"
        ]);
        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'comment' => 'The comment must be at least 5 characters.',
            ]);
    }

    public function testCommentUpdateCommentMaxConditionFailed()
    {
        $comment = Comment::create([
            'post_id' => $this->post->id,
            'comment' => $this->comment,
        ]);
        $response = $this->put("/comment/$comment->id", [
            'comment' => "safffdsbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbkdhbskkkkkkkkkkkdvkkkkkkkkkkkkgg"
        ]);
        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'comment' => 'The comment may not be greater than 100 characters.',
            ]);
    }

    public function testCommentDeleteSuccess()
    {
        $comment = Comment::create([
            'post_id' => $this->post->id,
            'comment' => $this->comment,
        ]);
        $response = $this->post("/comment/$comment->id", [
            '_method' => 'delete'
        ]);
        $this->assertSoftDeleted('comments', [
            'post_id' => $this->post->id,
            'comment' => $this->comment
        ]);
        $response->assertOk();
    }

    public function testCommentDeleteFailed()
    {
        $comment = Comment::create([
            'post_id' => $this->post->id,
            'comment' => $this->comment,
        ]);
        $response = $this->post("/comment/".($comment->id + 1), [
            '_method' => 'delete'
        ]);
        $response->assertStatus(404);
    }

}
