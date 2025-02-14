<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        $orders = Order::all();

        return view('order.index', [
            'orders' => $orders,
        ]);
    }

    public function create(Request $request): Response
    {
        return view('order.create');
    }

    public function store(OrderStoreRequest $request): Response
    {
        $order = Order::create($request->validated());

        $request->session()->flash('order.id', $order->id);

        return redirect()->route('orders.index');
    }

    public function show(Request $request, Order $order): Response
    {
        return view('order.show', [
            'order' => $order,
        ]);
    }

    public function edit(Request $request, Order $order): Response
    {
        return view('order.edit', [
            'order' => $order,
        ]);
    }

    public function update(OrderUpdateRequest $request, Order $order): Response
    {
        $order->update($request->validated());

        $request->session()->flash('order.id', $order->id);

        return redirect()->route('orders.index');
    }

    public function destroy(Request $request, Order $order): Response
    {
        $order->delete();

        return redirect()->route('orders.index');
    }
}
