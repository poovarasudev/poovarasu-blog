<?php

namespace App\Http\Requests\Api\v1;


use App\Exceptions\ApiExceptionHandler;

class UpdateBlogPost extends ApiExceptionHandler
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:5|max:10',
            'description' => 'required|min:15',
            'image_name.*' => 'mimes:jpeg,jpg,png',
            'tagInput' => 'min:2',
        ];
    }
}
