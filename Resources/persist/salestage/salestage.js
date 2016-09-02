$(document).ready(function() {
	/////Persist datatable (Save method)
	$(document).on('click', '#btnSave', function(event) {
		
		var $btn = $(this).button('loading')
    		//$btn.button('reset')

		var name=$('#txtName');
		var probability=$('#txtProbability');
		var state = $('#txtState');
		var errores = 0;//Contador de errores, para antes de la persistencia

		$('.validateInput').each(function() {
			if (!required($(this))) {
				errores++;
			}
		});

		if (errores==0) {
			/*$.ajax({
				url: '/path/to/file',
				type: 'default GET (Other values: POST)',
				dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
				data: {param1: 'value1'},
				success:function(data){

				}
			});*/
		}
		else {
			swal('','¡Fields in red are required!','error');
			$btn.button('reset')
			//console.log('error');
		}
	});
	/////Fin definición persist data (Save method)
});	