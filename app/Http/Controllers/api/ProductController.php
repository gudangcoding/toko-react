<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\api\ProductCollection;
use App\Http\Resources\api\ProductResource;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $products = Product::all();

        return new ProductCollection($products);
    }

    public function show(Request $request, Product $product): Response
    {
        return new ProductResource($product);
    }
}
