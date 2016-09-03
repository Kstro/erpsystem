/////Show forms panel

$(document).on('click', '.btnAdd', function(event) {
	//console.log('add');
	var id = $('#txtId').val();
	var probability=$('#txtProbability');
	//Cambiar nombre del panel heading para add (Inserción)
	$('.panel-heading').html('Add');
	if (id!='') {
		$('#txtId').val('');
		$('#txtName').val('');
		$('#pnAdd').slideDown();
	}
	else{
		$('#pnAdd').slideToggle();
	}
	$('#txtName').focus();
});

/////Fin show forms panel

/////Hide forms panel

$(document).on('click, input', '#btnCancel, .sortRecords, div.dataTables_filter input', function(event) {
	//console.log('add');
	$('#txtId').val('');
	$('#txtName').val('');
	$('#pnAdd').slideUp();
	$('.chkItemAll').prop({'checked': false});
	$('.btnAdd').removeClass('hidden');
	$('.btnDelete').addClass('hidden');
});


/////Fin hide forms panel