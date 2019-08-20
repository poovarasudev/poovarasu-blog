<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $fillable = ['comment', 'post_id'];

    /**
     * Get the post for the comment.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
