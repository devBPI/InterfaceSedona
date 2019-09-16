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

import dataToggle from './dataToggle.js';

const routes = require('../../public/js/fos_js_routes.json');

import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

$('[data-toggle="tooltip"]').tooltip({
    trigger: 'click',
    template: '<div class="tooltip" role="tooltip"><div class="tooltip-inner"></div></div>'
});


// Ajout boutton "Voir plus" suivant nombres d'informations - Notice Bibliographique ---------------------------------------------------------
$('.js-list-information ul.list-information__sub-list').each( function() {
    var $list = $(this),
        $children = $list.children(),
        $children_length = $children.length;

    if ( $children_length > 4 ) {
        $children.eq(4).nextAll().addClass('d-none');
        $children.eq(4).after('<li><button type="button" class="btn btn-small-link js-btn js-btn--more">Voir tout</button></li>');
    }
});

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
	.on('click', '.search-autocomplet__item-content', function (e) {
		$('#search-input').val($(this).html());
		$('#autocompletion-list').hide();
	})
	.on('show.bs.modal', '#modal-list-add,#modal-list-create', function (e) {
		let $inputContainer = $(this).find('#resume-container');
        $inputContainer.html('');
        let $invoker = $(e.relatedTarget);
		if ($invoker.data('context') == 'selection') {
            $inputContainer.parent('.modal-body').hide();
            for (let doc of $('[name="selection[document][]"]:checked')) {
                let card = $(doc).clone();
                $inputContainer.append(card);
			}
		} else {
            let objSelected = $('#contenu-site').find('input:checked.addableInList');
            for (let [key, value] of Object.entries(objSelected)) {
                let container = $(value).parents('.list-result__content-item');
                if (container.length > 0) {
                    let card = container.clone()[0];
                    card = card.innerHTML.replace(/__item__/gi, key);
                    $inputContainer.append(card);
                }
            }
		}
	})
;
