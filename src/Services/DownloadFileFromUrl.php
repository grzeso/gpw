<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class DownloadFileFromUrl
//zmienić na FIle
{
    private $client;
    private $date;
    private $fileName;

    private $url = 'https://www.gpw.pl/archiwum-notowan?fetch=1&type=10&instrument=&date=';

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function setDate(string $date)
    {
        $this->date = $date;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function downloadFile(): void
    {
        $response = $this->client->request(
            'GET',
            $this->url.$this->date
        );

        //TODO extension

        $this->fileName = tempnam(sys_get_temp_dir(), 'tmpxls');
        file_put_contents($this->fileName, $response->getContent());
    }

    public function fasade(string $date): string
    {
        $this->setDate($date);
        $this->downloadFile();

        return $this->getFileName();
    }
}
