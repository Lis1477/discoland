<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\PromoCode;
use App\Models\Category;
use App\Models\Item;


class PromoCodeController extends Controller
{
    public function promoCodeVerify(Request $request)
    {
        $promo_code = PromoCode::where('name', $request->promo_code)->first();

        if($promo_code) {

            // если заполнены поля categories или items, проверяем на наличие товаров

            // берем коды категорий
            $cats_str = trim($promo_code->categories);

            // берем коды товаров
            $items_str = trim($promo_code->items);

            // считаем количество
            $items_arr_cont = count($this->getItemArray($cats_str, $items_str));
            // $items_arr_cont = $this->getItemArray($cats_str, $items_str);

            // параметр
            if(($cats_str || $items_str) && $items_arr_cont == 0) {
                $items_arr_par = true;
            } else {
                $items_arr_par = false;
            }

            // выдаем сообщения или пропускаем
            if($promo_code->end_date && strtotime($promo_code->end_date) < strtotime(date('Y-m-d'))) {

                $data['active'] = 0;
                $data['text'] = "Промокод просрочен";

            } elseif($promo_code->for_user != 0 && !Auth::check()) {

                $data['active'] = 0;
                $data['text'] = "Войдите, чтобы применить промокод";

            } elseif($promo_code->for_user != 0 && $promo_code->for_user != Auth::id()) {

                $data['active'] = 0;
                $data['text'] = "Промокод не действует для Вашего аккаунта";

            } elseif($items_arr_par || $promo_code->num_use == 0 || $promo_code->active == 0) {

                $data['active'] = 0;
                $data['text'] = "Промокод не действителен";

            } else {
                $data['active'] = 1;
                $data['text'] = "";
            }

        } else {
            $data['active'] = 0;
            $data['text'] = "Промокод не существует";
        }

        return $data;
    }

    public function promoCodeActivate(Request $request)
    {

        $promo_code = PromoCode::where('name', trim($request->promo_code))->first();

        // берем коды категорий
        $cats_str = trim($promo_code->categories);

        // берем коды товаров
        $items_str = trim($promo_code->items);

        // собираем коды товаров промокода
        $items_arr = $this->getItemArray($cats_str, $items_str);

        return redirect()->back()
            ->withCookie(cookie('items_arr', json_encode($items_arr), 720))
            ->withCookie(cookie('promo_code', json_encode($promo_code), 720));
    }

    private function getItemArray($cats_str, $items_str)
    {
        // собираем массив кодов товаров
        $items_arr = [];

        if($cats_str) {

            // массив кодов товаров категорий
            $cat_items_arr = [];

            // если строка заканчивается ';', удаляем
            if(str_ends_with($cats_str, ';')) {
                $cats_str = mb_substr($cats_str, 0, -1);
            }

            // преобразуем в массив
            $cats_str_arr = explode(";", $cats_str);

            // применяем trim ко всем элементам массива, оставляем уникальные
            $cats_str_arr = array_unique(array_map("trim", $cats_str_arr));

            foreach($cats_str_arr as $val) {
                // берем товары категории
                $cat_items = Item::where('category_id', $val)->pluck('id')->toArray();

                // если есть
                if(count($cat_items)) {
                    // добавляем в массив
                    $cat_items_arr = array_merge($cat_items_arr, $cat_items);
                }

                // смотрим, есть ли дочерние категории
                $sub_cats = Category::where('parent_id', $val)->get(['id']);

                // если есть
                if($sub_cats->count()) {

                    foreach($sub_cats as $sub_cat) {

                        // берем товары категории
                        $sub_cat_items = Item::where('category_id', $sub_cat->id)->pluck('id')->toArray();

                        // если есть
                        if(count($sub_cat_items)) {
                            // добавляем в массив
                            $cat_items_arr = array_merge($cat_items_arr, $sub_cat_items);
                        }

                        // смотрим, есть ли дочерние категории
                        $sub_sub_cats = Category::where('parent_id', $sub_cat->id)->get(['id']);

                        // если есть
                        if($sub_sub_cats->count()) {
                            foreach($sub_sub_cats as $sub_sub_cat) {

                                // берем товары категории
                                $sub_sub_cat_items = Item::where('category_id', $sub_sub_cat->id)->pluck('id')->toArray();

                                // если есть
                                if(count($sub_sub_cat_items)) {
                                    // добавляем в массив
                                    $cat_items_arr = array_merge($cat_items_arr, $sub_sub_cat_items);
                                }
                            }
                        }
                    }


                }
            }

            $items_arr = $cat_items_arr;
        }

        // собираем коды товаров
        if($items_str) {

            // если строка заканчивается ';', удаляем
            if(str_ends_with($items_str, ';')) {
                $items_str = mb_substr($items_str, 0, -1);
            }

            // преобразуем в массив
            $items_code_arr = explode(";", $items_str);

            // применяем trim ко всем элементам массива
            $items_code_arr = array_map("trim", $items_code_arr);

            // проверяем наличие товаров с такими кодами
            $items_code_arr = Item::whereIn('id', $items_code_arr)->pluck('id')->toArray();

            // объединяем, оставляем уникальные
            $items_arr = array_unique(array_merge($items_arr, $items_code_arr));
        }


        return $items_arr;
    }

}
