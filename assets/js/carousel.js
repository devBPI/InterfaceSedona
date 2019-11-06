require('slick-carousel');

// Configuration Carousel Primary (Accueil + Parcours) ----------------------------------------------------------------
var $slider_items = $(".js-carousel-primary .carousel__slide, .js-carousel-secondary .carousel__slide");

if ($(window).width() > 576 && $(window).width() < 992) {
    for(var i = 0; i < $slider_items.length; i+=2) {
        $slider_items.slice(i, i+2).wrapAll('<div class="carousel__body"></div>');
    }
} else if ($(window).width() > 992) {
    for(var i = 0; i < $slider_items.length; i+=4) {
        $slider_items.slice(i, i+4).wrapAll('<div class="carousel__body"></div>');
    }
}

$('.js-carousel-primary')
    .slick({
        dots: true,
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        adaptiveHeight: true,
        autoplay: true,
        autoplaySpeed: 5000,
        prevArrow: '<button class="slick-prev" aria-label="Actualité précédente" type="button">Précédent</button>',
        nextArrow: '<button class="slick-next" aria-label="Actualité suivante" type="button">Suivant</button>',
        dotsClass: 'carousel__pagination',
        customPaging: function (slider, i) {
            var slideNumber = (i + 1),
                totalSlides = slider.slideCount;
            return '<button class="carousel__pagination-dot"><span class="sr-only">Actualités ' + slideNumber + ' page sur ' + totalSlides + '</span></button>';
        }
    })
    .on('afterChange', function(slick, currentSlide){
        $('.carousel__pagination li')
            .attr('aria-selected', 'false')
            .removeAttr('role');
        $('.carousel__pagination li.slick-active').attr('aria-selected', 'true');
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
$('.js-carousel-primary .slick-slide').removeAttr('role');
$('.carousel__pagination').attr('aria-label', "Choix d'un groupe d'actualités à afficher");
$('.carousel__pagination li').attr('aria-selected', 'false').removeAttr('role');
$('.carousel__pagination li.slick-active').attr('aria-selected', 'true');
$('.carousel__pagination .carousel__pagination-dot').removeAttr('aria-label');

// Configuration Carousel Secondary (Notices) --------------------------------------------------------------------------
$('.js-carousel-secondary').slick({
    infinite: false,
    slidesToShow: 1,
    slidesToScroll: 1,
    adaptiveHeight: true,
    autoplay: false,
    prevArrow: '<button class="slick-prev" aria-label="Actualité précédente" type="button">Précédent</button>',
    nextArrow: '<button class="slick-next" aria-label="Actualité suivante" type="button">Suivant</button>',
});

// Ajout boutton "Voir plus" suivant nombres d'informations - Notices ---------------------------------------------------------
$('.js-list-information ul.list-information__sub-list').each( function() {
    var $list = $(this),
        $children = $list.children(),
        $children_length = $children.length;

    if ( $children_length > 4 ) {
        $children
            .eq(4)
            .nextAll()
            .addClass('d-none');
        $children
            .last()
            .after('<li class="list-information__sub-item list-information__sub-item--btn"><button type="button" class="btn btn-small-link js-btn js-btn--more">Voir tout<span class="sr-only"> les résultats</span></button></li>');
    }
});

$(document)
    .on('click', '.js-btn', function() {
        var $this = $(this),
            $items = $(this).parent().parent().children().eq(4).nextAll();

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