require('datatables.net-bs4');

$(document).ready(function () {
    $('table.table-striped').dataTable({
        "paging":   false,
        "info":   false,
        "searching":   false,
        "scrollX": false
	});
});
