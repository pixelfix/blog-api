<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'author' => new UserResource($this->user),
            'href' => '/posts/' . $this->slug . '/view',
            'slug' => $this->slug,
            'category' => new CategoryResource($this->category),
            'title' => $this->title,
            'title_short' => Str::limit($this->title, 40),
            'image' => filter_var($this->image, FILTER_VALIDATE_URL) ? $this->image : config('app.blog_img_url') . "/storage/posts/" . $this->image,
            'excerpt' => $this->excerpt,
            'body' => $this->body,
            'tags' => new TagCollection($this->tags),
            'created' => $this->created_at->diffForHumans(),
            'prev_article' => $this->prev_article,
            'next_article' => $this->next_article,
            'commentCount' => $this->comments->count()
        ];
    }
}
