<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Collection;

use App\Models\Category;
use App\Models\Item;

class BreadCrumbsComposer
{

    public function compose(View $view) {

        // берем url
        $url = request()->path();

        // делим
        $url_arr = explode('/', $url);

        // тип 
        $bread_crumbs_type = $url_arr[0];

        if ($bread_crumbs_type == "category") {
            // id категории
            $category_id = $url_arr[1];

        } elseif ($bread_crumbs_type == "tovar") {
            // id товара
            $item_id = $url_arr[1];

            // id категории
            $category_id = Item::where('id', $item_id)->first(['category_id'])['category_id'];
        }

        // id родителя
        $parent_id = Category::where('id', $category_id)->first(['parent_id'])->parent_id;

        // определяем уровень категории
        $cat_level = $this->getCatLevel($parent_id);

        // собираем данные для Хлебных крошек ********************************
        $collect = new Collection;
        if($cat_level == 1) {
            // добавляем только 1-й уровень
            $collect->push([
                'id' => $category_id,
                'all_cats' => Category::where([['parent_id', 0], ['display', '1']])->orderBy('order')->get(),
            ]);
        } elseif($cat_level == 2) {
            // добавляем 1-й уровень
            $collect->push([
                'id' => $parent_id,
                'all_cats' => Category::where([['parent_id', 0], ['display', '1']])->orderBy('order')->get(),
            ]);
            // добавляем 2-й уровень
            $collect->push([
                'id' => $category_id,
                'all_cats' => Category::where([['parent_id', $parent_id], ['display', '1']])->orderBy('order')->get(),
            ]);
        } else { // если у категории 3-й уровень
            // добавляем 1-й уровень
            $collect->push([
                'id' => $parent,
                'all_cats' => Category::where([['parent_id', 0], ['display', '1']])->orderBy('order')->get(),
            ]);

            // добавляем 2-й уровень
            $collect->push([
                'id' => $category->parent_id,
                'all_cats' => Category::where([['parent_id', $parent], ['display', '1']])->orderBy('order')->get(),
            ]);

            // добавляем 3-й уровень
            $collect->push([
                'id' => $category->id,
                'all_cats' => Category::where([['parent_id', $category->parent_id], ['display', '1']])->orderBy('order')->get(),
            ]);
        }
        $data['bread_crumbs'] = $collect;
        $data['bread_crumbs_type'] = $bread_crumbs_type;

        return $view->with($data);
    }

    private function getCatLevel($catParentId) : int
    {

        // определяем уровень вложенности категории
        if($catParentId > 0) {
            $parent = Category::where('id', $catParentId)->first()->parent_id;
            if($parent > 0) {
                $cat_level = 3;
            } else {
                $cat_level = 2;
            }
        } else {
            $cat_level = 1;
        }

        return $cat_level;
    }
}