<?php

namespace App\Models;

use App\Scopes\ActivePostScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'category_id',
        'prev_article',
        'next_article',
        'image'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new ActivePostScope);
    }

    public function setTitle(string $title): Post
    {
        if ($title) {
            $this->title = $title;
        }

        return $this;
    }

    public function setExcerpt(string $excerpt): Post
    {
        if ($excerpt) {
            $this->excerpt = $excerpt;
        }

        return $this;
    }

    public function setBody(string $body): Post
    {
        if ($body) {
            $this->body = $body;
        }

        return $this;
    }

    public function setCategoryId(string $categoryId): Post
    {
        if ($categoryId) {
            $this->category_id = $categoryId;
        }

        return $this;
    }

    public function setPrevArticle(string $prevArticle): Post
    {
        if ($prevArticle) {
            $this->prev_article = $prevArticle;
        }

        return $this;
    }

    public function setNextArticle(string $nextArticle): Post
    {
        if ($nextArticle) {
            $this->next_article = $nextArticle;
        }

        return $this;
    }

    public function setImage($request)
    {
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/posts');
            $this->image = basename($path);
        }

        return $this;
    }

    public function commit()
    {
        $this->save();

        return $this;
    }
}
