/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require('jquery');

require('bootstrap');
require('icheck');
var Slider = require('bootstrap-slider');

import dataToggle from './data-toggle.js';

const routes = require('../../public/js/fos_js_routes.json');

import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';


Routing.setRoutingData(routes);

$('[data-toggle="tooltip"]').tooltip({
    trigger: 'click',
    template: '<div class="tooltip" role="tooltip"><div class="tooltip-inner"></div></div>'
});

$('input').iCheck({
    checkboxClass: 'check check--checkbox',
    radioClass: 'check check--radio',
    focusClass: 'focus'
});



// Gestion navigation focus - Menu principal ---------------------------------------------------------
$('.dropdown-link .nav-link').on('focus', function() {
    $('.dropdown-menu').removeClass('show');
    $(this).siblings('.dropdown-menu').addClass('show');
});


$(document).on('click', '.js-print-action', function () {
    let permalinkAuthority = $('.js-authority:checked');
    let permalinkNotice = $('.js-notice:checked');


    $('.js-print-authorities').val(permalinkAuthority.serialize());
    $('.js-print-notices').val(permalinkNotice.serialize())

});

$(document).on('click', '.js-export-form', function(e){
    let permalinkAuthority = $('.js-authority:checked');
    let permalinkNotice = $('.js-notice:checked');
    $('.js-print-authorities').val(permalinkAuthority.serialize());
    $('.js-print-notices').val(permalinkNotice.serialize());

});


/**
 *
 */
$(document).on('click', '.js-5-indices-around', function (event) {
    let $this = $(this);
    let url = $this.data('url');
    /**
     * send the form
     */
    $.ajax({
        url: url,
        type:"POST",
        beforeSend: function() {
            // put a spinner
        },
        success: function(response) {
            $('#around-index-wrapper').html(response.html);
        },
        error: function (response) {
                // put an error message here
            }
        });
});

/**
 *
 * @param element
 */
let copyToClipboard = function (element) {
    let $input = $("<input>");
    $input
        .css(
            {
                'position': 'fixed',
                'top':'0',
                'left':'0',
                'width':'2em',
                'height':'2em',
                'padding':'0',
                'border':'none',
                'outline':'none',
                'boxShadow':'none',
                'background':'transparent',
            }
        );

    $('body').append($input);
    $input.val(element).select();

    document.execCommand("copy");

    $input.remove();
};

$(document).on('click', '.js-copy_to_clipboard', function (e) {
    let url =  $('.js-url-to-copy').val();
    copyToClipboard(url);
}).css( 'cursor', 'pointer' );





var seconds = 10;
/*
let bSecondPassed = function () {
    var minutes = Math.round((seconds - 30)/60);

    var remainingSeconds = seconds % 60;
    if (remainingSeconds < 10) {
        remainingSeconds = "0" + remainingSeconds;
    }
    document.getElementById('countdown').innerHTML = minutes + ":" + remainingSeconds;
    if (seconds === 0) {
        window.location.href = document.getElementById('countdown').href;
    } else {
        seconds--;
    }
}
*/
let secondPassed = function(){
    console.log(seconds);
    $('#countdown').html(seconds);
    if (seconds <= 0){
        window.location.href = $('#countdown').attr('href');
    }
    seconds--;
}

window.setInterval(
    function(){secondPassed()}
    ,1000
);


