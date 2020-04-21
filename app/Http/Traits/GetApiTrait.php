<?php

namespace App\Http\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Collection;

trait GetApiTrait
{
    function getApi($method, $url, $data = []) {
        $client = new Client([
            'base_uri' => env('API_URL'),
        ]);
        
        $options = [
            'headers' => [
                'Authorization' => 'Bearer '. session()->get('token'),
                'Accept' => 'application/json',
            ],
        ];

        if ($data) {
            $options['json'] = $data;
        }

        $response = $client->request($method, $url, $options);
        $data = $response->getBody()->getContents();
        $data = json_decode($data);
        return $data;
    }
}