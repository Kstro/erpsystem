/*/////Show forms panel*/

$(document).on('click', '.btnAddPage', function(event) {
	
	var id = $('#txtId').val();
	
        var numPedidos=0;
        
        $('#btnLoadMoreGen').hide();
	$('#addTag').addClass('hidden');
	$('#addFile').addClass('hidden');
	$('#addedFiles').addClass('hidden');
	$('#addedTags').addClass('hidden');
	$('#wallmessages').addClass('hidden');
	$('#comentarios').addClass('hidden');
	$('#filterTag').addClass('hidden');
        
	$('.pnHeadingLabelAdd').removeClass('hidden');
	$('.pnHeadingLabelEdit').addClass('hidden');
	if (id!='') {
		/*// console.log("if");*/
		limpiarCampos();
		$('#btnSaveTop').addClass('hidden');
		$('#btnCancelTop').addClass('hidden');
		$('#btnAddPage').removeClass('hidden');
		$(this).addClass('hidden');
	}
	else{
		$('#pnAdd').show();
		$('#tasksList').parent().hide();
		$('#btnSave').removeClass('hidden');
		$('#btnSaveTop').removeClass('hidden');
		$('#btnCancelTop').removeClass('hidden');

	}
	
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
        $('#filterTag').removeClass('hidden');
	$('#addTag').addClass('hidden');
	$('#addedTags').addClass('hidden');
	$('#addedFiles').addClass('hidden');
        $('#addFile').addClass('hidden');
        $('#btnLoadMore').addClass('hidden');
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