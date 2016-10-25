$(document).ready(function() {

	$("#txtName").val('');

	/*/////Persist datatable (Save method)*/
	//var filesSelectedPrev = document.getElementById("file").files;
	/*// console.log(filesSelectedPrev[0]);*/
	
	$(document).on('click', '#btnSaveTop', function(event) {
		$('#frmCase').submit();
	});

	$('#frmCase').on('submit',(function(event) {
		/*/////Definición de variables*/
		event.preventDefault();
		var $btn = $('#btnSave').button('loading');
		var $btnT = $('#btnSaveTop').button('loading');
  		
  		/*//Contador de errores, para antes de la persistencia*/
  		var errores = 0;

		$('.validateInput').each(function() {
		 	if (!required($(this))) {
		 		$(this).addClass('errorform');
		 		errores++;
		 	}
		});
	
		if (errores==0) {
			
				$.ajax({
					url: Routing.generate('admin_case_save_ajax'),
					type: "POST",
					data: new FormData(this),
					contentType: false,
					cache: false,
					processData:false,
					success: function(data)
					{
						$("#message").html(data);
						$("#txtName").val(data.id1);
				
						if(data.msg){
                                                    swal('',data.msg,'success');
                                                    var table = $('#caseTipeList').DataTable();

                                                    $('#txtName').val('');
                                                    $('#txtId').val('');

                                                    $('#txtDescripcion').val('');
                                                    $('#dpTipo').val(data.Tipo).change().trigger("change");
                                                    $('#dpPrioridad').val(data.Prioridad).change().trigger("change");
                                                    $('#dpUser').val('').change().trigger("change");
                                                    $('#dpCuenta').val('').change().trigger("change");
                                                    $('.btnAddPage').click();
                                                    $btn.button('reset');
                                                    $btnT.button('reset');
						}
						if(data.error){
							
                                                    swal('',data.error,'error');
                                                    $btn.button('reset');
                                                    $btnT.button('reset');
						}
						table.ajax.reload();
						$('#btnCancelTop').addClass('hidden');
						$('#btnSaveTop').addClass('hidden');
						$('.btnAddPage').removeClass('hidden');
						$btn.button('reset');
						$btnT.button('reset');
					},
					error:function(data) {
						/* Act on the event */
						$btn.button('reset');
						$btnT.button('reset');
					}
				});
		}
		else {
			var requiredFields = $('.requiredFields').html();
			swal('',requiredFields,'error');
			$btn.button('reset');
			$btnT.button('reset');
		}
		event.preventDefault();
		return false;
	}));
	/*/////Fin definición persist data (Save method)*/

	/*/////Persist datatable (Edit method)*/
	$(document).on('click', '#caseList>tbody>tr>td:nth-child(2)', function(event) {
		/*/////Definición de variables*/
		/*// $('#accountsList').prop({disabled: true});*/
                        
		$(this).unbind('click');
		console.log('disabled');
		/*// return false;*/
		var text = $(this).prop('tagName');
		/*// console.log(text);*/
		var id=$(this).parent().children().first().children().attr('id');
		/*// console.log(id);*/
		//var idArray = id.split('-');
		/*// console.log(idArray);*/
		var idForm=$('#txtId').val();
		/*// var idForm=$('#txtId2').val();*/
		var selected = 0;
		/*//Cambiar nombre del panel heading para Modify*/
		$('.pnHeadingLabelAdd').addClass('hidden');
		$('.pnHeadingLabelEdit').removeClass('hidden');
               
                var objClicked = $(this);
               $('.chkItem').each(function() {
			if ($(this).is(':checked')) {
				selected++;
			}
		});	
		
		if (text=='TD' && id!=idForm && selected==0) {
                        objClicked.off('click');
			objClicked.css('cursor','progress');
			$.ajax({
				url: Routing.generate('admin_case_retrieve_ajax'),
				type: 'POST',
				data: {param1: id},
				success:function(data){
					if(data.error){
						swal('',data.error,'error');
						id.val(data.id);
                                                $('#addTag').addClass('hidden');
						$('#addedTags').addClass('hidden');
						$('#filterTag').removeClass('hidden');
						objClicked.on('click');
					}
					else{
                                      
						
						$('#txtId').val(data.id);
						$('#txtName').val(data.name);
                                                $('#txtDescripcion').val(data.Descripcion);
                                                $('#dpTipo').val(data.Tipo).change().trigger("change");
                                                $('#dpPrioridad').val(data.Prioridad).change().trigger("change");
                                                $('#dpUser').val(data.User).change().trigger("change");
                                                $('#dpCuenta').val(data.IdCuenta).change().trigger("change");
                                               					
						$('#pnAdd').show();
						$('.btnAddPage').addClass('hidden');
						$('#accountsList').parent().hide();
						$('#btnBack').removeClass('hidden');
						$('#btnCancelTop').removeClass('hidden');
						$('#btnSaveTop').removeClass('hidden');
                                               
						
						/*//seguimientoComet(data.id1);*/
						$('#addTag').removeClass('hidden');
						$('#addedTags').removeClass('hidden');
						$('#filterTag').addClass('hidden');
					}
                                        objClicked.on('click');
					activeAjaxConnections=0;
					objClicked.css('cursor', 'pointer');
				},
				error:function(data){
					if(data.error){
						/*// console.log(data.id);*/
						swal('',data.error,'error');
					}
                                        $('#addTag').addClass('hidden');
					$('#addedTags').addClass('hidden');
					$('#filterTag').removeClass('hidden');
					objClicked.on('click');
					objClicked.css('cursor', 'pointer');
				}
			});
		} 
		else {
			if(id==idForm && selected==0){
				/*// $('#pnAdd').slideDown();*/
			}
		}
	});
	/*/////Fin definición persist data (Edit method)*/

	/*/////Convertir en cliente*/
	$(document).on('click', '.btnAction', function(event) {
		var $btn = $(this).button('loading');
		/*/////Definición de variables*/
		var id=$(this).children().first().children().attr('id');
		var ids=[];
		var table = $('#caseList').DataTable();
		$('.chkItem').each(function() {
			if ($(this).is(':checked')) {
				ids.push($(this).parent().attr('id'));
			}
		});	
		/*// console.log(ids);*/
		/*// var probability=$('#txtProbability');*/
		var convertLabel = $('#convertLabel').html();
		var cancelButtonText = $('#cancelButtonText').html();
		/*// var removeButton = $('#removeButton').html();*/
		var alternateconfirmButtonText = $('#alternateconfirmButtonText').html();
		swal({
                        title: "",
                        text: convertLabel,
                        type: "info",
                        showCancelButton: true,
                        confirmButtonText: alternateconfirmButtonText,
                        cancelButtonText: cancelButtonText,
                        reverseButtons: true,
                    }).then(function(isConfirm) {
                        if (isConfirm) {
				$.ajax({
				 	url: Routing.generate('admin_provider_to_cliente_convert_ajax'),
				 	type: 'POST',
				 	data: {param1: ids},
				 	success:function(data){
				 		if(data.error){
				 			swal('',data.error,'error');
				 		}
				 		else{
				 			$('#txtId').val(data.id);
				 			$('#txtName').val(data.name);
							$('.chkItemAll').prop({'checked': false});
				 			$btn.button('reset');
							table.ajax.reload();
				 			swal('',data.msg,'success');
				 		}
						
				 		$('#pnAdd').slideUp();
				 		/*//$('.btnAdd').click();*/

				 		/*//table.ajax.reload();*/
				 	},
				 	error:function(data){
				 		if(data.error){
				 			console.log(data.id);
				 			swal('',data.error,'error');
				 			$btn.button('reset');
				 		}
				 		$btn.button('reset');
				 	}
				});
				$('.btnDelete').addClass('hidden');
				$('.btnAction').addClass('hidden');
				$('.btnAddPage').removeClass('hidden');
			}
			else{
				$btn.button('reset');
			}
		});
		$btn.button('reset');
	});
	/*/////Fin de convertir en cliente*/

	/*/////Persist datatable (Delete method)*/
	$(document).on('click', '.btnDelete', function(event) {
            

		var $btn = $(this).button('loading');
		/*/////Definición de variables*/
		var id=$(this).children().first().children().attr('id');
		var ids=[];
		var table = $('#caseList').DataTable();
		$('.chkItem').each(function() {
			if ($(this).is(':checked')) {
				ids.push($(this).parent().attr('id'));
			}
		});	
		/*// console.log(ids);*/
		swal({
                        title: "",
                        text: "Remove selected rows?",
                        type: "info",
                        showCancelButton: true,
                        confirmButtonText: "Remove",
                        cancelButtonText: "Cancel",
                        reverseButtons: true,
                    }).then(function(isConfirm) {
                        if (isConfirm) {
                            	$.ajax({
					url: Routing.generate('case_delete_ajax'),
					type: 'POST',
					data: {param1: ids},
					success:function(data){
						if(data.error){
							swal('',data.error,'error');
						}
						else{
							$('#txtId').val(data.id);
							$('#txtName').val(data.name);
							$('.chkItemAll').prop({'checked': false});
							$btn.button('reset');
							table.ajax.reload();
							swal('',data.msg,'success');
						}
						$('#pnAdd').slideUp();
					},
					error:function(data){
						if(data.error){
							console.log(data.id);
							swal('',data.error,'error');
						}
						$btn.button('reset');
					}
				});
                            		$('.btnDelete').addClass('hidden');
				$('.btnAddPage').removeClass('hidden');
                        	}
                    });
                	$btn.button('reset');		
	});
	/*/////Fin definición persist data (Delete method)*/


	/*/////Select checkboxes (All)*/
	$(document).on('click', '.chkItemAll', function(event) {
           
		/*/////Definición de variables*/
		var id=$(this).children().first().children().attr('id');
		$('#txtId').val('');
		$('#txtName').val('');
		$('#pnAdd').slideUp();
		if ($(this).is(':checked')) {
			$('.chkItem').each(function() {
				$('.btnAddPage').addClass('hidden');
				$('.btnDelete').removeClass('hidden');
				$('.btnAction').removeClass('hidden');
                                $('#btnSaveTop').addClass('hidden');
                                $('#btnCancelTop').addClass('hidden');
				$(this).prop({'checked': true});
			});	
		} 
		else {
			$('.chkItem').each(function() {
				$('.btnAddPage').removeClass('hidden');
				$('.btnDelete').addClass('hidden');
				$('.btnAction').addClass('hidden');
				$(this).prop({'checked': false});
			});
		}			
	});
	/*/////Fin select checkboxes (All)*/


	/*/////Select checkboxes (Single)*/
	$(document).on('click', '.chkItem', function(event) {

		/*/////Definición de variables*/
		var text = $(this).prop('tagName');
		var total=0;
		var selected=0;
		$('#pnAdd').slideUp();
		console.log(text);
		if (text=='INPUT' ) {
			var id=$(this).parent().attr('id');
			/*// var probability=$('#txtProbability');*/
			if ($(this).is(':checked')) {
				$('.btnAddPage').addClass('hidden');
				$('.btnDelete').removeClass('hidden');
				$('.btnAction').removeClass('hidden');
                                 $('#btnSaveTop').addClass('hidden');
                                 $('#btnCancelTop').addClass('hidden');
                                 
				$(this).prop({'checked': true});
			} 
			else {
				$('.btnAddPage').removeClass('hidden');
				$('.btnDelete').addClass('hidden');
				$('.btnAction').addClass('hidden');
				$(this).prop({'checked': false});
			}$('.chkItem').each(function() {
				total++;
				if ($(this).is(':checked')) {
					selected++;
					$('.btnAddPage').addClass('hidden');
					$('.btnDelete').removeClass('hidden');
					$('.btnAction').removeClass('hidden');
				}
			});	
		}
		
		if(total==selected){
			$('.chkItemAll').prop({'checked': true});
		}
		else{
			$('.chkItemAll').prop({'checked': false});	
		}
	});
	/*/////Fin select checkboxes (Single)*/

	/*/////Contadores para agregar o eliminar telefono, email y direccion*/
	var numPhones = 0;
	var numEmail = 0;
	
	$('.txtPhone').each(function(index, el) {
		numPhones++;
	});
	$('.txtEmail').each(function(index, el) {
		numEmail++;
	});
	$('.txtAddress').each(function(index, el) {
		numAddress++;
	});
	/*/////Fin de contadores para agregar o eliminar telefono, email y direccion*/


	/*/////Agregar/remover telefonos*/
	$(document).on('click', '#plusPhone', function(event) {
		numPhones++;
		var optionsPhoneType = $('.firstPhoneType').html();
		$('.phonesType').append('<div style="margin-top:27px;"><select id="types-'+numPhones+'" style="width:100%;margin-top:25px !important;" name="phoneType[]" class="input-sm form-control validateInput dpbTipoPhone">'+optionsPhoneType+'</select></div>');
		$('.phonesText').append('<input id="phones-'+numPhones+'" style="margin-top:25px;" type="text" name="phone[]" class="input-sm form-control validateInput txtPhone">');
		$('.phonesExtension').append('<input id="extension-'+numPhones+'" style="margin-top:25px;" type="text" name="phoneExt[]" class="input-sm form-control txtExtension">');
		$('.addPhone').append('<button id="deletePhone-'+numPhones+'" style="margin-top:27px;" class="btn removePhone btn-danger"><i class="fa fa-remove"></i></button>');
		$('#types-'+numPhones).select2();
		return false;
	});
	$(document).on('click', '.removePhone', function(event) {
		var numDel = $(this).attr('id');
		numDelArray= numDel.split('-');
		$('#types-'+numDelArray[1]).parent().remove();
		$('#phones-'+numDelArray[1]).remove();
		$('#extension-'+numDelArray[1]).remove();
		$('#deletePhone-'+numDelArray[1]).remove();
		return false;
	});
	/*/////Fin de agregar/remover telefonos*/


	/*/////Agregar/remover email*/
	$(document).on('click', '#plusEmail', function(event) {
		numEmail++;
		
		$('.emailText').append('<input id="email-'+numEmail+'" type="text" name="email[]" style="margin-top:25px ;" class="input-sm form-control validateInput validateInputEmail txtEmail">');
		$('.addEmail').append('<button id="deleteEmail-'+numEmail+'" style="margin-top:27px;" class="btn removeEmail btn-danger"><i class="fa fa-remove"></i></button>');
		return false;
	});
	$(document).on('click', '.removeEmail', function(event) {
		var numDel = $(this).attr('id');
		numDelArray= numDel.split('-');
		$('#email-'+numDelArray[1]).remove();
		$('#deleteEmail-'+numDelArray[1]).remove();
		
		return false;
	});
	/*/////Fin de agregar/remover email*/


	/*/////Agregar/remover direccion*/
	$(document).on('click', '#plusAddress', function(event) {
		numAddress++;
		var optionsCity = $('.dpbCityFirst').html();
		var optionsState = $('.dpbStateFirst').html();
		$('.address').append('<input style="margin-top:25px ;" id="address-'+numAddress+'" type="text" name="address[]" class="input-sm form-control validateInput txtAddress">');
		$('.city').append('<div style="margin-top:27px;"><select style="margin-top:25px; width:100%;" id="city-'+numAddress+'" name="addressCity[]" class="input-sm form-control dpbCity">'+optionsCity+' </select></div>');
		$('.state').append('<div style="margin-top:27px;"><select style="margin-top:25px; width:100%;" id="state-'+numAddress+'" name="addressDepartamento[]" class="input-sm form-control dpbState">'+optionsState+' </select></div>');
		/*//$('.state').append('<input style="margin-top:25px ;" id="state-'+numAddress+'" type="text" name="" class="input-sm form-control validateInput txtState">');*/
		$('.addAddress').append('<button id="deleteAddress-'+numAddress+'" style="margin-top:25px;" class="btn removeAddress btn-danger"><i class="fa fa-remove"></i></button>');
		$('#city-'+numAddress).select2();
		$('#state-'+numAddress).select2();
		return false;
	});
	$(document).on('click', '.removeAddress', function(event) {
		var numDel = $(this).attr('id');
		numDelArray= numDel.split('-');
		$('#address-'+numDelArray[1]).remove();
		$('#city-'+numDelArray[1]).parent().remove();
		$('#state-'+numDelArray[1]).parent().remove();
		$(this).remove();
		return false;
	});
	/*/////Fin de agregar/remover direccion*/


	/*/////Agregar/remover direccion*/
	$(document).on('change', '.dpbStateFirst,.dpbState', function(event) {		
		var id=$(this).val();
		var idHtml=$(this).attr('id');
		/*// console.log(id);*/
		/*// console.log(idHtml);*/
		var domElement=$(this);
		if (idHtml=='') {
			$.ajax({
				url: Routing.generate('admin_provider_search_cities_ajax'),
				type: 'POST',
				data: {param1: id},
				success:function(data){
					if(data.error){
						swal('',data.error,'error');
					}
					else{
						/*// console.log(data.ciudades[0]);*/
					}
					/*// console.log(data.ciudades[0][0]);*/
					/*// console.log(data.ciudades[0][1]);*/
					/*// console.log('assax'+data.ciudades.length);*/
					$('.dpbCityFirst').select2('destroy');
					$('.dpbCityFirst').html('');
					for (var i = 0; i <data.ciudades.length; i++) {
						if (i==0) {
							$('.dpbCityFirst').append('<option selected value="'+data.ciudades[0][0]+'">'+data.ciudades[0][1]+'</option>');
						} 
						else {
							$('.dpbCityFirst').append('<option value="'+data.ciudades[0][0]+'">'+data.ciudades[0][1]+'</option>');
						}
					}
					$('.dpbCityFirst').select2();
					/*//$('#pnAdd').slideUp();*/
				},
				error:function(data){
					if(data.error){
						/*// console.log(data.id);*/
						swal('',data.error,'error');
					}
					$btn.button('reset');
				}
			});
		} else {
			$.ajax({
				url: Routing.generate('admin_provider_search_cities_ajax'),
				type: 'POST',
				data: {param1: id},
				success:function(data){
					if(data.error){
						swal('',data.error,'error');
					}
					else{
						/*// console.log(data.ciudades[0]);*/
					}
					/*// console.log(data.ciudades[0][0]);*/
					/*// console.log(data.ciudades[0][1]);*/
					/*// console.log('assax'+data.ciudades.length);*/
					var idHtmlArray=idHtml.split('-');
					/*// console.log(idHtml);*/
					/*// console.log(idHtmlArray);*/
					$('#city-'+idHtmlArray[1]).select2('destroy');
					$('#city-'+idHtmlArray[1]).html('');
					/*// console.log('#city-'+idHtmlArray[1]);*/
					for (var i = 0; i <data.ciudades.length; i++) {
						if (i==0) {
							$('#city-'+idHtmlArray[1]).append('<option selected value="'+data.ciudades[0][0]+'">'+data.ciudades[0][1]+'</option>');
						} 
						else {
							$('#city-'+idHtmlArray[1]).append('<option value="'+data.ciudades[0][0]+'">'+data.ciudades[0][1]+'</option>');
						}
					}
					$('#city-'+idHtmlArray[1]).select2();
					/*//$('#pnAdd').slideUp();*/
				},
				error:function(data){
					if(data.error){
						console.log(data.id);
						swal('',data.error,'error');
					}
					$btn.button('reset');
				}
			});
		}
		return false;
	});
	/*/////Fin de agregar/remover direccion*/
});	
