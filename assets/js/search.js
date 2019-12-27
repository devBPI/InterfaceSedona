import {SelectionAdder} from './my-selection';

import {DateSlider} from './date-input-search';
new DateSlider(document.querySelector('#rfn-search-date-slider'));

import Pagination from './pagination';
let paginationField = document.querySelector('#pagination-input');
if (paginationField) {
    new Pagination(paginationField);
}

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
        }
    })
    .on('hide.bs.modal', '#modal-list-add', function (e) {
        let adder = new SelectionAdder($(this).get(0));
        adder.cleanContainer();
    })
;
