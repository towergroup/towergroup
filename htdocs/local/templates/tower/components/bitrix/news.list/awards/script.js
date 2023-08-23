$('.pagination-awards').click(function(){
	$('#load-more').trigger('click');
	$(this).addClass('button--refresh-load');
	$('.load-more-btn span').toggleClass('loaded');
	if ($('.load-more-btn span').hasClass('loaded')){
		$(this).removeClass('button--refresh-load');
		setTimeout(function(){
			$('.load-more-btn span').text('Скрыть награды');
			$('.icon--refresh').hide();
		}, 0);
	} else {
		$(this).removeClass('button--refresh-load');
		setTimeout(function(){
			$('.load-more-btn span').text('Показать еще');
			$('.icon--refresh').show();
		}, 0);
	}
	return false;
});