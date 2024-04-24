$(document).ready(function(){

	var pic_element, head_element;
	// при нажатии на кнопку влево
	$('.js-pic-left').click(function() {
		pic_element = $('.service-page_slider-pic.active');
		head_element = $('.service-page_pic-header-element.active');
		if(pic_element.prev().length != 0) {
			pic_element.prev().addClass('active');
			pic_element.removeClass('active');
			head_element.prev().addClass('active');
			head_element.removeClass('active');
		} else {
			$('.service-page_slider-pic:last-child').addClass('active');
			pic_element.removeClass('active');
			$('.service-page_pic-header-element:last-child').addClass('active');
			head_element.removeClass('active');
		}
	});

	// при нажатии на кнопку вправо
	$('.js-pic-right').click(function() {
		pic_element = $('.service-page_slider-pic.active');
		head_element = $('.service-page_pic-header-element.active');
		if(pic_element.next().length != 0) {
			pic_element.next().addClass('active');
			pic_element.removeClass('active');
			head_element.next().addClass('active');
			head_element.removeClass('active');
		} else {
			$('.service-page_slider-pic:first-child').addClass('active');
			pic_element.removeClass('active');
			$('.service-page_pic-header-element:first-child').addClass('active');
			head_element.removeClass('active');
		}
	});

	console.log('сервис-слайдер');

});