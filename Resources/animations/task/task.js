/*/////Show forms panel*/

$(document).on('click', '.btnAddPage', function(event) {
	/*//console.log('add');*/
	var id = $('#txtId').val();
	/*// $('.noCal').removeClass('hidden');*/
	/*// $('#estado').val(2).trigger('change');*/
	
	/*// console.log("ID: "+id);*/
	/*//Cambiar nombre del panel heading para add (Inserción)*/
	$('.pnHeadingLabelAdd').removeClass('hidden');
	$('.pnHeadingLabelEdit').addClass('hidden');
	if (id!='') {
		/*// console.log("if");*/
		
		/*// $('#btnBack').removeClass('hidden');*/
		/*// $('#btnCancelTop').removeClass('hidden');*/
		/*// $('#btnSaveTop').removeClass('hidden');*/
		limpiarCampos();
		$('#btnSaveTop').addClass('hidden');
		$('#btnCancelTop').addClass('hidden');
		$('#btnAddPage').removeClass('hidden');
		$(this).addClass('hidden');
	}
	else{
		/*// console.log("else");*/
		/*// limpiarCampos();*/
		$('#pnAdd').show();
		$('#tasksList').parent().hide();
		$('#btnSave').removeClass('hidden');
		$('#btnSaveTop').removeClass('hidden');
		$('#btnCancelTop').removeClass('hidden');

	}
	/*// $('#btnSaveTop').removeClass('hidden');*/
	/*// $('#btnCancelTop').removeClass('hidden');*/
	$('.btnAddPage').addClass('hidden');
	$('#txtName').focus();
});

/*/////Fin show forms panel*/

/*/////Hide forms panel*/

$(document).on('input', 'div.dataTables_filter input', function(event) {
	/*//console.log('add');*/
	$('#txtId').val('');
	$('#txtName').val('');
	$('#txtDuracion').val('');
	$('#pnAdd').slideUp();
	/*// $('.chkItemAll').prop({'checked': false});*/
	$('.btnAddPage').removeClass('hidden');
	$('.btnDelete').addClass('hidden');
	$('#btnBack').addClass('hidden');
	$('#btnCancelTop').addClass('hidden');
	$('#btnSaveTop').addClass('hidden');
});


$(document).on('click', '#btnCancel,#btnBack,#btnCancelTop', function(event) {
	
	/*// console.log('cancel');*/
	limpiarCampos();
	return false;
	
});
$(document).on('click', '#tasksList>thead>tr>th:gt(0)', function(event) {
	
	/*// console.log('cancel');*/
	$('.chkItemAll').prop({'checked': false});

	
});


function limpiarCampos(){
	$('#txtId').val('');
	$('#txtName').val('');
	$('#txtDescripcion').val('');
	$('#txtCompania').val('');
	$('#txtFechaInicio').val('');
	$('#txtFechaFin').val('');
	
	$('.validateInput').each(function(index, el) {
		$(this).removeClass('errorform');
	});
	$('.removePersona').each(function(index, el) {
		$(this).click();
	});
		
	
	$('.btnAddPage').removeClass('hidden');
	$('.btnDelete').addClass('hidden');
	$('#btnBack').addClass('hidden');
	$('#btnCancelTop').addClass('hidden');
	$('#btnSaveTop').addClass('hidden');


	$('#pnAdd').hide();
	$('#tasksList').parent().show();
}

/*/////Fin hide forms panel*/