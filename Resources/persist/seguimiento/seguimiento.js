var tipo = 0;
$(document).ready(function() {
	/*/////Cargar mas historial*/
        
	$(document).on('click', '#btnLoadMore', function(event) {
		var numPedidos =$('#iteracion').val();
		var idForm=$('#txtId1').val();
                console.log("iform"+idForm);
                console.log("pedidos"+numPedidos);
		seguimientoGeneral(idForm,numPedidos,$(this),tipo);
		return false;
	});
	/*/////Fin de agregar/remover direccion*/
	/*/////Cargar mas historial actividad*/

	$(document).on('click', '#btnLoadMoreAct', function(event) {
		var numPedidos =$('#iteracion').val();
		var idForm=$('#txtId').val();
                console.log("iform"+idForm);
                console.log("pedidos"+numPedidos);
		seguimientoActividad(idForm,numPedidos,$(this));
		return false;
	});
	/*/////Fin de agregar/remover direccion actividad*/
});

function mostrarocultar(numPedidos){
    /*console.log('asdcdsc');*/
    /*// return false;*/
    /*se limpia el seguimineto previo*/
    $('#comentarios').show();
    $('#wallmessages').show();
    $('#wallmessages').html('');
    $('#primeraFecha').val('');
    $('#iteracion').val(numPedidos);
}

function seguimiento(dataId, iteracion,boton){
	if (boton!=null) {
		boton.button('loading');	
	}
	$.ajax({
		url: Routing.generate('busqueda_cuenta_seguimiento_info'),
		type: 'GET',
		data: {param1: dataId,param2:iteracion},
		success:function(data){
			if(data.error){
				swal('',data.error,'error');
				boton.button('reset');
				$('#iteracion').val(iteracion);
			}else{
				var hoy = new Date();
				var fechaActualString = hoy.getYear();
				var dd = hoy.getDate();
				var mm = hoy.getMonth()+1; /*//hoy es 0!*/
				var yyyy = hoy.getFullYear();
				if(dd<10) {
				    dd='0'+dd
				} 
				if(mm<10) {
				    mm='0'+mm
				} 
				hoy = yyyy+'-'+mm+'-'+dd;
				var fechaCambio = hoy;
				var item='';
				var forInterno =0;
				for (var i = 0; i <data.data.length; i++) {
					var fechaRecuperada = data.data[i].fecha_registro; 
					if (i==0) {
						$('#primeraFecha').val(fechaRecuperada);
					}
					item='';
					
					var momentFecha = moment(fechaRecuperada).format('MMM D, YYYY  HH:mm');
					item+='<div class="message-item"><div class="message-inner"><div class="message-head clearfix">';
					if (data.data[i].src!=null) {
						item+='<div class="avatar pull-left"><img src="'+data.data[i].src+'"></div>';
					} else {
						item+='<div class="avatar pull-left"><img src="https://ssl.gstatic.com/accounts/ui/avatar_2x.png"></div>';
					}

					
					item+='<div class="user-detail pull-left"><h5 class="handle">'+data.data[i].nombres+' '+data.data[i].apellidos+'</h5><div class="post-meta"><div class="asker-meta"><span class="qa-message-what"></span><span class="qa-message-when">';
				        item+='<span class="qa-message-when-data">'+momentFecha+'</span></span><span class="qa-message-who"><span class="qa-message-who-pad"></span><span class="qa-message-who-data"></span>';
				        item+='</span></div></div></div>';
				        switch(parseInt(data.data[i].tipo)){
						case 1:/*/////Comentarios*/
                                                    switch(parseInt(data.data[i].tipocomentario)){
                                                        case 1:/*/////Comnetario*/
                                                            item+='<div class="pull-right"><i class="fa fa-comment"></i></div>';
                                                            break;
                                                        case 2: /*/////Files*/
                                                            item+='<div class="pull-right"><i class="fa fa-file"></i></div>';
                                                            break;
                                                    }							
                                                    item+='</div><div class="qa-message-content">'+data.data[i].comentario+'</div></div></div>';
						break;
						case 2:/*/////Adjuntar archivos*/
                                                    
							item+='<div class="pull-right"><i class="fa fa-file"></i></div>';
                                                        if(data.data[i].estado==1){
                                                            item+='</div><div class="qa-message-content">'+ data.files+' <a target="_blank" href="../../../files/accounts/'+data.data[i].comentario+'">'+data.data[i].comentario+'</a></div></div></div>';
                                                        }
                                                        else{
                                                            item+='</div><div class="qa-message-content">'+ data.files+' '+ data.data[i].comentario+'</div></div></div>';
                                                        }
						break;
						case 3:/*/////Asignacion  de recursos*/
							item+='<div class="pull-right"><i class="fa fa-plus"></i></div>';
                                                        item+='</div><div class="qa-message-content">'+data.data[i].comentario+'</div></div></div>';
						break;
						case 4:/*/////Otros 1*/
							item+='<div class="pull-right"><i class="fa fa-comment"></i></div>';
                                                        item+='</div><div class="qa-message-content">'+data.data[i].comentario+'</div></div></div>';
						break;
					}
					$('#wallmessages').append(item);
				}
				/*console.log('primero');*/
				if(boton ==null)
					$('#btnLoadMore').show();				
				if(boton !=null)
					boton.button('reset');
				iteracion++;
			}
			$('#iteracion').val(iteracion);		
		},
		error:function(data){
			if(data.error){
				swal('',data.error,'error');
			}
			boton.button('reset');
			$('#iteracion').val(iteracion);
		}
	});
}


function seguimientoActividad(dataId, iteracion,boton){
	if (boton!=null) {
		boton.button('loading');	
	}
	$.ajax({
		url: Routing.generate('busqueda_actividad_seguimiento_info'),
		type: 'GET',
		data: {param1: dataId,param2:iteracion},
		success:function(data){
			if(data.error){
				swal('',data.error,'error');
				boton.button('reset');
				$('#iteracion').val(iteracion);
			}else{
				var hoy = new Date();
				var fechaActualString = hoy.getYear();
				var dd = hoy.getDate();
				var mm = hoy.getMonth()+1; /*//hoy es 0!*/
				var yyyy = hoy.getFullYear();
				if(dd<10) {
				    dd='0'+dd
				} 
				if(mm<10) {
				    mm='0'+mm
				} 
				hoy = yyyy+'-'+mm+'-'+dd;
				var fechaCambio = hoy;
				var item='';
				var forInterno =0;
				for (var i = 0; i <data.data.length; i++) {
					var fechaRecuperada = data.data[i].fecha_registro; 
					if (i==0) {
						$('#primeraFecha').val(fechaRecuperada);
					}
					item='';
					
					var momentFecha = moment(fechaRecuperada).format('MMM D, YYYY  HH:mm');
					item+='<div class="message-item"><div class="message-inner"><div class="message-head clearfix">';
					if (data.data[i].src!=null) {
						item+='<div class="avatar pull-left"><img src="'+data.data[i].src+'"></div>';
					} else {
						item+='<div class="avatar pull-left"><img src="https://ssl.gstatic.com/accounts/ui/avatar_2x.png"></div>';
					}

					
					item+='<div class="user-detail pull-left"><h5 class="handle">'+data.data[i].nombres+' '+data.data[i].apellidos+'</h5><div class="post-meta"><div class="asker-meta"><span class="qa-message-what"></span><span class="qa-message-when">';
				        item+='<span class="qa-message-when-data">'+momentFecha+'</span></span><span class="qa-message-who"><span class="qa-message-who-pad"></span><span class="qa-message-who-data"></span>';
				        item+='</span></div></div></div>';
				        switch(parseInt(data.data[i].tipo)){
						case 1:/*/////Comentarios*/
                                                    switch(parseInt(data.data[i].tipocomentario)){
                                                        case 1:/*/////Comnetario*/
                                                            item+='<div class="pull-right"><i class="fa fa-comment"></i></div>';
                                                            break;
                                                        case 2: /*/////Files*/
                                                            item+='<div class="pull-right"><i class="fa fa-file"></i></div>';
                                                            break;
                                                    }							
                                                    item+='</div><div class="qa-message-content">'+data.data[i].comentario+'</div></div></div>';
						break;
						case 2:/*/////Adjuntar archivos*/
                                                    
							item+='<div class="pull-right"><i class="fa fa-file"></i></div>';
                                                        if(data.data[i].estado==1){
                                                            item+='</div><div class="qa-message-content">'+ data.files+' <a target="_blank" href="../../../files/accounts/'+data.data[i].comentario+'">'+data.data[i].comentario+'</a></div></div></div>';
                                                        }
                                                        else{
                                                            item+='</div><div class="qa-message-content">'+ data.files+' '+ data.data[i].comentario+'</div></div></div>';
                                                        }
						break;
						case 3:/*/////Asignacion  de recursos*/
							item+='<div class="pull-right"><i class="fa fa-plus"></i></div>';
                                                        item+='</div><div class="qa-message-content">'+data.data[i].comentario+'</div></div></div>';
						break;
						case 4:/*/////Otros 1*/
							item+='<div class="pull-right"><i class="fa fa-comment"></i></div>';
                                                        item+='</div><div class="qa-message-content">'+data.data[i].comentario+'</div></div></div>';
						break;
					}
					$('#wallmessages').append(item);
				}
				/*console.log('primero');*/
				if(boton ==null)
					$('#btnLoadMore').show();				
				if(boton !=null)
					boton.button('reset');
				iteracion++;
			}
			$('#iteracion').val(iteracion);		
		},
		error:function(data){
			if(data.error){
				swal('',data.error,'error');
			}
			boton.button('reset');
			$('#iteracion').val(iteracion);
		}
	});
}

function seguimientoGeneral(dataId, iteracion,boton,tipoComment){
        tipo=tipoComment;
	if (boton!=null) {
		boton.button('loading');	
	}
	$.ajax({
		url: Routing.generate('busqueda_general_seguimiento_info'),
		type: 'GET',
		data: {param1: dataId,param2:iteracion,param3:tipoComment},
		success:function(data){
			if(data.error){
				swal('',data.error,'error');
				boton.button('reset');
				$('#iteracion').val(iteracion);
			}else{
				var hoy = new Date();
				var fechaActualString = hoy.getYear();
				var dd = hoy.getDate();
				var mm = hoy.getMonth()+1; /*//hoy es 0!*/
				var yyyy = hoy.getFullYear();
				if(dd<10) {
				    dd='0'+dd
				} 
				if(mm<10) {
				    mm='0'+mm
				} 
				hoy = yyyy+'-'+mm+'-'+dd;
				var fechaCambio = hoy;
				var item='';
				var forInterno =0;
				for (var i = 0; i <data.data.length; i++) {
					var fechaRecuperada = data.data[i].fecha_registro; 
					if (i==0) {
						$('#primeraFecha').val(fechaRecuperada);
					}
					item='';
					
					var momentFecha = moment(fechaRecuperada).format('MMM D, YYYY  HH:mm');
					item+='<div class="message-item"><div class="message-inner"><div class="message-head clearfix">';
					if (data.data[i].src!=null) {
						item+='<div class="avatar pull-left"><img src="'+data.data[i].src+'"></div>';
					} else {
						item+='<div class="avatar pull-left"><img src="https://ssl.gstatic.com/accounts/ui/avatar_2x.png"></div>';
					}

					
					item+='<div class="user-detail pull-left"><h5 class="handle">'+data.data[i].nombres+' '+data.data[i].apellidos+'</h5><div class="post-meta"><div class="asker-meta"><span class="qa-message-what"></span><span class="qa-message-when">';
				        item+='<span class="qa-message-when-data">'+momentFecha+'</span></span><span class="qa-message-who"><span class="qa-message-who-pad"></span><span class="qa-message-who-data"></span>';
				        item+='</span></div></div></div>';
				        switch(parseInt(data.data[i].tipo)){
						case 1:/*/////Comentarios*/
                                                    switch(parseInt(data.data[i].tipocomentario)){
                                                        case 1:/*/////Comnetario*/
                                                            item+='<div class="pull-right"><i class="fa fa-comment"></i></div>';
                                                            break;
                                                        case 2: /*/////Files*/
                                                            item+='<div class="pull-right"><i class="fa fa-file"></i></div>';
                                                            break;
                                                    }							
                                                    item+='</div><div class="qa-message-content">'+data.data[i].comentario+'</div></div></div>';
						break;
						case 2:/*/////Adjuntar archivos*/
                                                    
							item+='<div class="pull-right"><i class="fa fa-file"></i></div>';
                                                        if(data.data[i].estado==1){
                                                            item+='</div><div class="qa-message-content">'+ data.files+' <a target="_blank" href="../../../files/accounts/'+data.data[i].comentario+'">'+data.data[i].comentario+'</a></div></div></div>';
                                                        }
                                                        else{
                                                            item+='</div><div class="qa-message-content">'+ data.files+' '+ data.data[i].comentario+'</div></div></div>';
                                                        }
						break;
						case 3:/*/////Asignacion  de recursos*/
							item+='<div class="pull-right"><i class="fa fa-plus"></i></div>';
                                                        item+='</div><div class="qa-message-content">'+data.data[i].comentario+'</div></div></div>';
						break;
						case 4:/*/////Otros 1*/
							item+='<div class="pull-right"><i class="fa fa-comment"></i></div>';
                                                        item+='</div><div class="qa-message-content">'+data.data[i].comentario+'</div></div></div>';
						break;
					}
					$('#wallmessages').append(item);
				}
				/*console.log('primero');*/
				if(boton ==null)
					$('#btnLoadMore').show();				
				if(boton !=null)
					boton.button('reset');
				iteracion++;
			}
			$('#iteracion').val(iteracion);		
		},
		error:function(data){
			if(data.error){
				swal('',data.error,'error');
			}
			boton.button('reset');
			$('#iteracion').val(iteracion);
		}
	});
}


function seguimientoComet(dataId){
	var primeraFecha = $('#primeraFecha').val();
	
	$.ajax({
		url: Routing.generate('busqueda_cuenta_seguimiento_comet_info'),
		type: 'GET',
		data: {param1: dataId,param2:primeraFecha},
		async:false,
		success:function(data){
			if(data.error){
				console.log(data.error);
			}else{
				var hoy = new Date();
				var fechaActualString = hoy.getYear();
				var dd = hoy.getDate();
				var mm = hoy.getMonth()+1; /*//hoy es 0!*/
				var yyyy = hoy.getFullYear();
				if(dd<10) {
				    dd='0'+dd
				} 
				if(mm<10) {
				    mm='0'+mm
				} 
				hoy = yyyy+'-'+mm+'-'+dd;
				var fechaCambio = hoy;
				var item='';
				var forInterno =0;
				for (var i = 0; i <data.data.length; i++) {
					if (i==0) {
						$('#primeraFecha').val(data.data[i].fecha_registro);
					}
					item='';
					var fechaRecuperada = data.data[i].fecha_registro; 
					var momentFecha = moment(fechaRecuperada).format('MMM D, YYYY  HH:mm');
					item+='<div class="message-item"><div class="message-inner"><div class="message-head clearfix">';
					if (data.data[i].src!=null) {
						item+='<div class="avatar pull-left"><img src="'+data.data[i].src+'"></div>';
					} else {
						item+='<div class="avatar pull-left"><img src="https://ssl.gstatic.com/accounts/ui/avatar_2x.png"></div>';
					}

					
					item+='<div class="user-detail pull-left"><h5 class="handle">'+data.data[i].nombres+' '+data.data[i].apellidos+'</h5><div class="post-meta"><div class="asker-meta"><span class="qa-message-what"></span><span class="qa-message-when">';
				        item+='<span class="qa-message-when-data">'+momentFecha+'</span></span><span class="qa-message-who"><span class="qa-message-who-pad"></span><span class="qa-message-who-data"></span>';
				        item+='</span></div></div></div>';
				        switch(parseInt(data.data[i].tipo)){
						case 1:/*/////Comentarios*/
							item+='<div class="pull-right"><i class="fa fa-comment"></i></div>';
						break;
						case 2:/*/////Asignacion  de recursos*/
							item+='<div class="pull-right"><i class="fa fa-plus"></i></div>';
						break;
						case 3:/*/////Otros 1*/
							item+='<div class="pull-right"><i class="fa fa-comment"></i></div>';
						break;
						case 4:/*/////Otros 2*/
							item+='<div class="pull-right"><i class="fa fa-comment"></i></div>';
						break;
					}
					item+='</div><div class="qa-message-content">'+data.data[i].comentario+'</div></div></div>';
					$('#wallmessages').prepend(item);
				}
				/*console.log('primero');*/
				
				iteracion++;
			}
			$('#iteracion').val(iteracion);	

			/*// seguimientoComet(dataId);
			//setInterval(function(){ seguimientoComet(dataId); }, 15000);*/
			
		},
		error:function(data){
			if(data.error){
				swal('',data.error,'error');
			}
			
			$('#iteracion').val(iteracion);
		}
	});
}