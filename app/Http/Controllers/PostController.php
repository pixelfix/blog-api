<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        return new PostCollection(Post::where('active', true)->get());
    }

    public function featured()
    {
        $posts =
            Post::where('featured', true)
                ->take(4)
                ->get();

        if ($posts->count() <= 0) {
            return $this->customResponse('No posts found', 400);
        }

        return new PostCollection($posts);
    }

    public function latest()
    {
        $posts =
            Post::orderBy('created_at', 'desc')
                ->take(4)
                ->get();

        if ($posts->count() <= 0) {
            return $this->customResponse('No posts found', 400);
        }

        return new PostCollection($posts);
    }

    public function gallery()
    {
        $posts =
            Post::inRandomOrder()
                ->take(8)
                ->get();

        if ($posts->count() <= 0) {
            return $this->customResponse('No posts found', 400);
        }

        return new PostCollection($posts);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required | max:255 | unique:posts',
            'excerpt' => 'required | max:255',
            'body' => 'required',
            'category' => 'required | exists:categories,slug',
            'prev_article' => 'url | nullable',
            'next_article' => 'url | nullable',
            'image' => 'required | image'
        ]);

        if ($validator->fails()) {
            return $this->customResponse($validator->getMessageBag()->all(), 400);
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/posts');
        }

        $post = Post::create([
            'user_id' => auth()->user()->id,
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('title')),
            'excerpt' => $request->input('excerpt'),
            'body' => $request->input('body'),
            'category_id' => Category::where('slug', $request->input('category'))->first()->id,
            'prev_article' => $request->input('prev_article'),
            'next_article' => $request->input('next_article'),
            'image' => basename($path)
        ]);

        return new PostResource($post);
    }

    public function show(Post $post)
    {
        return new PostResource($post);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post
            ->setTitle($request->title)
            ->setExcerpt($request->excerpt)
            ->setBody($request->body)
            ->setCategoryId($request->category_id)
            ->setPrevArticle($request->prev_article)
            ->setNextArticle($request->next_article)
            ->setImage($request)
            ->commit();

        return new PostResource($post);
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return $this->customResponse('Your post was successfully deleted', 200);
    }

    public function addTag(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'tag' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->customResponse($validator->getMessageBag()->all(), 400);
        }

        $tag = Tag::where('slug', $request->input('tag'))->first();

        if (!$tag) {
            return $this->customResponse('Tag not found', 400);
        }

        $post->tags()->sync($tag, false);
        return new PostResource($post);
    }
}
