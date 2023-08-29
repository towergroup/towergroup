// Сортировка
$(document).ready(function () {
    $(document).on('click', '[data-dropdown-id]', function(event) {
        var parent = $(this).closest('[data-dropdown]');
        var sortType = parent.find('[data-dropdown-label]').attr('data-dropdown-label');
        switch (sortType) {
            case "Выбор брокера" :
                sortType = "broker_id";
                break;
            case "Сортировка по дате" :
                sortType = "active_from";
                break;
        }
        var sort = $(this).attr('data-dropdown-id');
        if (sortType == 'broker_id' && sort == "") sort ="all";
        if (sortType == 'active_from') {
            switch (sort) {
                case "1" :
                    sort = "desc";
                    break;
                case "2" :
                    sort = "asc";
                    break;
                default : sort = "default"
            }
        }
        var params = getUrlParams();
        params[sortType] = sort;

        jQuery.ajax({
            type: "POST",
            data: params,
            dataType: 'html',
            success: function (html) {
                if (html) {
                    jQuery('.reviews-list').remove();
                    jQuery('.pagination-ajax').remove();
                    jQuery('.reviews-control').after($(html));

                    var queryParams = setUrlParams(params);
                    history.replaceState(null, null, queryParams);

                    revealNew();
                    lazyload.update();
                }
            }
        });
    });
});

$(document).on('click', '[data-show-more]', function () {
    var btn = $(this);
    var page = btn.attr('data-next-page');
    var id = btn.attr('data-show-more');
    var bx_ajax_id = btn.attr('data-ajax-id');
    var block_id = "#comp_" + bx_ajax_id;

    var data = {
        sectionDocs: jQuery('.choices__input option:selected').val(),
        page: page,
        showMore: true,
        bxajaxid: bx_ajax_id
    };
    data['PAGEN_' + id] = page;

    $.ajax({
        type: "GET",
        url: window.location.href,
        data: data,
        timeout: 3000,
        beforeSend : function () {
            btn.prop('disabled', true);
        },
        success: function (data) {
            //  Удаляем старую навигацию
            $("#btn_" + bx_ajax_id).remove();

            var elements = $(data).find('.ajax-list-item'),  //  Ищем элементы
                pagination = $(data).find('.pagination-more');//  Ищем навигацию

            $(block_id).append(elements); //  Добавляем посты в конец контейнера
            $('.vacancy').append(pagination); //  добавляем навигацию следом
            revealNew();
        }
    });
});