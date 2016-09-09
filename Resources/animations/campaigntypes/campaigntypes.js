/////Show forms panel

$(document).on('click', '.btnAdd', function(event) {
	//console.log('add');
	var id = $('#txtId').val();
	
	//Cambiar nombre del panel heading para add (Inserción)
	$('.pnHeadingLabelAdd').removeClass('hidden');
	$('.pnHeadingLabelEdit').addClass('hidden');
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

$(document).on('input', 'div.dataTables_filter input', function(event) {
	//console.log('add');
	$('#txtId').val('');
	$('#txtName').val('');
	$('#pnAdd').slideUp();
	$('.chkItemAll').prop({'checked': false});
	$('.btnAdd').removeClass('hidden');
	$('.btnDelete').addClass('hidden');
});


$(document).on('click', '#btnCancel, #campaignList>thead>tr>th:gt(0)', function(event) {
	//console.log('add');
	$('#txtId').val('');
	$('#txtName').val('');
	$('#pnAdd').slideUp();
	$('.chkItemAll').prop({'checked': false});
	$('.btnAdd').removeClass('hidden');
	$('.btnDelete').addClass('hidden');
});


/////Fin hide forms panel