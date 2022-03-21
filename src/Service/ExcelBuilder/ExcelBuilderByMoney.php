<?php

namespace App\Service\ExcelBuilder;

use App\Dto\StockDto;
use App\Entity\NameDictionary;
use App\Entity\Stocks;
use stdClass;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class ExcelBuilderByMoney extends ExcelBuilder
{
    private function translate(stdClass $moneyStock): ?string
    {
        $cache = new FilesystemAdapter();

        $values = $cache->get('dictionary.money', function (ItemInterface $item) {
            $item->expiresAfter(10);

            return $this->dictionaryRepository->findBy(['provider' => 1]);
        });

        /** @var NameDictionary $value */
        foreach ($values as $value) {
            if ($moneyStock->symbol === $value->getTheirName() || $moneyStock->nazwaPelna === $value->getTheirName()) {
                return $value->getMyName();
            }
        }

        return null;
    }

    protected function findUserStocks(): array
    {
        $dataSource = json_decode($this->getDataSource())->data->data;

        $this->stocks->setUser($this->user);
        $userStocksName = $this->stocks->getUserStocksName();
        $userStocks = $this->stocks->getUserStocks();
        $userStocksOutput = [];

        /** @var stdClass $item */
        foreach ($dataSource as $item) {
            if (!$item->symbol && !$item->nazwaPelna) {
                continue;
            }

            $name = $this->translate($item);

            if (!$name) {
                continue;
            }

            /* @var Stocks $userStock */
            foreach ($userStocks as $userStock) {
                if (in_array($name, $userStocksName) && $userStock->getName() == $name) {
                    $stock = new StockDto();
                    $stock->setName($name);
                    $stock->setValue($item->kurs);
                    $stock->setChange(round($item->changePrev, 2));
                    $stock->setPosition($userStock->getPosition());
                    $stock->setQuantity($userStock->getQuantity());
                    array_push($userStocksOutput, $stock);
                }
            }
        }

        return $userStocksOutput;
    }
}
