$(document).on('hide.bs.modal', '[role=dialog]:has([data-toggle-refresh])', function (event){
    var $this = $(this),
        $form =  $this.find('[data-toggle-refresh]'),
        uri = $form.attr('action') != undefined && $form.data('toggle-refresh') == "on" ? $form.attr('action') : $form.data('toggle-refresh');
    if ($form.length > 0 && uri != undefined) {
        $.get(uri)
            .done(function (data) {
                if (data === 'reload') {
                    window.location.reload();
                } else {
                    $form
                        .replaceWith(data)
                        .trigger('loaded.bs.modal');
                    $('[data-toggle*="-if-no-found"]').ifNoFound();
                }
            })
            .fail(function (data) {
                $form.replaceWith($(data.responseText));
            });
    }
});

