/*/////Show forms panel*/

$(document).on('click', '.btnAddPage', function(event) {
	/*//console.log('add');*/
	var id = $('#txtId').val();
        
        
        $('#comentarios').hide();
	$('#wallmessages').hide();
        $('#cuentaActividades').html('');
        $('#btnLoadMore').hide();
	$('#addTag').addClass('hidden');
	$('#addFile').addClass('hidden');
	$('#addedTags').addClass('hidden');
	$('#filterTag').addClass('hidden');
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
		$('#activitiesList').parent().hide();
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
        $('#filterTag').removeClass('hidden');
	$('#addTag').addClass('hidden');
	$('#addedTags').addClass('hidden');
	$('#addedFiles').addClass('hidden');
        $('#addFile').addClass('hidden');
        $('#btnLoadMore').addClass('hidden');
	limpiarCampos();
	return false;
	
});
$(document).on('click', '#activitiesList>thead>tr>th:gt(0)', function(event) {
	
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
	$('#txtDireccion').val('');
        $('#tipoActividades').val(1).change().trigger("change");
	
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

        $('.location').addClass('hidden');
        
	$('#pnAdd').hide();
	$('#activitiesList').parent().show();
}

$(document).on('change', '#tipoActividades', function(event) {
    var opcion = $(this).val();
    
    if(opcion == 3){
        $('.location').removeClass('hidden');
        $('#txtDireccion').addClass('validateInput');
    } else {
        $('.location').addClass('hidden');
        $('#txtDireccion').removeClass('validateInput');
    }
});

/*/////Fin hide forms panel*/