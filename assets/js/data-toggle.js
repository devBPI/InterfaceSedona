import Autocomplete from './autocomplete';

(function($) {
    "use strict";

    /**
     * Hide or show elements of selector defined in data-target
     * <input type="button"
     *          data-toggle="hide-if-no-found"      Mandatory > hide or show, disable or enable, visible
     *          data-spy=":radio"                   Mandatory > element listener (listen this element to execute function
     *          data-target=":radio:checked"        Mandatory > check this selector to execute function
     *          data-parent="tr"                    Optional  > target selector, by default current object
     *          data-effect="yes"                   Optional  > disable or enable effect, by default yes
     *      />
     */
    $.fn.ifNoFound = function () {
        this.each(function (i, el) {
            var $this = $(el);
            var object = {
                '$spy': $this.is('[data-spy]') ? $this.data('spy') : $this,
                '$cible': $this.is('[data-parent]') ? $this.parents($this.data('parent')).first() : $this,
                '$effect': $this.is('[data-effect]') ? $this.data('effect') : "yes",

                'find': function () {
                    return $($this.data('target')).length === 0;
                },

                'test': function () {
                    $.each(object.testFunction, function (attrName, fn) {
                        if ($this.is('[data-toggle^="' + attrName + '"]')) {
                            fn();
                        }
                    });
                },

                'testFunction': {
                    'hide-if-no-found': function () {
                        if (object.find()) {
                            if(object.$effect === 'no'){
                                object.$cible.hide();
                            } else {
                                object.$cible.filter(':visible').stop().slideUp();
                            }
                        } else {
                            if(object.$effect === 'no'){
                                object.$cible.show();
                            } else {
                                object.$cible.filter(':hidden').stop().slideDown();
                            }
                        }
                    },
                    'show-if-no-found': function () {
                        if (object.find()) {
                            object.$cible.filter(':hidden').stop().slideDown();
                        } else {
                            object.$cible.filter(':visible').stop().slideUp();
                        }
                    },
                    "disable-if-no-found": function () {
                        if (object.find()) {
                            object.$cible.attr('disabled', 'disabled');
                        } else {
                            object.$cible.removeAttr('disabled');
                        }
                    },
                    "disable-and-uncheck-if-no-found": function () {
                        if (object.find()) {
                            object.$cible.get(0).checked = false;
                            object.$cible
                                .attr('disabled', 'disabled')
                                .trigger('change');
                        } else {
                            object.$cible.removeAttr('disabled');
                        }
                    },
                    "enable-if-no-found": function () {
                        if (object.find()) {
                            object.$cible.filter(':disabled').removeAttr('disabled');
                        } else {
                            object.$cible.filter(':enabled').attr('disabled', 'disabled');
                        }
                    },
                    "visible-if-no-found": function () {
                        if (object.find()) {
                            object.$cible.css('visibility', 'visible');
                        } else {
                            object.$cible.css('visibility', 'hidden');
                        }
                    }
                },

                'openModal': function (event) {
                    event.stopPropagation();
                    var op = {'show': true};

                    var $modal = $($this.data('href')),
                        overrideContent = $this.attr('data-content');

                    if ($this.is('a[href]')) {
                        op['remote'] = $this.attr('href');
                    } else if($this.attr('data-remote') !== undefined && $this.attr('data-remote') !== '') {
                        op['remote'] = $this.attr('data-remote');
                    }

                    if (overrideContent !== undefined) {
                        $modal.find(".modal-body").html(overrideContent);
                    }
                    $modal.modal(op, this);
                    return false;
                }
            };

            $(document).on('click change keyup', object.$spy, object.test);
            object.test();
            $this.data('ifNoFound', object);

            if ($this.is('[data-toggle$="-modal"]') && $this.is('[data-href]')) {
                $this.on('click', object.openModal);
            }
        });
        return this;
    };

    $(document)
        .on('focus click loaded.bs.modal', '[data-toggle*="-if-no-found"]', function (e) {
            var $this = $(this);
            if ($this.data('ifNoFound'))
                return;
            e.preventDefault();
            // component click requires us to explicitly show it
            $this.ifNoFound();
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
                    $parentModal
                        .trigger('loaded.bs.modal')
                        .trigger('show.bs.modal')
                        .trigger('shown.bs.modal');
                }
            }).fail(function (data) {
                $form.replaceWith($(data.responseText));
            });
            return false;
        })
        .on('shown.bs.modal', '.modal', function () {
            let $this = $(this),
                firstInput = $this.find( 'input.is-invalid:visible:first');
            if (firstInput.length === 0) {
                firstInput = $this.find( 'input:visible:first');
            }
            if (firstInput.get(0) !== undefined) {
                firstInput.get(0).focus();
            }
        })
        .on('click', '[data-toggle=modal]', function(e) {
            var $this = $(this),
                $modal = $($this.data('target')),
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
                $modal.find(".modal-content").load(remote);
            }

            if (overrideContent !== undefined) {
                $modal.find(".modal-body").html(overrideContent);
            }

            return false;
        })
        .on('ifChanged click', '[data-toggle="check-all"]', function () {
            let $this = $(this),
                $form = $this.parents('form'),
                selector = $form.find(':checkbox')
            ;
            if ($this.data('target') !== undefined) {
                selector = $this.data('target');
            }
            var $selector = $(selector);

            $selector.attr('checked', $this.is(':checked'));
            $selector.on('ifChanged click', function () {
                if ($this.is(':checked') == false) {
                    $this.removeAttr('checked');
                    $this.parents('div.check').removeClass('checked');
                }
            });
            if ($this.is(':checked')) {
                $selector.parents('div.check').addClass('checked');
            } else {
                $selector.parents('div.check').removeClass('checked');
            }
        })
        .ready(function () {
            $('[data-toggle*="-if-no-found"]').ifNoFound();
        })
    ;

    document.querySelectorAll('[data-toggle="autocomplete"]').forEach(result => {
        new Autocomplete(result);
    })


})(window.jQuery || window.Zepto);
