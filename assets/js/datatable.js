require('datatables.net/js/jquery.dataTables.min.js');

$(document).ready(function () {
    $('table.table-striped').dataTable({
        "columnDefs": [
          {
            "targets": 'nosort',
            "orderable": false
          }
        ],
        "language": {
          "emptyTable": "Il n'y a aucun document dans votre liste",
          "aria": {
            "sortAscending": ": activer pour trier par ordre ascendant",
            "sortDescending": ": activer pour trier par ordre descendant"
          }
        },
        "paging":   false,
        "info":   false,
        "searching":   false
	});

  $('.table__thead-title').removeAttr('aria-label');
});
