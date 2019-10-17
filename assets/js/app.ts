/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');


// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
require('jquery');
require('bootstrap');
require('icheck');
require('./data-toggle.js');

const routes = require('../../assets/js/fos_js_routes.json');

import Routing from '../../assets/js/jsrouting.min.js';


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


$(document)
// Gestion navigation focus - Menu principal ---------------------------------------------------------
    .on('focus', '.dropdown-link .nav-link', function() {
        $('.dropdown-menu').removeClass('show');
        $(this).siblings('.dropdown-menu').addClass('show');
    })
    .on('click', '.js-print-action', function () {
        let permalinkAuthority = $('.js-authority:checked');
        let permalinkNotice = $('.js-notice:checked');

        $('.js-print-authorities').val(permalinkAuthority.serialize());
        $('.js-print-notices').val(permalinkNotice.serialize())
    })
    .on('click', '.js-export-form', function(e){
        let permalinkAuthority = $('.js-authority:checked');
        let permalinkNotice = $('.js-notice:checked');
        $('.js-print-authorities').val(permalinkAuthority.serialize());
        $('.js-print-notices').val(permalinkNotice.serialize());

    })
    .on('click', '.js-5-indices-around', function (event) {
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
    })
    // Affichage champs date - Modal recherche avancée ---------------------------------------------------------
    .on('change', '[name="adv-search-date"]', function() {
        console.log('change date', $(this).val());
        var $input_period = $('.search-date__date--second');

        if ( $(this).val() === 'Periode' ) {
            $input_period.removeClass('d-none');
        } else {
            $input_period.addClass('d-none');
        }
    })
    .on('click', '.js-copy_to_clipboard', function (e) {
        let url =  $('.js-url-to-copy').val();
        copyToClipboard(url);
    })
;

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

import SelectList from './select-list';
new SelectList(document.querySelector('#adv-search-langage'));

