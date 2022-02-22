<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class File
{
    private HttpClientInterface $client;
    private string $fileName;

//    private $url = 'https://www.gpw.pl/archiwum-notowan?fetch=1&type=10&instrument=&date=';
    private string $url_dev = 'https://hosting2215173.online.pro/2022-02-15_akcje.xls';

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function downloadFileByDate(string $date): void
    {
//        $response = $this->client->request(
//            'GET',
//            $this->url.$date
//        );

        $response = $this->client->request(
            'GET',
            $this->url_dev
        );

        $this->fileName = tempnam(sys_get_temp_dir(), 'tmpxls');
        file_put_contents($this->fileName, $response->getContent());
    }
}
