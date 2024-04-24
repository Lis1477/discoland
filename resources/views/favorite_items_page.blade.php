@extends('layouts.base')

@section('content')

<div class="items-page">
	<div class="container info-block">

		<div class="bread-crumbs-block">

			<a href="/" class="current-category-name">Главная</a>

			<div>
				@include('svg.arrow')
			</div>

			<div class="current-category-name-wrapper">

				<div class="current-category-name no-link">
					Избранные товары
				</div>

			</div>

		</div>

		<section class="items-page_wrapper">
			
			<h1 class="items-page_name">
				Избранные товары
			</h1>

 			<div class="items-page_info-block">

				<aside class="items-page_left-block">

					@if(isset($max_price) && isset($min_price) && $max_price > $min_price)

						<div class="item-page_filters-wrapper">
							@include('includes.item_filters')
						</div>

					@endif

{{-- 					<div class="items-page_advertising-block">
						<div class="advertising-test">
							<div class="text">РЕКЛАМА</div>
						</div>
					</div>
					<div class="items-page_advertising-block">
						<div class="advertising-test">
							<div class="text">РЕКЛАМА</div>
						</div>
					</div>
 --}}
				</aside>

				<div class="items-page_items-wrapper">

					@if($items && $items->count())

						@include('includes.sort_items_line')

						<div class="items-page_items-block">

							@foreach($items as $item)

								@include('includes.item_block')

							@endforeach
							
						</div>

						{{ $items->links('includes.paginate_line', [
								'q_line' => $query_line,
								'filter_parameters' => $filter_parameters,
							]) }}

					@endif

					@php
						if($items) {
							$no_favorites_class = "none";
						} else {
							$no_favorites_class = "block";
						}
					@endphp

					<div class="favorites-page_no-favorites js-no-favorites" style="display: {{ $no_favorites_class }};">
						У Вас пока нет избранных товаров!
					</div>
					
				</div>

			</div>

		</section>

	</div>
</div>

@section('css')
@parent

    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">

@endsection

@endsection


@section('scripts')
@parent

<script type="text/javascript" src="{{ asset('js/image_links_handler.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/paginate_handler.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/sort_handler.js') }}"></script>

<script type="text/javascript" src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/filters.js') }}"></script>

@endsection