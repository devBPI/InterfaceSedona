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

// $('#modal-refine-search').on('show.bs.modal', function (e) {
//     var sliderDate = new Slider('#rfn-date-slider', {
//         min: 1900,
//         max: 2010,
//         step: 5,
//         value: [1945,1980],
//         handle: 'square'
//     });
// })

// Gestion navigation focus - Menu principal ---------------------------------------------------------
$('.dropdown-link .nav-link').on('focus', function() {
    $('.dropdown-menu').removeClass('show');
    $(this).siblings('.dropdown-menu').addClass('show');
});
