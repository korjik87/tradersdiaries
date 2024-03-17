<?php

namespace models;

use DateTime;

abstract class BinancePriceHistory implements FuturesPriceHistory
{
    protected array $historyPrices = [];
    protected static DateTime $curentTime;
    protected string|null $minPriceDate = null;
    protected string|null $maxPriceDate = null;
}
