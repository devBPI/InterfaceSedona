$(document)
	.on('show.bs.modal', '#modal-list-add,#modal-list-create', function (e) {
		let $inputContainer = $(this).find('#resume-container');
        $inputContainer.html('');
		$inputContainer.parent('.modal-body').hide();
		for (let doc of $('[name="selection[document][]"]:checked')) {
			let card = $(doc).clone();
			$inputContainer.append(card);
		}
	})
    .on('ifChanged click', ':checkbox', function () {
    	$('#countSelectionList span').html('('+$('[name="selection[list][]"]:checked').length+')')
    	$('#countSelectionDocument span').html('('+$('[name="selection[document][]"]:checked').length+')')
    })
;
