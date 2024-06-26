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
require('./data-toggle.js');
require('./orejime.js');
require('./data-toggle-refresh.js')
import './polyfill-ie';


// =================================
const routes = require('../../assets/js/fos_js_routes.json');
import Routing from '../../assets/js/jsrouting.min.js';
Routing.setRoutingData(routes);

// =================================
import {SearchForm, CopyKeyword} from './search-form';
document.querySelectorAll('form').forEach((form: HTMLFormElement) => {
    new SearchForm(form);
});
let copyKeyword = new CopyKeyword();

// =================================
import SelectList from './select-list';
let select2Element = document.querySelector('#adv-search-langage') as HTMLSelectElement;
if (select2Element) {
    new SelectList(select2Element);
}

// =================================
import {DatePeriod} from './date-input-search';
new DatePeriod(document.querySelectorAll('[name="adv-search-date"]'));

// =================================
import {Printer} from './printer';
new Printer();

// =================================
$('[data-toggle="tooltip"]').tooltip({
    trigger: 'hover focus',
    template: '<div class="tooltip" role="tooltip"><div class="tooltip-inner"></div></div>'
});

$(document)

    .on('focus', '.nav-link', function() {
        if ($(window).width() > 768) {
            $('.dropdown-link .nav-link').removeClass('active');
            $('.dropdown-menu').removeClass('show');
        }
    })
    .on('focus', '.dropdown-link .nav-link', function() {
        if ($(window).width() > 768) {
            $('.dropdown-link .nav-link')
                .removeClass('active')
                .attr('aria-expanded', 'false');
            $('.dropdown-menu').removeClass('show');
            $(this).addClass('active');
            $(this)
                .siblings('.dropdown-menu')
                .addClass('show');
            $(this).attr('aria-expanded', 'true');
        }
    })
    .on('focus', '.dropdown-item__sub, .dropdown-item__title', function() {
        if ($(window).width() > 768) {
            $('.dropdown-link .nav-link')
                .removeClass('active')
                .attr('aria-expanded', 'false');
            $('.dropdown-menu').removeClass('show');
            $(this)
                .parents('.dropdown-menu')
                .siblings('.nav-link')
                .addClass('active')
                .attr('aria-expanded', 'true');
            $(this)
                .parents('.dropdown-menu')
                .addClass('show');
        }
    })
    .on('focusout', '.dropdown-menu [class^="col-"]:last-child .dropdown-item:last-child, .navbar--help .dropdown-menu .dropdown-item:last-child, .navbar--help', function () {
        if ($(window).width() > 768) {
            $('.dropdown-link .nav-link')
                .removeClass('active')
                .attr('aria-expanded', 'false');
            $('.dropdown-menu').removeClass('show');
        }
    })
    .on('mouseenter', '.dropdown-link .nav-link', function() {
        $('.search-banner__select select').blur();
        $('.dropdown-link .nav-link')
            .removeClass('active')
            .attr('aria-expanded', 'false');
        $(this).attr('aria-expanded', 'true');
    })
    .on('mouseleave', '.navbar-nav', function() {
        $('.dropdown-link .nav-link')
            .removeClass('active')
            .attr('aria-expanded', 'false');
    })
    .on('click', '.js-5-indices-around', function (event) {
        let url = $(this).data('url');

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
    .on('show.bs.modal', '#modal-search-advanced', function (e) {
        copyKeyword.copyKeywordValue();
    })
    .on('focus', '.modal.show .close', function() {

        $(this).keydown(function(e) {
            var keyCode = (window.event) ? e.which : e.keyCode;

            if(e.shiftKey && keyCode === 9) {
                e.preventDefault();
                $('.modal.show .modal-footer *:last-child').focus();
            }
        })
    })
;
if ($(window).width() > 992) {
    let menuLangueList = $('.js-menu-langue-list'),
        menuLangueItems = menuLangueList.children(),
        menuSecondaireList = $('.js-menu-secondaire-list'),
        menuSecondaireItems = menuSecondaireList.children();

    menuLangueList.removeAttr('aria-labelledby role tabindex');
    menuLangueItems.removeAttr('role');
    menuSecondaireList.removeAttr('aria-labelledby role tabindex');
    menuSecondaireItems.removeAttr('role');
}

$('.js-seeMoreAvailability').children('button').on( 'click', function(e){
    $(this).text(function(i, old) {
        return old.includes("plus") ? 'Voir moins' : "Voir plus"
    });
});
