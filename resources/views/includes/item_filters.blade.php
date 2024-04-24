@if ($items->count() > 10 || @json_decode($filter_data, true))

<div class="item-filters-block">

	<h2>Фильтры:</h2>

	<form
		method="post"
		action=""
		class="js-filter-form"
		data-filters="{{ $filter_data }}"
	>
		<div class="price-range-block">

			<h3>Цена, руб.:</h3>

			<div class="min-max-block js-min-max" data-min-price="{{ $min_price }}" data-max-price="{{ $max_price }}">

				<div class="from-block">
					<div class="title">от</div>
					<input
						type="text"
						name="filters[price][price_from]"
						id="price_from"
					>
				</div>

				<div class="to-block" id="amount">
					<div class="title">до</div>
					<input
						type="text"
						name="filters[price][price_to]"
						id="price_to"
					>
				</div>

			</div>

			<div class="slider-range-block">

				<div id="slider-range"></div>

			</div>

		</div>

		@if($cats->count() > 1)

			<div class="category-block js-filter-block">

				<h3>Категория:</h3>

				@foreach($cats->take(5) as $cat)

					<div class="input-element">

						<label>
							<input
								type="checkbox"
								name="filters[category][]"
								value="{{ $cat->id }}"
								class="js-filter-category"
							>
							<span>{{ $cat->name }}</span>
						</label>

					</div>

				@endforeach

				@if($cats->count() > 5)

					<div class="hidden-block js-hidden-block">

						@foreach($cats->splice(5) as $cat)

							<div class="input-element">

								<label>
									<input
										type="checkbox"
										name="filters[category][]"
										value="{{ $cat->id }}"
										class="js-filter-category"
									>
									<span>{{ $cat->name }}</span>
								</label>

							</div>

						@endforeach
						
					</div>

					<div class="view-all js-view-all">
						<span class="js-filter-str">Показать все</span>
						<span class="arrow">&darr;</span>
					</div>

				@endif

			</div>

		@endif

		@if($styles->count() > 1)

			<div class="category-block js-filter-block">

				<h3>Жанр:</h3>

				@foreach($styles->take(5) as $style)

					<div class="input-element">

						<label>
							<input
								type="checkbox"
								name="filters[styles][]"
								value="{{ $style->id }}"
								class="js-filter-style"
							>
							<span>{{ Str::ucfirst($style->name) }}</span>
						</label>

					</div>

				@endforeach

				@if($styles->count() > 5)

					<div class="hidden-block js-hidden-block">

						@foreach($styles->splice(5) as $style)

							<div class="input-element">

								<label>
									<input
										type="checkbox"
										name="filters[styles][]"
										value="{{ $style->id }}"
										class="js-filter-style"
									>
									<span>{{ Str::ucfirst($style->name) }}</span>
								</label>

							</div>

						@endforeach
						
					</div>

					<div class="view-all js-view-all">
						<span class="js-filter-str">Показать все</span>
						<span class="arrow">&darr;</span>
					</div>

				@endif

			</div>

		@endif
{{-- 		@if(count($brands) > 1)

			<div class="category-block js-filter-block">

				<h3>Бренды:</h3>

				@foreach($brands->take(5) as $brand)

					<div class="input-element">

						<label>
							<input
								type="checkbox"
								name="filters[brand][]"
								value="{{ $brand->brand_1c_id }}"
								class="js-filter-brand"
							>
							<span>{{ $brand->name }}</span>
						</label>

					</div>

				@endforeach

				@if($brands->count() > 5)

					<div class="hidden-block js-hidden-block">

						@foreach($brands->splice(5) as $brand)

							<div class="input-element">

								<label>
									<input
										type="checkbox"
										name="filters[brand][]"
										value="{{ $cat->brand_1c_id }}"
										class="js-filter-brand"
									>
									<span>{{ $brand->name }}</span>
								</label>

							</div>

						@endforeach
						
					</div>

					<div class="view-all js-view-all">
						<span class="js-filter-str">Показать все</span>
						<span class="arrow">&darr;</span>
					</div>

				@endif

			</div>

		@endif
 --}}
	{{-- 	@else

			@if(count($chars))

				<div class="characteristic-wrapper">

					@foreach($chars as $name => $char)

						@if(count($char) > 1)

							@php
								// считаем количество элементов в столбце
								// всего элементов
								$cnt = count($char);
								// если есть остаток деления на 2
								if($cnt/2 - floor($cnt/2)) {
									$el_count = floor($cnt/2) + 1;
								} else {
									$el_count = $cnt/2;
								}
							@endphp

							<div class="characteristic-block">
								
								<h3 class="name">{{ $name }}:</h3>

								<div class="input-block">

									<div class="input-element first">

										@foreach($char as $val)

											<label>
												<input type="checkbox" name="" value="">
												<span>{{ $val }}</span>
											</label>

											@if($loop->iteration == $el_count)
												@break
											@endif

										@endforeach
										
									</div>

									<div class="input-element second">

										@foreach($char as $val)

											@if($loop->iteration <= $el_count)
												@continue
											@endif

											<label>
												<input type="checkbox" name="" value="">
												<span>{{ $val }}</span>
											</label>

										@endforeach
										
									</div>

								</div>

							</div>

						@endif

					@endforeach

				</div>

			@endif

		@endif --}}

	</form>

	<div class="button-wrapper">
		<a
			href="{{ asset(\Request::getRequestUri()) }}"
			class="button js-filter-submit-button"
		>
			Показать товары
			<span class="js-filtred-items-count items-count">({{ $items->total() }})</span>
		</a>

		<div class="clear-filters js-clear-filters">
			Очистить фильтры
		</div>

	</div>

</div>

@endif