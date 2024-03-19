<?php

use models\ETHBTCPriceHistory;
use PHPUnit\Framework\TestCase;


class PriceHistoryTest extends TestCase
{


    public function testPriceMinMax(): void
    {

        ETHBTCPriceHistory::removeHistory();
        $data = json_decode(file_get_contents(__DIR__ . '/../csvjson.json'));

        foreach ($data as $key => $item) {
                $p = new ETHBTCPriceHistory($item->open, (int)($item->open_time/1000));
                $this->assertEquals((int)($item->open_time/1000), $p->getCurrentTime());


                if($key === 600) {



                    $this->assertEquals(110.15, $p->getMaxPriceHistory5m());
                    $this->assertEquals(110.05, $p->getMinPriceHistory5m());


                    $this->assertEquals(110.15, $p->getMaxPriceHistory24h());
                    $this->assertEquals(108.78, $p->getMinPriceHistory24h());




                }
        }



    }





}
