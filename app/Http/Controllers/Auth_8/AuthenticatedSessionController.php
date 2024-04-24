<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\CartItem;
use App\Models\FavoriteItem;
use App\Models\UserProfile;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        // *******************************************************
        $user_id = \Auth::user()->id;

        // проверяем, есть ли профайл
        $profile = UserProfile::where('user_id', $user_id)->first();

        // если нет, создаем
        if(!$profile) {
            $new_profile = new UserProfile;
            $new_profile->user_id = $user_id;
            $new_profile->save();
        }

        // объединение корзин *****************************
        // если есть кука корзины
        if(isset($_COOKIE['in_cart'])) {

            // берем корзину из куки
            $cookie_cart = json_decode($_COOKIE['in_cart']);

            // берем товары корзины из бд
            $bd_cart_items = CartItem::where('user_id', $user_id);

            // если есть
            if($bd_cart_items->count()) {
                // берем id товаров корзины из бд
                $bd_cart_codes = $bd_cart_items->pluck('item_id')->toArray();

                foreach($cookie_cart as $val) {

                    // если в элемент есть в бд
                    if(in_array($val->item_code, $bd_cart_codes)) {
                        // берем старое количество
                        $old_count = CartItem::where([['user_id', $user_id], ['item_id', $val->item_code]])->first(['amount'])->toArray()['amount'];

                        // новое количество
                        $new_count = $old_count + $val->item_count;
                        // обновляем количество
                        CartItem::where([['user_id', $user_id], ['item_id', $val->item_code]])
                            ->update([
                                'amount' => $new_count,
                        ]);
                    } else {
                        // добавляем в бд
                        CartItem::create([
                            'user_id' => $user_id,
                            'item_id' => $val->item_code,
                            'amount' => $val->item_count,
                        ]);
                    }
                }
            } else {
                // если в БД товаров нет, добавляем все из корзины
                foreach($cookie_cart as $val) {

                    $res = CartItem::create([
                        'user_id' => $user_id,
                        'item_id' => $val->item_code,
                        'amount' => $val->item_count,
                    ]);
                }

            }

            // удаляем куку корзины
            setcookie('in_cart', '', time() - 3600);

        }

        // объединение избранных товаров *****************************
        // если есть кука избранных
        if(isset($_COOKIE['favorite_items'])) {

            // берем товары из куки
            $cookie_favorite = json_decode($_COOKIE['favorite_items']);

            // берем избранные товары из бд
            $bd_favorite_items = FavoriteItem::where('user_id', $user_id);

            // если есть
            if($bd_favorite_items->count()) {
                // берем id товаров из бд
                $bd_favorite_codes = $bd_favorite_items->pluck('item_id')->toArray();

                foreach($cookie_favorite as $val) {
                    // если элементa нет в бд
                    if(!in_array($val->item_code, $bd_favorite_codes)) {
                        // добавляем в бд
                        FavoriteItem::create([
                            'user_id' => $user_id,
                            'item_id' => $val->item_code,
                        ]);
                    }
                }
            } else {
                // если в БД товаров нет, добавляем все из куки
                foreach($cookie_favorite as $val) {
                    FavoriteItem::create([
                        'user_id' => $user_id,
                        'item_id' => $val->item_code,
                    ]);
                }

            }

            // удаляем куку избранных
            setcookie('favorite_items', '', time() - 3600);
        }

        // return redirect()->intended(RouteServiceProvider::HOME);
        return redirect()->back();
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
