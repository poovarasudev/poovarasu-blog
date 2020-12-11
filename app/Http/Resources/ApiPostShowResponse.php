<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiPostShowResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "email" => $this->email,
            "title" => $this->title,
            "description" => $this->description,
            "created_at" => $this->created_at->toDateTimeString(),
            "updated_at" => $this->updated_at->toDateTimeString(),
            "tags" => ApiPostShowTag::collection($this->tags),
            "images" => ApiPostShowImage::collection($this->images)
        ];
    }
}
