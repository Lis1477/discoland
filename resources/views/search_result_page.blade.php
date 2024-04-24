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
					Результат поиска
				</div>

			</div>

		</div>

		<section class="items-page_wrapper">
			
			<h1 class="items-page_name">
				Результат поиска: &laquo;{{ $search_string }}&raquo;
			</h1>

 			<div class="items-page_info-block">

				@if($items)

					<aside class="items-page_left-block">

						@if($max_price > $min_price)

							<div class="item-page_filters-wrapper">
								@include('includes.item_filters')
							</div>

						@endif

					</aside>

					<div class="items-page_items-wrapper">

						@include('includes.sort_items_line')

						@if($items->count())

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
						
					</div>

				@else

					<div class="no-search-result">По Вашему запросу ничего не найдено!</div>

				@endif

			</div>

		</section>

	</div>
</div>

@endsection

@section('css')
@parent

    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">

@endsection

@section('scripts')
@parent

<script type="text/javascript" src="{{ asset('js/image_links_handler.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/paginate_handler.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/sort_handler.js') }}"></script>

<script type="text/javascript" src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/filters.js') }}"></script>

@endsection