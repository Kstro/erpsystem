$(document).ready(function() {
	/*/////Agregar comment*/

	var primerItem = $('#primerItem');
	if (!($.isEmptyObject(primerItem))) {
		$('#btnLoadMore').hide();
	}
	$(document).on('click', '.btnAddComment', function(event) {
		/*/////Definición de variables*/
		var $btn = $(this).button('loading');
		var commentDom = $('#txtAddComment');
		var comment = $('#txtAddComment').val();
		var id=$('#txtId1').val();
		var item='';
		
		primerItem = $('#primerItem');
		if (!required(commentDom)) {
			$(commentDom).addClass('errorform');
			$btn.button('reset');
		}
		else{
			$.ajax({
				url: Routing.generate('admin_providers_comment_add_ajax'),
				type: "POST",            
				data: {param1:id,param2:comment},
				success: function(data)  
				{
					if(data.error){
						/*// console.log(data.id);*/
						swal('',data.error,'error');
					}
					else{
						$btn.button('reset');
						/*console.log(data);*/
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
						

						
						$('#btnLoadMore').show();
					}
					$btn.button('reset');
					$('#txtAddComment').val('');
				},
				error:function(data) {
					/* Act on the event */
					$btn.button('reset');
				}
			});
		}
		return false;
	});
        
        
	$(document).on('click', '.btnAddCommentAct', function(event) {
		/*/////Definición de variables*/
		var $btn = $(this).button('loading');
		var commentDom = $('#txtAddComment');
		var comment = $('#txtAddComment').val();
		var id=$('#txtId').val();
		var item='';
		
		primerItem = $('#primerItem');
		if (!required(commentDom)) {
			$(commentDom).addClass('errorform');
			$btn.button('reset');
		}
		else{
			$.ajax({
				url: Routing.generate('admin_actividad_comment_add_ajax'),
				type: "POST",            
				data: {param1:id,param2:comment},
				success: function(data)  
				{
					if(data.error){
						/*// console.log(data.id);*/
						swal('',data.error,'error');
					}
					else{
						$btn.button('reset');
						/*console.log(data);*/
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
						

						
						$('#btnLoadMore').show();
					}
					$btn.button('reset');
					$('#txtAddComment').val('');
				},
				error:function(data) {
					/* Act on the event */
					$btn.button('reset');
				}
			});
		}
		return false;
	});
	
        $(document).on('click', '.btnAddCommentGen', function(event) {
		/*/////Definición de variables*/
		var $btn = $(this).button('loading');
                var tipoComment = $(this).attr('id');
		var commentDom = $('#txtAddComment');
		var comment = $('#txtAddComment').val();
		var id=$('#txtId1').val();
                console.log("id act: "+id);
		if (typeof id=== 'undefined') {
                        id= $('#txtId').val();
                }
		console.log("id act: "+id);
		primerItem = $('#primerItem');
		if (!required(commentDom)) {
			$(commentDom).addClass('errorform');
			$btn.button('reset');
		}
		else{
			agregarComentario($btn,id,comment,tipoComment);
		}
		return false;
	});
	/*/////Fin de agregar comment*/

});	

function agregarComentario($btn,id,comment,tipoComment){
    var item='';
    console.log('asdcadc '+id);
    $.ajax({
            url: Routing.generate('admin_general_comment_add_ajax'),
            type: "POST",            
            data: {param1:id,param2:comment,param3:tipoComment},
            success: function(data)  
            {
                    if(data.error){
                            /*// console.log(data.id);*/
                            swal('',data.error,'error');
                    }
                    else{
                            $btn.button('reset');
                            /*console.log(data);*/
                            /*/var fechaRecuperada = data.fecha_registro; */
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
                            item+='</span></div></div></div><div class="pull-right"><i class="fa fa-comment"></i></div></div><div class="qa-message-content">'+data.comentario+'</div></div></div>';
                            $('#wallmessages').prepend(item);



                            $('#btnLoadMore').show();
                    }
                    $btn.button('reset');
                    $('#txtAddComment').val('');
            },
            error:function(data) {
                    /* Act on the event */
                    $btn.button('reset');
            }
    });
    
}
