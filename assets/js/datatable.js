require('datatables.net/js/jquery.dataTables.min.js');

$(document).ready(function () {
    $('table.table-striped').dataTable({
        "language": {
          "emptyTable": "Il n'y a aucun document dans votre liste",
          "aria": {
            "sortAscending": ": activer pour trier par ordre ascendant",
            "sortDescending": ": activer pour trier par ordre descendant"
          }
        },
        "tabIndex": '-1',
        "paging":   false,
        "info":   false,
        "searching":   false
	});
});
