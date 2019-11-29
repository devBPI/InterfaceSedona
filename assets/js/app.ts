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
import './polyfill-ie';

const routes = require('../../assets/js/fos_js_routes.json');

import Routing from '../../assets/js/jsrouting.min.js';

import {SearchForm, CopyKeyword} from './search-form';
document.querySelectorAll('form').forEach((form: HTMLFormElement) => {
    new SearchForm(form);
})
let copyKeyword = new CopyKeyword();

import SelectList from './select-list';
let select2Element = document.querySelector('#adv-search-langage') as HTMLSelectElement;
if (select2Element) {
    new SelectList(select2Element);
}

import {DatePeriod} from './date-input-search';
new DatePeriod(document.querySelector('.search-date__date--second'));


Routing.setRoutingData(routes);

$('[data-toggle="tooltip"]').tooltip({
    trigger: 'hover focus',
    template: '<div class="tooltip" role="tooltip"><div class="tooltip-inner"></div></div>'
});

$('input').iCheck({
    checkboxClass: 'check check--checkbox',
    radioClass: 'check check--radio',
    focusClass: 'focus'
});
let printEngine = function(){
    let permalinkAuthority = $('.js-authority:checked');
    let permalinkNotice = $('.js-notice:checked');
    let permalinkIndice = $('.js-indicecdu:checked');
    let notice = [];
    let authority = [];
    let indice= [];
    permalinkNotice.each(function () {
        if ($(this).data('notice')){
            notice.push($(this).data('notice'));
        }
    });
    permalinkAuthority.each(function () {
        if ($(this).data('authority')){
            authority.push($(this).data('authority'));
        }
    });

    permalinkIndice.each(function () {
        if ($(this).data('indicecdu')){
            indice.push($(this).data('indicecdu'));
        }
    });


    $('.js-print-notices').val(JSON.stringify(notice));
    $('.js-print-authorities').val(JSON.stringify(authority));
    $('.js-print-indices').val(JSON.stringify(indice));
}

$(document)
    .on('focus', '.dropdown-link .nav-link', function() {
        if ($(window).width() > 768) {
            $('.dropdown-menu').removeClass('show');
            $(this).siblings('.dropdown-menu').addClass('show');
        }
    })
    .on('click', '.js-print-action', function () {
        printEngine();
    })
    .on('click', '.js-export-form', function(e){
        printEngine();
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
    .on('click', '.js-copy_to_clipboard', function (e) {
        let url =  $('.js-url-to-copy').val();
        copyToClipboard(url);
    })
    .on('show.bs.modal', '#modal-search-advanced', function (e) {
        copyKeyword.copyKeywordValue();
    })
    .on('click', '.dropdown-toggle--responsive', function() {
        let label = $(this).children('.js-label-aria');

        if($(this).hasClass('dropdown-toggle--close')) {
            label.text('Fermer');
            $(this)
                .removeClass('dropdown-toggle--close')
                .addClass('dropdown-toggle--open');
        } else {
            label.text('Ouvrir');
            $(this)
                .removeClass('dropdown-toggle--open')
                .addClass('dropdown-toggle--close');
        }
    })
;

/**
 *table__input;
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

$('.bibliographic-result__availability').children('button').on(    'click', function(e){
    $(this).text(function(i,old){
        return old=="Voir plus\n" +
        "                " ?  'Voir moins' :"Voir plus\n" +
            "                ";
    });
});
