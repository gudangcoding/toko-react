<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = false; // Ubah ke true jika sudah live
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Buat transaksi Midtrans
     * @param array $orderData
     * @return array
     */
    public function createTransaction(array $orderData)
    {
        return Snap::createTransaction($orderData);
    }

    /**
     * Handle callback dari Midtrans
     * @param array $callbackData
     * @return bool
     */
    public function handleCallback(array $callbackData)
    {
        $transactionStatus = $callbackData['transaction_status'] ?? null;
        $orderId = $callbackData['order_id'] ?? null;

        if (!$orderId || !$transactionStatus) {
            return false;
        }

        $order = \App\Models\Orders::where('transaction_id', $orderId)->first();
        if (!$order) {
            return false;
        }

        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
                $order->update(['payment_status' => 'paid']);
                break;

            case 'pending':
                $order->update(['payment_status' => 'pending']);
                break;

            case 'deny':
            case 'cancel':
            case 'expire':
                $order->update(['payment_status' => 'failed']);
                break;
        }

        return true;
    }
}
