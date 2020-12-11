<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','description','email','user_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at'];
    private $id;

    /**
     * Get the images for the post.
     */
    public function images(){
        return $this->hasMany(Image::class);
    }

    /**
     * Get the comments for the post.
     */
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the comments for the post.
     */
    public function tags(){
        return $this->belongsToMany(Tag::class,'post_tag')->withTimestamps();
    }
}
