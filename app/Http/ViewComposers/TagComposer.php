<?php
namespace App\Http\ViewComposers;

use App\Tag;
use Illuminate\Support\Facades\Cache;

class TagComposer
{
    public function compose($view)
    {
        $view->with('tags', Cache::remember('tags',now()->addMinutes(5),function (){
            return Tag::withCount('posts')->get();
        }));
    }
}