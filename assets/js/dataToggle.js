$(document)
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
	.on('click', '[data-reload="true"]', function (e) {
		location.reload();
	})
    .on('click', '[data-toggle="check-all"]', function () {
        let $form = $(this).parents('form'),
            selector = $form.find(':checkbox')
        ;
        if ($(this).data('target') !== undefined) {
            selector = $(this).data('target');
        }

        $(selector).attr('checked', $(this).is(':checked'));
    })
;
