<?php

use models\ETHBTCPriceHistory;
//use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;


class PriceHistoryTest extends TestCase
{
    /**
     * @dataProvider providePriceHistoryFirstDay
     * @throws Exception
     */
    #[DataProvider('providePriceHistoryFirstDay')]
    public function testPrice($lastPrice, $openTime): void
    {
//        var_dump($lastPrice, $openTime);
        $p = new ETHBTCPriceHistory($lastPrice, $openTime);

//        var_dump($p);

    }


    public static function providePriceHistoryFirstDay(): array
    {
        ETHBTCPriceHistory::removeHistory();
        $data = json_decode(file_get_contents(__DIR__ . '/../test12032024.json'));

        $out = [];
        foreach ($data as $item) {
            $out[] =  [
                'lastPrice' => $item->lastPrice,
                'openTime' => $item->openTime,
            ];
        }

        return [
            $out[0], $out[1], $out[2]
        ];



    }

}
