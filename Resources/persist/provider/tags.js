$(document).ready(function() {
	var ajaxRunning = 0;
	/*/////Crear tags*/
	$('#addTagForm').focusout(function(){
		/*focusFuera();*/
	});
	$(document).bind('keyup', function(e) {
		if(e.keyCode == 13) {
                    focusFuera();
                }
	});
	/*/////Fin de crear tags*/

	/*/////Borrar tags*/
	$(document).on('click', '.tagDelete', function(event) {
		/*/////Definición de variables*/
		var id=$(this);
		/*// console.log($(this).attr('id'));*/
		deleteTags(id);
		return false;
	});
	/*/////Fin borrar tags*/
});

function cargarTags(){
	$('#addedTags').html('');
	id = $('#txtId1').val();
	if(id!=''){
		$.ajax({
			url: Routing.generate('admin_tags_index_ajax'),
			type: 'GET',
			data: {param1: id},
			success:function(data){
				if (data.error) {
					/*swal('',data.error,'error');
					$('#addedTags').append(data.error);*/
				}
				else{
					if (!data.existe) {
						var addItem = '';
						/*console.log('d');
						console.log(data.data);*/
						for (var i = 0; i < data.data.length; i++) {
							/*console.log(i);*/
							addItem='<div class="col-xs-1" style="vertical-align:middle;"><a id="'+data.data[i].id+'" href="" class="tagDelete"><i style="margin-top:3px;vertical-align:middle;" class="fa fa-remove"></i></a></div><div class="col-xs-10">'+data.data[i].nombre+'</div>';
							$('#addedTags').append(addItem);
						}
					} 
				}
			},
			error:function(data){
				swal('',data.error,'error');					
			}
		});
	}
}

function focusFuera(){
	var tag = $('#addTagForm').val();
	var id = $('#txtId1').val();
	/*console.log(tag);*/
	if(tag!=''){
		ajaxRunning = 1;
		$.ajax({
			url: Routing.generate('admin_tags_add_ajax'),
			type: 'POST',
			data: {param1: id,param2:tag},
			success:function(data){
				if (data.error) {
					swal('',data.error,'error');
				}
				else{
					if(data.idTag!=null){
						var addItem='<div class="col-xs-1" style="vertical-align:middle;"><a id="'+data.idTag+'" href="" class="tagDelete"><i style="margin-top:3px;vertical-align:middle;" class="fa fa-remove"></i></a></div><div class="col-xs-10">'+tag+'</div>';
						$('#addedTags').append(addItem);
					}
				}
				$('#addTagForm').val('');
				ajaxRunning = 0;
			},
			error:function(data){
				swal('',data.error,'error');
				ajaxRunning = 0;
			}
		});
	}
}

function deleteTags(idDom){
	/*console.log(idDom);*/
	var idString=idDom.attr('id');
	/*console.log(idString);*/
	
	var deleteDomX = idDom.parent();
	var deleteDomLabel = idDom.parent().next();
	$.ajax({
		url: Routing.generate('admin_tags_delete_ajax'),
		type: 'POST',
		data: {param1: idString},
		success:function(data){
			if (data.error) {
				swal('',data.error,'error');
				$('#addedTags').append(data.error);
			}
			else{
				if (data.deletedetiqueta!='') {
					console.log('Actualizar tags');
					$('#selectTags').select2('destroy');
					$('#selectTags').remove(data.deletedetiqueta);
					$('#selectTags').select2();
					// $('#selectTags').val(0).trigger("change");
					/*$('#selectTags').val(0).change().trigger("change");*/
				}
				deleteDomX.remove();
				deleteDomLabel.remove();				
			}
		},
		error:function(data){
			swal('',data.error,'error');					
		}
	});
	
}