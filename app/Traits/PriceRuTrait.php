<?php

namespace App\Traits;

use App\Models\CurrencyRate;

trait PriceRuTrait
{
    public function getPriceRu($pr, $for)
    {

        if($for == 0) {
            $for = 4;
        } 

        // узнаем курс
        $rate = CurrencyRate::where('id', $for)->first(['rate'])['rate'];

        // считаем цену
        if($for == '1') {
            $price = $pr * 1.5;
        } elseif($for == '2') {
            $price = $pr * 1.5 * $rate;
        } elseif($for == 3) {
            $price = $pr * 1.9 * $rate;
        } else {
            $price = $pr / $rate * 100;
        }

        if($price < 1500) {
            $price *= 1.14;
        } 

        // округляем в большую сторону
        return $price = ceil($price / 10) * 10;
    }

}