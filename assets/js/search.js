import * as util from './notice-availibility.js'
// var Slider = require('bootstrap-slider');

import {SelectionAdder} from './my-selection';

$(document)
// Bouton "Voir plus" / "Voir moins" -----------------------------------------------------------------------------------
    .on('click', '.btn-see-more', function() {
        var $this = $(this);
        if ( $this.hasClass('btn-see-more--more') ) {
            $this
                .text('Voir moins')
                .removeClass('btn-see-more--more')
                .addClass('btn-see-more--less');
        } else {
            $this
                .text('Voir plus')
                .removeClass('btn-see-more--less')
                .addClass('btn-see-more--more');
        }
    })
    .on('click', '.js-btn', function() {
        var $this = $(this),
            $items = $this.parent().nextAll();

        if ( $this.hasClass('js-btn--more') ) {
            $items.removeClass('d-none');
            $this
                .text('Voir moins')
                .removeClass('js-btn--more')
                .addClass('js-btn--less');
        } else {
            $items.addClass('d-none');
            $this
                .text('Voir plus')
                .removeClass('js-btn--less')
                .addClass('js-btn--more');
        }
    })
    .on('change','.js-pagination-select', function (e) {
        let $this = $(this);
        let page = $this.val();
        window.location = $this.data('url').replace(-1, page);


    })
    .on('click', '.js-delete-filter',  function(e){
        e.preventDefault();
        let $this = $(this);
        $('.input-'+$this.data('name')).prop('checked', false);
    })
    .on('show.bs.modal', '#modal-list-add', function (e) {
        let adder = new SelectionAdder($(this).get(0));

        let items = $('#contenu-site').find('input:checked.addableInList');
        if (items.length > 0) {
            adder.displaySelectedResume(Object.entries(items));

            if (user_connected !== "1") {
                adder.addInSession(this.querySelector('form'));
            }
        }
    })
    // .on('show.bs.modal', '#modal-refine-search', function (e) {
    //     var sliderDate = new Slider('#rfn-date-slider', {
    //         min: 1900,
    //         max: 2010,
    //         step: 5,
    //         value: [1945,1980],
    //         handle: 'square'
    //     });
    // })
;
