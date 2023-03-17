$(document).on('hide.bs.modal', '[role=dialog]', function (event){
    var $this = $(this),
        $form =  $this.find('.modal-form');
    if ($form.length > 0) {
        $.get($form.attr('action'))
            .done(function (data) {
                if (data === 'reload') {
                    window.location.reload();
                } else {
                    $form.replaceWith(data);
                }
            }).fail(function (data) {
                $form.replaceWith($(data.responseText));
            });
    }

});


