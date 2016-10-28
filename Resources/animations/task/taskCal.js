/*/////Show forms panel*/
var opcion = $('#tipoActividades').val();
if(opcion == 3){
    $('.location').removeClass('hidden');
    $('#txtDireccion').addClass('validateInput');
} else {
    $('.location').addClass('hidden');
    $('#txtDireccion').removeClass('validateInput');
}

$(document).on('change', '#tipoActividades', function(event) {
    
    opcion = $(this).val();
    
    console.log('sdfdvdsv');
    if(opcion == 3){
        $('.location').removeClass('hidden');
        $('#txtDireccion').addClass('validateInput');
    } else {
        $('.location').addClass('hidden');
        $('#txtDireccion').removeClass('validateInput');
    }
});


$(document).on('click', '.btnAddPage', function(event) {
	/*//console.log('add');*/
	var id = $('#txtId').val();
	$('#comentarios').addClass('hidden');
	$('#btnLoadMore').addClass('hidden');
	$('#mostrarCancelados').addClass('hidden');
        
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
		$('#calendar').hide();
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
	$('#mostrarCancelados').removeClass('hidden');
        $('#txtDireccion').val('');
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
	$('#calendar').show();
}

/*/////Fin hide forms panel*/