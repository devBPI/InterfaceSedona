import * as util from './notice-availibility.js'

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
    .on('keyup', '#search-input', function (e) {
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
    .on('change','.js-pagination-select', function (e) {
        let $this = $(this);
        let page = $this.val();

        let url = $this.data('url').replace(0, page);
        window.location = url;
    })
    .on('click', '.js-delete-filter',  function(e){
        e.preventDefault();
        let $this = $(this);
        console.log($this.data('name'));

        $('.input-'+$this.data('name')).prop('checked', false);
    })
    .on('show.bs.modal', '#modal-list-add,#modal-list-create', function (e) {
        let $inputContainer = $(this).find('#resume-container');
        $inputContainer.html('');

        let objSelected = $('#contenu-site').find('input:checked.addableInList');
        for (let [key, value] of Object.entries(objSelected)) {
            let container = $(value).parents('.list-result__content-item');
            if (container.length == 0) {
                container = $(value).parents('.search-result');
            }
            if (container.length > 0) {
                let card = container.clone()[0];
                card = card.innerHTML.replace(/__item__/gi, key);
                $inputContainer.append(card);
            }
        }
    })
;
