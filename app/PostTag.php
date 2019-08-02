<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;

class PostTag extends Model
{
    protected $table='post_tag';
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $fillable = ['post_id','tag_id'];

}
