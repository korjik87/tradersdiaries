<?php

namespace models;
interface FuturesPriceHistory
{
    public function getMinPriceHistory(int $time): float|null;
    public function getMaxPriceHistory(int $time): float|null;
    public function addPrice(float|string $lastPrice, int $openTime): array;

}
