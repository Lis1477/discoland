$(document).ready(function(){

    console.log('товарные фильтры');

    // берем значения фильтра
    var filter_data = $('.js-filter-form').data('filters');

    // берем и устанавливаем значения для ценового диапазона
    var min_price = Number($('.js-min-max').data('minPrice'));
    var max_price = Number($('.js-min-max').data('maxPrice'));
    var val_min_price, val_max_price;
    if (filter_data) {
        val_min_price = Number(filter_data['price']['price_from']);
        val_max_price = Number(filter_data['price']['price_to']);
    } else {
        val_min_price = min_price;
        val_max_price = max_price;
    }

    // берем значения категорий, чекаем, если в фильтре
    if (filter_data['category']) {
        // собираем инпуты категорий
        var category_inputs = $('.js-filter-category');
        // чекаем
        category_inputs.each(function(){
            if ($.inArray($(this).val(), filter_data['category']) != -1) {
                $(this).prop('checked', true);
            }
        });
    }

    // берем значения стилей, чекаем, если в фильтре
    if (filter_data['styles']) {
        // собираем инпуты стилей
        var category_inputs = $('.js-filter-style');
        // чекаем
        category_inputs.each(function(){
            if ($.inArray($(this).val(), filter_data['styles']) != -1) {
                $(this).prop('checked', true);
            }
        });
    }
console.log(filter_data);
    // // берем значения брендов, чекаем, если в фильтре
    // if (filter_data['brand']) {
    //     // собираем инпуты брендов
    //     var brand_inputs = $('.js-filter-brand');
    //     // чекаем
    //     brand_inputs.each(function(){
    //         if ($.inArray($(this).val(), filter_data['brand']) != -1) {
    //             $(this).prop('checked', true);
    //         }
    //     });
    // }

    // обрабатываем ценовой диапазон
    $("#slider-range").slider({
        range: true,
        min: min_price,
        max: max_price,
        values: [val_min_price, val_max_price],
        step: 1,
        slide: function(event, ui) {
            $("#price_from").val(number_format(ui.values[0]));
            $("#price_to").val(number_format(ui.values[1]));
        }
    });

    $("#price_from").val(number_format($("#slider-range").slider("values", 0)));
    $("#price_to").val(number_format($("#slider-range").slider("values", 1)));

    // ручной ввод цен ************************************************
    $('#price_from, #price_to').bind('blur', function(){

        // берем введенное значение; меняем запятую на точку, если есть; преобразуем в число
        var entered_price = parseFloat($(this).val().replace(/,/, '.'), 10);

        // определяем значения для левого и правого инпута
        var left_price, right_price;
        if($(this).attr('id') == 'price_from') {

            // значение левого инпута
            if(entered_price < min_price || !entered_price) {
                left_price = min_price;
            } else if(entered_price > max_price) {
                left_price = max_price;
            } else {
                left_price = entered_price;
            }
            
            // берем значение правого инпута
            right_price = Number($('#price_to').val());

            // если меньше левого, уравниваем
            if(right_price < left_price) {
                right_price = left_price;
            }

        } else if($(this).attr('id') == 'price_to') {

            // значение правого инпута
            if(entered_price > max_price || !entered_price) {
                right_price = max_price;
            } else if(entered_price < min_price) {
                right_price = min_price;
            } else {
                right_price = entered_price;
            }
            
            // берем значение правого инпута
            left_price = Number($('#price_from').val());

            // если больше правого, уравниваем
            if(left_price > right_price) {
                left_price = right_price;
            }

        }

        // меняем положение ползунка
        $("#slider-range").slider({
            values: [left_price, right_price],
        });

        // вписываем новые значения в инпуты
        $('#price_from').val(number_format($("#slider-range").slider("values", 0)));
        $('#price_to').val(number_format($("#slider-range").slider("values", 1)));

    });


    // показываем-прячем скрытые блоки
    $('.js-view-all').click(function(){

        $(this).parents('.js-filter-block').find('.js-hidden-block').slideToggle();
        $(this).toggleClass('opened');

        if ($(this).hasClass('opened')) {
            $(this).find('.js-filter-str').text('Скрыть');
        } else {
            $(this).find('.js-filter-str').text('Показать все');
        }

    });


    // показываем количество товаров при использовании фильтра ********

    // функция подсчета количества фильтруемых товаров
    function getFiltredCount() {
        // форма
        var form = $('.js-filter-form');
        // собираем данные из формы
        var form_data = form.serializeArray();
        // токен
        var token = $('meta[name=csrf-token]').attr('content');
        form_data.push({name: '_token', value: token});

        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: form_data,
            success: function(result) {

console.log(result);
                // вставляем количество в кнопку фильтра
                $('.js-filtred-items-count').text('(' + result + ')');
            }
        });
    }

    // функция замены урла кнопки фильтров
    function postFilterUrl() {

        // собираем данные из формы
        var form_data = $('.js-filter-form').serialize();
        // берем значение ссылки
        var url = $('.js-filter-submit-button').attr('href');

        // определяем, есть ли параметры фильтров
        var filter_exist = url.indexOf('filters');

        if (filter_exist !== -1) { // если есть
            // очищаем урл от параметров фильтра
            url = url.substring(0, filter_exist);
        } else { // если нет
            // определяем разделитель
            var separator = url.indexOf('?') !== -1 ? "&" : "?";
            // добавляем разделитель к параметрам формы
            form_data = separator + form_data;
        }

        // добавляем параметры фильтра и меняем ссылку
        $('.js-filter-submit-button').attr('href', url + form_data);

    }

    // чекаем категории, жанры
    $('.js-filter-category, .js-filter-style').bind('click', function(){
        getFiltredCount();
        postFilterUrl();
    });

    // ценовой диапазон
    $('#price_from, #price_to').bind('blur', function(){
        getFiltredCount();
        postFilterUrl();
    });
    $('.ui-slider-handle').bind('mouseup mouseout', function(){
        getFiltredCount();
        postFilterUrl();
    });
    $('#slider-range').bind('click', function(){
        getFiltredCount();
        postFilterUrl();
    });


    // очистка фильтров
    $('.js-clear-filters').click(function(){

        // устанавливаем ценовой фильтр в дефолт **************************
        $("#slider-range").slider({
            range: true,
            min: min_price,
            max: max_price,
            values: [min_price, max_price],
            step: 1,
            slide: function(event, ui) {
                $("#price_from").val(number_format(ui.values[0]));
                $("#price_to").val(number_format(ui.values[1]));
            }
        });
        $("#price_from").val(number_format($("#slider-range").slider("values", 0)));
        $("#price_to").val(number_format($("#slider-range").slider("values", 1)));
        // ****************************************************************

        // убираем чек со всех чекбоксов
        $('.js-filter-form').find('input[type=checkbox]').attr('checked', false);

        getFiltredCount();
        postFilterUrl();
    });



});