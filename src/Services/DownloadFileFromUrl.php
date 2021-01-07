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

    public function downloadFile(string $url)
    {
        $response = $this->client->request(
            'GET',
            $url
        );

        $tmpfname = tempnam(sys_get_temp_dir(), 'tmpxls');
        file_put_contents($tmpfname, $response->getContent());

        return $tmpfname;
    }
}
