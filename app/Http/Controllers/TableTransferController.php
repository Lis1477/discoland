<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Wrapper;
use App\Models\Publisher;
use App\Models\DataCarrier;
use App\Models\Country;
use App\Models\MusicStyle;
use App\Models\MusicStyleRelation;

class TableTransferController extends Controller
{

    public function categoryTransfer()
    {

        // берем категории из старой бд
        $old_cats = DB::connection('mysql2')
            ->table('SS_categories')
            ->get();

        // массив старых id категорий в новой бд
        $new_cat_id_arr = Category::pluck('old_id')->toArray();

        // переносим
        foreach ($old_cats as $cat) {

            // если категория уже есть
            if (in_array($cat->categoryID, $new_cat_id_arr)) {

                // удаляем элемент из массива
                $new_cat_id_arr = array_diff($new_cat_id_arr, [$cat->categoryID]);

            } else {

                // узнаем id родителя
                if ($cat->parent == '0') {
                    $parent = 0;
                } else {
                    $parent = Category::where('old_id', $cat->parent)->first(['id'])->id;
                }

                $new_cat = new Category;

                // готовим данные для переноса
                $new_cat->old_id = $cat->categoryID;
                $new_cat->parent_id = $parent;
                $new_cat->name = $cat->name;
                $new_cat->slug = Str::of($cat->name)->slug('-');
                $new_cat->image = $cat->picture;
                $new_cat->text = $cat->description;
                $new_cat->keywords = $cat->keywords;
                $new_cat->description = $cat->description_small;
                $new_cat->order = $cat->order_cat;

                // сохраняем
                $new_cat->save();

            }

        }

        return 'Категории перенесены';
    }

    public function productTransfer()
    {

        // берем товары из старой бд
        $old_items = DB::connection('mysql2')
            ->table('SS_products')
            ->orderBy('productID')
            ->get();

        // массив старых id категорий в новой бд
        $new_item_id_arr = Item::pluck('old_id')->toArray();

        foreach ($old_items as $item) {

            // если товар уже есть
            if (in_array($item->productID, $new_item_id_arr)) {

                // удаляем элемент из массива
                $new_item_id_arr = array_diff($new_item_id_arr, [$item->productID]);

                // обновляем товар
                $item_for_update = Item::where('old_id', $item->productID)->first();

                $cat_id = Category::where('old_id', $item->categoryID)->first(['id'])->id;

                $item_for_update->old_id = $item->productID;
                $item_for_update->category_id = $cat_id;
                $item_for_update->name = $item->name;
                $item_for_update->slug = Str::of($item->name)->slug('-');
                $item_for_update->barcode = $item->product_code;
                $item_for_update->artist = $item->artist;
                $item_for_update->album_year = $item->album_year;
                $item_for_update->album_reissue = $item->album_reissue;
                $item_for_update->wrapper_id = $item->wrapper;
                $item_for_update->publisher_id = $item->publisher;
                $item_for_update->data_carrier_id = $item->data_carrier;
                $item_for_update->disks_number = $item->disks_number;
                $item_for_update->features = $item->features;
                $item_for_update->country_id = $item->country;
                $item_for_update->text = $item->description;
                $item_for_update->video = $item->video;
                $item_for_update->price = $item->Price;
                $item_for_update->formula = $item->formula;
                $item_for_update->amount = $item->in_stock;
                $item_for_update->description = mb_strimwidth(strip_tags($item->brief_description), 0, 510, '...');
                $item_for_update->for_sale = $item->enabled;

                $item_for_update->update();

            } else {

                // новый товар
                $new_item = new Item;

                // готовим данные для переноса
                $new_item->old_id = $item->productID;

                // находим id родительской категории
                $cat_id = Category::where('old_id', $item->categoryID)->first(['id'])->id;

                $new_item->category_id = $cat_id;
                $new_item->name = $item->name;
                $new_item->slug = Str::of($item->name)->slug('-');
                $new_item->barcode = $item->product_code;
                $new_item->artist = $item->artist;
                $new_item->album_year = $item->album_year;
                $new_item->album_reissue = $item->album_reissue;
                $new_item->wrapper_id = $item->wrapper;
                $new_item->publisher_id = $item->publisher;
                $new_item->data_carrier_id = $item->data_carrier;
                $new_item->disks_number = $item->disks_number;
                $new_item->features = $item->features;
                $new_item->country_id = $item->country;
                $new_item->text = $item->description;
                $new_item->video = $item->video;
                $new_item->price = $item->Price;
                $new_item->formula = $item->formula;
                $new_item->amount = $item->in_stock;
                $new_item->description = mb_strimwidth(strip_tags($item->brief_description), 0, 510, '...');
                $new_item->for_sale = $item->enabled;

                // сохраняем
                $new_item->save();
            }
        }

        return 'Товары перенесены';

    }

    public function productImageTransfer()
    {
        // // очишаем таблицу картинок (ТОЛЬКО ДЛЯ ПЕРВОГО ЗАПУСКА)
        // ItemImage::truncate();

        // берем товары из старой бд
        $old_items = DB::connection('mysql2')
            ->table('SS_products')
            ->orderBy('productID', 'desc')
            ->get(['productID', 'big_picture']);

        // устанавливаем таймаут на бесконечность
        ini_set('max_execution_time', '0');

        foreach ($old_items as $item) {

            // если не прописано имя файла, пропускаем
            if ($item->big_picture == '') {
                continue;
            }

            // определяем id изображения в новой базе
            $pic_id = Item::where('old_id', $item->productID)->first(['id'])->id;

            // имя входящего файла
            $img_input_name = $item->big_picture;

            // путь где лежит входящий файл
            $input_path = 'https://discoland.by/products_pictures/';

            $headers = @get_headers($input_path.$img_input_name);

            // если файл существует
            if (strpos(@get_headers($input_path.$img_input_name)[0], '200') !== false) {

                // путь где будет лежать исходящий файл
                $output_path = public_path('item_images/');

                // имя большого файла
                $pic_name_big = $pic_id.'_big.jpg';
                // имя среднего файла
                $pic_name_mid = $pic_id.'_mid.jpg';
                // имя малого файла
                $pic_name_sm = $pic_id.'_sm.jpg';

                // если файл НЕ существует
                if (strpos(@get_headers($output_path.$pic_name_big)[0], '200') === false) {
                    // высота большого файла
                    $height_big = 1200;

                    // высота среднего файла
                    $height_mid = 300;

                    // высота малого файла
                    $height_sm = 75;

                    // ресайзим
                    $this->imageHandler($img_input_name, $pic_name_big, $input_path, $output_path, '', $height_big);
                    $this->imageHandler($img_input_name, $pic_name_mid, $input_path, $output_path, '', $height_mid);
                    $this->imageHandler($img_input_name, $pic_name_sm, $input_path, $output_path, '', $height_sm);
                }

                // записываем или обновляем в бд

                ItemImage::updateOrCreate(
                    ['item_id' => $pic_id],
                    [
                        'image' => $pic_name_big,
                        'image_mid' => $pic_name_mid,
                        'image_sm' => $pic_name_sm
                    ]
                );
            }
        }

        return "Изображения перенесены";
    }

    public function productCharacteristicsTransfer()
    {
        // характеристики

        // берем упаковку из старой бд *******************
        $old_wrappers = DB::connection('mysql2')
            ->table('wrappers')
            ->get();

        // очищаем таблицу новой бд
        Wrapper::truncate();

        foreach ($old_wrappers as $wrapper) {
            $new_wrapper = new Wrapper;

            $new_wrapper->old_id = $wrapper->id;
            $new_wrapper->name_en = $wrapper->name_en;
            $new_wrapper->name_ru = $wrapper->name_ru;
            $new_wrapper->description = $wrapper->description;

            $new_wrapper->save();
        }

        // берем издателей из старой бд ********************
        $old_publishers = DB::connection('mysql2')
            ->table('publishers')
            ->get();

        // очищаем таблицу новой бд
        Publisher::truncate();

        foreach ($old_publishers as $publisher) {
            $new_publisher = new Publisher;

            $new_publisher->old_id = $publisher->id;
            $new_publisher->name = $publisher->name;

            $new_publisher->save();
        }

        // берем носители из старой бд *********************
        $old_data_carriers = DB::connection('mysql2')
            ->table('data_carriers')
            ->orderBy('id')
            ->get();

        // очищаем таблицу новой бд
        DataCarrier::truncate();

        foreach ($old_data_carriers as $data_carrier) {
            $new_data_carrier = new DataCarrier;

            $new_data_carrier->old_id = $data_carrier->id;
            $new_data_carrier->name = $data_carrier->name;

            $new_data_carrier->save();
        }

        // берем страны из старой бд *******************
        $old_countries = DB::connection('mysql2')
            ->table('countries')
            ->get();

        // очищаем таблицу новой бд
        Country::truncate();

        foreach ($old_countries as $country) {
            $new_country = new Country;

            $new_country->old_id = $country->id;
            $new_country->name = $country->name;

            $new_country->save();
        }

        // берем жанры из старой бд *******************
        $old_music_styles = DB::connection('mysql2')
            ->table('music_styles')
            ->get();

        // очищаем таблицу новой бд
        MusicStyle::truncate();

        foreach ($old_music_styles as $music_style) {
            $new_music_style = new MusicStyle;

            $new_music_style->old_id = $music_style->id;
            $new_music_style->name = $music_style->name;

            $new_music_style->save();
        }


        // берем связи товар-жанр из старой бд *******************
        $old_style_relations = DB::connection('mysql2')
            ->table('music_style_relations')
            ->get();

        // очищаем таблицу новой бд
        MusicStyleRelation::truncate();

        foreach ($old_style_relations as $style_relation) {
            $new_style_relation = new MusicStyleRelation;

            // узнаем id товара в новой базе 
            $product_id = Item::where('old_id', $style_relation->product_id)
                ->first(['id'])['id'];

            // узнаем id стиля в новой базе 
            $style_id = MusicStyle::where('old_id', $style_relation->style_id)
                ->first(['id'])['id'];

            $new_style_relation->product_id = $product_id;
            $new_style_relation->style_id = $style_id;

            $new_style_relation->save();
        }

        return "Характеристики товаров перенесены";
    }

    public function itemCharacteristicRetype()
    {
        // берем товары
        $items = Item::all();

        // вписываем новые значения id характеристик
        foreach ($items as $item) {

            if ($item->wrapper_id) {
                $new_wrapper_id = Wrapper::where('old_id', $item->wrapper_id)
                    ->first(['id'])->id;
                $item->wrapper_id = $new_wrapper_id;
            }

            if ($item->publisher_id) {
                $new_publisher_id = Publisher::where('old_id', $item->publisher_id)
                    ->first(['id'])->id;
                $item->publisher_id = $new_publisher_id;
            }

            if ($item->data_carrier_id) {
                $new_data_carrier_id = DataCarrier::where('old_id', $item->data_carrier_id)
                    ->first(['id'])->id;
                $item->data_carrier_id = $new_data_carrier_id;
            }

            if ($item->country_id) {
                $new_country_id = Country::where('old_id', $item->country_id)
                    ->first(['id'])->id;
                $item->country_id = $new_country_id;
            }

            $item->update();
        }

        return "id характеристик переписали";
    }

    public function newItemRetype()
    {
        // id товаров новинок из старой базы
        $old_new_item_arr = DB::connection('mysql2')
            ->table('SS_special_offers')
            ->pluck('productID')
            ->toArray();

        // берем товары
        $new_items = Item::whereIn('old_id', $old_new_item_arr)->get(['id', 'old_id', 'is_new_item']);

        // переписываем is_new_item 
        foreach ($new_items as $item) {
            $item->is_new_item = "1";
            $item->save();
        }

        return "новые товары записаны";
    }
}

