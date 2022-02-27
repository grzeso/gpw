<?php

namespace App\Service\DataSource;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GpwFile
{
    private HttpClientInterface $client;
    private string $fileName;

    private string $gpwSource;

    public function __construct(HttpClientInterface $client, string $gpwSource)
    {
        $this->client = $client;
        $this->gpwSource = $gpwSource;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function downloadFileByDate(string $date): void
    {
        $response = $this->client->request(
            'GET',
            sprintf($this->gpwSource, $date)
        );

        $this->fileName = tempnam(sys_get_temp_dir(), 'tmpxls');
        file_put_contents($this->fileName, $response->getContent());
    }
}
