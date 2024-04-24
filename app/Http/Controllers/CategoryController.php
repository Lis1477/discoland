<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use App\Traits\SortAndPaginateTrait;
use App\Traits\FilterTrait;
use App\Traits\PriceRuTrait;
use App\Traits\PriceRecalculateTrait;

use App\Models\Category;
use App\Models\Item;
use App\Models\CategoryBanner;

class CategoryController extends Controller
{
    use SortAndPaginateTrait, FilterTrait, PriceRuTrait, PriceRecalculateTrait;

    public function index(Request $request, $id, $slug)
    {

        // берем данные категории
        $category = Category::where('id', $id)->first();
        $data['current_cat'] = $category;

        // в массив id категорий    
        $cat_arr[] = $category->id;

        // берем дочерние категории
        $subs = Category::where([['parent_id', $category->id], ['display', '1']])->orderBy('order')->get();
        $data['sub_cats'] = $subs;

        // если не пусто
        if ($subs->count()) {
            // берем id подкатегорий
            $sub_ids = $subs->pluck('id')->toArray();

            // добавляем в массив категорий
            $cat_arr = array_merge($cat_arr, $sub_ids);

            // берем под под категории
            $sub_sub_ids = Category::whereIn('parent_id', $sub_ids)
                ->where('display', '1')
                ->pluck('id')->toArray();

            // если не пусто
            if (count($sub_sub_ids)) {
                // добавляем в массив категорий
                $cat_arr = array_merge($cat_arr, $sub_sub_ids);
            }
        }

        // берем товары
        $items = Item::whereIn('category_id', $cat_arr)
            ->where('for_sale', '1')
            ->get();

        // пересчитываем цену
        $items = $this->recalculatePrice($items);

        // берем данные фильтров, если есть
        if (isset($request->filters)) {
            $filter_data = $request->filters;
        } else {
            $filter_data = [];
        }
// dd($filter_data);

        // фильтруем, берем данные для фильтров
        $data = array_merge($data, $this->getFiltred($items, $filter_data));

        // если это ajax запрос количества **************************
        if ($request->ajax()) {
            // отдаем количество
            return $data['items']->count();
        }
        // **********************************************************

        // сортировка и пагинация
        $data = array_merge($data, $this->getSortedAndPaginated($data['items'], $request));

        // // берем баннеры категории
        // $banners = CategoryBanner::where('categories', 'like', '%'.$category->id.'%')
        //     ->orWhere('categories', '')
        //     ->get();
        // $data['banners'] = $banners;

        // для метатега title
        if(trim($category->title)) {
            $title = $category->title;
        } else {
            $title = $category->name;
        }
        $data['title'] = $title;

        // для метатэга keywords
        $keywords = trim($category->keywords);
        $data['keywords'] = $keywords;

        // для метатэга description
        $description = trim($category->description);
        $data['description'] = $description;

        // выводим страницу категорий с товарами
        return view('category_page')->with($data);

    }

    public function newItems(Request $request)
    {

        // берем товары

        $items = Item::where([['for_sale', '1'], ['amount', '>', '0']])
            ->orderByDesc('is_new_item')
            ->orderByDesc('created_at')
            ->take(60)
            ->get();

        // пересчитываем цену
        $items = $this->recalculatePrice($items);

        // берем данные фильтров, если есть
        if (isset($request->filters)) {
            $filter_data = $request->filters;
        } else {
            $filter_data = [];
        }

        // фильтруем, берем данные для фильтров
        $data = $this->getFiltred($items, $filter_data);

        // если это ajax запрос количества **************************
        if ($request->ajax()) {
            // отдаем количество
            return $data['items']->count();
        }
        // **********************************************************

        // сортировка и пагинация
        $data = array_merge($data, $this->getSortedAndPaginated($data['items'], $request));

        // выводим страницу товаров
        return view('new_items_page')->with($data);
    }

    public function discountedItems(Request $request)
    {

        // берем товары категрии
        $items = Item::where([['category_id', 3149], ['amount', '>', 0], ['for_sale', 1]]);

        // берем данные фильтров, если есть
        if (isset($request->filters)) {
            $filter_data = $request->filters;
        } else {
            $filter_data = [];
        }

        // фильтруем, берем данные для фильтров
        $data = $this->getFiltred($items, $filter_data);

        // если это ajax запрос количества **************************
        if ($request->ajax()) {
            // отдаем количество
            return $data['items']->count();
        }
        // **********************************************************

        // сортировка и пагинация
        $data = array_merge($data, $this->getSortedAndPaginated($data['items'], $request));

        // выводим страницу товаров
        return view('discounted_items_page')->with($data);
    }


}
