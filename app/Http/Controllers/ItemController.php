<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Cookie;

use App\Traits\PriceRecalculateTrait;
use App\Traits\PriceRuTrait;

use App\Models\Item;
use App\Models\Category;
use App\Models\MusicStyleRelation;
use App\Models\MusicStyle;

class ItemController extends Controller
{
    use PriceRuTrait, PriceRecalculateTrait;

    public function index(Request $request, $id, $slug)
    {
        // берем товар
        $item = Item::find($id);

        // если товар существует, продолжаем
        if($item) {

            // формируем строку Характеристики
            $char_str = "";
            if($item->disks_number && $item->data_carrier->name)
                $char_str .= $item->disks_number;
            if($item->data_carrier)
                $char_str .= $item->data_carrier->name.", ";
            if($item->features)
                $char_str .= $item->features.", ";
            if ($char_str)
                $char_str = substr_replace($char_str,'.',strlen($char_str)-2);
            // добавляем в коллекцию
            $item->char_str = $char_str;

            // формируем строку жанров
            $style_str = "";
            // массив id стилей
            $style_id_arr = $item->styles->pluck('style_id')->toArray();
            // массив имен стилей
            if (count($style_id_arr)) {
                $styles_arr = MusicStyle::whereIn('id', $style_id_arr)
                    ->pluck('name')->toArray();

                if (count($styles_arr)) {
                    foreach ($styles_arr as $style_name) {
                        $style_str .= $style_name.", ";
                    }
                    $style_str = substr_replace($style_str,'.',strlen($style_str)-2);
                }
            }
            // добавляем в коллекцию
            $item->style_str = $style_str;

            // строка Издание
            $edition_str = "";
            if ($item->album_year)
                $edition_str .= $item->album_year;
            if ($item->album_year && $item->album_reissue)
                $edition_str .= " / ";
            if ($item->album_reissue)
                $edition_str .= $item->album_reissue;
            if ($item->publisher)
                $edition_str .= ", (".$item->publisher->name.")";
            // добавляем в коллекцию
            $item->edition_str = $edition_str;

            // обрабатываем ссылку на youtube
            if ($item->video)
                $item->video = str_replace('watch?v=', 'embed/', $item->video);

            // пересчитываем цену
            $new_price = $this->getPriceRu($item->price, $item->formula);
            $item->price = $new_price;

            $data['item'] = $item;
            
            // добавляем в куки просмотренный товар
            if($request->cookie('seen')) $s = json_decode($request->cookie('seen'));
            $s[] = $id;
            $s = json_encode(array_unique($s));
            Cookie::queue('seen', $s, 720); // срок действия 12 часов

            // добавляем к просмотрам 1
            Item::where('id', $id)->update([
                'visite_counter' => $item->visite_counter + 1,
            ]);

            // для блока Похожие товары ********************
            // определяем жанры товара
            $item_style_arr = MusicStyleRelation::where('product_id', $item->id)
                ->pluck('style_id')
                ->toArray();
            // определяем коды товаров этого же стиля
            $style_items_arr = MusicStyleRelation::whereIn('style_id', $item_style_arr)
                ->pluck('product_id')
                ->toArray();

            // определяем id подкатегорий главного раздела
            $main_category_id = Category::where('id', $item->category_id)->first(['parent_id'])->parent_id;
            $sub_cat_id_arr = Category::where('parent_id', $main_category_id)->pluck('id')->toArray();

            $style_items = Item::
                whereIn('id', $style_items_arr)
                ->whereIn('category_id', $sub_cat_id_arr)
                ->where([['amount', '>', 0], ['for_sale', 1], ['id', '!=', $item->id]])
                ->orderByDesc('visite_counter')
                ->orderBy('name')
                ->take(24)
                ->get();

            // меняем цену на расчетную
            $style_items = $this->recalculatePrice($style_items);
            $data['style_items'] = $style_items;

            // выбираем просмотренные товары **************
            if($request->cookie('seen')) {

                $seen_arr = json_decode($request->cookie('seen'));
                $seen_items = Item::whereIn('id', $seen_arr)->get();

                // меняем цену на расчетную
                $seen_items = $this->recalculatePrice($seen_items);
            } else {
                $seen_items = '';
            }
            $data['seen_items'] = $seen_items;

            return view('item_card_page')->with($data);

        } else {

            // если нет, переходим на страницу ошибки

            return "Страница не существует";

        }
    }
}
