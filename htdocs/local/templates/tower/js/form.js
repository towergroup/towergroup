//Presentation form
function recall_form_presentation() {
    event.preventDefault();
    var formElement = this;
    setTimeout(function () {
        if ($('#presentation-form .form-item--success').length == 2) {

            var file = new FormData(formElement);
            var filePDF = file.get('pdf');

            var link = document.createElement('a');
            link.setAttribute('href', filePDF);
            link.setAttribute('download', 'presentation.pdf');
            link.click();

            var siteID = file.get('site_id');

            $.ajax({
                type: 'POST',
                url: '/ajax/request_presentation.php',
                data: file,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    if((siteID == 's1') || (siteID == 's2')) {
                        console.log(siteID);
                        console.log(data.number_ym);
                        ym(data.number_ym,'reachGoal','buildings_download_presentation');
                        gtag('event', 'buildings_download_presentation', {'event_category': 'buildings'});
                    } else {
                        ym(data.number_ym,'reachGoal','finish_downloading');
                        gtag('event', 'finish_downloading', {'event_category': 'zayavka'});
                        console.log(data.number_ym);
                    }

                }
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
        return false;
    }, 0)
};

$('#presentation-form').on('submit', recall_form_presentation);

//Presentation form detail
function recall_form_detail_presentation() {
    event.preventDefault();
    var formElement = this;
    setTimeout(function () {
        if ($('#detail-presentation .form-item--success, #detail-presentation-modal .form-item--success').length == 2) {

            modal.close('buklet');
            modal.open('success');

            var file = new FormData(formElement);

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
                success: function(data) {
                    ym(data.number_ym,'reachGoal','finish_downloading');
                    gtag('event', 'finish_downloading', {'event_category': 'zayavka'});
                    console.log(data.number_ym);
                }
            });
        }
        return false;
    }, 0)
};

$('#detail-presentation, #detail-presentation-modal').on('submit', recall_form_detail_presentation);

//Mortgage form
function recall_form_mortgage() {
    event.preventDefault();
    var formElement = this;
    setTimeout(function () {
        if ($('#credit-form .form-item--success').length == 2) {

            modal.open('success');

            var file = new FormData(formElement);

            $.ajax({
                type: 'POST',
                url: '/ajax/request_mortgage.php',
                data: file,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    ym(data.number_ym,'reachGoal','finish_downloading');
                    gtag('event', 'finish_downloading', {'event_category': 'zayavka'});
                    console.log(data.number_ym);
                }
            });
        }
        return false;
    }, 0)
};

$('#credit-form').on('submit', recall_form_mortgage);

//Mortgage form
function recall_form_detail_excursion() {
    event.preventDefault();
    var formElement = this;
    setTimeout(function () {
        if ($('#detail-excursion .form-item--success').length == 1) {

            modal.open('success');

            var file = new FormData(formElement);

            $.ajax({
                type: 'POST',
                url: '/ajax/request_excursion_detail.php',
                data: file,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    ym(data.number_ym,'reachGoal','want_excursion');
                    gtag('event', 'want_excursion', {'event_category': 'zayavka'});
                    console.log(data.number_ym);
                }
            });
        }
        return false;
    }, 0)
};

$('#detail-excursion').on('submit', recall_form_detail_excursion);

//Callback form
function recall_form_callback() {
    event.preventDefault();
    var formElement = this;
    setTimeout(function () {
        if ($('#backcall-form .form-item--success').length == 2) {
            var file = new FormData(formElement);
            var siteID = file.get('site_id');

            var recaptcha = document.getElementById('recaptchaResponse').value;
            file.set('recaptcha_response', recaptcha);

            $.ajax({
                type: 'POST',
                url: '/ajax/request_backcall.php',
                data: file,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    if(data.success != false) {
                        if ((siteID == 's1') || (siteID == 's2')) {
                            //console.log(siteID);
                            //console.log(data.number_ym);
                            ym(data.number_ym, 'reachGoal', 'request_call');
                            gtag('event', 'request_call', {'event_category': 'call'});
                        } else {
                            ym(data.number_ym, 'reachGoal', 'call_order');
                            gtag('event', 'call_order', {'event_category': 'zayavka'});
                        }
                        modal.close('backcall');
                        modal.open('success');
                    }
                },
            });
        }
        return false;
    }, 0)
};

$('#backcall-form').on('submit', recall_form_callback);

//Contact-with-us form
function recall_form_contact_with_us() {
    event.preventDefault();
    var formElement = this;
    setTimeout(function () {
        if ($('#contacts-us-form .form-item--success').length == 2) {
            var file = new FormData(formElement);

            var siteID = file.get('site_id');

            var recaptcha = document.getElementById('recaptchaResponse').value;
            file.set('recaptcha_response', recaptcha);

            $.ajax({
                type: 'POST',
                url: '/ajax/request_backcall.php',
                data: file,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    if(data.success != false) {
                        if (siteID == 's1' || siteID == 's2') {
                            //console.log(siteID);
                            //console.log(data.number_ym);
                            ym(data.number_ym, 'reachGoal', 'main_write');
                            gtag('event', 'main_write', {'event_category': 'main'});
                        }
                        modal.close('contact-with-us');
                        modal.open('success');
                    }

                }
            });
        }
        return false;
    }, 0)
};

$('#contacts-us-form').on('submit', recall_form_contact_with_us);

//Feedback form
function recall_form_feedback() {
    event.preventDefault();
    var formElement = this;
    setTimeout(function () {
        if ($('#feedback-form .form-item--success').length == 2) {
            var file = new FormData(formElement);

            var siteID = file.get('site_id');
            var url = file.get('url');

            var recaptcha = document.getElementById('recaptchaResponse').value;
            file.set('recaptcha_response', recaptcha);

            $.ajax({
                type: 'POST',
                url: '/ajax/request_backcall.php',
                data: file,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    if(data.success != false) {
                        if ((siteID == 's1' && (url == '/' || url == '/spb/')) || (siteID == 's2' && (url == "/" || url == "/spb/"))) {
                            //console.log(siteID);
                            //console.log(data.number_ym);
                            ym(data.number_ym, 'reachGoal', 'home_write_us');
                            gtag('event', 'home_write_ us', {'event_category': 'main'});
                        } else if ((siteID == 's1' && (url == '/about/' || url == '/spb/about/')) || (siteID == 's2' && (url == "/about" || url == "/spb/about/"))) {
                            //console.log(siteID);
                            //console.log(data.number_ym);
                            ym(data.number_ym, 'reachGoal', 'company_write');
                            gtag('event', 'company_write', {'event_category': 'zayavka'});
                        }
                        modal.close('feedback');
                        modal.open('success');
                    }
                }
            });
        }
        return false;
    }, 0)
};

$('#feedback-form').on('submit', recall_form_feedback);


function recall_form_contact() {
    event.preventDefault();
    var formElement = this;
    setTimeout(function () {
        if ($('#contact-form .form-item--success').length == 3) {
            var file = new FormData(formElement);

            var recaptcha = document.getElementById('recaptchaResponse').value;
            file.set('recaptcha_response', recaptcha);

            $.ajax({
                type: 'POST',
                url: '/ajax/request_backcall.php',
                data: file,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    if(data.success != false) {
                        modal.close('feedback');
                        modal.open('success');
                    }
                }
            });
        }
        return false;
    }, 0)
};

$('#contact-form').on('submit', recall_form_contact);

$('.button-object-social').on('click', function(e) {
	var valueButton = $(this).data('value');
	if(valueButton === 'telegram') {
		$('#object-new-form input[name="telegram_button"]').val('true');
		$('#object-new-form input[name="whatsapp_button"]').val('false');
	}
	if(valueButton === 'whatsapp') {
		$('#object-new-form input[name="whatsapp_button"]').val('true');
		$('#object-new-form input[name="telegram_button"]').val('false');
	}
});

//Object new form
function recall_form_object() {
    event.preventDefault();
    var formElement = this;
    setTimeout(function () {
        if ($('#object-new-form .form-item--success').length == 2) {

            modal.close('object-form');
            modal.open('success');

            var file = new FormData(formElement);

            var recaptcha = document.getElementById('recaptchaResponse').value;
            file.set('recaptcha_response', recaptcha);
            
            var siteID = file.get('site_id');
            
            $.ajax({
                type: 'POST',
                url: '/ajax/request_object.php',
                data: file,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#object-new-form input[name="telegram_button"]').val('false');
                    $('#object-new-form input[name="whatsapp_button"]').val('false');
                    if((siteID == 's1') || (siteID == 's2')) {
                        //console.log(siteID);
                        //console.log(data.number_ym);
                        ym(data.number_ym,'reachGoal','buildings_check_price');
                        gtag('event', 'buildings_check_price', {'event_category': 'buildings'});
                    } else {
                        ym(data.number_ym,'reachGoal','price_sms');
                        gtag('event', 'uznat_cenu', {'event_category': 'zayavka'});
                    }
                    setTimeout(() => {
                    	window.open(data.link_redirect, '_blank');
					});
                    //window.location.href = data.link_redirect;
                },
            });
        }
        return false;
    }, 0)
};

$('#object-new-form').on('submit', recall_form_object);

function recall_form_excursion() {
    event.preventDefault();
    var formElement = this;
    setTimeout(function () {
        if ($('#excursion-form .form-item--success').length == 2) {

            modal.close('excursion');
            modal.open('success');
            
            var file = new FormData(formElement);

            var recaptcha = document.getElementById('recaptchaResponse').value;
            file.set('recaptcha_response', recaptcha);

            $.ajax({
                type: 'POST',
                url: '/ajax/request_object.php',
                data: file,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    ym(data.number_ym,'reachGoal','price_sms');
                    gtag('event', 'uznat_cenu', {'event_category': 'zayavka'});
                }
            });
        }
        return false;
    }, 0)
};

$('#excursion-form').on('submit', recall_form_excursion);

//Vacancy new form
function recall_form_vacancy() {
    event.preventDefault();
    var formElement = this;
    setTimeout(function () {
        if ($('#vacancy-form .form-item--success').length == 2) {

            modal.close('vacancy');
            modal.open('success');


            var file = new FormData(formElement);

            $.ajax({
                type: 'POST',
                url: '/ajax/request_vacancy.php',
                data: file,
                dataType: 'json',
                contentType: false,
                processData: false,
            });
        }
        return false;
    }, 0)
};

$('#vacancy-form').on('submit', recall_form_vacancy);

//Review new form
function recall_form_review() {
    event.preventDefault();
    var formElement = this;
    setTimeout(function () {
        if ($('#rating-form .form-item--success').length >= 4) {

            modal.close('review');
            modal.open('success');

            var file = new FormData(formElement);
            var rating = $('.rating-value-active input').attr('id').replace(/rating-/g, '');
            file.set('rating', rating);

            $.ajax({
                type: 'POST',
                url: '/ajax/request_review.php',
                data: file,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    ym(data.number_ym,'reachGoal','want_excursion');
                    gtag('event', 'want_excursion', {'event_category': 'zayavka'});
                }
            });
        }
        return false;
    }, 0)
};

$('#rating-form').on('submit', recall_form_review);

//Broker new form
function recall_form_broker() {
    event.preventDefault();
    var formElement = this;
    setTimeout(function () {
        if ($('#broker-form .form-item--success').length == 2) {

            modal.close('broker');
            modal.open('success');

            var file = new FormData(formElement);
            var time = $(formElement).find('[data-dropdown-label]').text();
            file.set('time', time);

            var siteID = file.get('site_id');
            var url = file.get('url');

            $.ajax({
                type: 'POST',
                url: '/ajax/request_broker.php',
                data: file,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    if((siteID == 's1' && url == '/moskva/about/') || (siteID == 's2' && url == '/sbp/about/')) {
                        //console.log('broker request about');
                        //console.log(siteID);
                        //console.log(data.number_ym);
                        ym(data.number_ym,'reachGoal','company_contact');
                        gtag('event', 'company_contact', {'event_category': 'zayavka'});
                    } else if((siteID == 's1' && url != '/moskva/about/') || (siteID == 's2' && url != '/sbp/about/')){
                        //console.log(data.number_ym);
                        ym(data.number_ym,'reachGoal','order_selection_broker');
                        gtag('event', 'order_selection_broker', {'event_category': 'selection'});
                    } else {
                        //console.log(data.number_ym);
                        ym(data.number_ym,'reachGoal','want_excursion');
                        gtag('event', 'want_excursion', {'event_category': 'zayavka'});
                    }

                },
            });
        }
        return false;
    }, 0)
};

$('#broker-form').on('submit', recall_form_broker);

function recall_form_view() {
    event.preventDefault();
    var formElement = this;
    setTimeout(function () {
        if ($('#viewing-form .form-item--success').length == 2) {

            modal.close('broker');
            modal.open('success');

            var file = new FormData(formElement);
            var time = $(formElement).find('[data-dropdown-label]').text();
            file.set('time', time);

            var siteID = file.get('site_id');
            var url = file.get('url');

            //console.log(url);

            $.ajax({
                type: 'POST',
                url: '/ajax/request_broker.php',
                data: file,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    if(((url.indexOf('novostroyki') + 1) && siteID == 's1') || ((url.indexOf('novostroyki') + 1) && siteID == 's2')) {
                        //console.log('novostroyki');
                        ym(data.number_ym,'reachGoal','buildings_record_view');
                        gtag('event', 'buildings_record_view', {'event_category': 'buildings'});
                    } else if (((url.indexOf('kupit-kvartiru') + 1) && siteID == 's1') || ((url.indexOf('kupit-kvartiru') + 1) && siteID == 's2')){
                        //console.log('kupit-kvartiru');
                        ym(data.number_ym,'reachGoal','secondary_record_view');
                        gtag('event', 'secondary_record_view', {'event_category': 'secondary'});
                    } else if (((url.indexOf('kupit-dom') + 1) && siteID == 's1') || ((url.indexOf('kupit-dom') + 1) && siteID == 's2')){
                        //console.log('kupit-dom');
                        ym(data.number_ym,'reachGoal','country_record_view');
                        gtag('event', 'country_record_view', {'event_category': 'country'});
                    } else if (((url.indexOf('nedvizhimost-za-rubezhom') + 1) && siteID == 's1') || ((url.indexOf('nedvizhimost-za-rubezhom') + 1) && siteID == 's2')){
                        //console.log('nedvizhimost-za-rubezhom');
                        ym(data.number_ym,'reachGoal','foreign_record_view');
                        gtag('event', 'foreign_record_view', {'event_category': 'foreign'});
                    } else {
                        //console.log(data.number_ym);
                        ym(data.number_ym,'reachGoal','want_excursion');
                        gtag('event', 'want_excursion', {'event_category': 'zayavka'});
                    }

                }
            });
        }
        return false;
    }, 0)
};

$('#viewing-form').on('submit', recall_form_view);

//Quiz catalog new form
function recall_form_quiz_catalog() {
    event.preventDefault();
    var formElement = this;
    setTimeout(function () {
        if ($('#catalog-quiz-form .form-item--success').length == 2) {

            modal.open('success');

            var file = new FormData(formElement);
            var quizAnswer = quiz.getAnswers('catalog');
            file.set('quizAnswer', JSON.stringify(quizAnswer));

            var siteID = file.get('site_id');

            $.ajax({
                type: 'POST',
                url: '/ajax/request_quiz.php',
                data: file,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    if((siteID == 's1') || (siteID == 's2')) {
                        //console.log(siteID);
                        //console.log(data.number_ym);
                        ym(data.number_ym,'reachGoal','passing_test');
                        gtag('event', 'passing_test', {'event_category': 'test'});
                    }
                }
            });
        }
        return false;
    }, 0)
};

$('#catalog-quiz-form').on('submit', recall_form_quiz_catalog);

//Quiz catalog new form
function recall_form_quiz_request() {
    event.preventDefault();
    var formElement = this;
    setTimeout(function () {
        if ($('#request-quiz-form .form-item--success').length == 2) {

            modal.close('request-quiz');
            modal.open('success');

            var file = new FormData(formElement);
            var quizAnswer = quiz.getAnswers('request');
            file.set('quizAnswer', JSON.stringify(quizAnswer));

            var siteID = file.get('site_id');

            $.ajax({
                type: 'POST',
                url: '/ajax/request_quiz.php',
                data: file,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    if(siteID == 's1' || siteID == 's2') {
                        //console.log(siteID);
                        //console.log(data.number_ym);
                        ym(data.number_ym,'reachGoal','main_selection_broker');
                        gtag('event', 'main_selection_broker', {'event_category': 'main'});
                    }

                }
            });
        }
        return false;
    }, 0)
};

$('#request-quiz-form').on('submit', recall_form_quiz_request);

//Reserved new form
function recall_form_help() {
    event.preventDefault();
    var formElement = this;
    setTimeout(function () {
        if ($('#help-form .form-item--success').length == 2) {
            var file = new FormData(formElement);

            var siteID = file.get('site_id');

            var recaptcha = document.getElementById('recaptchaResponse').value;
            file.set('recaptcha_response', recaptcha);

            $.ajax({
                type: 'POST',
                url: '/ajax/request_backcall.php',
                data: file,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    if(data.success != false) {
                        if (siteID == 's1' || siteID == 's2') {
                            //console.log(siteID);
                            //console.log(data.number_ym);
                            ym(data.number_ym, 'reachGoal', 'home_need_help');
                            gtag('event', 'home_need_ help', {'event_category': 'main'});
                        }
                        modal.open('success');
                    }
                }
            });
        }
        return false;
    }, 0)
};

$('#help-form').on('submit', recall_form_help);

function recall_form_reserved() {
    event.preventDefault();
    var formElement = this;
    setTimeout(function () {
        if ($('#reserve-form .form-item--success').length == 2) {
            var file = new FormData(formElement);

            var siteID = file.get('site_id');
            var url = file.get('url');

            var recaptcha = document.getElementById('recaptchaResponse').value;
            file.set('recaptcha_response', recaptcha);

            $.ajax({
                type: 'POST',
                url: '/ajax/request_backcall.php',
                data: file,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    if(data.success != false) {
                        if (((url.indexOf('novostroyki') + 1) && siteID == 's1') || ((url.indexOf('novostroyki') + 1) && siteID == 's2')) {
                            //console.log(siteID);
                            //console.log(data.number_ym);
                            ym(data.number_ym, 'reachGoal', 'buildings_details_project');
                            gtag('event', 'buildings_details_project', {'event_category': 'buildings'});
                        } else if (((url.indexOf('kupit-kvartiru') + 1) && siteID == 's1') || ((url.indexOf('kupit-kvartiru') + 1) && siteID == 's2')) {
                            //console.log(siteID);
                            //console.log(data.number_ym);
                            ym(data.number_ym, 'reachGoal', 'secondary_details_project');
                            gtag('event', 'secondary_details_project', {'event_category': 'secondary'});
                        } else if (((url.indexOf('kupit-dom') + 1) && siteID == 's1') || ((url.indexOf('kupit-dom') + 1) && siteID == 's2')) {
                            //console.log(siteID);
                            //console.log(data.number_ym);
                            ym(data.number_ym, 'reachGoal', 'country_details_project');
                            gtag('event', 'country_details_project', {'event_category': 'country'});
                        } else if (((url.indexOf('nedvizhimost-za-rubezhom') + 1) && siteID == 's1') || ((url.indexOf('nedvizhimost-za-rubezhom') + 1) && siteID == 's2')) {
                            //console.log(siteID);
                            //console.log(data.number_ym);
                            ym(data.number_ym, 'reachGoal', 'foreign_details_project');
                            gtag('event', 'foreign_details_project', {'event_category': 'foreign'});
                        } else {
                            //console.log(data.number_ym);
                            ym(data.number_ym, 'reachGoal', 'want_excursion');
                            gtag('event', 'want_excursion', {'event_category': 'zayavka'});
                        }
                        modal.open('success');
                    }
                }
            });
        }
        return false;
    }, 0)
};

$('#reserve-form').on('submit', recall_form_reserved);

//Service new form
function recall_form_service() {
    event.preventDefault();
    var formElement = this;
    setTimeout(function () {
        if ($('#service-form .form-item--success').length == 2) {

            modal.open('success');

            var file = new FormData(formElement);
            var siteID = file.get('site_id');

            $.ajax({
                type: 'POST',
                url: '/ajax/request_service.php',
                data: file,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    //console.log(siteID);
                    //console.log(data.number_ym);
                    ym(data.number_ym,'reachGoal','services_application');
                    gtag('event', 'services_application', {'event_category': 'zayavka'});
                }
            });
        }
        return false;
    }, 0)
};

$('#service-form').on('submit', recall_form_service);

function recall_form_landing_backcall() {
    event.preventDefault();
    var formElement = this;
    setTimeout(function () {
        if ($('#landing-broker-form .form-item--success').length == 2) {

            modal.close('landing-backcall');
            modal.open('success');

            var file = new FormData(formElement);

            $.ajax({
                type: 'POST',
                url: '/ajax/request_broker.php',
                data: file,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    ym(data.number_ym,'reachGoal','want_excursion');
                    gtag('event', 'want_excursion', {'event_category': 'zayavka'});
                }
            });
        }
        return false;
    }, 0)
};

$('#landing-broker-form').on('submit', recall_form_landing_backcall);

/**New form 20.07.2021 for metrics*/

//Callback form secondary flats
function recall_form_callback_secondary() {
    event.preventDefault();
    var formElement = this;
    setTimeout(function () {
        if ($('#backcall-form-secondary .form-item--success').length == 2) {
            var file = new FormData(formElement);
            var siteID = file.get('site_id');
            var url = file.get('url');

            var recaptcha = document.getElementById('recaptchaResponse').value;
            file.set('recaptcha_response', recaptcha);

            $.ajax({
                type: 'POST',
                url: '/ajax/request_backcall.php',
                data: file,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    if(data.success != false) {
                        if (((url.indexOf('kupit-kvartiru') + 1) && siteID == 's1') || ((url.indexOf('kupit-kvartiru') + 1) && siteID == 's2')) {
                            //console.log(siteID);
                            //console.log(data.number_ym);
                            ym(data.number_ym, 'reachGoal', 'secondary_callback');
                            gtag('event', 'secondary_callback', {'event_category': 'secondary'});
                        } else if (((url.indexOf('kupit-dom') + 1) && siteID == 's1') || ((url.indexOf('kupit-dom') + 1) && siteID == 's2')) {
                            //console.log(siteID);
                            //console.log(data.number_ym);
                            ym(data.number_ym, 'reachGoal', 'country_back_call');
                            gtag('event', 'country_back_call', {'event_category': 'country'});
                        } else if (((url.indexOf('nedvizhimost-za-rubezhom') + 1) && siteID == 's1') || ((url.indexOf('nedvizhimost-za-rubezhom') + 1) && siteID == 's2')) {
                            //console.log(siteID);
                            //console.log(data.number_ym);
                            ym(data.number_ym, 'reachGoal', 'foreign_callback');
                            gtag('event', 'foreign_callback', {'event_category': 'foreign'});
                        } else {
                            //console.log(data.number_ym);
                            ym(data.number_ym, 'reachGoal', 'call_order');
                            gtag('event', 'call_order', {'event_category': 'zayavka'});
                        }
                        modal.close('backcall');
                        modal.open('success');
                    }
                }
            });
        }
        return false;
    }, 0)
};

$('#backcall-form-secondary').on('submit', recall_form_callback_secondary);