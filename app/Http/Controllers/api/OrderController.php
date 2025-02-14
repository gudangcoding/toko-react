<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Resources\api\OrderCollection;
use App\Http\Resources\api\OrderResource;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        $orders = Order::all();

        return new OrderCollection($orders);
    }

    public function show(Request $request, Order $order): Response
    {
        return new OrderResource($order);
    }
}
