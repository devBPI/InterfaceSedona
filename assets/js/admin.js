import {SelectionAdder, SelectionList} from './my-selection';

let selectionListObject = new SelectionList();

import InputDecorator from './input-decorator';
let inputDecorator = new InputDecorator($('input:radio, input:checkbox'));
inputDecorator.decorate();

$(document)
	.on('show.bs.modal', '#modal-list-add,#modal-list-create', function (e) {
        let adder = new SelectionAdder($(this).get(0));
        adder.hideContainer();

        for (let doc of $('[name="selection[document][]"]:checked')) {
            adder.cloneSelectedItemInContainer(doc);
        }
	})
    .on('ifChanged click', ':checkbox', function () {
        selectionListObject.updateListCount($('[name="selection[list][]"]:checked').length);
        selectionListObject.updateDocumentCount($('[name="selection[document][]"]:checked').length);
    })
;
