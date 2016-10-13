$(document).ready(function() {
	/*/////Persist datatable (Save method)*/
	$(document).on('click', '#btnSave', function(event) {
		/*/////Definición de variables*/
		var $btn = $(this).button('loading');
		var $btnT = $('#btnSaveTop').button('loading');
    		var id=$('#txtId');
		var name=$('#txtName');
		var probability=$('#txtProbability');
		var state = $('#txtState');
		var table = $('#saleStageList').DataTable();
		var errores = 0;
		/*//Contador de errores, para antes de la persistencia*/
		$('.validateInput').each(function() {
			if (!required($(this))) {
				errores++;
			}
		});
		if (errores==0) {
			$.ajax({
				url: Routing.generate('admin_ctletapaventa_save_ajax'),
				type: 'POST',
				data: {param1: name.val(),param2:probability.val(),param3:id.val()},
				success:function(data){
					if(data.msg){
						swal('',data.msg,'success');
						id.val(data.id);
						$('#txtId').val('');
						$('#txtName').val('');
						$('#txtProbability').val('10');
						probability.slider('setValue', 10);
						$('.btnAdd').click();
						$btn.button('reset');
						$btnT.button('reset');
						table.ajax.reload();
					}
					if(data.error){
						console.log(data.id);
						swal('',data.error,'error');
						$btn.button('reset');
						$btnT.button('reset');
					}
					
				},
				error:function(data){
					if(data.error){
						console.log(data.id);
						swal('',data.error,'error');
					}
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
			/*//console.log('error');*/
		}
	});
	/*/////Fin definición persist data (Save method)*/


	/*/////Persist datatable (Edit method)*/
	$(document).on('click', '#saleStageList>tbody>tr>td:nth-child(2), #saleStageList>tbody>tr>td:nth-child(3), #saleStageList>tbody>tr>td:nth-child(4)', function(event) {
		/*/////Definición de variables*/
		var text = $(this).prop('tagName');
		console.log(text);
		var id=$(this).parent().children().first().children().attr('id');
		var idForm=$('#txtId').val();
		var probability=$('#txtProbability');
		var selected = 0;
		/*//Cambiar nombre del panel heading para Modify*/
		$('.pnHeadingLabelAdd').addClass('hidden');
		$('.pnHeadingLabelEdit').removeClass('hidden');
		$('.chkItem').each(function() {
			if ($(this).is(':checked')) {
				selected++;
			}
		});	

		if (text=='TD' && id!=idForm && selected==0) {
			$.ajax({
				url: Routing.generate('admin_ctletapaventa_retrieve_ajax'),
				type: 'POST',
				data: {param1: id},
				success:function(data){
					if(data.error){
						swal('',data.error,'error');
						id.val(data.id);
					}
					else{
						$('#txtId').val(data.id);
						$('#txtName').val(data.name);
						probability.slider('setValue', data.probability);
						$('#pnAdd').slideDown();
					}					
					
				},
				error:function(data){
					if(data.error){
						console.log(data.id);
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
		var table = $('#saleStageList').DataTable();
		$('.chkItem').each(function() {
			if ($(this).is(':checked')) {
				ids.push($(this).parent().attr('id'));
			}
		});	
		console.log(ids);
		/*// var probability=$('#txtProbability');*/
		var cancelLabel = $('#removeLabel').html();
		var cancelButtonText = $('#cancelButtonText').html();
		/*/ var removeButton = $('#removeButton').html();*/
		var confirmButtonText = $('#confirmButtonText').html();
		
		swal({
                        title: "",
                        text: cancelLabel,
                        type: "info",
                        showCancelButton: true,
                        confirmButtonText: confirmButtonText,
                        cancelButtonText: cancelButtonText,
                        reverseButtons: true,
                    }).then(function(isConfirm) {
                        if (isConfirm) {
				$.ajax({
					url: Routing.generate('admin_ctletapaventa_delete_ajax'),
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
						/*//$btn.button('reset');*/
					}
				});
				$('.btnDelete').addClass('hidden');
				$('.btnAdd').removeClass('hidden');
			}
			else{
				$btn.button('reset');
			}
		});
		$btn.button('reset');
	});
	/*/////Fin definición persist data (Delete method)*/


	/*/////Select checkboxes (All)*/
	$(document).on('click', '.chkItemAll', function(event) {
		/*/////Definición de variables*/
		var id=$(this).children().first().children().attr('id');
		var probability=$('#txtProbability');

		$('#txtId').val('');
		$('#txtName').val('');
		probability.slider('setValue', 14);
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
		var selected = 0;
		var total=0;
		var selected=0;
		$('#pnAdd').slideUp();
		console.log(text);
		if (text=='INPUT' ) {
			var id=$(this).parent().attr('id');
			var probability=$('#txtProbability');
			if ($(this).is(':checked')) {
				$('.btnAdd').addClass('hidden');
				$('.btnDelete').removeClass('hidden');
				$(this).prop({'checked': true});
			} 
			else {
				$('.btnAdd').removeClass('hidden');
				$('.btnDelete').addClass('hidden');
				$(this).prop({'checked': false});
			}
			$('.chkItem').each(function() {
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
});	
