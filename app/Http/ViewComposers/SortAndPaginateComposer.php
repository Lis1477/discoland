<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Http\Request;


class SortAndPaginateComposer
{

    public function compose(View $view)
    {

        $paginate_num = $view->paginate_num;
        $sort_parameter = $view->sort_parameter;

        // для блоков Показывать по и Сортировки
        if($paginate_num == 60) {
            $p_60 = 'active';
            $p_40 = '';
            $p_20 = '';
            $sort_first_param = "?items=".$paginate_num;
            $sort_delimiter = "&";
            $sort_first_param_pl = "&items=".$paginate_num;
        } elseif($paginate_num == 40) {
            $p_60 = '';
            $p_40 = 'active';
            $p_20 = '';

            $sort_first_param = "?items=".$paginate_num;
            $sort_delimiter = "&";
            $sort_first_param_pl = "&items=".$paginate_num;
        } else {
            $p_60 = '';
            $p_40 = '';
            $p_20 = 'active';

            $sort_first_param = \URL::current();
            $sort_delimiter = "?";
            $sort_first_param_pl = "";
        }

        if($sort_parameter == "popular") {
            $paginate_second_param = "";
            $paginate_first_delimiter = "";
            $paginate_delimiter = "";
        } else {
            $paginate_second_param = "sort=".$sort_parameter;
            $paginate_first_delimiter = "?";
            $paginate_delimiter = "&";
        }

        $popular_active = $new_items_active = $low_price_active = $high_price_active = $actions_active = $comments_active = $az_active = $za_active ="";

        if($sort_parameter == "new_items") {
            $sort_str = "новинки";
            $new_items_active = "active";
        } elseif($sort_parameter == "low_price") {
            $sort_str = "дешевые";
            $low_price_active = "active";
        } elseif($sort_parameter == "high_price") {
            $sort_str = "дорогие";
            $high_price_active = "active";
        } elseif($sort_parameter == "actions") {
            $sort_str = "акции и скидки";
            $actions_active = "active";
        } elseif($sort_parameter == "comments") {
            $sort_str = "с отзывами";
            $comments_active = "active";
        } elseif($sort_parameter == "alphabetAZ") {
            $sort_str = "по алфавиту А-Я";
            $az_active = "active";
        } elseif($sort_parameter == "alphabetZA") {
            $sort_str = "по алфавиту Я-А";
            $za_active = "active";
        } else {
            $sort_str = "популярные";
            $popular_active = "active";
        }

        // собираем в data
        $data['p_20'] = $p_20;
        $data['p_40'] = $p_40;
        $data['p_60'] = $p_60;

        $data['sort_first_param'] = $sort_first_param;
        $data['sort_first_param_pl'] = $sort_first_param_pl;
        $data['sort_delimiter'] = $sort_delimiter;

        $data['paginate_second_param'] = $paginate_second_param;
        $data['paginate_first_delimiter'] = $paginate_first_delimiter;
        $data['paginate_delimiter'] = $paginate_delimiter;

        $data['sort_str'] = $sort_str;
        $data['popular_active'] = $popular_active;
        $data['new_items_active'] = $new_items_active;
        $data['low_price_active'] = $low_price_active;
        $data['high_price_active'] = $high_price_active;
        $data['actions_active'] = $actions_active;
        $data['comments_active'] = $comments_active;
        $data['az_active'] = $az_active;
        $data['za_active'] = $za_active;

        if (isset($view->search_string)) {
            $search_string_param = 'search_string='.$view->search_string;
        } else {
            $search_string_param = '';
        }
        $data['search_string_param'] = $search_string_param;

        // формируем строку запроса для постраничной пагинации*****
        $query_line = '';
        $delimiter = '';

        if ($paginate_num != 20 || $sort_parameter != 'popular' || $search_string_param) {

            if ($paginate_num != 20) {

                $query_line .= 'items='.$paginate_num;

            }

            if ($sort_parameter != 'popular') {

                if ($query_line) {
                    $delimiter = '&';
                }

                $query_line .= $delimiter.'sort='.$sort_parameter;
            }

            if ($search_string_param) {

                if ($query_line) {
                    $delimiter = '&';
                }

                $query_line .= $delimiter.$search_string_param;
            }

            if ($query_line) {

                $query_line = '?'.$query_line;
            }
        }
        $data['query_line'] = $query_line;

        return $view->with($data);
    }

}