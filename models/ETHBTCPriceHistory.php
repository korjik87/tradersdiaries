<?php

namespace models;

use DateTime;
use Exception;

class ETHBTCPriceHistory extends BinancePriceHistory
{
    const NAME_FILE_HISTORY = 'history.json';

    /**
     * @throws Exception
     */
    function __construct(float|string $lastPrice, int $openTime)
    {
        $this->recoveryHistory();
        $this->curentTime = $this::timestampToDateTime($openTime);
//        print_r($openTime);
//        print_r($this::timestampToDateTime($openTime));
//        print_r($this->curentTime);

        $this->checkDataIsBad();
        $this->addPrice($lastPrice, $openTime);
    }


    public function getCurrentTime(): int
    {
        return $this->curentTime->getTimestamp();
    }







    public function recoveryHistory(): void {
        if(file_exists(ETHBTCPriceHistory::NAME_FILE_HISTORY)) {
            $h = json_decode(file_get_contents(ETHBTCPriceHistory::NAME_FILE_HISTORY), true);
            $this->historyPrices = $h['historyPrices'];
            $this->minPriceDate = $h['minPriceDate'];
            $this->maxPriceDate = $h['maxPriceDate'];
        }
    }

    /**
     * @throws Exception
     */
    public function checkDataIsBad(): void
    {
        if ($this->isOldDateMinPrice()) {
            $min = $this->findMinPriceHistory();
            if($min !== null) {
                $this->minPriceDate = $min;
            }
        }

        if ($this->isOldDateMaxPrice()) {
            $max = $this->findMaxPriceHistory();
            if($max !== null) {
                $this->minPriceDate = $max;
            }
        }
    }

    public function findMinPriceHistory(): string|null
    {
        if($this->historyPrices) {
            return max(array_keys($this->historyPrices, min($this->historyPrices)));
        }
        return null;
    }

    public function findMaxPriceHistory(): string|null
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
        $this->historyPrices[$this->getTime($this->curentTime)] = ['price' => $lastPrice, 'time' => $openTime];

        $this->updateMinPrice($lastPrice, $openTime);
        $this->updateMaxPrice($lastPrice, $openTime);
        return $this->historyPrices;
    }

    public function getTime(DateTime $dateTime): string
    {
        return $dateTime->format('H:i:s.u');
    }

    public function updateMinPrice(float $price, int $openTime)
    {
        if ($this->minPriceDate === null) {
            $this->minPriceDate = $this->getTime($this->curentTime);
        } else {
            $this->minPriceDate = $this->getMinPriceHistory() > $price ? $this->getTime($this->timestampToDateTime($openTime)) : $this->minPriceDate;
        }
    }

    public function updateMaxPrice(float $price, int $openTime)
    {
        if ($this->maxPriceDate === null) {
            $this->maxPriceDate = $this->getTime($this->curentTime);
        } else {
            $this->maxPriceDate = $this->getMaxPriceHistory() < $price ? $this->getTime($this->timestampToDateTime($openTime)) : $this->maxPriceDate;
        }
    }


    public static function timestampToDateTime(int $timestamp): DateTime
    {
        return (new DateTime())->setTimestamp($timestamp);
    }


    public function isOldDateMinPrice(): bool
    {
        if ($this->minPriceDate && $this->historyPrices[$this->minPriceDate]['time']) {
            return ($this::timestampToDateTime($this->historyPrices[$this->minPriceDate]['time']))->diff($this->curentTime)->days > 0;
        } else {
            return false;
        }
    }

    /**
     * @throws Exception
     */
    public function isOldDateMaxPrice(): bool
    {
        if ($this->maxPriceDate && $this->historyPrices[$this->maxPriceDate]['time']) {
            return $this::timestampToDateTime($this->historyPrices[$this->maxPriceDate]['time'])->diff($this->curentTime)->days > 0;
        } else {
            return false;
        }
    }

    function __destruct()
    {
        file_put_contents(ETHBTCPriceHistory::NAME_FILE_HISTORY,
            json_encode(['historyPrices' => $this->historyPrices, 'maxPriceDate' => $this->maxPriceDate, 'minPriceDate' => $this->minPriceDate]));
    }

    public static function removeHistory(): void {
        unlink(ETHBTCPriceHistory::NAME_FILE_HISTORY);
    }

}
