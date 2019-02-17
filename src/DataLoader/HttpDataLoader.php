<?php

namespace App\DataLoader;

use GuzzleHttp\Client;

class HttpDataLoader implements HttpLoader
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getResponseBody(string $url): string
    {
        $response = $this->client->get($url);
        $responseBody = $response->getBody();
        return $responseBody;
    }
}
