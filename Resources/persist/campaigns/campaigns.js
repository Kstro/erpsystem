$(document).ready(function() {
	$('.dpbCityFirst').select2();
	$('.dpbStateFirst').select2();
	$("#txtId").val('');
	$('.btnAddCommentGen').attr('id',3);/*Campañas*/
	var numAddress = 0;
        var numPedidos = 0;
	/*/////Persist datatable (Save method)*/
	
	/*// console.log(filesSelectedPrev[0]);*/
	
	$(document).on('click', '#btnSaveTop', function(event) {
		$('#frmCampaigns').submit();
	});

	
	$('#frmCampaigns').on('submit',(function(event) {
		/*/////Definición de variables*/
		event.preventDefault();
		var $btn = $('#btnSave').button('loading');
		var $btn1 = $('#btnSaveTop').button('loading');
  		var errores = 0;
  		/*//Contador de errores, para antes de la persistencia*/
  		var idUser=[];
  		var textFechaInicio= $('#txtFechaInicio').val();
  		var textFechaFin=$('#txtFechaFin').val();
  		var idAct=$('#txtId').val();
		$('.validateInput').each(function() {
		 	if (!required($(this))) {
		 		$(this).addClass('errorform');
		 		errores++;
		 	}
		});
		$('.validateSelect').each(function() {
		 	if (!required($(this))) {
		 		$(this).next().children().children().addClass('errorform');
		 		errores++;
		 	}
		});
		var dataForm = new FormData(this);
		if (errores==0) {
			$('.dpbResponsable').each(function() {
			 	id = $(this).val();
			 	idUser.push(id);
			});
			console.log(idUser);
			$.ajax({
				url: Routing.generate('admin_campaigns_save_ajax'), 
				type: "POST",
				data: dataForm,
				contentType: false,
				cache: false,
				processData:false,
				success: function(data)
				{
					$("#txtId").val(data.id);
					if(data.msg){
						swal('',data.msg,'success');
						var table = $('#tasksList').DataTable();
						$('.btnAddPage').click();
						$('.btnAddPage').removeClass('hidden');
						table.ajax.reload();
					}
					if(data.error){
						swal('',data.error,'error');
					}
					$btn.button('reset');
					$btn1.button('reset');
				},
				error:function(data) {
					/* Act on the event */
					$btn.button('reset');
					$btn1.button('reset');
				}
			});
		}
		else {
			var requiredFields = $('.requiredFields').html();
			swal('',requiredFields,'error');
			$btn.button('reset');
			$btn1.button('reset');
		}
		event.preventDefault();
		return false;
	}));
	/*/////Fin definición persist data (Save method)*/


	/*/////Persist datatable (Edit method)*/
	$(document).on('click', '#tasksList>tbody>tr>td:nth-child(2),#tasksList>tbody>tr>td:nth-child(3),#tasksList>tbody>tr>td:nth-child(4),#tasksList>tbody>tr>td:nth-child(5),#tasksList>tbody>tr>td:nth-child(6),#tasksList>tbody>tr>td:nth-child(7)', function(event) {
		/*/////Definición de variables*/
		var text = $(this).prop('tagName');
		/*// console.log(text);*/
		var id=$(this).parent().children().first().children().attr('id');                
		/*// console.log(id);*/
		/*// var idArray = id.split('-');*/
		/*// console.log(idArray);*/
		var idForm=$('#txtId1').val();
		/*// var idForm=$('#txtId2').val();*/
		var selected = 0;
                $('#addedFiles').html('');
                $('#wallmessages').html('');
                $('#wallmessages').removeClass('hidden');
                $('#comentarios').removeClass('hidden');
                numPedidos=1;
		/*//Cambiar nombre del panel heading para Modify*/
		$('.pnHeadingLabelAdd').addClass('hidden');
		$('.pnHeadingLabelEdit').removeClass('hidden');

		/*// console.log(id);*/
		/*// console.log(idArray[0]);*/
		/*// console.log(idArray[1]);*/
		$('.chkItem').each(function() {
			if ($(this).is(':checked')) {
				selected++;
			}
		});	
		if (text=='TD' && id!=idForm && selected==0) {
			$.ajax({
				url: Routing.generate('admin_retrieve_campaign_ajax'),
				type: 'POST',
				data: {param1: id},
				success:function(data){

					if(data.error){
						swal('',data.error,'error');
						id.val(data.id);
					}
					else{
						if (data.id!=0) {
							/*// console.log('if');*/
							/*// $('#cuentaActividades').select2('destroy');*/
							/*// $('#cuentaActividades').val(data.cuentaId).trigger('change');	*/
							$('#txtId').val(data.id);
							$('#txtName').val(data.name);
							$('#tipoCampania').val(data.tipoCampania).trigger('change');
							if (data.personaId!=0) {
								/*// $('#responsableCampania').append('<option value="'+data.personaId+'">'+data.personaNombre+'</option>');*/
							}
							
							$('#estadoCampania').val(data.estadoCampania).trigger('change');
							$('#txtFechaInicio').val(data.fechaInicio);
							$('#txtFechaFin').val(data.fechaFin);
							$('#txtDescripcion').val(data.descripcion);

							$('#pnAdd').show();
							$('.btnAddPage').addClass('hidden');
							$('#tasksList').parent().hide();
							$('#btnSaveTop').removeClass('hidden');
							$('#btnCancelTop').removeClass('hidden');
							/*// console.log('end');*/

							/*var numDirecciones = data.responsables.length;

							for (var i = 0; i < numDirecciones; i++) {
								/* console.log(i);*/
								/*// console.log(data.addressArray[i]);*/
								/*switch(i){
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
							}*/
                                                    for (var i = 0; i < data.docs.length; i++) {
                                                        /*console.log(i);*/
                                                        if(data.docs[i].estado==1){
                                                            var addItem='<div class="col-xs-1" style="vertical-align:middle;">';

                                                                addItem+='<a id="'+data.docs[i].id+'" href="" class="fileDelete">';

                                                                addItem+='<i style="margin-top:3px;vertical-align:middle;" class="fa fa-remove"></i>';

                                                                addItem+='</a>';


                                                            addItem+='</div><div class="col-xs-10">';

                                                                addItem+='<a target="_blank" href="../../../files/'+data.path+'/';
                                                                addItem+=data.docs[i].nombre;
                                                                addItem+='">';

                                                            addItem+=data.docs[i].nombre;

                                                                addItem+='</a>';

                                                            addItem+='</div>';
                                                            $('#addedFiles').append(addItem);
                                                        }
                                                    }
						}
						else{
							/*// console.log('else');*/
							$('#cuentaActividades').html('<option value="0"></option>');
						}
						
                                                
						
						/*// if (data.estado!=0) {*/
						/*// //Cancelado 3 por defecto en la base*/
						// 	/*// console.log(data);*/
						// 	$('#txtId').val(data.id);
						// 	$('#txtName').val(data.nombre);
						// 	$('#txtDescripcion').val(data.descripcion);
						// 	$('#txtFechaInicio').val(data.fechaInicio);
						// 	$('#txtFechaFin').val(data.fechaFin);
							
						// 	/*// console.log(data.personaArray);*/
						// 	var numPersonas = data.personaArray.length;
							
						// 	$('#estado').val(data.estado).change().trigger("change");
						// 	/*// Direcciones*/
						// 	for (var i = 0; i < numPersonas; i++) {
						// 		/*// console.log(i);*/
						// 		/*// console.log(data.addressArray[i]);*/
						// 		switch(i){
						// 			case 0:
						// 				$(".firstResponsable").val(data.personaArray[i]).trigger("change");
						// 				$(".firstTipoRecordatorio").val(data.tipoRecordatorioArray[i]).trigger("change");
						// 				$(".firstTiempoRecordatorio").val(data.tiempoRecordatorioArray[i]).trigger("change");									
						// 			break;
						// 			default:
						// 				$('#plusAddress').click();
						// 				$("#state-"+(numAddress)).val(data.personaArray[i]).trigger("change");
						// 				$("#city-"+(numAddress)).val(data.tipoRecordatorioArray[i]).trigger("change");
						// 				$('#address-'+(numAddress)).val(data.tiempoRecordatorioArray[i]);
						// 			break;
						// 		}
						// 	}
							
						// 	$('#pnAdd').show();
						// 	$('.btnAddPage').addClass('hidden');
						// 	$('#tasksList').parent().toggle();
						// 	$('#btnBack').removeClass('hidden');
						// 	$('#btnCancelTop').removeClass('hidden');
						// 	if (data.estado==3) {
						// 	/*//Cancelado 3 por defecto en la base*/
						// 		$('#btnSave').addClass('hidden');
						// 		$('#btnSaveTop').addClass('hidden');
						// 	}
						// 	else{
						// 		$('#btnSave').removeClass('hidden');
						// 		$('#btnSaveTop').removeClass('hidden');
						// 	}
							
						// } else {
						// 	var taskNoEdit = $('#taskNoEdit').html();
						// 	swal('',data.nombre+' '+taskNoEdit,'error');
						// }*/
					}
                                        seguimientoGeneral(data.id, numPedidos,null,3);
                                        $('#addTag').removeClass('hidden');
                                        $('#addedTags').removeClass('hidden');
                                        $('#addedFiles').removeClass('hidden');
                                        $('#filterTag').addClass('hidden');
                                        $('#addFile').removeClass('hidden');
                                        $('#btnLoadMore').removeClass('hidden');
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
		var table = $('#tasksList').DataTable();
		$('.chkItem').each(function() {
			if ($(this).is(':checked')) {
				ids.push($(this).parent().attr('id'));
			}
		});	
		/*// console.log(ids);*/
		var cancelLabel = $('#cancelLabel').html();
		var cancelButtonText = $('#cancelButtonText').html();
		/*// var removeButton = $('#removeButton').html();*/
		var alternateconfirmButtonText = $('#alternateconfirmButtonText').html();
		
		swal({
                        title: "",
                        text: cancelLabel,
                        type: "info",
                        showCancelButton: true,
                        confirmButtonText: alternateconfirmButtonText,
                        cancelButtonText: cancelButtonText,
                        reverseButtons: true,
                        showLoaderOnConfirm: true,
                        preConfirm: function(email) {
		    return new Promise(function(resolve, reject) {
				$.ajax({
					url: Routing.generate('admin_task_cancel_ajax'),
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
							/*// console.log(data.id);*/
							swal('',data.error,'error');
						}
						$btn.button('reset');
					}
				});      
		    });
		  },
		  allowOutsideClick: false
		}).then(function(email) {
		  swal({
		    type: 'success',
		    title: 'Ajax request finished!',
		    html: 'Submitted email: ' + email
		  });
		});
/*
                   
    //                 }).then(function(isConfirm) {
    //                     if (isConfirm) {
    //                         	$.ajax({
				// 	url: Routing.generate('admin_task_cancel_ajax'),
				// 	type: 'POST',
				// 	data: {param1: ids},
				// 	success:function(data){
				// 		if(data.error){
				// 			swal('',data.error,'error');
				// 		}
				// 		else{
				// 			$('#txtId').val(data.id);
				// 			$('#txtName').val(data.name);
				// 			$('.chkItemAll').prop({'checked': false});
				// 			$btn.button('reset');
				// 			table.ajax.reload();
				// 			swal('',data.msg,'success');
				// 		}
				// 		$('#pnAdd').slideUp();
				// 	},
				// 	error:function(data){
				// 		if(data.error){
				// 			// console.log(data.id);
				// 			swal('',data.error,'error');
				// 		}
				// 		$btn.button('reset');
				// 	}
				// });
    //                         		$('.btnDelete').addClass('hidden');
				// $('.btnAddPage').removeClass('hidden');
    //                     	}
    //                 });*/
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
		/*// console.log(text);*/
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

	/*/////Contadores para agregar o eliminar personas*/
	var numPersonas = 0;
	
	
	$('.dpbResponsable').each(function(index, el) {
		numPersonas++;
	});
	/*/////Fin de contadores para agregar o eliminar personas*/


	/*/////Agregar/remover personas*/
	$(document).on('click', '#plusPersona', function(event) {
		numPersonas++;
		var personas = $('.firstResponsable').html();
		var tipoRecordatorio = $('.firstTipoRecordatorio').html();
		var tiempoRecordatorio = $('.firstTiempoRecordatorio').html();
		/*// console.log(personas);*/
		/*// console.log(tipoRecordatorio);*/
		/*// console.log(tiempoRecordatorio);*/
		$('.responsable').append('<div style="margin-top:27px;"><select id="persona-'+numPersonas+'" style="width:100%;margin-top:25px !important;" name="responsable[]" class="input-sm form-control validateInput ">'+personas+'</select></div>');
		$('.tipoRecordatorio').append('<div style="margin-top:27px;"><select id="types-'+numPersonas+'" style="width:100%;margin-top:25px !important;" name="tipoRecordatorio[]" class="input-sm form-control validateInput ">'+tipoRecordatorio+'</select></div>');
		$('.tiempoRecordatorio').append('<div style="margin-top:27px;"><select id="times-'+numPersonas+'" style="width:100%;margin-top:25px !important;" name="tiempoRecordatorio[]" class="input-sm form-control validateInput ">'+tiempoRecordatorio+'</select></div>');
		$('.addPersona').append('<button id="deletePersona-'+numPersonas+'" style="margin-top:25px;" class="btn removePersona btn-danger"><i class="fa fa-remove"></i></button>');
		$('#persona-'+numPersonas).select2();
		$('#types-'+numPersonas).select2();
		$('#times-'+numPersonas).select2();
		return false;
	});
	$(document).on('click', '.removePersona', function(event) {
		var numDel = $(this).attr('id');
		numDelArray= numDel.split('-');
		$('#persona-'+numDelArray[1]).parent().remove();
		$('#types-'+numDelArray[1]).parent().remove();
		$('#times-'+numDelArray[1]).parent().remove();
		$(this).remove();
		return false;
	});
	/*/////Fin de agregar/remover telefonos*/


});	
