require('datatables.net-bs4');

$(document).ready(function () {
    $('table.table-striped').dataTable({
        "language": {
          "emptyTable": "Il n'y a aucun document dans votre liste"
        },
        "paging":   false,
        "info":   false,
        "searching":   false,
        "scrollX": true
	});
});
