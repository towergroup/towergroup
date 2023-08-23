// Сортировка
$(document).ready(function () {
	$(document).on('click', '[data-dropdown-id]', function(event) {
        var sort = $(this).attr('data-dropdown-id');
        var bx_ajax_id = $(".news-list").attr('id');
        bx_ajax_id = bx_ajax_id.replace(/comp_/gi, "");
        var block_id = "#comp_"+bx_ajax_id;
        var data = {
            bxajaxid:bx_ajax_id
        };
		switch (sort) {
			case "1" :
				sort = "desc";
				break;
			case "2" :
				sort = "asc";
				break;
			default : sort = "default"
		}

        var params = getUrlParams();
        params["sort"] = sort;

        var url = setUrlParams(params);
        data['sort'] = sort;
        $.ajax({
            type: "GET",
            url: history.replaceState({},"", url),
            data: data,
            timeout: 3000,
            success: function(data) {
                $("#btn_" + bx_ajax_id).remove();
                var elements = $(data).find('.ajax-list-item'),  //  Ищем элементы
                    pagination = $(data).find('.pagination-more');//  Ищем навигацию
				$(block_id).empty();
                $(block_id).append(elements); //  Добавляем посты в конец контейнера
                $('.pagination-ajax').append(pagination); //  добавляем навигацию следом
				revealNew();
            }
        });
    });
});

$(document).on('click', '[data-show-more]', function(){
	var btn = $(this);
	var page = btn.attr('data-next-page');
	var id = btn.attr('data-show-more');
	var bx_ajax_id = btn.attr('data-ajax-id');
	var block_id = "#comp_"+bx_ajax_id;

	var data = {
		bxajaxid:bx_ajax_id
	};
	data['PAGEN_'+id] = page;

	$.ajax({
		type: "GET",
		url: window.location.href,
		data: data,
		timeout: 3000,
		success: function(data) {
			$("#btn_"+bx_ajax_id).remove();
			var elements = $(data).find('.ajax-list-item'),  //  Ищем элементы
				pagination = $(data).find('.pagination-more');//  Ищем навигацию
			$(block_id).append(elements); //  Добавляем посты в конец контейнера
			$('.pagination-ajax').append(pagination); //  добавляем навигацию следом
            revealNew();
		}
	});
});
