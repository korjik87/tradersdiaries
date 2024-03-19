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
                    var_dump($p);
                    var_dump($p->getMaxPriceHistory(ETHBTCPriceHistory::HISTORT_5m));
                    var_dump($p->getMinPriceHistory(ETHBTCPriceHistory::HISTORT_5m));


                    var_dump($p->getMaxPriceHistory(ETHBTCPriceHistory::HISTORT_15m));
                    var_dump($p->getMinPriceHistory(ETHBTCPriceHistory::HISTORT_15m));
                    exit();
                }
        }



    }





}
