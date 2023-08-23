$(function(){
    $("input[name='form_text_4']").attr("placeholder", "Имя");
    $("input[name='form_text_5']").attr("placeholder", "Телефон");
    $("input[name='form_email_6']").attr("placeholder", "Email");
    $("textarea[name='form_textarea_7']").attr("placeholder", "Сообщение");

    function formFeedbackCheck() {
        if($("form[name='FEEDBACK'] .form-unlock").length >= 4) {
            $('#button-form-feedback').prop("disabled", false);
        } else {
            $('#button-form-feedback').prop("disabled", true);
        }
    };

    $("form[name='FEEDBACK']").on("submit", function(){
        $("form[name='FEEDBACK']").slideUp(400);
        modal.open('success');
        //$("#success").show();
    });

    $(document).mouseup(function (e){
        var div = $(".modal--opened");
        if (!div.is(e.target) && div.has(e.target).length === 0) {
            modal.close('success');
            //$("#success").hide();
            $(this).find('form').show();
        }
    });

    $(document).on('blur', '.field input', function() {
        var id = $(this).attr('name');
        var val = $(this).val();

        switch(id) {
            case 'form_text_4':
                var rv_name = /^[a-zA-Zа-яА-Я _]+$/;

                if (val.length > 2 && val != '' && rv_name.test(val)) {
                    $(this).parent('.field').addClass('form-unlock').removeClass('input--error');
                    $(this).next(".field-error-message").remove();
                } else {
                    $(this).parent('.field').removeClass('form-unlock').addClass('input--error');
                    if (!$(this).next('.field-error-message').length) {
                        $(this).after('<span class="field-error-message">Ошибка в заполнении поля "Имя"</span>');
                    }
                }
                break;

            case 'form_text_5':
                var rv_phone = /^[0-9()\-+ ]+$/;
                if (val != '' && rv_phone.test(val) ) {
                    $(this).parent('.field').addClass('form-unlock').removeClass('input--error');
                    $(this).next(".field-error-message").remove();
                } else {
                    $(this).parent('.field').removeClass('form-unlock').addClass('input--error');
                    if (!$(this).next('.field-error-message').length) {
                        $(this).after('<span class="field-error-message">Ошибка в заполнении поля "Телефон"</span>');
                    }
                }
                break;
                
            case 'form_email_6':
                var rv_email = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
                if(rv_email.test(val)) {
                    $(this).parent('.field').addClass('form-unlock').removeClass('input--error');
                    $(this).next(".field-error-message").remove();
                } else if (val == '') {
                    $(this).parent('.field').addClass('form-unlock').removeClass('input--error');
                    $(this).next(".field-error-message").remove();
                } else {
                    $(this).parent('.field').removeClass('form-unlock').addClass('input--error');
                    if (!$(this).next('.field-error-message').length) {
                        $(this).after('<span class="field-error-message">Ошибка в заполнении поля "Email"</span>');
                    }
                }
                break;
        }

        formFeedbackCheck();
    });

    $("input[name='form_checkbox_POLITICS[]']").change(function() {
        if($(this).prop('checked')) {
            $(this).closest('.form-item').addClass('form-unlock').removeClass('input--error');
        } else {
            $(this).closest('.form-item').removeClass('form-unlock').addClass('input--error');
        }
        formFeedbackCheck();
    });

});