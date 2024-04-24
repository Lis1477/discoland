<div class="items-page_sort-line-block">

	<div class="sort-block">
		<div class="title">
			Сначала
		</div>
		<div class="title-block">
			<div class="title js-sort-but">
				<div>{{ $sort_str }}</div>
				<div class="sort-arrow">
					@include('svg.arrow')
				</div>
			</div>

			<div class="sort-links js-sort-block" style="display: none;" data-sort="{{ $sort_str }}">
				<a
					href="{{
							$sort_first_param
						}}{{
							($search_string_param) ? (($sort_first_param == \URL::current()) ? '?' : '&').$search_string_param : ''
						}}{{
							($filter_parameters) ? (($sort_first_param == \URL::current() && $search_string_param) ? '?' : '&').$filter_parameters : '' 
						}}"
					class="{{ $popular_active }} js-sort-link"
				>
					популярные
				</a>
				<a
					href="{{
							$sort_first_param.$sort_delimiter
						}}sort=new_items{{
							($search_string_param) ? '&'.$search_string_param : ''
						}}{{
							($filter_parameters) ? '&'.$filter_parameters : '' 
						}}"
					class="{{ $new_items_active }} js-sort-link"
				>
					новинки
				</a>
				<a
					href="{{
							$sort_first_param.$sort_delimiter
						}}sort=low_price{{
							($search_string_param) ? '&'.$search_string_param : ''
						}}{{
							($filter_parameters) ? '&'.$filter_parameters : '' 
						}}"
					class="{{ $low_price_active }} js-sort-link"
				>
					дешевые
				</a>
				<a
					href="{{
							$sort_first_param.$sort_delimiter
						}}sort=high_price{{
							($search_string_param) ? '&'.$search_string_param : '' 
						}}{{
							($filter_parameters) ? '&'.$filter_parameters : '' 
						}}"
					class="{{ $high_price_active }} js-sort-link"
				>
					дорогие
				</a>
{{-- 				<a
					href="{{
							$sort_first_param.$sort_delimiter
						}}sort=actions{{
							($search_string_param) ? '&'.$search_string_param : ''
						}}{{
							($filter_parameters) ? '&'.$filter_parameters : '' 
						}}"
					class="{{ $actions_active }} js-sort-link"
				>
					акции и скидки
				</a> --}}
{{-- 				<a
					href="{{
							$sort_first_param.$sort_delimiter
						}}sort=comments{{
							($search_string_param) ? '&'.$search_string_param : ''
						}}{{
							($filter_parameters) ? '&'.$filter_parameters : '' 
						}}"
					class="{{ $comments_active }} js-sort-link"
				>
					с отзывами
				</a> --}}
				<a
					href="{{
							$sort_first_param.$sort_delimiter
						}}sort=alphabetAZ{{
							($search_string_param) ? '&'.$search_string_param : ''
						}}{{
							($filter_parameters) ? '&'.$filter_parameters : '' 
						}}"
					class="{{ $az_active }} js-sort-link"
				>
					по алфавиту А-Я
				</a>
				<a
					href="{{
							$sort_first_param.$sort_delimiter
						}}sort=alphabetZA{{
							($search_string_param) ? '&'.$search_string_param : '' 
						}}{{
							($filter_parameters) ? '&'.$filter_parameters : '' 
						}}"
					class="{{ $za_active }} js-sort-link"
				>
					по алфавиту Я-А
				</a>

			</div>
		</div>
	</div>

	@if($items->total() > 20)

	    <div class="paginate-counts-block">

	    	<div class="title">
	    		Показывать по:
	    	</div>

	    	<div class="paginate-count-link js-select-but">
		    	<div class="paginate-count">
		    		{{ $paginate_num }}
		    	</div>
		    	<div class="link-arrow-block">
		    		@include('svg.arrow')
		    	</div>
	    	</div>

	    	<div class="select-block js-select-block" style="display: none;" data-paginate="{{ $paginate_num }}">
	    		<a
	    			href="{{
	    					URL::current().$paginate_first_delimiter.$paginate_second_param
	    				}}{{
	    					($search_string_param) ? (($paginate_first_delimiter) ? '&' : '?').$search_string_param : ''
						}}{{
							($filter_parameters) ? ((!$paginate_first_delimiter && !$search_string_param) ? '?' : '&').$filter_parameters : '' 
						}}"
	    			class="{{ $p_20 }} js-paginate-link"
	    		>
	    			20
	    		</a>
	    		<a
	    			href="?items=40{{
	    					$paginate_delimiter.$paginate_second_param
	    				}}{{
	    					($search_string_param) ? '&'.$search_string_param : ''
	    				}}{{
							($filter_parameters) ? '&'.$filter_parameters : '' 
						}}"
	    			class="{{ $p_40 }} js-paginate-link"
	    		>
	    			40
	    		</a>
	    		<a
	    			href="?items=60{{
	    					$paginate_delimiter.$paginate_second_param
	    				}}{{
	    					($search_string_param) ? '&'.$search_string_param : ''
	    				}}{{
							($filter_parameters) ? '&'.$filter_parameters : '' 
						}}"
	    			class="{{ $p_60 }} js-paginate-link"
	    		>
	    			60
	    		</a>
	    	</div>

	    </div>

	@endif

</div>
