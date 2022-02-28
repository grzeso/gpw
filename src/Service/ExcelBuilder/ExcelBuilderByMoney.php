<?php

namespace App\Service\ExcelBuilder;

use App\Dto\StockDto;
use App\Entity\Stocks;
use stdClass;

class ExcelBuilderByMoney extends ExcelBuilder
{
    private array $translate = [
        'BDX' => 'BUDIMEX',
        'CDR' => 'CDPROJEKT',
        'EAT' => 'AMREST',
        'ALE' => 'ALLEGRO',
        'PKO' => 'PKOBP',
        'PZU' => 'PZU',
        '11B' => '11BIT',
        'JSW' => 'JSW',
        'KGH' => 'KGHM',
        'ALR' => 'ALIOR',
        'LTS' => 'LOTOS',
        'PEO' => 'PEKAO',
        'PKN' => 'PKNORLEN',
        'CCC' => 'CCC',
        'DNP' => 'DINOPL',
        'KRU' => 'KRUK',
        'ACP' => 'ASSECOPOL',
        'OPL' => 'ORANGEPL',
        'CIG' => 'CIGAMES',
        'LVC' => 'LIVECHAT',
        'CIE' => 'CIECH',
        'NL0015000AU7' => 'PEPCO',
        'Pepco Group N.V.' => 'PEPCO',
        'SES.s' => 'SESCOM',
        'MRB' => 'MIRBUD',
        'PLGRPRC00015' => 'GRUPRACUJ',
        'Grupa Pracuj SA' => 'GRUPRACUJ',
        'PLSTSHL00012' => 'STSHOLDING',
        'STS Holding SA' => 'STSHOLDING',
    ];

    private function translate(stdClass $moneyStock): ?string
    {
        if (array_key_exists($moneyStock->symbol, $this->translate)) {
            return $this->translate[$moneyStock->symbol];
        }
        if (array_key_exists($moneyStock->nazwaPelna, $this->translate)) {
            return $this->translate[$moneyStock->nazwaPelna];
        }

        return null;
    }

    public function findUserStocks(): array
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
                if ($name && in_array($name, $userStocksName) && $userStock->getName() == $name) {
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
