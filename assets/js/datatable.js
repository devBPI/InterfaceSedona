require('datatables.net/js/jquery.dataTables.min.js');

function removeAttributeWithValue(nodes, attribute, value)
{
	//alert("removeAttributeWithValue("+nodes+", "+attribute+", "+value+")");
	if(null == nodes || null == attribute)
		return;
	/*alert("Is Array? "+ Array.isArray(nodes));
	if(!Array.isArray(nodes))
		nodes = [nodes];*/
	for(var i = 0; i < nodes.length; i++)
	{
		var node = nodes[i];
		//alert("HTML : "+node.outerHTML);
		//alert("attributes : "+node.attributes[0]);
		if(node.hasAttribute(attribute) && value == node.getAttribute(attribute))
		{
			//alert("found atribute" + attribute);
			node.removeAttribute(attribute);
		}
		if(node.hasChildNodes())
		{
			var children = node.children;
			for (var j = 0; j < children.length; j++)
				removeAttributeWithValue([children[j]], attribute, value);
		}
	}
}

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

	$('table.table-striped').on( 'order.dt', function () {
		// This will show: "Ordering on column 1 (asc)", for example
		window.setTimeout(function (){
			//$('table.table-striped [role]').removeAttr('role');
			removeAttributeWithValue($('table.table-striped'), "role", "grid");//CTLG-584
			removeAttributeWithValue($('table.table-striped'), "role", "row");//CTLG-584
			removeAttributeWithValue($('table.table-striped'), "role", "columhear");//CTLG-584
		},0);
	} );

	/*$('table.table-striped [role]').removeAttr('role');
	$('table.table-striped').removeAttr('role');*/
	removeAttributeWithValue($('table.table-striped'), "role", "grid");//CTLG-584
	removeAttributeWithValue($('table.table-striped'), "role", "row");//CTLG-584
	removeAttributeWithValue($('table.table-striped'), "role", "columnhear");//CTLG-584
	removeAttributeWithValue($('table.table-striped'), "role", "columnheader");//CTLG-584
/*    $('table.table-striped').on( 'order.dt', function () {
        // This will show: "Ordering on column 1 (asc)", for example
        window.setTimeout(function (){
            $('table.table-striped [aria-label]').removeAttr('aria-label');
        },0);
    } );

    $('table.table-striped [aria-label]').removeAttr('aria-label');*/
});
