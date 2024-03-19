<?php

namespace models;
interface FuturesPriceHistory
{




    public function getMinPriceHistory5m(): float|null;
    public function getMaxPriceHistory5m(): float|null;


    public function getMinPriceHistory15m(): float|null;
    public function getMaxPriceHistory15m(): float|null;


    public function getMinPriceHistory1h(): float|null;
    public function getMaxPriceHistory1h(): float|null;


    public function getMinPriceHistory4h(): float|null;
    public function getMaxPriceHistory4h(): float|null;

    public function getMinPriceHistory24h(): float|null;
    public function getMaxPriceHistory24h(): float|null;





    public function addPrice(float|string $lastPrice, int $openTime): array;

}
