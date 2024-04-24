<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use App\Traits\PriceRuTrait;
use App\Traits\PriceRecalculateTrait;

use App\Models\Slider;
// use App\Models\MainBanner;
use App\Models\Item;
// use App\Brand;
// use App\News;


class MainPageController extends Controller
{

    use PriceRecalculateTrait, PriceRuTrait;

    public function index(Request $request)
    {

        // выбираем изображения для слайдера
        $sliders = Slider::where('display', 1)->orderBy('order')->get();
        $data['sliders'] = $sliders;

        // товары для баннеров ******************************
        // берем id самых просматриваемых - 20 шт
        $banner_item_ids = Item::orderByDesc('visite_counter')
            ->take(20)
            ->pluck('id')
            ->toArray();

        // перемешиваем
        $banner_item_ids = Arr::shuffle($banner_item_ids);

        // оставляем 3 первых
        $banner_item_ids = array_slice($banner_item_ids, 0, 3);

        // берем товары
        $banners = Item::whereIn('id', $banner_item_ids)
            ->get(['id', 'name', 'slug']);

        $data['banners'] = $banners;
        // ***************************************************

        // выбираем новые товары *************************
        $new_items = Item::where([['for_sale', '1'], ['amount', '>', '0']])
            ->orderByDesc('is_new_item')
            ->orderByDesc('created_at')
            ->take(40)
            ->get();

        // если есть
        if($new_items->count()) {
            // пересчитываем цену
            $new_items = $this->recalculatePrice($new_items);
        } else {
            $new_items = '';
        }
        $data['new_items'] = $new_items;

        // выбираем популярные товары *********************
        $popular_items = Item::where([['for_sale', 1], ['amount', '>', 0]])
            ->orderByDesc('visite_counter')
            ->take(24)
            ->get();
        // меняем цену на расчетную
        $popular_items = $this->recalculatePrice($popular_items);
        $data['popular_items'] = $popular_items;

        // выбираем просмотренные товары ******************
        if($request->cookie('seen')) {

            $seen_arr = json_decode($request->cookie('seen'));
            $seen_items = Item::whereIn('id', $seen_arr)->get();

            if ($seen_items->count()) {
                // меняем цену на расчетную
                $popular_items = $this->recalculatePrice($popular_items);
            }

        } else {
            $seen_items = '';
        }
        $data['seen_items'] = $seen_items;

        // // берем бренды
        // $brands = Brand::all();
        // $data['brands'] = $brands;

        // // берем новости
        // $news = News::where([['is_active', 1], ['for_retail', 1]])
        //     ->orderByDesc('created_at')
        //     ->take(4)
        //     ->get(['alias', 'title', 'path_image']);
        // $data['news'] = $news;

        return view('main_page')->with($data);
    }
}
