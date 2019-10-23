import * as util from './notice-availibility.js'
import Slider from 'bootstrap-slider';

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
    .on('show.bs.modal', '#modal-refine-search', function (e) {
        // document.querySelectorAll('.js-slider').forEach(function (input: HTMLInputElement) {
        //     new Slider(this);
        // })
        if ($('#rfn-search-date-slider').data('slider-plugin') != undefined) {
            return;
        }

        var slider = new Slider('#rfn-search-date-slider', {
            handle: 'square'
        });
        $('#rfn-search-date-slider').data('slider-plugin', slider);
    })
;
