<?php

namespace App\Traits;

use App\Models\Category;
use App\Models\MusicStyle;
use App\Models\MusicStyleRelation;
// use App\Brand;

trait FilterTrait
{
    public function getFiltred($items, $filter_data)
    {

        // минимальная и максимальная цена
        $min_price = $items->min('price');
        $data['min_price'] = $min_price;

        $max_price = $items->max('price');
        $data['max_price'] = $max_price;

        // собираем коды категорий **************
        $cat_id_arr = $items->pluck('category_id')->unique()->toArray();

        // собираем категории
        $cats = Category::whereIn('id', $cat_id_arr)->orderBy('name')->get(['id', 'name']);
        $data['cats'] = $cats;

        // собираем жанры ***********************
        // собираем массив кодов товаров
        $item_id_arr = $items->pluck('id')->unique()->toArray();
        // собираем массив кодов жанров
        $style_id_arr = MusicStyleRelation::whereIn('product_id', $item_id_arr)->pluck('style_id')->unique()->toArray();
        // собираем стили
        $styles = MusicStyle::whereIn('id', $style_id_arr)->orderBy('name')->get(['id', 'name']);
        $data['styles'] = $styles;

        // // собираем коды брендов
        // $brand_arr = $items->pluck('brand_id_1c')->unique()->toArray();

        // // собираем бренды
        // $brands = Brand::whereIn('brand_1c_id', $brand_arr)->orderBy('name')->get(['brand_1c_id', 'name']);
        // $data['brands'] = $brands;

        // фильтруем *****************************
        // если есть фильтр цен
        if (isset($filter_data['price'])) {

            if ($filter_data['price']['price_from'] > $min_price || $filter_data['price']['price_to'] < $max_price) {

                $items = $items->whereBetween('price', [$filter_data['price']['price_from'], $filter_data['price']['price_to']]);

            }
        }

        // если есть фильтр категорий
        if (isset($filter_data['category'])) {

            $items = $items->whereIn('category_id', $filter_data['category']);

        }

        // если есть фильтр жанров
        if (isset($filter_data['styles'])) {

            // берем id товаров
            $item_ids = MusicStyleRelation::whereIn('product_id', $item_id_arr)
                ->whereIn('style_id', $filter_data['styles'])
                ->pluck('product_id')
                ->unique()
                ->toArray();

            $items = $items->whereIn('id', $item_ids);
        }

        // // если есть фильтр брендов
        // if (isset($filter_data['brand'])) {

        //     $items = $items->whereIn('brand_id_1c', $filter_data['brand']);

        // }

        $data['items'] = $items;

        if (count($filter_data)) {

            // параметры для фильтров
            $qq['filters'] = $filter_data;
            // $filter_parameters = urldecode(http_build_query($qq));
            $filter_parameters = http_build_query($qq);

            $filter_data = json_encode($filter_data);
        } else {
            $filter_parameters = '';
            $filter_data = '';
        }

        $data['filter_parameters'] = $filter_parameters;
        $data['filter_data'] = $filter_data;

        return $data;

    }


}