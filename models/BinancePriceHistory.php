<?php

namespace models;

use DateTime;

abstract class BinancePriceHistory implements FuturesPriceHistory
{
    protected array $historyPrices = [];
    protected static DateTime $curentTime;
    protected int|null $minPriceDate;
    protected int|null $maxPriceDate;
}
