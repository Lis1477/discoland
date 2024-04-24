<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\CurrencyRate;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        // курсы валют для пересчета цены
        $currency_rates = CurrencyRate::pluck('rate', 'id')->toArray();

        $this->currency_rates = $currency_rates;

    }

    // обработка изображений ***************************************************
    public function imageHandler($img_input_name, $img_output_name, $input_path, $output_path, $width, $height)
    {
        // $img_input_name - имя исходного файла
        // $img_output_name - имя полученного файла
        // $input_path - путь где брать файл
        // $output_path - путь куда положить
        // $width - ширина для исходящего изобр, или пусто
        // $height - высота для исходящего изобр, или пусто

        // если параметр ширины не пустой
        if($width) {
            // смотрим ширину входящего файла
            $input_width = Image::make($input_path.$img_input_name)->width();

            // если меньше, оставляем меньшую
            if($input_width <= $width) {
                $width = $input_width;
            }
        } else {
            $width = null;
        }

        if(!$height) $height = null;

        $img_result = Image::make($input_path.$img_input_name)
            ->resize($width, $height, function ($constraint) {
                $constraint->upsize();
                $constraint->aspectRatio();
            })
            ->save($output_path.$img_output_name);
    }

    // public function calc_price($price, $formula) : int
    // {

    //     $rates = $this->currency_rates;

    //     if ($formula == 0) {
    //         // пересчитываем из белорусской цены
    //         $calc_price = ceil($price / $rates[4] * 100 / 10) * 10;
    //     } elseif ($formula == 1) {
    //         // для российской цены
    //         $calc_price = ceil($price * 1.5 / 10) * 10;
    //     } elseif ($formula == 2) {
    //         // для евро российского ворнера
    //         $calc_price = ceil($price * 1.5 * $rates[2] / 10) * 10;
    //     } elseif ($formula == 3) {
    //         // для евро из Европы
    //         $calc_price = ceil($price * 1.5 * $rates[3] / 10) * 10;
    //     }

    //     return $calc_price;
    // }
    // public function getSortedAndPaginated($items, $request)
    // {

    //     // ***********************************************************
    //     // определяем количество выводимых товаров
    //     if(isset($request->items) && ($request->items == 40 || $request->items == 60)) {
    //         $paginate_num = $request->items;
    //     } else {
    //         $paginate_num = 20;
    //     }
    //     $data['paginate_num'] = $paginate_num;

    //     // определяем параметр сортировки
    //     if(isset($request->sort) && ($request->sort == "new_items" || $request->sort == "low_price" || $request->sort == "high_price" || $request->sort == "actions" || $request->sort == "comments")) {
    //         $sort_parameter = $request->sort;
    //     } else {
    //        $sort_parameter = "popular";
    //     }
    //     $data['sort_parameter'] = $sort_parameter;

    //     // в зависимости от параметра сортировки
    //     if($sort_parameter == "popular") {
    //         $items = $items->sortByDesc('visite_counter');
    //     } elseif($sort_parameter == "new_items") {
    //         $items = $items->sortBy([['is_new_item', 'desc'], ['name']]);
    //     } elseif($sort_parameter == "low_price") {
    //         $items = $items->sortBy([['price'], ['name']]);
    //     } elseif($sort_parameter == "high_price") {
    //         $items = $items->sortBy([['price', 'desc'], ['name']]);
    //     } elseif($sort_parameter == "actions") {
    //         $items = $items->sortBy([['is_action', 'desc'], ['name']]);
    //     } elseif($sort_parameter == "comments") {
    //         $items = $items->sortBy([['comment_counter', 'desc'], ['name']]);
    //     }

    //     // страница пагинации
    //     if (isset($request->page)) {
    //         $page = $request->page;
    //     } else {
    //         $page = 1;
    //     }

    //     // пагинация
    //     $data['items'] = $this->paginate($items, $paginate_num, $page);

    //     return $data;
    // }

    // public function paginate($items, $perPage = 5, $page = null, $options = [])
    // {
    //     $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    //     $items = $items instanceof Collection ? $items : Collection::make($items);
    //     return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    // }

}
