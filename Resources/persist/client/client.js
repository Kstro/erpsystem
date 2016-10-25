$(document).ready(function() {
	$('.dpbCityFirst').select2();
	$('.dpbStateFirst').select2();
        var numPedidos=0;
        $('.btnAddCommentGen').attr('id',1);
	$("#txtId1").val('');
	$("#txtId2").val('');
	var numAddress = 0;
        var numContacts = 0;
	/*/////Persist datatable (Save method)*/
	var filesSelectedPrev = document.getElementById("file").files;
	/*// console.log(filesSelectedPrev[0]);*/
	
	$(document).on('click', '#btnSaveTop', function(event) {
		$('#frmClient').submit();
	});

	$(document).on('change', '#file', function(event) {
		var filesSelected = document.getElementById("file").files;
		if(filesSelectedPrev[0]=="undefined"){
			filesSelectedPrev = document.getElementById("file").files;
		}
			var fileToLoad = filesSelected[0];
		          var fileReader = new FileReader();
		          if ((filesSelected[0].size<=4096000)){
		          	fileReader.onload = function(fileLoadedEvent) {
			          	srcData = fileLoadedEvent.target.result;
			            	$('#imgTest').attr('src', srcData);
			        	};
			        	var imgBase64 = $('#imgTest').attr('src');
			        	fileReader.readAsDataURL(fileToLoad);
				$('#imgTest').show();
		          }
		          else{
		          	$(this).val('');
		          	$('#imgTest').attr('src', '');
		          	$('#imgTest').hide();
		          	swal('',$('.imageError').html(),'error');
		          }
	});
	$('#frmClient').on('submit',(function(event) {
		/*/////Definición de variables*/
		event.preventDefault();
		var $btn = $('#btnSave').button('loading');
		var $btnT = $('#btnSaveTop').button('loading');
  		var errores = 0;
  		/*//Contador de errores, para antes de la persistencia*/
  		var erroresEmail = 0;
  		/*//Contador de errores, para antes de la persistencia*/
		$('.validateInput').each(function() {
		 	if (!required($(this))) {
		 		$(this).addClass('errorform');
		 		errores++;
		 	}
		});
		$('.validateInputEmail').each(function() {
		 	if (!isValidEmailAddress($(this).val())) {
		 		$(this).addClass('errorform');
		 		erroresEmail++;
		 	}
		});
		if (errores==0) {
			if (erroresEmail==0) {
				$.ajax({
					url: Routing.generate('admin_client_save_ajax'),
					type: "POST",            
					data: new FormData(this),
					contentType: false,    
					cache: false,           
					processData:false,   
					success: function(data)  
					{
						/*//$('#loading').hide();*/
						$("#message").html(data);
						$("#txtId1").val(data.id1);
						$("#txtId2").val(data.id2);
						if(data.msg){
							swal('',data.msg,'success');
							var table = $('#clienteList').DataTable();
							/*//id.val(data.id1);*/
							$('#txtId1').val('');
							$('#txtName').val('');
							/*// $('#txtProbability').val('10');*/
							/*// probability.slider('setValue', 10);*/
                                                        $('#pnAdd').addClass('hidden');
                                                        $('#clienteList').show();
							$('.btnAddPage').click();
							$btn.button('reset');
							$btnT.button('reset');
						}
						if(data.error){
							/*// console.log(data.id);*/
							swal('',data.error,'error');
							$btn.button('reset');
							$btnT.button('reset');
						}
						table.ajax.reload();
						$btn.button('reset');
						$btnT.button('reset');
						$('#btnSaveTop').addClass('hidden');
						$('#btnCancelTop').addClass('hidden');
						$('.btnAddPage').removeClass('hidden');
						/*// console.log('updata table');*/
						/*// console.log(table);*/
					},
					error:function(data) {
						/* Act on the event */
						$btn.button('reset');
						$btnT.button('reset');
					}
				});
			}
			else{
				var emailFields = $('.emailFields').html();
				swal('',emailFields,'error');
				$btn.button('reset');
				$btnT.button('reset');	
			}
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
	$(document).on('click', '#clienteList>tbody>tr>td:nth-child(2),#clienteList>tbody>tr>td:nth-child(3),#clienteList>tbody>tr>td:nth-child(4),#clienteList>tbody>tr>td:nth-child(5)', function(event) {
		/*/////Definición de variables*/
		var text = $(this).prop('tagName');
		/*// console.log(text);*/
		var id=$(this).parent().children().first().children().attr('id');
		/*// console.log(id);*/
		var idArray = id.split('-');
		console.log(idArray);
		var idForm=$('#txtId1').val();
		/*// var idForm=$('#txtId2').val();*/
		var selected = 0;
		/*//Cambiar nombre del panel heading para Modify*/
		$('.pnHeadingLabelAdd').addClass('hidden');
		$('.pnHeadingLabelEdit').removeClass('hidden');


                numPedidos=1;
                mostrarocultar(numPedidos);
		/*console.log('asdcdsc');*/
		/*// return false;*/
		/*se limpia el seguimineto previo*/
		$('#comentarios').show();
		$('#wallmessages').show();
		$('#wallmessages').html('');
		$('#addedTags').html('');//limpiar tags anteriores
		$('#addedFiles').html('');//limpiar archivos anteriores
		$('#primeraFecha').val('');
		$('#iteracion').val(numPedidos);
		/*// console.log(id);*/
		console.log(idArray[0]);
		console.log(idArray[1]);
		$('.chkItem').each(function() {
			if ($(this).is(':checked')) {
				selected++;
			}
		});	
		if (text=='TD' && id!=idForm && selected==0) {
			$.ajax({
				url: Routing.generate('admin_client_retrieve_ajax'),
				type: 'POST',
				data: {param1: idArray[0],param2:idArray[1]},
				success:function(data){
					if(data.error){
						swal('',data.error,'error');
						id.val(data.id);
					}
					else{
						/*// console.log(data);*/
						$('#txtId1').val(data.id1);
						$('#txtId2').val(data.id2);
						$('#dpbTitulo').val(data.titulo);
						$('#txtName').val(data.nombre);
						$('#txtApellido').val(data.apellido);
						$('#txtCompania').val(data.compania);
						/*// console.log(data.addressArray);*/
						var numDirecciones = data.addressArray.length;
						var numTelefonos = data.phoneArray.length;
						var numCorreos = data.emailArray.length;
						var numContactos = data.contactoIdArray.length;
						$('.dpbTipoPersona').val(data.satisfaccion).change().trigger("change");
						/*// Direcciones*/
						for (var i = 0; i < numDirecciones; i++) {
							/*// console.log(i);*/
							/*// console.log(data.addressArray[i]);*/
							switch(i){
								case 0:
									$(".dpbStateFirst").val(data.stateArray[i]).trigger("change");
									$(".dpbCityFirst").val(data.cityArray[i]).trigger("change");
									$('.txtAddressFirst').val(data.addressArray[i]);
								break;
								default:
									$('#plusAddress').click();
									$("#state-"+(numAddress)).val(data.stateArray[i]).trigger("change");
									$("#city-"+(numAddress)).val(data.cityArray[i]).trigger("change");
									$('#address-'+(numAddress)).val(data.addressArray[i]);
								break;
							}
						}
						/*// Telefonos*/
						for (var i = 0; i < numTelefonos; i++) {
							/*// console.log(i);*/
							/*// console.log(data.addressArray[i]);*/
							switch(i){
								case 0:
									$(".firstPhoneType").val(data.typePhoneArray[i]).trigger("change");
									
									$('.firstPhoneTxt').val(data.phoneArray[i]);
									$('.firstPhoneExtension').val(data.extPhoneArray[i]);
								break;
								default:
									$('#plusPhone').click();
									/*//$('#types-'+(numPhones)).val(data.typePhoneArray[i]).change();*/
									$('#types-'+(numPhones)).val(data.typePhoneArray[i]).trigger("change");
									
									$('#phones-'+(numPhones)).val(data.phoneArray[i]);
									$('#extension-'+(numPhones)).val(data.extPhoneArray[i]);
								break;
							}
						}
						/*// Correos*/
						for (var i = 0; i < numCorreos; i++) {
							/*// console.log(i);*/
							/*// console.log(data.addressArray[i]);*/
							switch(i){
								case 0:
									$('.txtEmailFirst').val(data.emailArray[i]);
								break;
								default:
									$('#plusEmail').click();
									$('#email-'+(numEmail)).val(data.emailArray[i]);
								break;
							}
						}
						/*// Contactos*/
						for (var i = 0; i < numContactos; i++) {
							/*// console.log(i);*/
							/*// console.log(data.addressArray[i]);*/
							switch(i){
								case 0:
									$('#contactos').html('<option value="'+data.contactoIdArray[i]+'">'+data.contactoNombreArray[i]+'</option>');
								break;
								default:
									$('#plusContact').click();
									$('#contact-'+numContacts).html('<option value="'+data.contactoIdArray[i]+'">'+data.contactoNombreArray[i]+'</option>');
                                                                        
								break;
							}
						}
						if(data.src!=''){
							$('#imgTest').attr('src','../../../photos/accounts/'+data.src);	
						}
						else{
							$('#imgTest').attr('src','http://placehold.it/250x250');
						}

						/*// $('.dpbTipoPersona').val(data.entidad).change().trigger("change");*/
						/*// $('.dpbIndustria').val(data.industria).change().trigger("change");*/
												
						$('.txtWebsite').val(data.website);
						$('#pnAdd').removeClass('hidden');
						$('.btnAddPage').addClass('hidden');
						//$('#clienteList').parent().hide();
						$('#btnBack').removeClass('hidden');
						$('#btnCancelTop').removeClass('hidden');
						$('#btnSaveTop').removeClass('hidden');
                                                /*seguimiento(data.id1, numPedidos,null);*/
                                                seguimientoGeneral(data.id1, numPedidos,null,1);
						/*cargarTags();*/
                                                var addItem = '';
                                                for (var i = 0; i < data.tags.length; i++) {
                                                    /*console.log(i);*/
                                                    addItem='<div class="col-xs-1" style="vertical-align:middle;"><a id="'+data.tags[i].id+'" href="" class="tagDelete"><i style="margin-top:3px;vertical-align:middle;" class="fa fa-remove"></i></a></div><div class="col-xs-10">'+data.tags[i].nombre+'</div>';
                                                    $('#addedTags').append(addItem);
                                                }
                                                
                                                for (var i = 0; i < data.docs.length; i++) {
                                                    /*console.log(i);*/
                                                    if(data.docs[i].estado==1){
                                                        var addItem='<div class="col-xs-1" style="vertical-align:middle;">';
                                                        
                                                            addItem+='<a id="'+data.docs[i].id+'" href="" class="fileDelete">';
                                                                                                                
                                                            addItem+='<i style="margin-top:3px;vertical-align:middle;" class="fa fa-remove"></i>';
                                                        
                                                            addItem+='</a>';
                                                                                                                

                                                        addItem+='</div><div class="col-xs-10">';
                                                        
                                                            addItem+='<a target="_blank" href="../../../files/accounts/';
                                                            addItem+=data.docs[i].nombre;
                                                            addItem+='">';
                                                        
                                                        addItem+=data.docs[i].nombre;
                                                     
                                                            addItem+='</a>';
                                                     
                                                        addItem+='</div>';
                                                        $('#addedFiles').append(addItem);
                                                    }
                                                }
                                                
						/*//seguimientoComet(data.id1);*/
						$('#addTag').removeClass('hidden');
						$('#addedTags').removeClass('hidden');
						$('#addedFiles').removeClass('hidden');
						$('#filterTag').addClass('hidden');
                                                $('#addFile').removeClass('hidden');
                                                $('#btnLoadMoreFiles').removeClass('hidden');
					}					
				},
				error:function(data){
					if(data.error){
						/*// console.log(data.id);*/
						swal('',data.error,'error');
					}
				}
			});
		} 
		else {
			if(id==idForm && selected==0){
                            $('#pnAdd').slideDown();
			}
		}
	});
	/*/////Fin definición persist data (Edit method)*/


	/*/////Persist datatable (Delete method)*/
	$(document).on('click', '.btnDelete', function(event) {
		var $btn = $(this).button('loading');
		/*/////Definición de variables*/
		var id=$(this).children().first().children().attr('id');
		var ids=[];
		var table = $('#clienteList').DataTable();
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
					url: Routing.generate('admin_providers_account_delete_ajax'),
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
				$(this).prop({'checked': true});
			});	
		} 
		else {
			$('.chkItem').each(function() {
				$('.btnAddPage').removeClass('hidden');
				$('.btnDelete').addClass('hidden');
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
				$(this).prop({'checked': true});
			} 
			else {
				$('.btnAddPage').removeClass('hidden');
				$('.btnDelete').addClass('hidden');
				$(this).prop({'checked': false});
			}$('.chkItem').each(function() {
				total++;
				if ($(this).is(':checked')) {
					selected++;
					$('.btnAddPage').addClass('hidden');
					$('.btnDelete').removeClass('hidden');
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
	numContacts= 0;
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


        /*/////Agregar/remover contactos*/
	$(document).on('click', '#plusContact', function(event) {
		numContacts++;
		//var optionsPhoneType = $('.firstPhoneType').html();
		$('.contacts').append('<div style="margin-top:27px;"><select id="contact-'+numContacts+'" style="width:100%;margin-top:25px !important;" name="contactos[]" class="input-sm form-control validateInput dpbContactos"></select></div>');
//          	$('.phonesText').append('<input id="phones-'+numPhones+'" style="margin-top:25px;" type="text" name="phone[]" class="input-sm form-control validateInput txtPhone">');
//		$('.phonesExtension').append('<input id="extension-'+numPhones+'" style="margin-top:25px;" type="text" name="phoneExt[]" class="input-sm form-control txtExtension">');
		$('.addContact').append('<button id="deleteContact-'+numContacts+'" style="margin-top:27px;" class="btn removeContact btn-danger"><i class="fa fa-remove"></i></button>');
		//$('#contacts-'+numContacts).select2();
                $('#contact-'+numContacts).select2({
                    ajax: {
                           url: Routing.generate('busqueda_contacto_select_info'),
                           dataType: 'json',
                           delay: 250,
                           data: function (params) {
                             return {
                               q: params.term, // search term
                               page: params.page
                             };
                           },
                           processResults: function (data, params) {
                                               var select2Data = $.map(data.data, function (obj) {
                                                   obj.id = obj.id;
                                                   obj.text = obj.nombre;

                                                   return obj;
                                               });

                                               return {
                                                   results: select2Data
                                                   
                                                   
                                               };
                                           },
                           cache: true
                         },
                         escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                         minimumInputLength: 1,
                         templateResult: formatRepo, // omitted for brevity, see the source of this page
                         // templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
                       });
		return false;
	});
	$(document).on('click', '.removeContact', function(event) {
		var numDel = $(this).attr('id');
		numDelArray= numDel.split('-');
		$('#contact-'+numDelArray[1]).parent().remove();
		$('#deleteContact-'+numDelArray[1]).remove();
		return false;
	});
	/*/////Fin de agregar/remover contactos*/

	/*/////Agregar/remover direccion*/
	$(document).on('change', '.dpbStateFirst,.dpbState', function(event) {		
		var id=$(this).val();/*//Valor id estado*/
		var idHtml=$(this).attr('id');/*//Valor id objecto html*/
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
