$(document).on('click', '.btnAddPage', function(event) {
	
	var id = $('#txtId').val();
 
        $('#comentarios').hide();
	$('#wallmessages').hide();
	$('#btnLoadMore').hide();

	$('#addTag').addClass('hidden');
	$('#addedTags').addClass('hidden');
	$('#filterTag').addClass('hidden');
        
	$('.pnHeadingLabelAdd').removeClass('hidden');
	$('.pnHeadingLabelEdit').addClass('hidden');
	if (id!='') {
		
           // limpiarCampos();
            $('#btnBack').removeClass('hidden');
            $('#btnCancelTop').removeClass('hidden');
            $('#btnSaveTop').removeClass('hidden');
          //  $(this).addClass('hidden');
            $('#pnAdd').slideDown();
	}
	else{
        $('#txtName').val('');
        $('#txtDescripcion').val('');
	$('#dpTipo').select2('destroy');
		// console.log("else");
		// limpiarCampos();
		//$('#pnAdd').show();
                $('#pnAdd').slideToggle();
                
		//$('#accountsList').parent().hide();
	}
	$('#btnSaveTop').removeClass('hidden');
	$('#btnCancelTop').removeClass('hidden');
	$('.btnAddPage').addClass('hidden');
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
	$('#btnCancelTop').addClass('hidden');
	$('#btnSaveTop').addClass('hidden');
});


$(document).on('click', '#btnCancel,#btnBack,#btnCancelTop', function(event) {
	// console.log('cancel');
     
        $('#filterTag').removeClass('hidden');
	$('#addTag').addClass('hidden');
	$('#addedTags').addClass('hidden');
	limpiarCampos();
	return false;
	
});
$(document).on('click', '#accountsList>thead>tr>th:gt(0)', function(event) {
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
	$('#btnCancelTop').addClass('hidden');
	$('#btnSaveTop').addClass('hidden');
	$('#pnAdd').hide();
	$('#accountsList').parent().show();
}

/////Fin hide forms panel