<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tag_name'];

    /**
     * Get the comments for the post.
     */
    public function posts(){
        return $this->belongsToMany(Post::class)->withTimestamps();
    }
}
