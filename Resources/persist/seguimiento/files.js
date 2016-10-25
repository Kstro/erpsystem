$(document).ready(function() {
	var ajaxRunning = 0;
        
        /*/////Borrar files*/
	$(document).on('click', '.fileDelete', function(event) {
		/*/////Definición de variables*/
		var id=$(this);
		/*// console.log($(this).attr('id'));*/
                
                var cancelLabel = $('#removeFile').html();
                var fileName = $(this).parent().next().children().html();
		var cancelButtonText = $('#cancelButtonText').html();
		/*// var removeButton = $('#removeButton').html();*/
		var alternateconfirmButtonText = $('#alternateconfirmButtonText').html();
                swal({
                        title: "",
                        text: cancelLabel+' '+fileName+' ?',
                        type: "info",
                        showCancelButton: true,
                        confirmButtonText: alternateconfirmButtonText,
                        cancelButtonText: cancelButtonText,
                        reverseButtons: true,
                        showLoaderOnConfirm: true,
                        preConfirm: function(email) {
                        return new Promise(function(resolve, reject) {
                            deleteFiles(id);
                            /*alert('vfdv');*/
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
                
                
		/*deleteFiles(id);*/
		return false;
	});
	/*/////Fin borrar files*/
        
        $(document).on('change', '#fileAttached', function(event) {
            console.log('archivo subido');
            if(this.files[0].size<1.95*1024*1024){
                $('#frmFiles').submit();
            }
            else{
                swal('','¡File too big!','error');
            }
	});
        
        $('#frmFiles').on('submit',(function(event) {
            var formData = new FormData(this);
            var idCuenta = $('#txtId1').val();
            /*console.log(idCuenta);*/
            if (typeof idCuenta=== 'undefined') {
                idCuenta = $('#txtId').val();
            }
            /*console.log(idCuenta);*/
            var tipoComment = $('#txtTipoComment').val();
            formData.append('param1',idCuenta);
            formData.append('param2',tipoComment);
            $.ajax({
                url: Routing.generate('admin_files_add_ajax'),
                type: "POST",            
                data: formData,
                contentType: false,    
                cache: false,           
                processData:false,   
                success: function(data)  
                {
                    if (data.error) {
                        swal('',data.error,'error');
                    }
                    else{
                        var item='';
                        var addItem='<div class="col-xs-1" style="vertical-align:middle;"><a id="'+data.idFileg+'" href="'+data.path+data.nombreFile+'" class="fileDelete"><i style="margin-top:3px;vertical-align:middle;" class="fa fa-remove"></i></a></div><div class="col-xs-10"><a target="_blank" href="../../../'+data.path+data.nombreFile+'">'+data.nombreFile+'</a></div>';
                        $('#addedFiles').prepend(addItem);
                        
                        var fechaRecuperada = data.fecha; 
                        var momentFecha = moment(fechaRecuperada).format('MMM D, YYYY  HH:mm');
                        item+='<div class="message-item"><div class="message-inner"><div class="message-head clearfix">';
                        if (data.src!=null) {
                                item+='<div class="avatar pull-left"><img src="'+data.src+'"></div>';
                        } else {
                                item+='<div class="avatar pull-left"><img src="https://ssl.gstatic.com/accounts/ui/avatar_2x.png"></div>';
                        }
                        item+='<div class="user-detail pull-left"><h5 class="handle">'+data.usuario+'</h5><div class="post-meta"><div class="asker-meta"><span class="qa-message-what"></span><span class="qa-message-when">';
                        item+='<span class="qa-message-when-data">'+momentFecha+'</span></span><span class="qa-message-who"><span class="qa-message-who-pad"></span><span class="qa-message-who-data"></span>';
                        item+='</span></div></div></div><div class="pull-right"><i class="fa fa-file"></i></div></div><div class="qa-message-content"><a href="../../../'+data.path+data.nombreFile+'">'+data.nombreFile+'</a></div></div></div>';
                        $('#wallmessages').prepend(item);
                    }
                },
                error:function(data) {
                    swal('',data.error,'error'); 
                }
            });
            event.preventDefault();
            return false;
        }));
        
});

function cargarFiles(){
	//$('#addedTags').html('');
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

function deleteFiles(idDom){
	/*console.log(idDom);*/
	var idString=idDom.attr('id');
	var id2=$('#txtId1').val();
	/*console.log(idString);*/
	if (typeof id2=== 'undefined') {
                id2= $('#txtId').val();
        }
        var tipoComment = $('#txtTipoComment').val();
	var deleteDomX = idDom.parent();
	var deleteDomLabel = idDom.parent().next();
	$.ajax({
		url: Routing.generate('admin_files_delete_ajax'),
		type: 'POST',
		data: {param1: idString,param2: id2,param3:tipoComment},
		success:function(data){
			if (data.error) {
				swal('',data.error,'error');
				$('#addedTags').append(data.error);
			}
			else{
				if (data.deletedetiqueta) {
					/*console.log('Actualizar tags');
					console.log(data);*/
                                        
					
					/*// $('#selectTags').val(0).trigger("change");*/
					/*$('#selectTags').val(0).change().trigger("change");*/
				}
                                var item='';
                                var fechaRecuperada = data.fecha_registro; 
                                var momentFecha = moment(fechaRecuperada).format('MMM D, YYYY  HH:mm');
                                item+='<div class="message-item"><div class="message-inner"><div class="message-head clearfix">';
                                if (data.src!=null) {
                                        item+='<div class="avatar pull-left"><img src="'+data.src+'"></div>';
                                } else {
                                        item+='<div class="avatar pull-left"><img src="https://ssl.gstatic.com/accounts/ui/avatar_2x.png"></div>';
                                }
                                item+='<div class="user-detail pull-left"><h5 class="handle">'+data.usuario+'</h5><div class="post-meta"><div class="asker-meta"><span class="qa-message-what"></span><span class="qa-message-when">';
                                item+='<span class="qa-message-when-data">'+momentFecha+'</span></span><span class="qa-message-who"><span class="qa-message-who-pad"></span><span class="qa-message-who-data"></span>';
                                item+='</span></div></div></div><div class="pull-right"><i class="fa fa-comment"></i></div></div><div class="qa-message-content">'+data.comentario+'</div></div></div>';
                                $('#wallmessages').prepend(item);
				swal('',idDom.parent().next().children().html()+' '+$('#removedFile').html(),'success');
                                deleteDomX.remove();
				deleteDomLabel.remove();
			}
		},
		error:function(data){
			swal('',data.error,'error');					
		}
	});
	
}