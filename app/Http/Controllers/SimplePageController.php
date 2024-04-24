<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SimplePage;

class SimplePageController extends Controller
{
    public function index($slug)
    {
        $page = SimplePage::where('slug', $slug)->first();
        $data['page'] = $page;

        if(empty($page->title)) {
            $title = $page->name." | Интернет-магазин disco-land.ru";
        } else {
            $title = $page->title;
        }
        $data['title'] = $title;

        $data['keywords'] = $page->keywords;
        $data['description'] = $page->description;

        return view('simple_page')->with($data);
    }
}
