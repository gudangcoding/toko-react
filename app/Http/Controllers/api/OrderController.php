<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MidtransService;
use App\Services\RajaongkirService;
use App\Models\Orders;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $midtransService;
    protected $rajaongkirService;

    public function __construct(MidtransService $midtransService, RajaongkirService $rajaongkirService)
    {
        $this->midtransService = $midtransService;
        $this->rajaongkirService = $rajaongkirService;
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.name' => 'required|string',
            'products.*.price' => 'required|numeric',
            'products.*.quantity' => 'required|integer|min:1',
            'destination' => 'required',
            'weight' => 'required|numeric|min:1',
            'shipping_address' => 'required|string',
        ]);

        // Hitung total harga produk
        $totalProductPrice = 0;
        foreach ($request->products as $product) {
            $totalProductPrice += $product['price'] * $product['quantity'];
        }

        // Hitung ongkir dari RajaOngkir
        $ongkir = $this->rajaongkirService->getOngkir($request->destination, $request->weight);

        if (!$ongkir) {
            return response()->json(['message' => 'Gagal menghitung ongkir'], 400);
        }

        // Hitung total pembayaran
        $totalPayment = $totalProductPrice + $ongkir['cost'];

        // Simpan order ke database
        $order = Orders::create([
            'user_id' => Auth::user()->id,
            'total_price' => $totalPayment,
            'shipping_address' => $request->shipping_address,
            'payment_status' => 'pending',
            'transaction_id' => uniqid(),
            'shipping_cost' => $ongkir['cost'],
        ]);

        // Simpan produk ke order items
        foreach ($request->products as $product) {
            OrderItems::create([
                'order_id' => $order->id,
                'product_id' => $product['id'],
                'product_name' => $product['name'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
            ]);
        }

        // Kirim transaksi ke Midtrans
        $transactionData = [
            'transaction_details' => [
                'order_id' => $order->transaction_id,
                'gross_amount' => $totalPayment,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                // 'shipping_address' => Auth::user()->address,
            ],
            'item_details' => array_merge(
                array_map(function ($product) {
                    return [
                        'id' => $product['id'],
                        'price' => $product['price'],
                        'quantity' => $product['quantity'],
                        'name' => $product['name'],
                    ];
                }, $request->products),
                [
                    [
                        'id' => 'ONGKIR',
                        'price' => $ongkir['cost'],
                        'quantity' => 1,
                        'name' => 'Biaya Pengiriman',
                    ]
                ]
            )
        ];

        $transaction = $this->midtransService->createTransaction($transactionData);

        return response()->json([
            'snap_token' => $transaction['token'],
            'order' => $order,
            'ongkir' => $ongkir,
        ]);
    }
}
