$(document).on('click', '[data-show-more]', function () {
    var btn = $(this);
    var page = btn.attr('data-next-page');
    var id = btn.attr('data-show-more');
    var bx_ajax_id = btn.attr('data-ajax-id');
    var block_id = "#comp_" + bx_ajax_id;

    var data = {
        sectionDocs: jQuery('.choices__input option:selected').val(),
        page: page,
        bxajaxid: bx_ajax_id
    };
    data['PAGEN_' + id] = page;

    $.ajax({
        type: "GET",
        url: window.location.href,
        data: data,
        timeout: 3000,
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