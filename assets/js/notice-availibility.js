/**
 *
 * @param event
 * @param selector
 * @returns {boolean}
 */
let validateMailForm = function(mail) {

    // regex utilisé par symfony https://github.com/symfony/validator/blob/master/Constraints/EmailValidator.php
    // /^[a-zA-Z0-9.!#$%&\'*+\\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$/
    return /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(mail);
}


function captchaValid()
{
    $('#captcha-error').hide();
    app.form.captcha = true;
}

function captchaInvalid()
{
    $('#captcha-error').show();
    app.form.captcha = false;
}

function checkCaptcha(event) {

    if(app.form.captcha) {
        // activation du style des erreurs
        var sheet = window.document.styleSheets[0];
        sheet.insertRule('input:required:invalid { border-bottom: 1px solid red!important; }', sheet.cssRules.length);
        sheet.insertRule('input:required:valid { border-bottom: 1px solid #ced4da!important; }', sheet.cssRules.length);
    } else {
        event.preventDefault();
        captchaInvalid();

        return false;
    }

    return true;
}

window.app = (typeof window.app === 'undefined') ? {} : window.app;
app.form   = [];

function captchaReset()
{
    app.form.captcha = false;
    $('#captcha-error').hide();
}

$(document).ready(function () {
    window.captchaValid = captchaValid;
    window.captchaReset = captchaReset;

    captchaReset();
});


/**
 *
 */
$(document)
    .on('click', '.js-notice-available', function (event) {
        event.preventDefault();
        let $this = $(this);
        let form = $this.parent().parent('form');
        let url = form.attr('action');

        let formValue = form.find("input");


        if(!validateMailForm(formValue.val())){
            form.find('.error-message').show();
            return false;
        }

        /*
        if (!checkCaptcha(event)){
            form.find('.error-message').show();

            return false;
        }
        */

        captchaValid();

        $('#modal-notified').modal('hide');

        /**
         * send the form
         */
        $.ajax({
            url: url,
            type:"POST",
            data: form.serialize(),
            beforeSend: function() {
                // put a spinner
            },
            success: function(response) {
                $('.error-message').hide();
            },
            error: function (response) {
                $('.error-message').show();
            }
        });

        //$('#modal-notified').hide();

    })
    .on('click', '.js-envelope', function (event) {
        let url = $(this).data('url');
        $('#form-availability').attr('action', url);
    });
