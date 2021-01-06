<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class DownloadFileFromUrl
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function downloadFile(string $url): string
    {
        $response = $this->client->request(
            'GET',
            $url
        );

//        $statusCode = $response->getStatusCode();
//        $contentType = $response->getHeaders()['content-type'][0];

        return $response->getContent();
    }
}
