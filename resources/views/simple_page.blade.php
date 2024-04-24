@extends('layouts.base')

@section('content')

<div class="simple-page">
	<div class="container info-block">

		<div class="bread-crumbs-block">

			<a href="/" class="current-category-name">Главная</a>

			<div>
				@include('svg.arrow')
			</div>

			<div class="current-category-name-wrapper">

				<div class="current-category-name no-link">

					{{ $page->name }}

				</div>

			</div>

		</div>

		<div class="simple-page_block">

			<h1>{{ $page->name }}</h1>

			<div class="simple-page_content">

				{!! $page->content !!}

			</div>

		</div>

	</div>
</div>

@endsection
