/*/////Show forms panel*/

$(document).on('click', '.btnAddPage', function(event) {
	/*//console.log('add');*/
        $('#wallmessages').addClass('hidden');
        $('#comentarios').addClass('hidden');
        
        var id = $('#txtId1').val();
	
	/*// console.log("ID: "+id);*/
	/*//Cambiar nombre del panel heading para add (Inserción)*/
	$('.pnHeadingLabelAdd').removeClass('hidden');
	$('.pnHeadingLabelEdit').addClass('hidden');
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
		$('#pnAdd').show();
		$('#clientePotencialList').parent().hide();    
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
	$('#txtId1').val('');
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
        $('#addedFiles').addClass('hidden');
        $('#addFile').addClass('hidden');
        $('#btnLoadMore').addClass('hidden');
	limpiarCampos();
	return false;
	
});
$(document).on('click', '#clientePotencialList>thead>tr>th:gt(0)', function(event) {	
	
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
        $('.txtCity').val('');
        $('.txtState').val('');
        $('.txtZipCode').val('');
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
	$('#btnCancelTop').addClass('hidden');
	$('#btnSaveTop').addClass('hidden');
	$('#pnAdd').hide();
	$('#clientePotencialList').parent().show();
}

/*/////Fin hide forms panel*/