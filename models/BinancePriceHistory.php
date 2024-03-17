<?php

namespace models;

use DateTime;

abstract class BinancePriceHistory implements FuturesPriceHistory
{
    protected array $historyPrices = [];
    protected DateTime $curentTime;
    protected string|null $minPriceDate = null;
    protected string|null $maxPriceDate = null;
}
