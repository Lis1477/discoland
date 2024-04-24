<?php

namespace App\Traits;

trait PriceRecalculateTrait
{
    public function recalculatePrice($items)
    {
        foreach ($items as $item) {
            $item->price = $this->getPriceRu($item->price, $item->formula);
        }

        return $items;
    }
}