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

	
	$(document).on('click', '#btnSave', function(event) {
        var btn = $(this).button('loading');
        var id=$('#txtId');
        var name=$('#txtName');
        var icon=$('#txtIcon');
        var table = $('#activityList').DataTable();
        var errores = 0;
/*        //Contador de errores, para antes de la persistencia*/

        $('.validateInput').each(function() {
            console.log($(this).val());
            
            if (!required($(this))) {
                errores++;
            }
        });

        if (errores==0) {
            var data = {
                            param1: id.val(),
                            param2 : name.val(),
                            param3 : icon.val()
                        };

            $.ajax({
                data: data,
                url: Routing.generate('admin_activity_type_save_ajax'),
                type: 'POST',
                dataType: 'json',
                success: function (response)
                {
                    
                    
                    if(!response.error){
                        swal('', response.msg,'success');
                        $('#txtId').val('');
                        $('#txtName').val('');
                        $('#txtIcon').val('');
                        
                        table.ajax.reload();
                        $('#pnAdd').slideToggle();
                    } else {
                        swal('', response.error, 'error');
                        /*btn.button('reset');*/
                    }                    
                    
                    btn.button('reset');
                    
                },
                error:function(response){
                    if(response.msg.error){
                        swal('', response.msg.error, 'error');
                    }
                    btn.button('reset');
                }
            });
            
            return false;        
        }
        else {
            swal('','Fields in red are required!','error');
            btn.button('reset');
        }                
       
    });    
	/*/////Fin definición persist data (Save method)*/


	/*/////Persist datatable (Edit method)*/
	$(document).on('click', '#activityList>tbody>tr>td:nth-child(2),#activityList>tbody>tr>td:nth-child(3)', function(event) {
		/*/////Definición de variables*/
		var text = $(this).prop('tagName');
		/*// console.log(text);*/
		var id=$(this).parent().children().first().children().attr('id');
		/*// console.log(id);*/
		/*// var idArray = id.split('-');*/
		/*// console.log(idArray);*/
		var idForm=$('#txtId').val();
		/*// var idForm=$('#txtId2').val();*/
		var selected = 0;
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
				url: Routing.generate('admin_retrieve_activity_types_ajax'),
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
							$('#txtIcon').val(data.icon);
							
                                                        $('#txtIcon').next().children().removeClass();
                                                        $('#txtIcon').next().children().addClass('fa '+data.icon);
                                                        
							$('#pnAdd').slideDown();
							
							$('.btnAddPage').addClass('hidden');
							/*$('#activityList').parent().hide();*/
							$('#btnSaveTop').removeClass('hidden');
							$('#btnCancelTop').removeClass('hidden');
							
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
                                        /*seguimientoGeneral(data.id, numPedidos,null,3);
                                        $('#addTag').removeClass('hidden');
                                        $('#addedTags').removeClass('hidden');
                                        $('#addedFiles').removeClass('hidden');
                                        $('#filterTag').addClass('hidden');
                                        $('#addFile').removeClass('hidden');
                                        $('#btnLoadMore').removeClass('hidden');*/
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
				$('.btnAdd').addClass('hidden');
				$('.btnDelete').removeClass('hidden');
				$(this).prop({'checked': true});
			});	
		} 
		else {
			$('.chkItem').each(function() {
				$('.btnAdd').removeClass('hidden');
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
				$('.btnAdd').addClass('hidden');
				$('.btnDelete').removeClass('hidden');
				$(this).prop({'checked': true});
			} 
			else {
				$('.btnAdd').removeClass('hidden');
				$('.btnDelete').addClass('hidden');
				$(this).prop({'checked': false});
			}$('.chkItem').each(function() {
				total++;
				if ($(this).is(':checked')) {
					selected++;
					$('.btnAdd').addClass('hidden');
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
