<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\api\CategoryResource;
use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        $categories = Categories::all();

        return new CategoryCollection($categories);
    }

    public function show(Request $request, Categories $category)
    {
        return new CategoryResource($category);
    }
}
