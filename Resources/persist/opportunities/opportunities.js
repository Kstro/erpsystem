$(document).ready(function() {
    var numPersonas = 0;
    
    /* Al momento que se dé click en guardar la información del formulario */
    $('#frmOpportunities').on('submit',(function(event) {
        event.preventDefault();
        var table = $('#oppotunitiesList').DataTable();
        var errores = 0;
        var $btn;
        
        $btn = $('#btnSave').button('loading');
        
        $('.validateSelectP').each(function() {
            if (!requiredSelectP($(this))) {
                $(this).next().children().children().addClass('errorform');
                errores++;
            }
        });

        $('.validateInput').each(function() {
            if (!required($(this))) {
                $(this).addClass('errorform');
                errores++;
            }
        });
        
        if (errores==0) {
            $.ajax({
                url: Routing.generate('admin_opportunity_save_ajax'),
                type: "POST",            
                data: new FormData(this),
                contentType: false,      
                cache: false,            
                processData:false,     
                success: function(data)  
                {
                    $("#message").html(data);
                    $("#txtId").val(data.id);
                    
                    if(data.msg){
                        swal('',data.msg,'success');
                        
                        $('#txtId').val('');
                        $('#txtName').val('');

                        $('.btnAddPage').click();
                        $btn.button('reset');
                    }
                    if(data.error){
                        swal('',data.error,'error');
                        $btn.button('reset');
                    }
                    
                    table.ajax.reload();
                    $btn.button('reset');
                    
                    $('#btnSaveTop').addClass('hidden');
                    $('#btnCancelTop').addClass('hidden');
                    $('.btnAddPage').removeClass('hidden');
                },
                error:function(data) {
                        /* Act on the event */
                        $btn.button('reset');
                }
            });
        } else {
            var requiredFields = $('.requiredFields').html();
            swal('',requiredFields,'error');
            
            $btn.button('reset');
            return false;
        }
        
        event.preventDefault();
        
        return false;
    })); /* Fin del submit del formulario frmOpportunities */
    
    /* Al momento de seleccionar un tipo de cuenta filtra las cuentas vinculadas al */
    /* tipo de cuenta seleccionado llenando el combobox con las cuentas recuperadas */
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
                    
                    $('#cuenta').addClass('validateSelectP');
                    $('#cuenta').select2();
                    
                    if(data.cuentas.length > 0) {
                        $('#divCuentaOportunidad').removeClass('hidden');
                    } else {
                        $('#divCuentaOportunidad').addClass('hidden');
                    }    
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
    
    /* Al momento de seleccionar la fuente de origen de la oportunidad de venta, en el caso de que */
    /* haya seleccionado que la fuente de origen ha sido por medio de campañas muestra el combobox */
    /* donde se selecciona cual ha sido la campaña                                                 */
    $(document).on('change', '#fuente', function(event) {
        var id = $(this).val();
        var idHtmt = document.getElementById("divCampania");
        
        if(id == 1) {
            $('#divCampania').removeClass('hidden');
            $('#campania').addClass('validateSelectP');
        } else {
            $('#divCampania').addClass('hidden');
            $('#campania').removeClass('validateSelectP');
        }
        
        return false;
    }); /* Fin del onChange del combobox fuente */
    
    /* Agregar personas */
    $(document).on('click', '#plusUser', function(event) {
        numPersonas++;
        var personas = $('.firstResponsable').html();
        
        $('.responsable').append('<div style="margin-top:27px;"><select id="persona-'+numPersonas+'" style="width:100%;margin-top:25px !important;" name="responsable[]" class="input-sm form-control validateInput ">'+personas+'</select></div>');
        $('.addUser').append('<button id="deletePersona-'+numPersonas+'" style="margin-top:25px;" class="btn removePersona btn-danger"><i class="fa fa-remove"></i></button>');
        $('#persona-'+numPersonas).select2();

        
        return false;
    }); /* Fin de agregar personas */
    
    /* Remover personas */
    $(document).on('click', '.removePersona', function(event) {
        var numDel = $(this).attr('id');
        numDelArray= numDel.split('-');
        $('#persona-'+numDelArray[1]).parent().remove();
        $(this).remove();
        
        return false;
    }); /* Fin de remover personas */
});