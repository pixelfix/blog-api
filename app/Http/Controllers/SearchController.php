<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\Models\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $search = htmlentities($request->input('search'));
        $page = (integer) $request->input('page');

        $posts = Post::where('active', true)
                    ->where(function($query) use ($search){
                        $query->where('title', 'like', '%' . $search . '%')
                            ->orWhere('excerpt', 'like', '%' . $search . '%');
                    })
                    ->offset(($page - 1)*12)
                    ->paginate(12);

        return new PostCollection($posts);
    }
}
