@extends('layouts.base')

@section('content')

@php

	if(request()->cookie('promo_code')) {
		$promo_code = json_decode(request()->cookie('promo_code'));
		$items_arr = json_decode(request()->cookie('items_arr'));
	}

// dd($promo_code->percent, $items_arr);

@endphp

<div class="cart-page">
	<div class="container info-block">

		<div class="bread-crumbs-block">

			<a href="/" class="current-category-name">Главная</a>

			<div>
				@include('svg.arrow')
			</div>

			<div class="current-category-name-wrapper">

				<div class="current-category-name no-link">
					Корзина товаров
				</div>

			</div>

		</div>

		<div class="cart-page_items-block-wrapper">

			<h1>Корзина товаров</h1>

			@if($cart_products)

				<div class="cart-page_in-cart-info-wrapper js-info-wrapper">

					<div class="cart-page_items-block">

						<div class="cart-page_headers">

							<div class="image-block"></div>
							<div class="name-block">Товар</div>
							<div class="in-cart-block">Количество</div>
							<div class="line-total-price-block">Стоимость</div>
							
						</div>

						@foreach($cart_products as $item)

							<div
								class="cart-page_item-line js-cart-line"
								data-item-weight="{{ $item['item']->weight }}"
								data-item-id="{{ $item['item']->id }}"
							>

								<div class="image-block">

									@if($item['item']->images->count())

										<img src="{{ asset('item_images/'.$item['item']->images[0]->image_sm) }}">

									@else

										<img src="{{ asset('/img/no_image.jpg') }}">

									@endif

								</div>

								<div class="name-block">
									<div class="name">
										<a href="{{ asset('tovar/'.$item['item']->id.'/'.$item['item']->slug) }}">
											{{ $item['item']->name }}
										</a>
									</div>

									<div class="code">
										Код товара: {{ $item['item']->id }}
									</div>

									<div class="sings-line">

										@if($item['item']->comment_counter > 0)

											<div class="comments-wrapper">

												<div class="comment-stars">
													@include('includes.stars_handler')
												</div>

												<div class="comment-count">
													{{ $item['item']->comment_counter }} отзывов
												</div>

											</div>

										@endif

										@if($item['item']->is_new_item)

											<div class="new-item-sing">
												NEW
											</div>

										@endif
										
										@if($item['item']->is_action)

											<div class="action-sing">
												Акция
											</div>

										@endif

										@if(request()->cookie('promo_code') && (in_array($item['item']->id, $items_arr) || !$items_arr) && !$item['item']->is_action)

											<div class="promo-sing">
												Промокод: <span class="promo-name">{{ $promo_code->name }}</span>
											</div>

										@endif
									</div>

								</div>

								<div class="in-cart-block">

									<div class="item-card-page_count-input-block cart">

										<div class="minus js-in-cart-minus item_in_cart">-</div>

										<div
											class="order-count js-in-cart-input item_in_cart"
											data-item-code="{{ $item['item']->id }}"
											data-rout-name="{{ Route::currentRouteName() }}"
										>
											<input type="number" value="{{ $item['amount'] }}" min="1" data-amount="{{ $item['item']->amount }}">
										</div>

										<div class="plus js-in-cart-plus item_in_cart">+</div>

									</div>

									@if($item['item']->is_action)

										<div class="item-price">
											<span class="js-item-price">{{ number_format($item['item']->action_price, 0, '.', ' ') }}</span>
											руб за шт.
										</div>

										<div class="old-price-wrapper">
											<div class="old-price-block">
												<div class="old-price">
													<span class="js-old-price">{{ number_format($item['item']->price, 0, '.', ' ') }}</span>
													руб
												</div>
												<div class="action-percent">
													-{{ number_format((1 - $item['item']->action_price / $item['item']->price) * 100, 0, '.', ' ') }}
													%
												</div>
											</div>
										</div>

									@elseif(request()->cookie('promo_code') && (in_array($item['item']->id, $items_arr) || !$items_arr))

										@php
											if(doubleval($promo_code->fixed)) {
												$promo_price = $item['item']->price - $promo_code->fixed;
												$promo_percent = $promo_code->fixed / $item['item']->price * 100;
											} else {
												$promo_price = $item['item']->price * (1 - $promo_code->percent / 100);
												$promo_percent = $promo_code->percent;
											}
										@endphp

										<div class="item-price">
											<span class="js-item-price">{{ number_format($promo_price, 0, '.', ' ') }}</span>
											руб за шт.
										</div>

										<div class="old-price-wrapper">
											<div class="old-price-block">
												<div class="old-price">
													<span class="js-old-price">{{ number_format($item['item']->price, 0, '.', ' ') }}</span>
													руб
												</div>
												<div class="action-percent">
													-{{ number_format($promo_percent, 0, '.', ' ') }}
													%
												</div>
											</div>
										</div>

									@else

										<div class="item-price">
											<span class="js-item-price">{{ number_format($item['item']->price, 0, '.', ' ') }}</span>
											руб за шт.
										</div>

									@endif

								</div>

								@php

									if($item['item']->is_action) {
										$line_price = $item['item']->action_price;
										$line_economy = number_format((($item['item']->price - $item['item']->action_price) * $item['amount']), 0, '.', '');
									} elseif(request()->cookie('promo_code') && (in_array($item['item']->id, $items_arr) || !$items_arr)) {
										$line_price = number_format($promo_price, 0, '.', '');
										$line_economy = ($item['item']->price - number_format($promo_price, 0, '.', '')) * $item['amount'];
									} else {
										$line_price = $item['item']->price;
										$line_economy = 0;
									}
								@endphp

								<div class="line-total-price-block">

									<div class="line-total-price-wrapper">

										<div class="line-total-price">
											<span class="js-line-total-price">{{ number_format($item['amount'] * $line_price, 0, '.', ' ') }}</span>
											руб
										</div>

										@if(request()->cookie('promo_code') && (in_array($item['item']->id, $items_arr) || !$items_arr) || $item['item']->is_action)

											<div class="line-economy">
												экономия:
												<span class="js-line-economy">{{ $line_economy }}</span>
												руб
											</div>

										@endif
										
									</div>

									<div class="delete-item-link js-delete-item" title="Удалить {{ $item['item']->name }}">
										Удалить
									</div>

								</div>

							</div>

						@endforeach

						<div class="cart-page_promocode-block">

							<form method="POST" action="{{ route('promocode-activate') }}">

								{{ csrf_field() }}

								<div class="input">
									<input type="text" name="promo_code" placeholder="Промокод">
								</div>

								<div class="button" title="Применить промокод">
									<button type="submit" class="js-promo-button">Применить</button>
								</div>
								
							</form>

							<div class="drop-out-wrapper js-drop-out">
								<div class="drop-out-block">

									<div class="js-answer-text"></div>

									<div class="arrow">
										<div class="close-button js-close-drop">✕</div>
									</div>

								</div>
							</div>

						</div>



					</div>

					<div class="cart-page_result-line">

						@php
							// считаем результат
							$total_price = 0;
							$total_economy = 0;
							$total_weight = 0;
							$price_for_sale = 0;
							$sale_percent = 0;
							// строка экономии
							$economy_str = "";

							foreach($cart_products as $item) {


								if($item['item']->is_action) {
									$line_economy_price = ($item['item']->price - $item['item']->action_price) * $item['amount'];
									$total_economy += $line_economy_price;
									$total_price += $item['item']->action_price * $item['amount'];

								} elseif(request()->cookie('promo_code') && (in_array($item['item']->id, $items_arr) || !$items_arr)) {

									if(doubleval($promo_code->fixed)) {
										$promo_price = $item['item']->price - $promo_code->fixed;
									} else {
										$promo_price = $item['item']->price * (1 - $promo_code->percent / 100);
									}

									$line_economy_price = ($item['item']->price - number_format($promo_price, 2, '.', '')) * $item['amount'];
									$total_economy += $line_economy_price;
									$total_price += number_format($promo_price, 0, '.', '') * $item['amount'];
								} else {
									$line_economy_price = "";
									$total_price += $item['item']->price * $item['amount'];
									$price_for_sale += $item['item']->price * $item['amount'];
								}

								// если есть экономия
								if($line_economy_price) {
									$economy_str .= $item['item']->id."-".$line_economy_price."|";
								}

								$total_weight += $item['item']->weight * $item['amount'];
							}

							// если строка экономии не пуста, удаляем крайнюю палочку
							if($economy_str) {
								$economy_str = mb_substr($economy_str, 0, -1);
							}

							// отображение строки результата экономии
							if($total_economy) {
								$total_economy_style = "block";
							} else {
								$total_economy_style = "none";
							}

							// отображение способов доставки в зависимости от веса
							if($total_weight < 28) {
								$delivery_type_style = "flex";
							} else {
								$delivery_type_style = "none";
							}

							// расчет бонусов по сумме
							if($price_for_sale) {
								// процент скидки
								if ($price_for_sale > 20000)
									$sale_percent = 10;
								elseif ($price_for_sale > 18000)
									$sale_percent = 8;
								elseif ($price_for_sale > 16000)
									$sale_percent = 7;
								elseif ($price_for_sale > 14000)
									$sale_percent = 6;
								elseif ($price_for_sale > 12000)
									$sale_percent = 5;
								elseif ($price_for_sale > 10000)
									$sale_percent = 4;
								elseif ($price_for_sale > 8000)
									$sale_percent = 3;
								elseif ($price_for_sale > 6000)
									$sale_percent = 2;
							}

							// считаем скидку
							$bonus_sum = $price_for_sale / 100 * $sale_percent;

							// показываем/скрываем блоки с инфой о бонусах
							if ($bonus_sum) 
								$bonus_style = "flex";
							else
								$bonus_style = "none";


// dd($price_for_sale);


							$bonus_sum = number_format($bonus_sum, 0, '.', ' ');
							// $whole_sum = number_format($whole_sum, 0, '.', ' ');
							$total_price = number_format($total_price, 0, '.', ' ');
							$total_economy = number_format($total_economy, 0, '.', ' ');
							$total_weight = number_format($total_weight, 0, '.', ' ');

						@endphp

						<div class="cart-page_result-block">

							<div class="total-price-block">
								<div class="title">
									Всего по товару:
								</div>
								<div class="price">
									<span class="js-total-price">{{ $total_price }}</span>
									руб
								</div>
							</div>

{{-- 							<div class="total-economy-wrapper js-total-economy-wrapper" style="display: {{ $total_economy_style }};">
								<div class="total-economy-block">
									<div class="title">Экономия:</div>
									<div class="economy">
										<span class="js-total-economy">{{ $total_economy }}</span>
										руб
									</div>
								</div>
							</div> --}}

							<div class="total-price-block js-sale-block" style="display: {{ $bonus_style }};">
								<div class="bonus-info">
									Вам будет начислено бонусных баллов на следующие покупки:
									<span>{{ $bonus_sum }}</span>
								</div>
							</div>


{{-- 							<div class="total-weight-wrapper js-total-weight-wrapper">
								<div class="total-weight-block">
									<div class="title">Общий вес:</div>
									<div class="weight">
										<span class="js-total-weight">{{ $total_weight }}</span>
										кг
									</div>
								</div>
							</div> --}}

						</div>

					</div>

					<div class="cart-page_start-registration-line js-start-registration">
						<a href="{{ asset('order') }}" class="button">Оформить заказ</a>
					</div>


				</div>

			@endif

			@php
				if($cart_products) {
					$items_no_style = "none";
				} else {
					$items_no_style = "block";
				}
			@endphp

			<div class="cart-page_no-items-wrapper js-empty-cart" style="display: {{ $items_no_style }};">

				<div class="cart-page_no-items">
					Корзина пуста!
				</div>
				
			</div>
			
		</div>

	</div>

</div>

@endsection

@section('scripts')
@parent

<script type="text/javascript" src="{{ asset('js/promo_code.js') }}"></script>

@endsection