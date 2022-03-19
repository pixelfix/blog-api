<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCommentCollection;
use App\Http\Resources\PostCommentResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostCommentController extends Controller
{
    public function index(Request $request, Post $post)
    {
        $page = (integer) $request->input('page');
        $comments =
            $post
            ->comments()
            ->orderBy('created_at', 'desc')
            ->offset(($page - 1) * 5)
            ->take(5)
            ->get();

        if ($comments->count() > 0) {
            return new PostCommentCollection($comments);
        }

        return $this->customResponse('No comments found', 400);
    }

    public function store(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required | min:25'
        ]);

        if ($validator->fails()) {
            return $this->customResponse($validator->getMessageBag()->all(), 400);
        }

        $result = $post->comments()->create([
            'user_id' => auth()->user()->id,
            'body' => $request->input('comment')
        ]);

        return new PostCommentResource($result);
    }
}
