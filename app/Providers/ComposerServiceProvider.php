<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\CategoryButtonComposer;
use App\Http\ViewComposers\ItemInCartComposer;
use App\Http\ViewComposers\ItemInFavoriteComposer;
use App\Http\ViewComposers\BreadCrumbsComposer;
use App\Http\ViewComposers\SortAndPaginateComposer;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['includes.button_category'], CategoryButtonComposer::class);
        view()->composer(['*'], ItemInCartComposer::class);
        view()->composer(['*'], ItemInFavoriteComposer::class);
        view()->composer(['includes.bread_crumbs_line'], BreadCrumbsComposer::class);
        view()->composer([
            'includes.sort_items_line',
            'category_page',
            'new_items_page',
            'discounted_items_page',
            'search_result_page',
            'favorite_items_page'
        ], SortAndPaginateComposer::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
