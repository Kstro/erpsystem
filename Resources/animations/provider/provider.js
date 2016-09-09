/////Show forms panel

$(document).on('click', '.btnAddPage', function(event) {
	//console.log('add');
	var id = $('#txtId1').val();
	console.log("ID: "+id);
	//Cambiar nombre del panel heading para add (Inserción)
	$('.pnHeadingLabelAdd').removeClass('hidden');
	$('.pnHeadingLabelEdit').addClass('hidden');
	if (id!='') {
		// console.log("if");
		limpiarCampos();
	}
	else{
		// console.log("else");
		// limpiarCampos();
		$('#pnAdd').toggle();
		$('#providersList').parent().toggle();
	}
	
	$('#txtName').focus();
});

/////Fin show forms panel

/////Hide forms panel

$(document).on('input', 'div.dataTables_filter input', function(event) {
	//console.log('add');
	$('#txtId').val('');
	$('#txtName').val('');
	$('#txtDuracion').val('');
	$('#pnAdd').slideUp();
	// $('.chkItemAll').prop({'checked': false});
	$('.btnAddPage').removeClass('hidden');
	$('.btnDelete').addClass('hidden');
	$('#btnBack').addClass('hidden');
});


$(document).on('click', '#btnCancel,#btnBack', function(event) {
	
	console.log('cancel');
	limpiarCampos();
	return false;
	
});
$(document).on('click', '#providersList>thead>tr>th:gt(0)', function(event) {
	
	console.log('cancel');
	$('.chkItemAll').prop({'checked': false});

	
});


function limpiarCampos(){
	$('#txtId').val('');
	$('#txtName').val('');
	$('#txtApellido').val('');
	$('#txtDuracion').val('');
	$('#txtCompania').val('');
	$('.txtAddressFirst').val('');
	$('.firstPhoneTxt').val('');
	$('.firstPhoneExtension').val('');
	$('.txtEmailFirst').val('');
	$('#imgTest').attr('src','http://placehold.it/250x250');
	$('#file').val('');

	$('.validateInput').each(function(index, el) {
		$(this).removeClass('errorform');
	});
	$('.removeAddress').each(function(index, el) {
		$(this).click();
	});
	$('.removePhone').each(function(index, el) {
		$(this).click();
	});
	$('.removeEmail').each(function(index, el) {
		$(this).click();
	});
		
	$('.chkItemAll').prop({'checked': false});
	$('.btnAddPage').removeClass('hidden');
	$('.btnDelete').addClass('hidden');
	$('#btnBack').addClass('hidden');
	$('#pnAdd').toggle();
	$('#providersList').parent().toggle();
}

/////Fin hide forms panel