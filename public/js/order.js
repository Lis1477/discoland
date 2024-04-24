$(document).ready(function(){

	console.log('оформление заказа');

	// показываем результат поиска города
    $('body').on('input', '.js-city-search', function (e) {

    	// скрываем и очищаем выпадашку вариантов доставки, блока цены доставки
		$('.js-city-result').hide().html('');
		$('.js-delivery-block, .js-variant-block').hide();
		$('.js-delivery-price, js-whole-price, js-variant-lines').html('');
		// очищаем ранее сформированную карту
		$('#js-cdek-pv-map').html('');
		$('.js-delivery-pv').val('');

		var result, city_line;

    	// берем поисковую фразу
        search_string = $.trim($(this).val());
        // берем код страны
        country_code = $('select[name=country]').val();

		// берем токен
		var token = $('input[name=_token]').val();

        if(search_string.length > 2) {

	        $.ajax({
	            type: 'post',
	            url: "/ajax-city-search",
	            data: {
	                'search_string': search_string,
	                'country_code': country_code,
	                '_token': token,
	            },
	            success: function(data){
	            	if (data.length) {

		            	// формируем результат
						result = '<div class="city-lines">';

						$.each(data, function(i, val){

			                city_line = val.city;
			                if (val.sub_region && val.sub_region != val.city)
			                    city_line += ", "+val.sub_region;
			                if (val.region && val.region != val.city)
			                    city_line += ", "+val.region;
			                if (val.country)
			                    city_line += ", "+val.country;

			                result += '<div class="line js-city-line" data-code="'+val.code+'" data-latitude="'+val.latitude+'" data-longitude="'+val.longitude+'" data-city="'+val.city+'">'+city_line+'</div>';
						});

						result += '</div>';

						// вставляем
		            	$('.js-city-result').show().html(result);

		            	// console.log(data);
	            	}
	            },
	        });
        }
    });

    // обработка выпадашки городов
    $('.js-city-result').delegate('.js-city-line', 'click', function(){

    	// вставляем город в инпут
    	$('.js-city-search').val($(this).text()).data('latitude', $(this).data('latitude')).data('longitude', $(this).data('longitude'));
    	$('.js-city-search').data('latitude', $(this).data('latitude'));
    	$('.js-city-search').data('longitude', $(this).data('longitude'));
    	$('.js-city-search').data('city', $(this).data('city'));

    	// скрываем и очищаем выпадашку
   		$('.js-city-result').hide().html('');

   		// берем страну
   		var country = $('.js-country').val();
   		// берем способ доставки
   		var delivery_type = $('.js-delivery-type').val();

   		// если выбрана страна и способ доставки сдек
   		if (country && delivery_type == "cdek") {
			// берем токен
			var token = $('input[name=_token]').val();
			// берем код города доставки
			var city_code = $(this).data('code');

			var result, description_line, checked_attr, total_sum, choice_pv, active_pv;
			var delivery_price = 0;
			var delivery_sum = 0;

	   		// берем цены доставки в этот город
	        $.ajax({
	            type: 'post',
	            url: "/ajax-delivery-price",
	            data: {
	                'city_code': city_code,
	                '_token': token,
	            },
	            success: function(data){

	            	if (data.length) {

	            		//отображаем блок вариантов доставки
	            		$('.js-variant-block').show();

		            	// формируем результат
						result = "";
						$.each(data, function(i, val){

							description_line = "";

							// формируем строку описания
							if (val.tariff_code == 136 || val.tariff_code == 137)
								description_line += "Стандартная доставка";
							else if (val.tariff_code == 233 || val.tariff_code == 234)
								description_line += "Экономичная доставка";
							else
								description_line += val.tariff_description;

							if (val.tariff_code == 136 || val.tariff_code == 483 || val.tariff_code == 234)
								description_line += " до ПВ";
							else
								description_line += " до двери";

							if (val.period_min != val.period_max)
								description_line += " ("+(val.period_min + 1)+" - "+(val.period_max + 1)+" рабочих дн.)";
							else
								description_line += " ("+(val.period_max + 1)+" рабочих дн.)";

							delivery_price = Math.ceil(val.delivery_sum / 100) * 100;

							description_line += " -&nbsp;<strong>"+delivery_price+"</strong>&nbsp;руб";

							// чекаем первый элемент
							if (i === 0) {
								checked_attr = " checked";
								delivery_sum = delivery_price;
							} else {
								checked_attr = "";
							}

							if (val.delivery_mode === 4) {
								if (i === 0) {
									active_pv = "active";
								} else {
									active_pv = "";
								}
								choice_pv = '<div class="choice-pv '+active_pv+' js-choice-pv" data-city-code="'+city_code+'">Выберите ПВ на карте</div>';
							} else {
								choice_pv = "";
							}


			                result += '<div class="delivery-variant-line js-delivery-variant-line"><label><input type="radio" name="delivery_variant" value="'+val.tariff_code+'" data-delivery-price="'+delivery_price+'" data-delivery-mode="'+val.delivery_mode+'"'+checked_attr+'>'+description_line+'</label>'+choice_pv+'</div>';
						});

						// вставляем
		            	$('.js-variant-lines').html(result);

		            	// отображаем блок доставки
		            	$('.js-delivery-block').show();
		            	// вставляем стоимость доставки
		            	$('.js-delivery-price').text(number_format(delivery_sum)+" руб");
		            	// берем стоимость товара
		            	total_sum = $('.js-total-price').data('totalPrice');
		            	// вставляем общую сумму
		            	$('.js-whole-price').text(number_format(delivery_sum + total_sum)+" руб");



	            	} else {
	            		result = '';
	            		$('.js-variant-lines').html('');
	            		$('.js-variant-block').hide();
	            	}

	            	console.log(data);
	            },
	        });
   		}

    });


    // обработка вариантов доставки
    $('.js-variant-lines').delegate('input[name=delivery_variant]', 'click', function(){
    	// берем стоимость доставки
    	var delivery_price = $(this).data('deliveryPrice');
    	// вставляем стоимость доставки
    	$('.js-delivery-price').text(number_format(delivery_price)+" руб");
    	// берем стоимость товара
    	var total_sum = $('.js-total-price').data('totalPrice');
    	// вставляем общую сумму
    	$('.js-whole-price').text(number_format(delivery_price + total_sum)+" руб");

    	var delivery_mode = $(this).data('deliveryMode');
    	$('.js-choice-pv').removeClass('active');
    	if (delivery_mode == 4) {
    		$(this).parents('.js-delivery-variant-line').find('.js-choice-pv').addClass('active');
    	} else {
	    	$('.js-pv-address').remove();
	    	$('.js-delivery-pv').val('');
    	}

    });

    // обработчик селекта Страна
    $('.js-country').on('change', function(){

    	// скрываем и очищаем выпадашку вариантов доставки, блока цены доставки, очищаем инпут города
		$('.js-city-result').hide().html('');
		$('.js-delivery-block, .js-variant-block').hide();
		$('.js-delivery-price, js-whole-price, js-variant-lines').html('');
		$('.js-city-search').val('');

		// если Другая страна
		if ($(this).val() == "") {
			// блокируем доставку сдек, делаем активной По договоренности, чекаем чекбокс
			$('.js-type-cdek').prop('disabled', true).prop('selected', false);
			$('.js-type-agreement').prop('selected', true);
			$('.js-another-choice').prop('checked', true);
		} else {
			// возвращаем в дефолт
			$('.js-type-cdek').prop('disabled', false).prop('selected', true);
			$('.js-type-agreement').prop('selected', false);
			$('.js-another-choice').prop('checked', false);
		}
    });

    // обработчик чекбокса Желаете обсудить
    $('.js-another-choice').click(function(){
    	if ($(this).prop('checked')) {
	    	// скрываем и очищаем выпадашку вариантов доставки, блока цены доставки, очищаем инпут города
			$('.js-city-result').hide().html('');
			$('.js-delivery-block, .js-variant-block').hide();
			$('.js-delivery-price, js-whole-price, js-variant-lines').html('');

			// блокируем доставку сдек, делаем активной По договоренности
			$('.js-type-cdek').prop('disabled', true).prop('selected', false);
			$('.js-type-agreement').prop('selected', true);

			$('input[name=delivery_variant]').prop('checked', false);
    	} else {
			// возвращаем в дефолт
			$('.js-type-cdek').prop('disabled', false).prop('selected', true);
			$('.js-type-agreement').prop('selected', false);
			$('.js-city-search').val('');
    	}
    });

    // ремарка для населенного пункта
    $('.js-remark-sign').click(function(){
    	$(this).next().show();
    });
    // закрыть ремарку
    $('.js-remark-cross').click(function(){
    	$('.js-remark-text-block').hide();
    });
    // прячем ремарку при нажатии вне
    $(document).click(function(e) {
        if (!($('.js-remark-block').has(e.target).length)) {
            $('.js-remark-text-block').hide();
        }

        e.stopPropagation();
    });

    // обработка типов доставки
    $('.js-delivery-type').on('change', function(){
    	if ($(this).find('.js-type-cdek').prop('selected')) {
			// снимаеи чек чекбокса
			$('.js-another-choice').prop('checked', false);

			// очищаем инпут города
			$('.js-city-search').val('');
    	}
    	if ($(this).find('.js-type-agreement').prop('selected')) {
	    	// скрываем и очищаем выпадашку вариантов доставки, блока цены доставки, очищаем инпут города
			$('.js-city-result').hide().html('');
			$('.js-delivery-block, .js-variant-block').hide();
			$('.js-delivery-price, js-whole-price, js-variant-lines').html('');

			// чекаем чекбокс
			$('.js-another-choice').prop('checked', true);
    	}
    });

    $('.js-variant-lines').delegate('.js-choice-pv', 'click', function(){

    	if ($('#js-cdek-pv-map').html() == "") {
			// берем токен
			var token = $('input[name=_token]').val();
			// берем код города
			var city_code = $(this).data('cityCode');
			// берем широту и долготу
			var latitude = $('.js-city-search').data('latitude');
			var longitude = $('.js-city-search').data('longitude');
			// берем название города
			var city = $('.js-city-search').data('city');

	    	// запрос пунктов выдачи
	        $.ajax({
	            type: 'post',
	            url: "/ajax-cdek-pv",
	            data: {
	                'city_code': city_code,
	                '_token': token,
	            },
	            success: function(data){

	            	// показываем попап карты
	            	$('.js-pv-cdek').show();
	            	// вписывам название города в заголовок
	            	$('.js-city').text(city);

					var center = [latitude, longitude];

					var zoom;

					if (city_code == 44 || city_code == 137) {
						zoom = 10;
					} else {
						zoom = 11;
					}

					// Выводим карту с магазинами
					ymaps.ready(init);

					function init () {
						var myMap = new ymaps.Map("js-cdek-pv-map", {
							center: center,
							zoom: zoom
						}, {
							searchControlProvider: 'yandex#search'
						});

						var GeoObjects = [];

				    	$.each(data, function(i, point){
						    GeoObjects[i] = new ymaps.GeoObject({
						        geometry: {
						        	type: "Point",
						        	coordinates: [point['location']['latitude'], point['location']['longitude']]
						        },
						        properties: {
						            // Содержимое балуна
									balloonContent: '<div class="ymap-point-block"><div class="point-title">'+point['name']+'</div><div class="point-text-line"><strong>Адрес:</strong> '+point['location']['address']+'</div><div class="point-text-line"><strong>Комментарий:</strong> '+point['address_comment']+'</div><div class="point-text-line"><strong>Режим работы:</strong> '+point['work_time']+'</div><div class="point-button-block js-point-button" data-pv-address="'+point['code']+', '+point['location']['city']+', '+point['location']['address']+'"><div class="point-button">Выбрать</div></div></div>',
						            // Всплывающая подсказка
						            hintContent: point['location']['address'],
						        }
						    });
				    	});
					 
						var clusterer = new ymaps.Clusterer({ 
						    hasBalloon: false,
						    hasHint: false,
						    maxZoom: 14,
						    minClusterSize: 3,
						    zoomMargin: 20, 
						});

						clusterer.add(GeoObjects);
						myMap.geoObjects.add(clusterer);
					}

	            	console.log(data[0]);
	            },
	        });
    	} else {
        	// показываем попап карты
        	$('.js-pv-cdek').show();
    	}

    });

    $('#js-cdek-pv-map').delegate('.js-point-button', 'click', function(){
    	// удаляем вписанный ранее адрес
    	$('.js-pv-address').remove();
    	// вписываем выбранный адрес ПВ 
    	$('input[name=delivery_variant]:checked').parents('.js-delivery-variant-line').append('<div class="pv-address js-pv-address"><strong>Адрес ПВ CDEK:</strong> '+$(this).data('pvAddress')+'</div>');
    	$('.js-delivery-pv').val($(this).data('pvAddress'));
    	// скрываем попап карты
    	$('.js-pv-cdek').hide();
    });

    // кнопка Продолжить
    $('.js-order-continue').click(function(){

   		$('.js-continue-error-string').hide().text('');
   		$('.js-choice-pv').css('color', 'inherit');

    	// проверка обязательных инпутов
    	var input_error = false;
    	var error_string = '* ';
    	var input_required = $('.js-input-required');
    	$.each(input_required, function(i, input){
	    	if (!$.trim($(input).val())) {
	    		input_error = true;
	    		$(input).css({'borderColor': '#D81935'});
	    	} else {
	    		$(input).css({'borderColor': '#ccc'});
	    	}
    	});
    	// если есть незаполненные 
    	if (input_error) {
    		// дописываем строку ошибок
    		error_string += "Заполните обязательные поля! ";
    	}

    	if (input_error) {
    		// выводим строку с ошибкой
    		$('.js-continue-error-string').fadeIn().text(error_string);
    		// останавливаем
    		return false;
    	}

    	// если не выбран ли ПВ
    	if ($('input[name=delivery_variant]:checked').data('deliveryMode') == 4 && $('.js-delivery-pv').val() == '') {
    		// выделяем строку выбора ПВ
    		$('.js-choice-pv').css('color', '#D81935');
    		// дописываем строку ошибок
			error_string += "Выберите Пункт выдачи заказа!";
    		// выводим строку с ошибкой
    		$('.js-continue-error-string').fadeIn().text(error_string);
    		// останавливаем
    		return false;
    	}


		console.log('Продолжить');

    });

    $('.js-popup-close-button').click(function(){
    	$('.js-pv-cdek').fadeOut();
    	$('#js-cdek-pv-map').html('');
    	$('.js-delivery-pv').val('');
    });

});
