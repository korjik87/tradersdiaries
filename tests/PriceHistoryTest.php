<?php

use models\ETHBTCPriceHistory;
use PHPUnit\Framework\TestCase;


class PriceHistoryTest extends TestCase
{

    /**
     * @dataProvider providePriceHistoryFirstDay
     * @throws Exception
     */
    public function testPrice($lastPrice, int $openTime): void
    {
        $p = new ETHBTCPriceHistory($lastPrice, $openTime);
        $this->assertEquals($openTime, $p->getCurrentTime());
    }


    public function testPriceMinMax(): void
    {

        ETHBTCPriceHistory::removeHistory();
        $data = json_decode(file_get_contents(__DIR__ . '/../test12032024.json'));
        $data2 = json_decode(file_get_contents(__DIR__ . '/../test15032024.json'));

        $count = count($data) - 1;
        foreach ($data as $key => $item) {
                $p = new ETHBTCPriceHistory($item->lastPrice, (int)($item->openTime/1000));
                $this->assertEquals((int)($item->openTime/1000), $p->getCurrentTime());

                if($count === $key) {
                    $this->assertEquals(1128261033.0, $p->getMaxPriceHistory());
                    $this->assertEquals(0, $p->getMinPriceHistory());
                }
        }

        $count2 = count($data2) - 1;
        foreach ($data2 as $key => $item) {
                $p = new ETHBTCPriceHistory($item->lastPrice, (int)($item->openTime/1000));
                $this->assertEquals((int)($item->openTime/1000), $p->getCurrentTime());

                if($count2 === $key) {
                    $this->assertEquals(1062200204.0, $p->getMaxPriceHistory());
                    $this->assertEquals(0, $p->getMinPriceHistory());
                }
        }

    }


    public static function providePriceHistoryFirstDay(): array
    {
        ETHBTCPriceHistory::removeHistory();
        $data = json_decode(file_get_contents(__DIR__ . '/../test12032024.json'));
        $data2 = json_decode(file_get_contents(__DIR__ . '/../test15032024.json'));

        $out = [];
        foreach ($data as $item) {
            if($item->lastPrice) {
                $out[] =  [
                    'lastPrice' => $item->lastPrice,
                    'openTime' => (int)($item->openTime/1000),
                ];
            }
        }

        foreach ($data2 as $item) {
            $out[] =  [
                'lastPrice' => $item->lastPrice,
                'openTime' => (int)($item->openTime/1000),
            ];
        }


        return $out;



    }




}
