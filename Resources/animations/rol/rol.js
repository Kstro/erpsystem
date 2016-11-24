/*/////Show forms panel*/

$(document).on('click', '.btnAddPage', function(event) {
	console.log('add');
	var id = $('#txtId').val();
	/*//Cambiar nombre del panel heading para add (Inserción)*/
	$('.pnHeadingLabelAdd').removeClass('hidden');
	$('.pnHeadingLabelEdit').addClass('hidden');
	if (id!='') {
		$('#txtId').val('');
		$('#txtName').val('');
		$('#pnAdd').slideDown();
		$('#roleList').slideUp();
		
	}
	else{
		$('#pnAdd').slideToggle();
	}
	$('#txtName').focus();
});

/*/////Fin show forms panel*/

/*/////Hide forms panel*/

$(document).on('input', 'div.dataTables_filter input', function(event) {
	var probability=$('#txtProbability');
	$('#txtId').val('');
	$('#txtName').val('');
	$('#pnAdd').slideUp();
	$('.chkItemAll').prop({'checked': false});
	probability.slider('setValue', 10);	
	$('.btnAdd').removeClass('hidden');
	$('.btnDelete').addClass('hidden');
});

$(document).on('click', '#saleStageList>thead>tr>th:nth-child(2),#saleStageList>thead>tr>th:nth-child(3),#btnCancel', function(event) {
	var probability=$('#txtProbability');
	$('#txtId').val('');
	$('#txtName').val('');
	$('#pnAdd').slideUp();
	$('.chkItemAll').prop({'checked': false});
	probability.slider('setValue', 10);	
	$('.btnAdd').removeClass('hidden');
	$('.btnDelete').addClass('hidden');
});


/*/////Fin hide forms panel*/