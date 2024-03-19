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

        foreach ($this->minPriceDate as $key => $minPriceDate) {
            if ($this->isOldDateMinPrice($key)) {
                $min = $this->findMinPriceHistory($key);
                if($min !== null) {
                    $this->minPriceDate[$key] = $min;
                }
            }

            if ($this->isOldDateMaxPrice($key)) {
                $max = $this->findMaxPriceHistory($key);
                if($max !== null) {
                    $this->maxPriceDate[$key] = $max;
                }
            }
        }

    }

    public function findMinPriceHistory(int $time): string|null
    {
        if($this->historyPrices) {
            $out = null;
            foreach ($this->historyPrices as $key => $history) {
                if($this->diffMinutes((new DateTime())->setTimestamp($history['time'])) < $time) {
                    if($out === null) {
                        $out = $key;
                    } else {
                        if($this->historyPrices[$out]['price'] > $history['price']) {
                            $out = $key;
                        }
                    }

                }
            }
        }


        return $out ?? null;
    }

    public function findMaxPriceHistory(int $time): string|null
    {
        if($this->historyPrices) {
            $out = null;
            foreach ($this->historyPrices as $key => $history) {
                if($this->diffMinutes((new DateTime())->setTimestamp($history['time'])) < $time) {
                    if($out === null) {
                        $out = $key;
                    } else {
                        if($this->historyPrices[$out]['price'] < $history['price']) {
                            $out = $key;
                        }
                    }
                }
            }
        }
        return $out ?? null;
    }

    public function getMinPriceHistory(int $time): float|null
    {
        if($this->minPriceDate[$time] === null) {
            return null;
        }


        $item = $this->historyPrices[$this->minPriceDate[$time]] ?? null;
        if ($item) {
            return $item['price'] ?? null;
        } else {
            return null;
        }
    }

    public function getMaxPriceHistory(int $time): float|null
    {
        if($this->maxPriceDate[$time] === null) {
            return null;
        }

        $item = $this->historyPrices[$this->maxPriceDate[$time]] ?? null;
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
        foreach ($this->minPriceDate as $key => $item) {
            $historyPriceTime = $this->getMinPriceHistory($key);
            if($historyPriceTime === null) {
                $this->minPriceDate[$key] = $this->getTime($this->curentTime);
            } else if($this->historyPrices[$historyPriceTime]['price'] > $price) {
               $this->minPriceDate[$key] = $this->getTime($this->curentTime);
            }

//            var_dump($price);
        }

    }

    public function updateMaxPrice(float $price, int $openTime)
    {
        foreach ($this->maxPriceDate as $key => $item) {
            $historyPriceTime = $this->getMaxPriceHistory($key);
            if($historyPriceTime === null) {
                $this->maxPriceDate[$key] = $this->getTime($this->curentTime);
            } else if($this->historyPrices[$historyPriceTime]['price'] < $price) {
                $this->maxPriceDate[$key] = $this->getTime($this->curentTime);
            }
        }


    }


    public static function timestampToDateTime(int $timestamp): DateTime
    {
        return (new DateTime())->setTimestamp($timestamp);
    }



    public function diffMinutes(DateTime $date): int
    {
        return $date->diff($this->curentTime)->i;
    }

    public function isOldDateMinPrice(int $time): bool
    {


        if ($this->minPriceDate[$time] !== null &&
            $this->historyPrices[$this->minPriceDate[$time]]['time']) {
            $minutes = $this->diffMinutes($this::timestampToDateTime($this->historyPrices[$this->minPriceDate[$time]]['time']));
            return $minutes >= $time;
        } else {
            return false;
        }
    }

    /**
     * @throws Exception
     */
    public function isOldDateMaxPrice(int $time): bool
    {
        if ($this->maxPriceDate[$time] !== null && $this->historyPrices[$this->maxPriceDate[$time]]['time']) {
            $minutes = $this->diffMinutes($this::timestampToDateTime($this->historyPrices[$this->maxPriceDate[$time]]['time']));
            return $minutes >= $time;
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
