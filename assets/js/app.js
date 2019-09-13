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
require('slick-carousel');

const routes = require('../../public/js/fos_js_routes.json');

import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

$('[data-toggle="tooltip"]').tooltip({
	trigger: 'click',
	template: '<div class="tooltip" role="tooltip"><div class="tooltip-inner"></div></div>'
});

// Configuration Carousel Primary (Accueil + Parcours) ----------------------------------------------------------------
$('.js-carousel-primary')
	.slick({
		dots: true,
		infinite: true,
		slidesToShow: 4,
		slidesToScroll: 4,
		autoplay: true,
		autoplaySpeed: 5000,
		prevArrow: '<button class="slick-prev" aria-label="Actualité précédente" type="button">Précédent</button>',
		nextArrow: '<button class="slick-next" aria-label="Actualité suivante" type="button">Suivant</button>',
		dotsClass: 'carousel__pagination',
		customPaging: function (slider, i) {
			var slideNumber = (i + 1),
				totalSlides = slider.slideCount;
			return '<a class="carousel__pagination-dot" href="#" type="button" role="tab"><span class="sr-only">' + slideNumber + ' page sur ' + totalSlides + '</span></a>';
		},
		responsive: [
			{
				breakpoint: 992,
				settings: 	{
					slidesToShow: 2,
					slidesToScroll: 2
				}
			},
			{
				breakpoint: 576,
				settings: 	{
					slidesToShow: 1,
					slidesToScroll: 1
				}
			}
		]
	})
	.on('afterChange', function(slick, currentSlide){
		// Correctif suivant retours RGAA
		$('.carousel__pagination li').attr("aria-selected", "false");
		$('.carousel__pagination li.slick-active').attr("aria-selected", "true");
	})
;

// Gestion Bouton "Pause/Play" - Carousel Primary ---------------------------------------------------------------------
$('.carousel__button').on('click', function() {
	var $this = $(this);
	if( $this.hasClass('carousel__button--pause') ) {
		$('.js-carousel-primary').slick('slickPause');
		$this
			.attr('aria-label', 'Mettre en lecture le carousel')
			.removeClass('carousel__button--pause')
			.addClass('carousel__button--play');
	} else {
		$('.js-carousel-primary').slick('slickPlay');
		$this
			.attr('aria-label', 'Mettre en pause le carousel')
			.removeClass('carousel__button--play')
			.addClass('carousel__button--pause');
	}
});

// Correctif suivant retours RGAA
$(".js-carousel-primary .slick-slide").removeAttr("role");
$(".carousel__pagination").attr("aria-label", "Choix d'un groupe d'actualités à afficher");

// Configuration Carousel Secondary (Notices) --------------------------------------------------------------------------
$('.js-carousel-secondary').slick({
	infinite: false,
	slidesToShow: 4,
	slidesToScroll: 1,
	autoplay: false,
	prevArrow: '<button class="slick-prev" aria-label="Actualité précédente" type="button">Précédent</button>',
	nextArrow: '<button class="slick-next" aria-label="Actualité suivante" type="button">Suivant</button>',
	responsive: [
		{
			breakpoint: 992,
			settings: 	{
				slidesToShow: 2,
				slidesToScroll: 1
			}
		},
		{
			breakpoint: 576,
			settings: 	{
				slidesToShow: 1,
				slidesToScroll: 1
			}
		}
	]
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

	// -- Formulaire Remoter -----------------------------------------------------------------------------------------
	.on('submit', '[data-toggle=form-remote]', function (event) {
		// Stop form from submitting normally
		event.preventDefault();
		var $form = $(this),
			data = $form.serializeArray(),
			$parentModal = $form.parents('.modal:first')
		;

		if ($(event.originalEvent.explicitOriginalTarget).is(':submit')) {
			var $button = $(event.originalEvent.explicitOriginalTarget);
			data.push({name: $button.attr('name'), value: $button.val()});
		}

		$.post(
			$form.attr('action'),
			data
		).done(function (data) {
		    if (data === 'reload') {
                window.location.reload();
            } else {
                $form.replaceWith(data);
                $parentModal.trigger('loaded.bs.modal');
            }
		}).fail(function (data) {
            $form.replaceWith(data);
		});
		return false;
	})
    .on('click', '[data-toggle=modal]', function(e) {
        var $this = $(this),
            $modal = $($this.data('target')),
            reload = $this.attr('data-reload') !== undefined,
            overrideContent = $this.attr('data-content');

        if ($this.is('a, :submit') ) {
            e.preventDefault();
        }

        var remote = null;
        if ($this.is('a[href]')) {
            remote = $this.attr('href');
        } else if($this.attr('data-remote') !== undefined && $this.attr('data-remote') !== '') {
            remote = $this.attr('data-remote');
        }

        if (remote !== null && remote !== undefined) {
            if (reload) {
                var originalContent = $modal.find(".modal-content").html();
                $modal.on('hidden.bs.modal',function () {
                    $modal
                        .removeData('bs.modal')
                        .find('.modal-body')
                        .html(originalContent);
                });
            }

            $modal.find(".modal-content").load(remote);
        }

        if (overrideContent !== undefined) {
            $modal.find(".modal-body").html(overrideContent);
        }

        return false;
    })
	.on('click', '[data-toggle="tabajax"]', function(e) {
		var $this = $(this),
			loadurl = $this.attr('href'),
			targ = $this.attr('data-target');

		$.get(loadurl, function(data) {
			$(targ).html(data);
		});

		$this.tab('show');
		return false;
	})
	.on('keyup', '#search-input', function(e){
		let $this = $(this);
		/**
		  * @type string
		 */
		let url =  $this.data('urlAutocomplete');

		/**
		 *
		 * @type {{word: *}}
		 */
		let datas ={'word': $this.val()};

		if ($this.val().length >= 3){
			setTimeout(function () {
				 /**
				  * send the form
				  **/
					$.ajax({
						method: "POST",
						url: url,
						data: datas,
					}).done(function (data) {
						// stop the spinner
						$('#autocompletion-list')
							.html(data.html)
							.show()
						;
					}).fail(
						function (jqXHR, textStatus) {
						   // handle the message jqXHR.responseJSON.message;
							// stop the spinner and show the message
						}
					);
			},300);

		}
	})
	.on('click', '.search-autocomplet__item-content', function (e) {
		$('#search-input').val($(this).html());
		$('#autocompletion-list').hide();
	})
	.on('show.bs.modal', '#modal-list-add', function () {
        $('#resume-container').html('');
		let objSelected = $('#contenu-site').find('input:checked.addableInList');
        for (let [key, value] of Object.entries(objSelected)) {
        	let container = $(value).parents('.list-result__content-item');
        	if (container.length > 0) {
        		let card = container.clone()[0];
        		card = card.innerHTML.replace(/__item__/gi, key);
                $('#resume-container').append(card);
			}
        }
	});
