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

console.log('test');

$('[data-toggle="tooltip"]').tooltip({
	trigger: 'click',
	template: '<div class="tooltip" role="tooltip"><div class="tooltip-inner"></div></div>'
});

// Configuration Carousel Primary (Accueil + Parcours)
$('.js-carousel-primary').slick({
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
});

// Gestion Bouton "Pause/Play" - Carousel Primary
$('.carousel__button').on('click', function() {
	if( $(this).hasClass('carousel__button--pause') ) {
		$('.js-carousel-primary').slick('slickPause');
		$(this).attr('aria-label', 'Mettre en lecture le carousel');
		$(this).removeClass('carousel__button--pause')
		$(this).addClass('carousel__button--play');
	} else {
		$('.js-carousel-primary').slick('slickPlay');
		$(this).attr('aria-label', 'Mettre en pause le carousel');
		$(this).removeClass('carousel__button--play')
		$(this).addClass('carousel__button--pause');
	}
});

// Correctif suivant retours RGAA
$(".js-carousel-primary .slick-slide").removeAttr("role");
$(".carousel__pagination").attr("aria-label", "Choix d'un groupe d'actualités à afficher");

$('.js-carousel-primary').on('afterChange', function(slick, currentSlide){
  $('.carousel__pagination li').attr("aria-selected", "false");
  $('.carousel__pagination li.slick-active').attr("aria-selected", "true");
});

// Configuration Carousel Secondary (Notices)
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
