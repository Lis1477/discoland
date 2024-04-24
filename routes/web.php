<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TableTransferController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FavoriteItemController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PromoCodeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SimplePageController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\SdekController;

use App\Http\Controllers\Cabinet\ProfileController;
use App\Http\Controllers\Cabinet\HistoryController;
use App\Http\Controllers\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [MainPageController::class, 'index'])->name('main-page');

Route::any('/category/{id}/{slug}', [CategoryController::class, 'index'])->name('category-page');
Route::any('/noviye-tovary', [CategoryController::class, 'newItems'])->name('new-items');
Route::any('/favorite-items', [FavoriteItemController::class, 'index'])->name('favorite-items-page');
Route::post('/change-favorite', [FavoriteItemController::class, 'changeFavorite'])->name('change-favorite');

Route::get('tovar/{id}/{slug}', [ItemController::class, 'index'])->name('item-page');

Route::get('page/{slug}', [SimplePageController::class, 'index']);

Route::post('callback', [FeedbackController::class, 'callback']);
Route::post('feedback', [FeedbackController::class, 'feedback']);
Route::post('want-cheaper', [FeedbackController::class, 'wantСheaper']);



Route::get('/cart', [CartController::class, 'index'])->name('cart-page');
Route::post('change-cart', [CartController::class, 'changeCart'])->name('change-cart');
Route::post('/promocode-activate', [PromoCodeController::class, 'promoCodeActivate'])->name('promocode-activate');
Route::post('/promocode-verify', [PromoCodeController::class, 'promoCodeVerify']);

Route::get('order', [OrderController::class, 'index']);
Route::post('order', [OrderController::class, 'postOrder'])->name('order');
Route::post('one-click-order', [OrderController::class, 'oneClickOrder'])->name('one-click-order');
Route::post('ajax-city-search', [OrderController::class, 'ajaxGetCity']);
Route::post('ajax-delivery-price', [OrderController::class, 'ajaxGetDeliveryPrice']);
Route::post('ajax-cdek-pv', [OrderController::class, 'ajaxGetCdekPv']);

Route::any('/ajax-search', [SearchController::class, 'search'])->name('ajax-search');
Route::any('/search', [SearchController::class, 'search'])->name('get-search');


// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

// require __DIR__.'/auth.php';

Auth::routes();
Route::post('/password/send', [ForgotPasswordController::class, 'mailNewPassword']);

Route::group(['middleware' => 'auth', 'prefix' => 'cabinet'], function() {

    Route::get('profile', [ProfileController::class, 'index'])->name('view-profile');
    Route::post('profile-edit', [ProfileController::class, 'editProfile']);
    Route::post('add-address', [ProfileController::class, 'addAddress']);
    Route::post('update-address', [ProfileController::class, 'updateAddress']);
    Route::post('del-address', [ProfileController::class, 'deleteAddress']);
    Route::get('history', [HistoryController::class, 'index'])->name('view-history');
});

// перенос бд
Route::get('/cat-transfer', [TableTransferController::class, 'categoryTransfer']);
Route::get('/item-transfer', [TableTransferController::class, 'productTransfer']);
Route::get('/item-image-transfer', [TableTransferController::class, 'productImageTransfer']);
Route::get('/item-characteristics-transfer', [TableTransferController::class, 'productCharacteristicsTransfer']);
Route::get('/item-characteristics-retype', [TableTransferController::class, 'itemCharacteristicRetype']);
Route::get('/new-item-retype', [TableTransferController::class, 'newItemRetype']);


// тест сдека
Route::get('/test-sdek', [SdekController::class, 'testSdek']);