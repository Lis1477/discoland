<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Category;

class CategoryButtonComposer
{
    public function compose(View $view) {

    	// собираем категории
    	$cats = Category::where('display', "1")->orderBy('order')->orderBy('name')->get();
        $data['cats'] = $cats;

        return $view->with($data);
    }
}