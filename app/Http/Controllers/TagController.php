<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagCollection;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        return new TagCollection(Tag::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required | max:255 | unique:tags'
        ]);

        if ($validator->fails()) {
            return $this->customResponse($validator->getMessageBag()->all(), 400);
        }

        $tag = Tag::create([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name'))
        ]);

        return new TagResource($tag);
    }

    public function show(Tag $tag)
    {
        return new TagResource($tag);
    }

    public function update(Request $request, Tag $tag)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required | max:255 | unique:tags,name,' . $tag->id
        ]);

        if ($validator->fails()) {
            return $this->customResponse($validator->getMessageBag()->all(), 400);
        }

        $tag->update([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name'))
        ]);

        return new TagResource($tag);
    }
}
