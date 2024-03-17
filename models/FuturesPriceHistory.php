<?php

namespace models;
interface FuturesPriceHistory
{
    public function getMinPriceHistory(): float|null;
    public function getMaxPriceHistory(): float|null;
    public function addPrice(float|string $lastPrice, int $openTime): array;

}
