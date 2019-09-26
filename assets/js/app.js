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

            // see the annotated config in the README for details on how everything works
            window.orejimeConfig = {
                appElement: "#app",
                privacyPolicy: "http://www.bpi.fr/home/gestion/informations-sur-les-cookies.html",
                lang:"fr",
                translations: {
                    fr: {
                        consentModal: {
                            description: "En poursuivant votre navigation sur le site sans modifier vos paramètres, vous acceptez l'utilisation des cookies pour vous proposer des contenus et services adaptés à vos centres d’intérêt",
                        }
                    },
                    en: {
                        consentModal: {
                            description: "En poursuivant votre navigation sur le site sans modifier vos paramètres, vous acceptez l'utilisation des cookies pour vous proposer des contenus et services adaptés à vos centres d’intérêt",
                        },
                        "inline-tracker": {
                            description: "Example of an inline tracking script that sets a dummy cookie",
                        },
                        "external-tracker": {
                            description: "Example of an external tracking script that sets a dummy cookie",
                        },
                        "always-on": {
                            description: "this example app will not set any cookie",
                        },
                        "disabled-by-default": {
                            description: "this example app will not set any cookie",
                        },
                        purposes: {
                            analytics: "Analytics",
                            security: "Security",
                            ads: "Ads"
                        }
                    },
                    es: {
                        consentModal: {
                            description: "En poursuivant votre navigation sur le site sans modifier vos paramètres, vous acceptez l'utilisation des cookies pour vous proposer des contenus et services adaptés à vos centres d’intérêt",
                        },
                        purposes: {
                            analytics: "Analytics",
                            security: "Security",
                            ads: "Ads"
                        }
                    },


                },
                apps: [
                    {
                        name: "inline-tracker",
                        title: "Inline Tracker",
                        purposes: ["analytics"],
                        cookies: ["inline-tracker"],
                        onlyOnce: true,
                    }
                ],
            }

// since there is a orejimeConfig global variable in index.js, a window.orejime instance was created when including the lib
document.querySelector('.consent-modal-button').addEventListener('click', function() {
    orejime.show();
}, false);
