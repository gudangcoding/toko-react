<?php

namespace App\Services;

use GuzzleHttp\Client;

class RajaongkirService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getOngkir($destination, $weight)
    {
        $response = $this->client->post('https://api.rajaongkir.com/starter/cost', [
            'headers' => [
                'key' => env('RAJAONGKIR_API_KEY')
            ],
            'form_params' => [
                'origin' => env('RAJAONGKIR_ORIGIN_CITY'),
                'destination' => $destination,
                'weight' => $weight,
                'courier' => 'jne'
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
