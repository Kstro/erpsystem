$(document).ready(function() {
	$('.dpbCityFirst').select2();
	$('.dpbStateFirst').select2();
	$("#txtId").val('');
	
	var numAddress = 0;
	/////Persist datatable (Save method)
	
	// console.log(filesSelectedPrev[0]);
	
	$(document).on('click', '#btnSaveTop', function(event) {
		$('#frmTasks').submit();
	});

	
	$('#frmTasks').on('submit',(function(event) {
		/////Definición de variables
		event.preventDefault();
		var $btn = $('#btnSave').button('loading');
  		var errores = 0;//Contador de errores, para antes de la persistencia
		$('.validateInput').each(function() {
		 	if (!required($(this))) {
		 		$(this).addClass('errorform');
		 		errores++;
		 	}
		});
		if (errores==0) {
			$.ajax({
				url: Routing.generate('admin_tasks_save_ajax'), // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
				contentType: false,       // The content type used when sending data to the server.
				cache: false,             // To unable request pages to be cached
				processData:false,        // To send DOMDocument or non processed data file it is set to false
				success: function(data)   // A function to be called if request succeeds
				{
					//$('#loading').hide();
					// $("#message").html(data);
					$("#txtId").val(data.id);
					if(data.msg){
						swal('',data.msg,'success');
						var table = $('#tasksList').DataTable();
						//id.val(data.id1);
						// $('#txtId').val('');
						// $('#txtName').val('');
						// $('#txtProbability').val('10');
						// probability.slider('setValue', 10);
						$('.btnAddPage').click();
						$btn.button('reset');
						$('.btnAddPage').removeClass('hidden');
							

					}
					if(data.error){
						// console.log(data.id);
						swal('',data.error,'error');
						$btn.button('reset');
					}
					//table.ajax.reload();
					$btn.button('reset');
					// console.log('updata table');
					// console.log(table);
				},
				error:function(data) {
					/* Act on the event */
					$btn.button('reset');
				}
			});
		}
		else {
			var requiredFields = $('.requiredFields').html();
			swal('',requiredFields,'error');
			$btn.button('reset');
		}
		event.preventDefault();
		return false;
	}));
	/////Fin definición persist data (Save method)


	/////Persist datatable (Edit method)
	$(document).on('click', '#tasksList>tbody>tr>td:nth-child(2),#tasksList>tbody>tr>td:nth-child(3),#tasksList>tbody>tr>td:nth-child(4),#tasksList>tbody>tr>td:nth-child(5),#tasksList>tbody>tr>td:nth-child(6),#tasksList>tbody>tr>td:nth-child(7)', function(event) {
		/////Definición de variables
		var text = $(this).prop('tagName');
		// console.log(text);
		var id=$(this).parent().children().first().children().attr('id');
		// console.log(id);
		// var idArray = id.split('-');
		// console.log(idArray);
		var idForm=$('#txtId1').val();
		// var idForm=$('#txtId2').val();
		var selected = 0;
		//Cambiar nombre del panel heading para Modify
		$('.pnHeadingLabelAdd').addClass('hidden');
		$('.pnHeadingLabelEdit').removeClass('hidden');

		// console.log(id);
		// console.log(idArray[0]);
		// console.log(idArray[1]);
		$('.chkItem').each(function() {
			if ($(this).is(':checked')) {
				selected++;
			}
		});	
		if (text=='TD' && id!=idForm && selected==0) {
			$.ajax({
				url: Routing.generate('admin_task_retrieve_ajax'),
				type: 'POST',
				data: {param1: id},
				success:function(data){
					if(data.error){
						swal('',data.error,'error');
						id.val(data.id);
					}
					else{
						if (data.estado!=1) {//Finalizado 1 por defecto en la base
							// console.log(data);
							$('#txtId').val(data.id);
							$('#txtName').val(data.nombre);
							$('#txtDescripcion').val(data.descripcion);
							$('#txtFechaInicio').val(data.fechaInicio);
							$('#txtFechaFin').val(data.fechaFin);
							
							
							// console.log(data.personaArray);
							var numPersonas = data.personaArray.length;
							
							$('#estado').val(data.estado).change().trigger("change");
							// Direcciones
							for (var i = 0; i < numPersonas; i++) {
								// console.log(i);
								// console.log(data.addressArray[i]);
								switch(i){
									case 0:
										$(".firstResponsable").val(data.personaArray[i]).trigger("change");
										$(".firstTipoRecordatorio").val(data.tipoRecordatorioArray[i]).trigger("change");
										$(".firstTiempoRecordatorio").val(data.tiempoRecordatorioArray[i]).trigger("change");									
									break;
									default:
										$('#plusAddress').click();
										$("#state-"+(numAddress)).val(data.personaArray[i]).trigger("change");
										$("#city-"+(numAddress)).val(data.tipoRecordatorioArray[i]).trigger("change");
										$('#address-'+(numAddress)).val(data.tiempoRecordatorioArray[i]);
									break;
								}
							}
							
							$('#pnAdd').show();
							$('.btnAddPage').addClass('hidden');
							$('#tasksList').parent().toggle();
							$('#btnBack').removeClass('hidden');
							$('#btnCancelTop').removeClass('hidden');
							$('#btnSaveTop').removeClass('hidden');
						} else {
							var taskNoEdit = $('#taskNoEdit').html();
							swal('',data.nombre+' '+taskNoEdit,'error');
						}
					}					
				},
				error:function(data){
					if(data.error){
						// console.log(data.id);
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
	/////Fin definición persist data (Edit method)


	/////Persist datatable (Delete method)
	$(document).on('click', '.btnDelete', function(event) {
		var $btn = $(this).button('loading');
		/////Definición de variables
		var id=$(this).children().first().children().attr('id');
		var ids=[];
		var table = $('#tasksList').DataTable();
		$('.chkItem').each(function() {
			if ($(this).is(':checked')) {
				ids.push($(this).parent().attr('id'));
			}
		});	
		// console.log(ids);
		var cancelLabel = $('#cancelLabel').html();
		var cancelButtonText = $('#cancelButtonText').html();
		// var removeButton = $('#removeButton').html();
		var alternateconfirmButtonText = $('#alternateconfirmButtonText').html();
		
		swal({
                        title: "",
                        text: cancelLabel,
                        type: "info",
                        showCancelButton: true,
                        confirmButtonText: alternateconfirmButtonText,
                        cancelButtonText: cancelButtonText,
                        reverseButtons: true,
                    }).then(function(isConfirm) {
                        if (isConfirm) {
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
							// console.log(data.id);
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
	/////Fin definición persist data (Delete method)


	/////Select checkboxes (All)
	$(document).on('click', '.chkItemAll', function(event) {
		/////Definición de variables
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
	/////Fin select checkboxes (All)


	/////Select checkboxes (Single)
	$(document).on('click', '.chkItem', function(event) {

		/////Definición de variables
		var text = $(this).prop('tagName');
		var total=0;
		var selected=0;
		$('#pnAdd').slideUp();
		// console.log(text);
		if (text=='INPUT' ) {
			var id=$(this).parent().attr('id');
			// var probability=$('#txtProbability');
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
	/////Fin select checkboxes (Single)

	/////Contadores para agregar o eliminar personas
	var numPersonas = 0;
	
	
	$('.dpbResponsable').each(function(index, el) {
		numPersonas++;
	});
	/////Fin de contadores para agregar o eliminar personas


	/////Agregar/remover personas
	$(document).on('click', '#plusPersona', function(event) {
		numPersonas++;
		var personas = $('.firstResponsable').html();
		var tipoRecordatorio = $('.firstTipoRecordatorio').html();
		var tiempoRecordatorio = $('.firstTiempoRecordatorio').html();
		// console.log(personas);
		// console.log(tipoRecordatorio);
		// console.log(tiempoRecordatorio);
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
	/////Fin de agregar/remover telefonos


});	
