@if ($paginator->hasPages())

@php($current_page = $paginator->currentPage())
{{-- {{dd($q_line)}} --}}
	<div class="paginate-links-block">

		<ul class="pagination">

	        @if($paginator->onFirstPage())
		            
				<li>
					<div class="arrow-block left">
						@include('svg.arrow')
					</div>
				</li>

	        @else

				<li>
					<a
						href="{{
								$q_line
							}}{{
								($current_page > 2) ? (($q_line) ? '&' : '?').'page='.($current_page - 1) : (($q_line) ? '' : \URL::current())
							}}{{
								($filter_parameters) ? (($current_page == 2 && !$q_line) ? '?' : '&').$filter_parameters : '' 
							}}"
						rel="prev"
					>
						<div class="arrow-block left active" title="Предыдущая страница">
							@include('svg.arrow')
						</div>
					</a>
				</li>

	        @endif

            @foreach($elements[0] as $page => $url)

                @if($page == $current_page)

					<li>
						<div class="page-number active" title="Страница {{ $page }}">
							{{ $page }}
						</div>
					</li>

                @else

					<li>
						<a href="{{
								$q_line
							}}{{
								($page > 1) ? (($q_line) ? '&' : '?').'page='.$page : (($q_line) ? '' : \URL::current())
							}}{{
								($filter_parameters) ? (($page == 1 && !$q_line) ? '?' : '&').$filter_parameters : '' 
							}}">
							<div class="page-number" title="Страница {{ $page }}">
								{{ $page }}
							</div>
						</a>
					</li>

                @endif

            @endforeach

	        @if($paginator->hasMorePages())

				<li>
					<a href="{{
							$q_line
						}}{{
							($q_line) ? '&' : '?' }}{{ 'page='.($current_page + 1)
						}}{{
							($filter_parameters) ? '&'.$filter_parameters : '' 
						}}"
						rel="next"
					>
						<div class="arrow-block right active" title="Следующая страница">
							@include('svg.arrow')
						</div>
					</a>
				</li>

	        @else
		            
				<li>
					<div class="arrow-block right">
						@include('svg.arrow')
					</div>
				</li>

	        @endif

	    </ul>

	</div>

@endif
