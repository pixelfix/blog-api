<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            'title' => 'sometimes | max:255 | unique:posts,title,' . $this->post->id,
            'excerpt' => 'sometimes | max:255',
            'body' => 'sometimes',
            'category' => 'sometimes | exists:categories,slug',
            'prev_article' => 'sometimes | url | nullable',
            'next_article' => 'sometimes | url | nullable',
            'image' => 'sometimes | image'
        ];
    }
}
