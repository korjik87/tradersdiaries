<?php

use models\ETHBTCPriceHistory;
use PHPUnit\Framework\TestCase;


class PriceHistoryTest extends TestCase
{

    /**
     * @dataProvider providePriceHistoryFirstDay
     * @throws Exception
     */
    public function testPrice($lastPrice, int $openTime, bool $last, bool $last2): void
    {

        $p = new ETHBTCPriceHistory($lastPrice, $openTime);
        $this->assertEquals($openTime, $p->getCurrentTime());
        if($last) {
            $this->assertEquals(1128261033.0, $p->getMaxPriceHistory());
            $this->assertEquals(0, $p->getMinPriceHistory());
        }
        if($last2) {
            $this->assertEquals(1062200204.0, $p->getMaxPriceHistory());
            $this->assertEquals(0, $p->getMinPriceHistory());
        }
    }


    public static function providePriceHistoryFirstDay(): array
    {
        ETHBTCPriceHistory::removeHistory();
        $data = json_decode(file_get_contents(__DIR__ . '/../test12032024.json'));
        $data = json_decode(file_get_contents(__DIR__ . '/../test15032024.json'));
        $data2 = json_decode(file_get_contents(__DIR__ . '/../test15032024.json'));

        $out = [];
        $count = count($data);
        $count2 = count($data2);

        foreach ($data as $item) {
            if($item->lastPrice) {
                $out[] =  [
                    'lastPrice' => $item->lastPrice,
                    'openTime' => (int)($item->openTime/100),
                    'last' => false ,
                    'last2' => false,

                ];
            }

        }

        foreach ($data2 as $item) {
            $out[] =  [
                'lastPrice' => $item->lastPrice,
                'openTime' => (int)($item->openTime/100),
                'last' => false ,
                'last2' => false,

            ];
        }

        $out[$count - 1]['last'] = true;
        $out[$count + $count2 - 2]['last2'] = true;

        return $out;



    }




}
