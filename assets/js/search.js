import * as util from './notice-availibility.js'
import Routing from '../../assets/js/jsrouting.min.js';
// var Slider = require('bootstrap-slider');

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
    .on('show.bs.modal', '#modal-list-add,#modal-list-create', function (e) {
        let $inputContainer = $(this).find('#resume-container');
        $inputContainer.html('');

        let objSelected = $('#contenu-site').find('input:checked.addableInList');
        for (let [key, value] of Object.entries(objSelected)) {
            let container = $(value).parents('.list-result__content-item');
            if (container.length == 0) {
                container = $(value).parents('.js-list-result-authority-item');
            }
            if (container.length > 0) {
                let card = container.clone()[0];
                card = card.innerHTML.replace(/__item__/gi, key);
                $inputContainer.append(card);
            }
        }

        if (user_connected !== "1") {
            let datas = $inputContainer.parents('form:first').serializeArray();

            $.ajax({
                method: "POST",
                url: Routing.generate('user_selection_list_add_session'),
                data: datas,
            }).done(function (data) {
                // stop the spinner
            });
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
