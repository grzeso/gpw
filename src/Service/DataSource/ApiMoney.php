<?php

namespace App\Service\DataSource;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiMoney
{
    private HttpClientInterface $client;
    private string $data;
    private string $moneySource;

    public function __construct(HttpClientInterface $client, string $moneySource)
    {
        $this->client = $client;
        $this->moneySource = $moneySource;
    }

    public function downloadDataByDate(string $date): void
    {
        $response = $this->client->request(
            'GET',
            sprintf($this->moneySource, $date)
        );
        $this->data = $response->getContent();
    }

    public function getData(): string
    {
        return $this->data;
    }
}
