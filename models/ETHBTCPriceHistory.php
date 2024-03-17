<?php

class ETHBTCPriceHistory extends BinancePriceHistory
{


    /**
     * @throws Exception
     */
    function __construct(float $lastPrice, float $openTime) {

        $this::$curentTime = new DateTime($openTime);
        $this->checkDataIsBad();
        $this->addPrice($lastPrice, $openTime);
    }

    /**
     * @throws Exception
     */
    public function checkDataIsBad(): void
    {
        if($this->isOldDateMinPrice()) {
            $this->minPriceDate = $this->getTime($this->findMinPriceHistory()['time']);
        }

        if($this->isOldDateMaxPrice()) {
            $this->minPriceDate = $this->getTime($this->findMaxPriceHistory()['time']);
        }
    }

    public function findMinPriceHistory(): DateTime|null
    {
        return max(array_keys($this->historyPrices, min($this->historyPrices)));
    }

    public function findMaxPriceHistory(): DateTime|null
    {
        return max(array_keys($this->historyPrices, max($this->historyPrices)));
    }

    public function getMinPriceHistory(): float|null
    {
        $item = $this->historyPrices[$this->minPriceDate] ?? null;
        if($item) {
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

    public function addPrice(float $lastPrice, float $openTime): array
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

    public function updateMinPrice(float $price, float $openTime) {
        if($this->minPriceDate == null) {
            $this->minPriceDate = $this->getTime($this::$curentTime);
        } else {
            $this->minPriceDate = $this->getMinPriceHistory() > $price ? $openTime : $this->minPriceDate;
        }
    }

    public function updateMaxPrice(float $price, float $openTime) {
        if($this->maxPriceDate == null) {
            $this->maxPriceDate = $this->getTime($this::$curentTime);
        } else {
            $this->maxPriceDate = $this->getMaxPriceHistory() < $price ? $openTime : $this->maxPriceDate;
        }
    }

    /**
     * @throws Exception
     */
    public function isOldDateMinPrice(): bool
    {
        if($this->minPriceDate) {
            return (new DateTime($this->historyPrices[$this->minPriceDate]['time']))->diff($this::$curentTime)->days > 0;
        } else {
            return true;
        }
    }

    /**
     * @throws Exception
     */
    public function isOldDateMaxPrice(): bool
    {
        if($this->maxPriceDate) {
            return (new DateTime($this->historyPrices[$this->maxPriceDate]['time']))->diff($this::$curentTime)->days > 0;
        } else {
            return true;
        }
    }

}
