<?php

namespace models;

use DateTime;
use Exception;

class ETHBTCPriceHistory extends BinancePriceHistory
{


    /**
     * @throws Exception
     */
    function __construct(float|string $lastPrice, int $openTime)
    {

        $this::$curentTime = $this::timestampToDateTime($openTime/1000);

        $this->checkDataIsBad();
        $this->addPrice($lastPrice, $openTime);
    }

    /**
     * @throws Exception
     */
    public function checkDataIsBad(): void
    {
        if ($this->isOldDateMinPrice()) {
            $min = $this->findMinPriceHistory();
            if($min !== null) {
                $this->minPriceDate = $this->getTime( $this::timestampToDateTime($min['time']));
            }
        }

        if ($this->isOldDateMaxPrice()) {
            $max = $this->findMaxPriceHistory();
            if($max !== null) {
                $this->minPriceDate = $this->getTime($this::timestampToDateTime($max['time']));
            }
        }
    }

    public function findMinPriceHistory(): array|null
    {
        if($this->historyPrices) {
            return max(array_keys($this->historyPrices, min($this->historyPrices)));
        }
        return null;
    }

    public function findMaxPriceHistory(): array|null
    {
        if($this->historyPrices) {
            return max(array_keys($this->historyPrices, max($this->historyPrices)));
        }
        return null;
    }

    public function getMinPriceHistory(): float|null
    {
        $item = $this->historyPrices[$this->minPriceDate] ?? null;
        if ($item) {
            return $item['price'] ?? null;
        } else {
            return null;
        }
    }

    public function getMaxPriceHistory(): float|null
    {
        $item = $this->historyPrices[$this->maxPriceDate] ?? null;
        if ($item) {
            return $item['price'] ?? null;
        } else {
            return null;
        }
    }

    public function addPrice(float|string $lastPrice, int $openTime): array
    {
        $this->historyPrices[$this->getTime($this::$curentTime)] = ['price' => $lastPrice, 'time' => $openTime];
        $this->updateMinPrice($lastPrice, $openTime);
        $this->updateMaxPrice($lastPrice, $openTime);
        return $this->historyPrices;
    }

    public function getTime(DateTime $dateTime): string
    {
        return $dateTime->format('H:i:s');
    }

    public function updateMinPrice(float $price, int $openTime)
    {
        if ($this->minPriceDate == null) {
            $this->minPriceDate = $this->getTime($this::$curentTime);
        } else {
            $this->minPriceDate = $this->getMinPriceHistory() > $price ? $openTime : $this->minPriceDate;
        }
    }

    public function updateMaxPrice(float $price, int $openTime)
    {
        if ($this->maxPriceDate == null) {
            $this->maxPriceDate = $this->getTime($this::$curentTime);
        } else {
            $this->maxPriceDate = $this->getMaxPriceHistory() < $price ? $openTime : $this->maxPriceDate;
        }
    }


    public static function timestampToDateTime(int $timestamp): DateTime
    {
        return (new DateTime())->setTimestamp($timestamp);
    }

    /**
     * @throws Exception
     */
    public function isOldDateMinPrice(): bool
    {
        if ($this->minPriceDate) {
            return ($this::timestampToDateTime($this->historyPrices[$this->minPriceDate]['time']))->diff($this::$curentTime)->days > 0;
        } else {
            return false;
        }
    }

    /**
     * @throws Exception
     */
    public function isOldDateMaxPrice(): bool
    {
        if ($this->maxPriceDate) {
            return $this::timestampToDateTime($this->historyPrices[$this->maxPriceDate]['time'])->diff($this::$curentTime)->days > 0;
        } else {
            return false;
        }
    }

}
