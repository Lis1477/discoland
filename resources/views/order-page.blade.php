@extends('layouts.base')

@section('content')

@php
	if(request()->cookie('promo_code')) {
		$promo_code = json_decode(request()->cookie('promo_code'));
		$items_arr = json_decode(request()->cookie('items_arr'));
	}
@endphp

<div class="order-page">
	<div class="container info-block">

		<div class="bread-crumbs-block">

			<a href="/" class="current-category-name">Главная</a>

			<div>
				@include('svg.arrow')
			</div>

			<div class="current-category-name-wrapper">

				<div class="current-category-name no-link">

					Оформление заказа

				</div>

			</div>

		</div>

		<div class="order-page_block">

			<h1>Оформление заказа</h1>

			<div class="order-page_content-wrapper">

				<div class="order-page_client-info-wrapper">

                    <form id="order-form" method="POST" action="{{ route('order') }}">
                        {{ csrf_field() }}

{{--                         <input type="hidden" name="items_total" value="{{ $total_price }}">
                        <input type="hidden" name="items_economy" value="{{ $total_economy }}">
                        <input type="hidden" name="items_weight" value="{{ $total_weight }}">
                        <input type="hidden" name="delivery_price" value="0">
                        <input type="hidden" name="items_string" value="{{ $economy_str }}">
                        <input type="hidden" name="promo_code_id"
                            value="@if (session('promo_code')) {{ $promo_code->id }} @endif">
 --}}
	                    <div class="order-page_recipient-block">

	                        <div class="order-input-block">

	                            <div class="title">
	                                Фамилия
	                                <span class="red-star">*</span>
	                            </div>

	                            <div class="input">
	                                <input
	                                	type="text"
	                                	name="family_name"
	                                	class="js-input-required"
	                                	required
	                                	placeholder="Фамилия"
	                                    value="@if (Auth::check() && $user['address']->count()) {{ $user['address'][0]->family_name }} @endif"
	                                >
	                                <div class="inset js-input-inset" style="display: none;">
	                                    <span class="">Это обязательное поле</span>
	                                    <div class="inset-arrow"></div>
	                                </div>
	                            </div>

	                        </div>

	                        <div class="order-input-block">

	                            <div class="title">
	                                Имя получателя
	                                <span class="red-star">*</span>
	                            </div>

	                            <div class="input">
	                                <input
	                                	type="text"
	                                	name="first_name"
	                                	class="js-input-required"
	                                	required
	                                	placeholder="Имя получателя"
	                                    value="@if (Auth::check() && $user['address']->count()) {{ $user['address'][0]->first_name }} @endif"
	                                >
	                                <div class="inset js-input-inset" style="display: none;">
	                                    <span class="">Это обязательное поле</span>
	                                    <div class="inset-arrow"></div>
	                                </div>
	                            </div>

	                        </div>

	                        <div class="order-input-block">

	                            <div class="title">
	                                Отчество
	                            </div>

	                            <div class="input">
	                                <input
	                                	type="text"
	                                	name="second_name"
	                                	placeholder="Отчество"
	                                    value="@if (Auth::check() && $user['address']->count()) {{ $user['address'][0]->second_name }} @endif"
	                                >
	                            </div>

	                        </div>

                            <div class="order-input-block">

                                <div class="title">
                                    Телефон
                                    <span class="red-star">*</span>
                                </div>

                                <div class="input">
                                    <input
                                    	type="tel"
                                    	id="phone"
                                    	name="phone"
	                                	class="js-input-required"
	                                	required
                                    	placeholder="Ваш телефон"
                                        value="@if (Auth::check()) {{ $user['phone'] }} @endif"
                                    >
                                </div>

                            </div>

                            <div class="order-input-block">

                                <div class="title">
                                    E-mail
                                    <span class="red-star">*</span>
                                </div>

                                <div class="input">
                                    <input
                                    	type="email"
                                    	name="email"
	                                	class="js-input-required"
	                                	required
                                    	placeholder="Ваш E-mail"
                                        value="@if (Auth::check()) {{ $user['email'] }} @endif"
                                    >
                                </div>

                            </div>

	                    </div>

	                    <div class="order-page_recipient-block">

	                        <div class="order-input-block">

	                            <div class="title">
	                                Страна
	                                <span class="red-star">*</span>
	                            </div>

	                            <div class="input">
									<select name="country" class="js-country">
										<option value="RU">Россия</option>
										<option value="BY">Беларусь</option>
										<option value="KZ">Казахстан</option>
										<option value="">Другая страна</option>
									</select>
	                            </div>

	                        </div>

	                        <div class="order-input-block city">

	                            <div class="title">
	                                Населенный пункт
	                                <span class="red-star">*</span>

	                                <div class="remark-block js-remark-block">
		                                <div class="remark-sign js-remark-sign">?</div>
		                                <div class="remark-text-block js-remark-text-block" style="width: 250px;">
		                                	<div class="remark-cross js-remark-cross">&#10006;</div>
		                                	<div class="remark-text">
		                                		<p>Введите населенный пункт полностью. В выпадающем списке выберите Ваш.</p>
		                                		<p>Если не появится выпадающий список, или Вашего нет в списке, проверьте корректность введенных данных или выберите Способ доставки "По договоренности".</p>
		                                		<p>Мы свяжемся с Вами, чтобы уточнить данные.</p>
		                                	</div>
		                                </div>
	                                </div>

	                            </div>

	                            <div class="input">
	                                <input
	                                	type="text"
	                                	name="city"
	                                	placeholder="Введите полностью"
	                                	class="js-city-search js-input-required"
	                                	data-latitude=""
	                                	data-longitude=""
	                                	data-city=""
	                                	required
	                                >
	                            </div>

	                            <div class="city-line-wrapper js-city-result"></div>



	                        </div>

	                        <div class="order-input-block">

	                            <div class="title">
	                                Способ доставки
	                                <span class="red-star">*</span>
	                            </div>

	                            <div class="input">
									<select name="delivery_type" class="js-delivery-type">
										<option value="cdek" class="js-type-cdek">CDEK</option>
										<option value="agreement" class="js-type-agreement">По договоренности</option>
									</select>
	                            </div>

	                        </div>

	                    </div>

	                    <div class="order-page_delivery-variant-block js-variant-block">
	                    	<div class="title">
	                    		Выберите вариант доставки:
	                    	</div>

	                    	<div class="delivery-variants js-variant-lines"></div>
	                    </div>

	                    <div class="order-page_agreement-block">

	                    	<label>
	                    		<input type="checkbox" name="another_choice" value="1" class="js-another-choice">
	                    		Выберите, если доставку и оплату желаете обсудить с нашим сотрудником
	                    	</label>

	                    </div>

	                    <div class="order-page_address-block">

                            <div class="order-input-block">

                                <div class="title">
                                    Улица
                                    <span class="red-star">*</span>
                                </div>

                                <div class="input">
                                    <input type="text" name="street"
                                        value="@if (Auth::check() && $user['address']->count()) {{ $user['address'][0]->street }} @endif">
                                </div>

                            </div>

                            <div class="order-input-block address">

                                <div class="title">
                                    Дом
                                    <span class="red-star">*</span>
                                </div>

                                <div class="input">
                                    <input type="text" name="house"
                                        value="@if (Auth::check() && $user['address']->count()) {{ $user['address'][0]->house }} @endif">
                                </div>

                            </div>

                            <div class="order-input-block address">

                                <div class="title">
                                    Корпус
                                </div>

                                <div class="input">
                                    <input type="text" name="corpus"
                                        value="@if (Auth::check() && $user['address']->count()) {{ $user['address'][0]->corpus }} @endif">
                                </div>

                            </div>

                            <div class="order-input-block address">

                                <div class="title">
                                    Квартира
                                </div>

                                <div class="input">
                                    <input type="text" name="flat"
                                        value="@if (Auth::check() && $user['address']->count()) {{ $user['address'][0]->flat }} @endif">
                                </div>

                            </div>

                        </div>



	                    <div class="order-page_continue-block">

	                    	<input type="hidden" name="delivery_pv" value="" class="js-delivery-pv">

	                        <div class="button js-order-continue">
	                            Продолжить
	                        </div>

	                        <div class="error-string js-continue-error-string"></div>

	                    </div>

	                </form>















				</div>

				<div class="order-page_cart-info-wrapper">

					@php
						$total_price = 0;
						$price_for_sale = 0;
					@endphp

					@foreach($cart_products as $item)

						<div class="order-page_item-line">

							<div class="image-block">

								@if($item['item']->images->count())

									<img src="{{ asset('item_images/'.$item['item']->images[0]->image_sm) }}">

								@else

									<img src="{{ asset('/img/no_image.jpg') }}">

								@endif

							</div>

							<div class="name-block">
								<div class="name">
									{{ $item['item']->name }}
								</div>
							</div>

							<div class="price-block">

								<div class="price">
									{{ $item['amount'] }}
									х

									@php
										if ($item['item']->is_action) {
											$price = $item['item']->action_price;
										} elseif (request()->cookie('promo_code') && (in_array($item['item']->id, $items_arr) || !$items_arr)) {
											if(intval($promo_code->fixed)) {
												$price = $item['item']->price - $promo_code->fixed;
											} else {
												$price = $item['item']->price * (1 - $promo_code->percent / 100);
											}
										} else {
											$price = $item['item']->price;
											$price_for_sale += $price * $item['amount'];
										}

										$total_price += $price * $item['amount'];
									@endphp

									{{ number_format($price, 0, '.', ' ') }}
									руб

								</div>

							</div>

						</div>

					@endforeach

					@php
						// считаем бонус
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
							else
								$sale_percent = 0;
						}

						$bonus_sum = $price_for_sale / 100 * $sale_percent;

					@endphp

					<div class="order-page_total-price-wrapper">
						<div class="order-page_total-price-block">
							<div class="title">Всего по товару:</div>
							<div class="price js-total-price" data-total-price="{{ $total_price }}">
								{{ number_format($total_price, 0, '.', ' ') }} руб
							</div>
						</div>

						<div class="order-page_total-price-block js-delivery-block" style="display: none;">
							<div class="title">
								Доставка:
							</div>
							<div class="price js-delivery-price"></div>
						</div>

						<hr class="js-delivery-block" style="display: none;">

						<div class="order-page_total-price-block js-delivery-block" style="display: none;">
							<div class="title">
								Итого:
							</div>
							<div class="price js-whole-price"></div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@include('popups.cdek_pv_map')

@endsection

@section('scripts')
@parent

<script type="text/javascript" src="{{ asset('js/order.js') }}"></script>
{{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@cdek-it/widget@3" charset="utf-8"></script> --}}

{{-- <script type="text/javascript" src="{{ asset('js/map_activation_shops.js') }}"></script> --}}

<script type="text/javascript" src="//api-maps.yandex.ru/2.1/?apikey=c2bfddad-d7e1-40d0-9182-d0d638fa3b58&lang=ru_RU"></script>

@endsection