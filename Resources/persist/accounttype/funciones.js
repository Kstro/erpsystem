function seleccionarTodo() {	
    var seleccionado = 0;
    
    $('input[type=checkbox]').each( function() {			
        if($("input[name=checktodos]:checked").length == 1){
            this.checked = true;
            seleccionado = 1;
            
        } else {
            this.checked = false;
            seleccionado = 0;
            
        }
    });
    
    if(seleccionado == 1){
        $('.btnAdd').hide();
        $('.btnDelete').show();
    } else {
        $('.btnAdd').show();
        $('.btnDelete').hide();
    }
}