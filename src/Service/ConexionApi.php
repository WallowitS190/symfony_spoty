<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ConexionApi
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getToken(): string
    {
        $response = $this->client->request('POST', 'https://accounts.spotify.com/api/token', [
            // defining data using a regular string
            'body' => 'x-www-form-urlencoded data',

            // defining data using an array of parameters
            'body' => [
                'grant_type' => 'client_credentials',
                'client_id' => 'ebdb08a2e1b04a729d47bea264066733',
                'client_secret' => '36d25637ed8f49a9b39d42277593833e',
            ],
        ]);

        $token = $response->toArray();

        return "Bearer " . $token['access_token'];
    }
}
