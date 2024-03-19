<?php

namespace models;

use DateTime;

abstract class BinancePriceHistory implements FuturesPriceHistory
{
    const HISTORT_5m = 5;
    const HISTORT_15m = 15;
    const HISTORT_1h = 60;
    const HISTORT_4h = 240;
    const HISTORT_24h = 1440;


    abstract protected function getMinPriceHistory(int $time): float|null;
    abstract protected function getMaxPriceHistory(int $time): float|null;

    protected array $historyPrices = [];
    protected DateTime $curentTime;
    public array $minPriceDate = [self::HISTORT_5m=>null, self::HISTORT_15m=>null, self::HISTORT_1h=>null,
        self::HISTORT_4h=>null, self::HISTORT_24h=>null];
    public array $maxPriceDate = [self::HISTORT_5m=>null, self::HISTORT_15m=>null, self::HISTORT_1h=>null,
        self::HISTORT_4h=>null, self::HISTORT_24h=>null];
}
