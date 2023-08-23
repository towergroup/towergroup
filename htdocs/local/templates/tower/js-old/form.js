//Presentation form
function recall_form_presentation() {
    if ($('#presentation-form .form-item--success').length == 2) {

        var file = new FormData(this);
		var filePDF = file.get('pdf');

		var link = document.createElement('a');
		link.setAttribute('href', filePDF);
		link.setAttribute('download', 'presentation.pdf');
		link.click();

        $.ajax({
            type: 'POST',
            url: '/ajax/request_presentation.php',
            data: file,
            dataType: 'json',
            contentType: false,
            processData: false,
        });

		$('#presentation-form').hide(500);
		$('.success-form-presentation').show(500);
		$("#presentation-form")[0].reset();

		setTimeout(function () {
			$('#presentation-form').show(500);
			$("#presentation-form .form-item").removeClass('form-item--validate form-item--error');
			$('.success-form-presentation').hide(500);
		}, 5000);
    }
    event.preventDefault();
    return false;
};

$('#presentation-form').on('submit', recall_form_presentation);

//Callback form
function recall_form_callback() {
    if ($('#backcall-form .form-item--success').length == 3) {

        modal.close('backcall');
        modal.open('success');


        var file = new FormData(this);

        $.ajax({
            type: 'POST',
            url: '/ajax/request_backcall.php',
            data: file,
            dataType: 'json',
            contentType: false,
            processData: false,
        });
    }
    event.preventDefault();
    return false;
};

$('#backcall-form').on('submit', recall_form_callback);

//Contact-with-us form
function recall_form_contact_with_us() {
    if ($('#contacts-us-form .form-item--success').length == 3) {

        modal.close('contact-with-us');
        modal.open('success');


        var file = new FormData(this);

        $.ajax({
            type: 'POST',
            url: '/ajax/request_backcall.php',
            data: file,
            dataType: 'json',
            contentType: false,
            processData: false,
        });
    }
    event.preventDefault();
    return false;
};

$('#contacts-us-form').on('submit', recall_form_contact_with_us);

//Feedback form
function recall_form_feedback() {
    if (($('#feedback-form .form-item--success').length == 3) || ($('#contact-form .form-item--success').length == 4)) {

        modal.close('feedback');
        modal.open('success');


        var file = new FormData(this);

        $.ajax({
            type: 'POST',
            url: '/ajax/request_backcall.php',
            data: file,
            dataType: 'json',
            contentType: false,
            processData: false,
        });
    }
    event.preventDefault();
    return false;
};

$('#feedback-form, #contact-form').on('submit', recall_form_feedback);

//Object new form
function recall_form_object() {
    if ($('#object-new-form .form-item--success').length == 3) {

		modal.close('object-form');
		modal.open('success');


        var file = new FormData(this);

        $.ajax({
            type: 'POST',
            url: '/ajax/request_object.php',
            data: file,
            dataType: 'json',
            contentType: false,
            processData: false,
        });
    }
    event.preventDefault();
    return false;
};

$('#object-new-form').on('submit', recall_form_object);

//Vacancy new form
function recall_form_vacancy() {
    if ($('#vacancy-form .form-item--success').length == 3) {

        modal.close('vacancy');
        modal.open('success');


        var file = new FormData(this);

        $.ajax({
            type: 'POST',
            url: '/ajax/request_vacancy.php',
            data: file,
            dataType: 'json',
            contentType: false,
            processData: false,
        });
    }
    event.preventDefault();
    return false;
};

$('#vacancy-form').on('submit', recall_form_vacancy);

//Review new form
function recall_form_review() {
    if ($('#rating-form .form-item--success').length >= 4) {

        modal.close('review');
        modal.open('success');

        var file = new FormData(this);
        var rating = $('.rating-value-active input').attr('id').replace(/rating-/g, '');
        file.set('rating', rating);

        $.ajax({
            type: 'POST',
            url: '/ajax/request_review.php',
            data: file,
            dataType: 'json',
            contentType: false,
            processData: false,
        });
    }
    event.preventDefault();
    return false;
};

$('#rating-form').on('submit', recall_form_review);

//Broker new form
function recall_form_broker() {
    if ($('#broker-form .form-item--success').length == 3) {

        modal.close('broker');
        modal.open('success');

        var file = new FormData(this);
        var time = $(this).find('[data-dropdown-label]').text();
        file.set('time', time);

        $.ajax({
            type: 'POST',
            url: '/ajax/request_broker.php',
            data: file,
            dataType: 'json',
            contentType: false,
            processData: false,
        });
    }
    event.preventDefault();
    return false;
};

$('#broker-form').on('submit', recall_form_broker);

//Quiz catalog new form
function recall_form_quiz_catalog() {
    if ($('#catalog-quiz-form .form-item--success').length == 3) {

        modal.open('success');

        var file = new FormData(this);
        var quizAnswer = quiz.getAnswers('catalog');
        file.set('quizAnswer', JSON.stringify(quizAnswer));

        $.ajax({
            type: 'POST',
            url: '/ajax/request_quiz.php',
            data: file,
            dataType: 'json',
            contentType: false,
            processData: false,
        });
    }
    event.preventDefault();
    return false;
};

$('#catalog-quiz-form').on('submit', recall_form_quiz_catalog);

//Quiz catalog new form
function recall_form_quiz_request() {
    if ($('#request-quiz-form .form-item--success').length == 3) {

        modal.close('request-quiz');
        modal.open('success');

        var file = new FormData(this);
        var quizAnswer = quiz.getAnswers('request');
        file.set('quizAnswer', JSON.stringify(quizAnswer));

        $.ajax({
            type: 'POST',
            url: '/ajax/request_quiz.php',
            data: file,
            dataType: 'json',
            contentType: false,
            processData: false,
        });
    }
    event.preventDefault();
    return false;
};

$('#request-quiz-form').on('submit', recall_form_quiz_request);

//Reserved new form
function recall_form_reserved() {
    if (($('#reserve-form .form-item--success').length == 3) || ($('#help-form .form-item--success').length == 3)) {

        modal.open('success');

        var file = new FormData(this);

        $.ajax({
            type: 'POST',
            url: '/ajax/request_backcall.php',
            data: file,
            dataType: 'json',
            contentType: false,
            processData: false,
        });
    }
    event.preventDefault();
    return false;
};

$('#reserve-form, #help-form').on('submit', recall_form_reserved);

//Service new form
function recall_form_service() {
    if ($('#service-form .form-item--success').length == 3) {

        modal.open('success');

        var file = new FormData(this);

        $.ajax({
            type: 'POST',
            url: '/ajax/request_service.php',
            data: file,
            dataType: 'json',
            contentType: false,
            processData: false,
        });
    }
    event.preventDefault();
    return false;
};

$('#service-form').on('submit', recall_form_service);