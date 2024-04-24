$(document).ready(function(){

	console.log('Заказ в 1 клик');

	$('.js-one-click-butt').click(function(e) {

		// запрещаем действие по умолчанию
		e.preventDefault();

		// открываем окно с формой
		$('.js-one-click-order').fadeIn();

		// узнаем имя и id товара
		var item_name = $(this).data('name');
		var item_id = $(this).data('id');

		// вставляем имя товара в заголовок и в input
		$('.js-item-name').text(item_name);
		$('input[name=item_name]').val(item_name);

		// вставляем id товара в input
		$('input[name=item_id]').val(item_id);

	});

	// закрываем окно с формой
	$('.js-popup-close-button, .js-popup-background').click(function(){
		$('.js-one-click-order').fadeOut();
	});

});