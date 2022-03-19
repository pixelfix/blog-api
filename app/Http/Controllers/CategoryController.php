<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return new CategoryCollection(Category::all());
    }

    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required | max:255 | unique:categories'
        ]);

        if ($validator->fails()) {
            return $this->customResponse($validator->getMessageBag()->all(), 400);
        }

        $category = Category::create([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name'))
        ]);

        return new CategoryResource($category);
    }

    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required | max:255 | unique:categories,name,' . $category->id
        ]);

        if ($validator->fails()) {
            return $this->customResponse($validator->getMessageBag()->all(), 400);
        }

        $category->update([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name'))
        ]);

        return new CategoryResource($category);
    }
}
