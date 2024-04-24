<div class="bread-crumbs-block">

	<a href="/" class="current-category-name">Главная</a>

	<div>
		@include('svg.arrow')
	</div>

	@foreach($bread_crumbs as $bread)

		@if($bread_crumbs->count() == $loop->iteration && $bread_crumbs_type != "tovar")

			<div class="current-category-name-wrapper">

				<div class="current-category-name no-link">

					{{ $bread['all_cats']->where('id', $bread['id'])->first()->name }}

				</div>

				@if($bread['all_cats']->where('id', '!=', $bread['id'])->count())

					<nav class="category-links-block">
						<ul>

							@foreach($bread['all_cats']->where('id', '!=', $bread['id']) as $cat)

								@if($cat->id == 193)
									@continue
								@endif

								<li>
									<a href="{{ asset(asset('category/'.$cat->id.'/'.$cat->slug)) }}">
										{{ $cat->name }}
									</a>
								</li>

							@endforeach
							
						</ul>
					</nav>

				@endif
				
			</div>

		@else

			<div class="current-category-name-wrapper">

				<a
					href="{{ asset('category/'.$bread['id'].'/'.$bread['all_cats']->where('id', $bread['id'])->first()->slug) }}"
					class="current-category-name"
				>

					{{ $bread['all_cats']->where('id', $bread['id'])->first()->name }}

				</a>

				@if($bread['all_cats']->where('id', '!=', $bread['id'])->count())

					<nav class="category-links-block">
						<ul>

							@foreach($bread['all_cats']->where('id', '!=', $bread['id']) as $cat)

								@if($cat->id == 193)
									@continue
								@endif

								<li>
									<a href="{{ asset(asset('category/'.$cat->id.'/'.$cat->slug)) }}">
										{{ $cat->name }}
									</a>
								</li>

							@endforeach
							
						</ul>
					</nav>

				@endif

			</div>

			<div>
				@include('svg.arrow')
			</div>

		@endif

	@endforeach

	@if($bread_crumbs_type == "tovar")

		<div class="current-category-name no-link">

			{{ $item->name }}

		</div>

	@endif

</div>