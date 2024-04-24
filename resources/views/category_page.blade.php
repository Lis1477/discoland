@extends('layouts.base')

@section('content')

<div class="category-page">
	<div class="container info-block">

		@include('includes.bread_crumbs_line')

		<section class="category-page_wrapper">
			
			<h1 class="category-page_name">
				{{ $current_cat->name }}
			</h1>

			<div class="category-page_info-block">
				
				<aside class="category-page_left-block">

					@if($max_price > $min_price)

						<div class="item-page_filters-wrapper">
							@include('includes.item_filters')
						</div>

					@endif

{{-- 					@if($banners->count())

						<div class="banners-block">
							
							@foreach($banners as $banner)

								<div class="banner-element">
									<a href="{{ asset($banner->link) }}" title="{{  $banner->title  }}">
										<img src="{{ asset('/img/'.$banner->image) }}">
									</a>
								</div>

							@endforeach

						</div>

					@endif
 --}}					
				</aside>

				<div class="category-page_right-block">

					<div class="category-page_category-links-block">

						@foreach($sub_cats as $cat)

							<a href="{{ asset('category/'.$cat->id.'/'.$cat->slug) }}" class="category-page_category-link-element">
								<div class="image">

									@if($cat->image)

										<img src="{{ asset('img/'.$cat->image) }}">

									@else

										<img src="{{ asset('/img/no_image.jpg') }}">

									@endif

								</div>

								<div class="name">
									{{ $cat->name }}
								</div>
							</a>
						
						@endforeach

					</div>

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

					<div class="category-page_text-block text">

						{!! $current_cat->text !!}
						
					</div>
					
				</div>
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

<script type="text/javascript" src="{{ asset('js/image_links_handler.js') }}"></script>

<script type="text/javascript" src="{{ asset('js/paginate_handler.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/sort_handler.js') }}"></script>

<script type="text/javascript" src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-ui-touch.js') }}"></script>

<script type="text/javascript" src="{{ asset('js/filters.js') }}"></script>

@parent


@endsection