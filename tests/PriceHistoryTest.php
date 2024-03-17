<?php

use models\ETHBTCPriceHistory;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;


class PriceHistoryTest extends TestCase
{
    #[DataProvider('providePriceHistoryFirstDay')]
    public function testPrice($lastPrice, $openTime): void
    {
        var_dump($lastPrice, $openTime);
        $p = new ETHBTCPriceHistory($lastPrice, $openTime);


        var_dump($p);
    }


    public static function providePriceHistoryFirstDay(): array
    {

        $data = json_decode(file_get_contents(__DIR__ . '/../test12032024.json'));

print_r($data[0]);

        return [
            [
                'lastPrice' => "0.05461000",
                'openTime' => 1710262614420,
            ],
        ];
    }

}
