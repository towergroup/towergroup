$(function(){
    $("input[name='form_text_1']").attr("placeholder", "Имя");
    $("input[name='form_text_2']").attr("placeholder", "Телефон");

    function formNomodalCallbackCheck() {
        if($("form[name='BACKCALL_NOMODAL'] .form-unlock").length >= 3) {
            $('#button-form-callback-1').prop("disabled", false);
        } else {
            $('#button-form-callback-1').prop("disabled", true);
        }
    };

    $("form[name='BACKCALL_NOMODAL']").on("submit", function(){
        $("form[name='BACKCALL_NOMODAL']").slideUp(400);
        modal.open('success');
        //$("#success").show();
    });

    $(document).mouseup(function (e){
        var div = $(".modal--opened");
        if (!div.is(e.target) && div.has(e.target).length === 0) {
            //$("#success").hide();
            modal.close('success');
            $(this).find('form').show();
        }
    });

    $(document).on('blur', '.field input', function() {
        var id = $(this).attr('name');
        var val = $(this).val();

        switch(id) {
            case 'form_text_1':
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

            case 'form_text_2':
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
        }

        formNomodalCallbackCheck();
    });

    $("input[name='form_checkbox_POLITICS[]']").change(function() {
        if($(this).prop('checked')) {
            $(this).closest('.form-item').addClass('form-unlock').removeClass('input--error');
        } else {
            $(this).closest('.form-item').removeClass('form-unlock').addClass('input--error');
        }
        formNomodalCallbackCheck();
    });

});