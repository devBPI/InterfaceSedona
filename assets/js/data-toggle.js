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
                    if ($this.is('[href]')) {
                        op['remote'] = $this.attr('href');
                    }
                    $($this.data('href')).modal(op, this);
                    return false;
                }
            };

            $(document).on('click change keyup ifChecked ifUnchecked', object.$spy, object.test);
            object.test();
            $this.data('ifNoFound', object);

            if ($this.is('[data-toggle$="-modal"]') && $this.is('[data-href]')) {
                $this.on('click', object.openModal);
            }
        });
        return this;
    };

    let autocompleteRequest;
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
                $form.replaceWith(data);
            });
            return false;
        })
        .on('shown.bs.modal', '.modal', function () {
            $(this).find( 'input:visible:first').focus();
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
        .on('click', '[data-reload="true"]', function (e) {
            location.reload();
        })
        .on('ifChanged click', '[data-toggle="check-all"]', function () {
            let $form = $(this).parents('form'),
                selector = $form.find(':checkbox')
            ;
            if ($(this).data('target') !== undefined) {
                selector = $(this).data('target');
            }

            $(selector).attr('checked', $(this).is(':checked'));
            if ($(this).is(':checked')) {
                $(selector).parents('div.check').addClass('checked');
            } else {
                $(selector).parents('div.check').removeClass('checked');
            }
        })
        .on('click', '[data-toggle="collection-add"]', function () {
            var $this = $(this),
                $prototype = $this.data('target') != undefined ? $($this.data('target')) : $this,
                newWidget = $prototype.data('prototype'),
                widgetCount = $prototype.data('count');

            if ($this.data('limit') != undefined && $prototype.children().length >= $this.data('limit')) {
                alert('Limite atteinte : '+$this.data('limit'));
                return false;
            }

            var protoname = new RegExp($prototype.data('prototype-name') != undefined ? $prototype.data('prototype-name') : '__index__',"g");

            // remplace les "__id__" utilisés dans l'id et le nom du prototype
            // par un nombre unique pour chaque email
            // le nom de l'attribut final ressemblera à name="contact[emails][2]"
            newWidget = newWidget.replace(protoname, widgetCount);
            widgetCount++;

            // créer une nouvelle liste d'éléments et l'ajoute à notre liste
            $prototype
                .append(newWidget)
                .data('count', widgetCount);

            if ($this.data('placement') && $this.data('placement') == "new")
                $(document.body).scrollTop($prototype.children().last().offset().top);

            if ($this.data('modal'))
                $('#'+$this.data('modal')).modal('show');

            if ($this.data('limit') != undefined && $prototype.children().length >= $this.data('limit')) {
                $this.attr('disabled', true);
            }

            return false;
        })
        .on('click', '[data-toggle="remove-element"]', function (e) {
            var $this = $(this),
                $target = $this.data('parent') != undefined ? $this.parents($this.data('parent')).first()  : $this,
                $parent = $target.parents(':first');
            if($this.hasClass('btn-confirm')) {
                $('#confirmation-modal #confirmation-modal-confirm').one('click',function(e){
                    e.preventDefault();
                    $target.remove();
                    $('#confirmation-modal').modal('hide');
                    $parent.trigger('change');
                });
                e.preventDefault();
                return false;
            }
            $target.remove();
            $parent.trigger('change');
            $('[data-toggle="collection-add"]').attr('disabled', false);
        })
        .on('keyup', '[data-toggle="autocomplete"]', function () {
            let $this = $(this),
                $target = $($(this).data('target')),
                $type = $($(this).data('type')),
                url =  $this.data('url')
            ;

            window.clearTimeout(autocompleteRequest);

            $target
                .addClass('d-none')
                .html()
            ;
            
            if ($this.val().length >= 3) {
                autocompleteRequest = window.setTimeout(function () {
                    /**
                     * send the form
                     **/
                    $.ajax({
                        method: "GET",
                        url: url,
                        data: {'word': $this.val(), 'type': $type.val()},
                    }).done(function (data) {
                        // stop the spinner
                        if (data.html) {
                            $target
                                .removeClass('d-none')
                                .html(data.html)
                            ;
                        }
                    }).fail(
                        function (jqXHR, textStatus) {
                            // handle the message jqXHR.responseJSON.message;
                            // stop the spinner and show the message
                        }
                    );
                }, 300);

            }

            $type.on('change', function () {
                $this.trigger('keyup');
            })
        })
        .ready(function () {
            $('[data-toggle*="-if-no-found"]').ifNoFound();
        })
    ;

})(window.jQuery || window.Zepto);
