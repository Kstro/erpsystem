/*/////Show forms panel*/

$(document).on('click', '.btnAddPage', function(event) {
	/*//console.log('add');*/
	var id = $('#txtId1').val();
	$('#comentarios').hide();
	$('#wallmessages').hide();

        $('#btnLoadMore').hide();
	$('#addTag').addClass('hidden');
	$('#addFile').addClass('hidden');
	$('#addedTags').addClass('hidden');
	$('#filterTag').addClass('hidden');
	/*// console.log("ID: "+id);*/
	/*//Cambiar nombre del panel heading para add (Inserción)*/
	$('.pnHeadingLabelAdd').removeClass('hidden');
	$('.pnHeadingLabelEdit').addClass('hidden');
        $('#pnAdd').removeClass('hidden');
	if (id!='') {
		/*// console.log("if");*/
		limpiarCampos();
		$('#btnBack').removeClass('hidden');
		$('#btnCancelTop').removeClass('hidden');
		$('#btnSaveTop').removeClass('hidden');

		$(this).addClass('hidden');
	}
	else{
		/*// console.log("else");*/
		/*// limpiarCampos();*/
		
		$('#clienteList').parent().hide();
	}
	$('#btnSaveTop').removeClass('hidden');
	$('#btnCancelTop').removeClass('hidden');
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
        $('#btnLoadMoreFiles').addClass('hidden');
        $('#clienteList').parent().hide();
	limpiarCampos();
	return false;
	
});
$(document).on('click', '#clienteList>thead>tr>th:gt(0)', function(event) {
	
	console.log('cancel');
	$('.chkItemAll').prop({'checked': false});

	
});


function limpiarCampos(){
	$('#txtId1').val('');
	$('#txtId2').val('');
	$('#txtName').val('');
	$('#txtApellido').val('');
	$('#txtDuracion').val('');
	$('#txtCompania').val('');
	$('.txtAddressFirst').val('');
	$('.firstPhoneTxt').val('');
	$('.firstPhoneExtension').val('');
	$('.txtEmailFirst').val('');
	$('.txtZipCode').val('');
	$('.txtCity').val('');
	$('.txtState').val('');
        $('#wallmessages').html('');
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
        
        $('.removeContact').each(function(index, el) {
		$(this).click();
	});
        
        $('.dpbFirstContacts').html('<option value=0></option>');
        $('.telefonoContactoFirst').html('');
        $('.correoContactoFirst').html('');
		
	$('.chkItemAll').prop({'checked': false});
	$('.btnAddPage').removeClass('hidden'); 
	$('.btnDelete').addClass('hidden');
	$('#btnBack').addClass('hidden');
	$('#btnCancelTop').addClass('hidden');
	$('#btnSaveTop').addClass('hidden');
	$('#pnAdd').addClass('hidden');
	$('#clienteList').parent().show();
}

/*/////Fin hide forms panel*/