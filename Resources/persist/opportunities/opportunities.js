$(document).ready(function() {
    var numPersonas = 0;
    
    /* Al momento de seleccionar un tipo de cuenta filtra las cuentas */
    /* vinculadas al tipo de cuenta seleccionado llenando el combobox */
    /* con las cuentas recuperadas*/
    $(document).on('change', '#tipoCuenta', function(event) {
        var id = $(this).val();
        
        $.ajax({
            url: Routing.generate('admin_opportunities_search_accounts_ajax'),
            type: 'GET',
            data: {param1: id},
            success:function(data){
                if(data.error){
                    swal('',data.error,'error');
                }
                else{
                    $('#cuenta').select2('destroy');
                    $('#cuenta').html('');
                    console.log(data.cuentas);
                    
                    for (var i = 0; i < data.cuentas.length; i++) {
                        if (i==0) {
                            $('#cuenta').append('<option selected value="'+data.cuentas[0][0]+'">'+data.cuentas[0][1]+'</option>');
                        } 
                        else {
                            $('#cuenta').append('<option value="'+data.cuentas[i][0]+'">'+data.cuentas[i][1]+'</option>');
                        }
                    }
                    $('#cuenta').select2();
                }
                                        
                return false;
            },
            error:function(data){
                if(data.error){
                    swal('',data.error,'error');
                }
            }
        });
        
        return false;
    }); /* Fin del onChange del combobox tipoCuenta */
    
    /* Al momento de seleccionar una etapa de venta se obtiene la probabilidad de ésta */
    /* se llegue a concluir con exito */
    $(document).on('change', '#etapaVenta', function(event) {
        var id = $(this).val();
        
        $.ajax({
            url: Routing.generate('admin_opportunities_search_probability_ajax'),
            type: 'GET',
            data: {param1: id},
            success:function(data){
                if(data.error){
                    swal('',data.error,'error');
                }
                else{
                    $('#txtProbability').val(data.probabilidad);
                }
                                        
                return false;
            },
            error:function(data){
                if(data.error){
                    swal('',data.error,'error');
                }
            }
        });
        
        return false;
    }); /* Fin del onChange del combobox etapaVenta */
    
    /* Al momento de seleccionar la fuente de origen de la oportunidad de venta, en */
    /* el caso de que haya seleccionado que la fuente de origen ha sido por medio de */
    /* campañas muestra el combobox donde se selecciona cual ha sido la campaña */
    $(document).on('change', '#fuente', function(event) {
        var id = $(this).val();
        var idHtmt = document.getElementById("divCampania");
        
        if(id == 1) {
            $('#divCampania').removeClass('hidden');	
        } else {
            $('#divCampania').addClass('hidden');
        }
        
        return false;
    }); /* Fin del onChange del combobox fuente */
    
    /* Agregar/remover personas */
    $(document).on('click', '#plusUser', function(event) {
        numPersonas++;
        var personas = $('.firstResponsable').html();
        
        $('.responsable').append('<div style="margin-top:27px;"><select id="persona-'+numPersonas+'" style="width:100%;margin-top:25px !important;" name="responsable[]" class="input-sm form-control validateInput ">'+personas+'</select></div>');
        $('.addUser').append('<button id="deletePersona-'+numPersonas+'" style="margin-top:25px;" class="btn removePersona btn-danger"><i class="fa fa-remove"></i></button>');
        $('#persona-'+numPersonas).select2();

        
        return false;
    });
    $(document).on('click', '.removePersona', function(event) {
        var numDel = $(this).attr('id');
        numDelArray= numDel.split('-');
        $('#persona-'+numDelArray[1]).parent().remove();
        $(this).remove();
        
        return false;
    }); /* Fin de agregar/remover telefonos */
});