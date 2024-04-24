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

					Тест CDEK

				</div>

			</div>

		</div>

		<div class="simple-page_block">

			<h1>Тест CDEK</h1>

			<div class="simple-page_content">

				<div id="cdek-map" style="width:800px; height:600px"></div>

			</div>

		</div>

	</div>
</div>

@endsection

@section('scripts')
@parent

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@cdek-it/widget@3" charset="utf-8"></script>

<script type="text/javascript">
    $(document).ready(function() {
		new window.CDEKWidget({
			from: 'Минск',
			root: 'cdek-map',
			apiKey: 'c2bfddad-d7e1-40d0-9182-d0d638fa3b58',
			servicePath: 'http://host1862077.hostland.pro/service.php',
			defaultLocation: 'Москва'
	    });
	});
</script>

@endsection