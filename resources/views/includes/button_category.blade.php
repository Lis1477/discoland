<div class="button-category_wrapper js-category-drop-down" style="display: none;">

	<div class="button-category_comp js-comp-category" style="display: none;">

		<div class="button-category_main-category-block">

			@foreach($cats->where('parent_id', 0) as $cat)

				@php
					if($loop->iteration == 1) {
						$first_class = 'js-first-main-cat';
					} else {
						$first_class = '';
					}
				@endphp

				<a
					href="{{ asset('category/'.$cat->id.'/'.$cat->slug) }}"
					class="button-category_main-category-element js-main-category {{ $first_class }}"
					data-cat="{{ $cat->id }}"
				>

					<div class="button-category_main-category-name">
						{{ $cat->name }}
					</div>

					<div class="button-category_arrow">
						@include('svg.arrow')
					</div>

				</a>

			@endforeach
			
		</div>

		<div class="button-category_sub-category-block">
{{-- 
			<div class="button-category_advert-line">

				<a href="#">АКЦИИ</a>
				<a href="{{ asset('noviye-tovary') }}">НОВИНКИ</a>
				<a href="{{ asset('utsenionniye-tovary') }}">УЦЕНЕННЫЕ ТОВАРЫ</a>
				
			</div>
 --}}			
			@foreach($cats->where('parent_id', 0) as $cat)

				@php
					if($loop->iteration == 1) {
						$disp = 'block';
						$f_class = 'js-first-sub-cat';
					} else {
						$disp = 'none';
						$f_class = '';
					}
				@endphp

				<div
					class="button-category_sub-category-element-wrapper js-sub-category js-sub-cat-{{ $cat->id }} {{ $f_class }}"
					style="display: {{ $disp }};"
				>

					<ul class="button-category_sub-category-element">

						@foreach($cats->where('parent_id', $cat->id) as $sub_cat)

							<li class="name-block-wrapper">

								<a href="{{ asset('category/'.$sub_cat->id.'/'.$sub_cat->slug) }}" class="name-block">

									<div class="sub-category-thumb-image">

										@if($sub_cat->thumb_image)

											<img src="https://alfastok.by/storage/catalog-thumbs/{{ $sub_cat->thumb_image }}">

										@endif

									</div>

									<div class="sub-category-name">
										{{ $sub_cat->name }}
									</div>
									
								</a>
								
							</li>

							@foreach($cats->where('parent_id', $sub_cat->id) as $sub_sub_cat)

								<li>

									<a href="{{ asset('category/'.$sub_sub_cat->id.'/'.$sub_sub_cat->slug) }}" class="sub-sub-category-name">
										{{ $sub_sub_cat->name }}
									</a>

								</li>

							@endforeach

						@endforeach
						
					</ul>
						
				</div>

			@endforeach

		</div>

	</div>

	<div class="button-category_mobile js-mobile-category" style="display: none;">
{{-- 
		<div class="button-category_advert-line mobile">

			<a href="#">АКЦИИ</a>
			<a href="{{ asset('noviye-tovary') }}">НОВИНКИ</a>
			<a href="{{ asset('utsenionniye-tovary') }}">УЦЕНЕННЫЕ ТОВАРЫ</a>
			
		</div>
 --}}
		<div class="button-category_main-category-block mobile">

			@foreach($cats->where('parent_id', 0) as $cat)

				<a
					href="{{ asset('category/'.$cat->id.'/'.$cat->slug) }}"
					class="button-category_main-category-element js-main-category-mobile"
				>

					<div class="button-category_main-category-name">
						{{ $cat->name }}
					</div>

					<div class="button-category_arrow js-arrow">
						@include('svg.arrow')
					</div>

				</a>

				<div class="button-category_sub-category-block js-subcategory-mobile-block">

					<div class="button-category_sub-category-element-wrapper">

						<ul class="button-category_sub-category-element">

							<li class="name-block-wrapper">

								<a href="{{ asset('category/'.$cat->id.'/'.$cat->slug) }}" class="name-block first">

									<div class="sub-category-thumb-image">

										@if($cat->thumb_image)

											<img src="https://alfastok.by/storage/catalog-thumbs/{{ $cat->thumb_image }}">

										@endif

									</div>

									<div class="sub-category-name">
										Все товары категории
									</div>
									
								</a>
								
							</li>

							@foreach($cats->where('parent_id', $cat->id) as $sub_cat)

								<li class="name-block-wrapper">

									<a href="{{ asset('category/'.$sub_cat->id.'/'.$sub_cat->slug) }}" class="name-block">

										<div class="sub-category-thumb-image">

											@if($sub_cat->thumb_image)

												<img src="https://alfastok.by/storage/catalog-thumbs/{{ $sub_cat->thumb_image }}">

											@endif

										</div>

										<div class="sub-category-name">
											{{ $sub_cat->name }}
										</div>
										
									</a>
									
								</li>

								@foreach($cats->where('parent_id', $sub_cat->id) as $sub_sub_cat)

									<li>

										<a href="{{ asset('category/'.$sub_sub_cat->id.'/'.$sub_sub_cat->slug) }}" class="sub-sub-category-name">
											{{ $sub_sub_cat->name }}
										</a>

									</li>

								@endforeach

							@endforeach
							
						</ul>
							
					</div>

				</div>




			@endforeach
			
		</div>

	</div>

	
</div>