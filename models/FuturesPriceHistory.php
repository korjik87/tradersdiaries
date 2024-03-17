<?php

interface FuturesPriceHistory
{
    public function getMinPriceHistory():float|null;
    public function getMaxPriceHistory():float|null;
    public function addPrice(float $lastPrice, float $openTime):array;

}
