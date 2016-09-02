	$(document).on('click', '.errorform', function(event) {
		$(this).removeClass('errorform');
		/* Act on the event */
	});
	
	/////Funcion que verifica que los campos no esten vacios
	function required(object){
		///El valor '' es para input que requieran que se ingrese texto, el valor 0 puede ser para selects2 (dropdowns)
		var valor = object.val();
		if (valor=='' || valor==0) {
			object.addClass('errorform');
			return false;///Valor vacio (invalido)
		} 
		else {
			return true;///Valor valido
		}
	}
