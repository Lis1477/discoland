<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

trait SortAndPaginateTrait
{
    public function getSortedAndPaginated($items, $request)
    {

        // ***********************************************************
        // определяем количество выводимых товаров
        if(isset($request->items) && ($request->items == 40 || $request->items == 60)) {
            $paginate_num = $request->items;
        } else {
            $paginate_num = 20;
        }
        $data['paginate_num'] = $paginate_num;

        // определяем параметр сортировки
        if(isset($request->sort) && (
                $request->sort == "new_items" || 
                $request->sort == "low_price" || 
                $request->sort == "high_price" || 
                $request->sort == "actions" || 
                $request->sort == "comments" || 
                $request->sort == "alphabetAZ" || 
                $request->sort == "alphabetZA"
            )) {

            $sort_parameter = $request->sort;

        } else {

            $sort_parameter = "popular";

        }
        $data['sort_parameter'] = $sort_parameter;

        // в зависимости от параметра сортировки
        if($sort_parameter == "popular") {

            $items = $items->sortBy([['visite_counter', 'desc'], ['name']]);
// dd($items);
        } elseif($sort_parameter == "new_items") {

            $items = $items->sortBy([['is_new_item', 'desc'], ['updated_at', 'desc'], ['name']]);

        } elseif($sort_parameter == "low_price") {

            $items = $items->sortBy([['price'], ['name']]);

        } elseif($sort_parameter == "high_price") {

            $items = $items->sortBy([['price', 'desc'], ['name']]);

        } elseif($sort_parameter == "actions") {
// исправить!!
            $items = $items->sortBy('name');

        } elseif($sort_parameter == "comments") {

            $items = $items->sortBy([['comment_counter', 'desc'], ['name']]);

        } elseif($sort_parameter == "alphabetAZ") {

            $items = $items->sortBy('name');

        } elseif($sort_parameter == "alphabetZA") {

            $items = $items->sortByDesc('name');
        }

        // страница пагинации
        if (isset($request->page)) {
            $page = $request->page;
        } else {
            $page = 1;
        }

        // пагинация
        $data['items'] = $this->paginate($items, $paginate_num, $page)->withPath(\URL::current());

        return $data;
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {

        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}