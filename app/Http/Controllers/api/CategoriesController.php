<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\api\CategoryCollection;
use App\Http\Resources\api\CategoryResource;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoriesController extends Controller
{
    public function index(Request $request): Response
    {
        $categories = Category::all();

        return new CategoryCollection($categories);
    }

    public function show(Request $request, Category $category): Response
    {
        return new CategoryResource($category);
    }
}
