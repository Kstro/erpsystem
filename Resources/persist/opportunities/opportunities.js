$(document).ready(function() {
    var numPersonas = 0;
    var recuperaDataCuenta = false;
    var recuperaDataProbabilidad = false;
    
    /* Al momento que se dé click en guardar la información del formulario */
    $('#frmOpportunities').on('submit',(function(event) {
        event.preventDefault();
        var table = $('#oppotunitiesList').DataTable();
        var errores = 0;
        var $btn;
        
        $btn = $('#btnSave').button('loading');
        
        /* Verificando si se ha ingresado la información necesaria de la oportunidad */
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
        
        /* Si se ha ingresado toda la información necesaria sobre la oportunidad de venta */
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

                        $('#pnAdd').hide();
                        $('#oppotunitiesList').parent().show();
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
        } /* Si no se ha ingresado toda la información necesaria sobre la oportunidad */
        else {
            var requiredFields = $('.requiredFields').html();
            swal('',requiredFields,'error');
            
            $btn.button('reset');
            return false;
        }
        
        event.preventDefault();
        
        return false;
    })); /* Fin del submit del formulario frmOpportunities */
    
    /* Al momento de hacer click en un registro del datatable presenta el formulario          
       con la informacion corresondiente  a la oportunidad de venta seleccionada donde   
      se edita la información de la oportunidad                                         */
    $(document).on('click', '#oppotunitiesList>tbody>tr>td:nth-child(2),#oppotunitiesList>tbody>tr>td:nth-child(3),#oppotunitiesList>tbody>tr>td:nth-child(4),#oppotunitiesList>tbody>tr>td:nth-child(5),#oppotunitiesList>tbody>tr>td:nth-child(6)', function(event) {
        /* Definición de variables */
        var table = $('#oppotunitiesList').DataTable();
        var text = $(this).prop('tagName');
        var id=$(this).parent().children().first().children().attr('id');
        var idForm=$('#txtId').val();
        var selected = 0;
        var objClicked = $(this);
        recuperaDataCuenta = true;
        recuperaDataProbabilidad = true;
        
        /* Cambio del nombre del panel heading para Modify */
        $('.pnHeadingLabelAdd').addClass('hidden');
        $('.pnHeadingLabelEdit').removeClass('hidden');
        
        /*$('#addedTags').html('');
        $('#wallmessages').html('');*/
        
        /* Verificando si se ha seleccionado algún checkbox del datatable */
        $('.chkItem').each(function() {
            if ($(this).is(':checked')) {
                selected++;
            }
        });
        
        if (text=='TD' && id!=idForm && selected==0) {
            objClicked.off('click');
            objClicked.css('cursor','progress');
            
            $.ajax({
                url: Routing.generate('admin_opportunities_retrieve_ajax'),
                type: 'POST',
                data: {param1: id},
                success:function(data){
                    if(data.error){
                        swal('',data.error,'error');
                        id.val(data.id);
                    }
                    else{
                        /*console.log(data);*/
                        
                        /* Seteando Id de oportunidad de venta en campo de texto oculto */
                        $('#txtId').val(data.id);

                        /* Seteando Nombre de oportunidad de venta en su respectivo campo de texto */
                        $('#txtName').val(data.name);

                        /* seteando el valor del tipo de cuenta en su respectivo select */
                        $('#tipoCuenta').val(data.tipoCuenta).change().trigger("change");


                        /* Seteando la respectiva cuenta asociada a la oportunidad, así como las opciones */
                        /* del tipo de cuenta asociado a la cuenta seleccionada                           */ 
                        $('#cuenta').select2('destroy');
                        $('#cuenta').html('');

                        for (var j = 0; j < data.cuentas.length; j++) {
                            if (j==0) {
                                $('#cuenta').append('<option selected value="'+data.cuentas[0][0]+'">'+data.cuentas[0][1]+'</option>');
                            } 
                            else {
                                $('#cuenta').append('<option value="'+data.cuentas[j][0]+'">'+data.cuentas[j][1]+'</option>');
                            }
                        }

                        $('#cuenta').addClass('validateSelectP');
                        $('#cuenta').select2();

                        if(data.cuentas.length > 0) {
                            $('#divCuentaOportunidad').removeClass('hidden');
                        } else {
                            $('#divCuentaOportunidad').addClass('hidden');
                        }

                        $('#cuenta').val(data.compania).change().trigger("change");
                        $('#divCuentaOportunidad').removeClass('hidden');
                        /* Fin de seteo de la cuenta asociada y las opciones del select */


                        /* seteando el valor de la etapa de venta en su respectivo select */
                        $('#etapaVenta').val(data.etapaVenta).change().trigger("change");

                        /* Seteando fecha de cierre de oportunidad de venta en su respectivo campo de texto */
                        $('#txtFechaCierre').val(data.fechaCierre);

                        /* Seteando la probabilidad de que la oportunidad de venta cierre con exito en su respectivo campo de texto */
                        $('#txtProbability').val(data.probability);

                        /* Seteando fecha de cierre de oportunidad de venta en su respectivo campo de texto */
                        $('#descripcion').val(data.description);

                        /* seteando el valor de la fuente de origen en su respectivo select */
                        $('#fuente').val(data.fuente).change().trigger("change");

                        /*   Si la fuente de origen ha sido a traves de una campaña   */
                        /*   Se setea la campaña asociada a la oportunidad de venta   */ 
                        if(data.fuente == 1) {
                            $('#campania').val(data.campania).change().trigger("change");
                            $('#divCampania').removeClass('hidden');
                        }


                        /* Obteniendo las opciones del select de usuario asignado   */
                        var personas = $('.firstResponsable').html();

                        /* Obteniendo el contenido del label*/
                        var assignedUserOportunidad =$('#assignedUserOportunidad').html();

                        /* Seteando a los usuarios asignados  a la oportunidad, así como las opciones */
                        /* de los usuarios que pueden ser asignados a la oportunidad                  */ 
                        $('.responsable').html('');
                        numPersonas = 0;
                        $('.responsable').append('<label id="assignedUserOportunidad">'+assignedUserOportunidad+'</label>');

                        for (var j = 0; j < data.asignados.length; j++) {
                            if(j > 0) {
                                $('.responsable').append('<div style="margin-top:27px;"><select id="persona-'+numPersonas+'" style="width:100%;margin-top:25px !important;" name="responsable[]" class="input-sm form-control validateSelectP ">'+personas+'</select></div>');
                                $('.addUser').append('<button id="deletePersona-'+numPersonas+'" style="margin-top:25px;" class="btn removePersona btn-danger"><i class="fa fa-remove"></i></button>');
                            } else {
                                $('.responsable').append('<select id="persona-'+numPersonas+'" style="width:100%;" name="responsable[]" class="input-sm form-control validateSelectP dpbResponsable firstResponsable">'+personas+'</select>');
                            }

                            $('#persona-'+numPersonas).select2();
                            $('#persona-'+numPersonas).val(data.asignados[j]).change().trigger("change");
                            numPersonas++;                                                                        
                        } /* Fin de seteo de los usuarios asignados y sus opciones del select */                                


                        /* Seteando los productos / servicios vinculados  a la oportunidad, así como las opciones */
                        /* de los productos / servicios que pueden ser asignados a la oportunidad de venta        */ 
                        var productos = $('.firstProduct').html();
                        contador=0;
                        i=0;

                        $('.producto').html('');
                        $('.cantidad').html('');

                        for (var j = 0; j < data.productos.length; j++) {
                            if(j == 0) {
                                $('.producto').append('<div id="producto-' + i + '" ><select id="sProducto-0" style="width:100%;" type="text" name="sProducto[]" class="sProducto firstProduct input-sm form-control validateSelectP">'+productos+'</select></div>');
                                $('.cantidad').append('<input id="txtCantidad-' + i + '" type="text" name="cantidad[]" class="cant input-sm form-control text-right validateInput" min="1">');
                            } else {
                                $('.producto').append('<div id="producto-' + i + '" style="margin-top:6px;"><select id="sProducto-' + i + '" style="width:100%;" type="text" name="sProducto[]" class="sProducto input-sm form-control validateSelectP">'+productos+'</select></div>');
                                $('.cantidad').append('<input id="txtCantidad-' + i + '" type="text" name="cantidad[]" class="cant input-sm form-control text-right validateInput" min="1" style="margin-top:5px;">');

                                if(contador > 1) {
                                    $('.removeRow').append('<button id="deleteProd-' + i + '" class="btn removeProd btn-danger" style="margin-top:7px;"><i class="fa fa-remove"></i></button>');            
                                } else {
                                    if(i == 1) {
                                        $('#deleteProd-0').removeClass('hidden');
                                        $('#deleteProd-0').show();
                                    } else if(contador == 1) {
                                        $('.removeProd').each(function( index, value ) { 
                                            $('#' + $(this).attr('id')).removeClass('hidden');
                                        });
                                    }

                                    $('.removeRow').append('<button id="deleteProd-' + i + '" class="btn removeProd btn-danger" style="margin-top:7px;"><i class="fa fa-remove"></i></button>');
                                }
                            }

                            $('.cant').numeric('.'); 
                            $('#sProducto-' + i).select2();    

                            $('#sProducto-' + i).val(data.productos[j]).change().trigger("change");
                            $('#txtCantidad-' + i).val(data.cantProduct[j]);

                            i++;
                            contador++;                                    
                        } 
                                                
                        if(data.productos.length > 0) {
                            $('#productos').removeClass('hidden');	
                            $("input[name='hayProductos']").prop('checked', true);
                            
                            contador--;
                        } else { 
                            $('.producto').append('<div id="producto-0" ><select id="sProducto-0" style="width:100%;" type="text" name="sProducto[]" class="sProducto firstProduct input-sm form-control validateSelectP">'+productos+'</select></div>');
                            $('.cantidad').append('<input id="txtCantidad-0" type="text" name="cantidad[]" class="cant input-sm form-control text-right validateInput" value="1" min="1">');
                            
                            $('#productos').addClass('hidden');	
                            $("input[name='hayProductos']").prop('checked', false);
                        } /* Fin de seteo de los productos/servicios y sus opciones del select */


                        /* Ocultando Tabla de oportunidades */
                        $('#oppotunitiesList').parent().hide();

                        /* Mostrando el formulario con la información a editar */
                        $('#pnAdd').show();
                        
                        /* Mostrando las cotizaciones vinculadas a la oportunidad de venta */
/****                        $('#pnCotizacion').show();        ****/

                        $('.btnAddPage').addClass('hidden');
                        $('#btnBack').removeClass('hidden');
                        $('#btnCancelTop').removeClass('hidden');
                        $('#btnSaveTop').removeClass('hidden');
/****                        $('#btnNewQuotation').removeClass('hidden');        ****/
                        
                        /* Mostrando data de las cotizaciones vinculadas a la oportunidad de venta */
                        for (var j = 0; j < data.cotizaciones.length; j++) {                            
                            /*$('#cuenta').append('<option selected value="'+data.cuentas[0][0]+'">'+data.cuentas[0][1]+'</option>');*/
                        }

                        if(data.cotizaciones.length > 0) {
                            $('#divQuotes').removeClass('hidden');
                            $('#noQuotes').addClass('hidden');
                        } else {
                            $('#divQuotes').addClass('hidden');
                            $('#noQuotes').removeClass('hidden');
                        } /*  Fin de Data Cotizaciones vinculadas  a la oportunidad de venta  */

                        /*var addItem = '';
                        for (var i = 0; i < data.tags.length; i++) {
                            addItem='<div class="col-xs-1" style="vertical-align:middle;"><a id="'+data.tags[i].id+'" href="" class="tagDelete"><i style="margin-top:3px;vertical-align:middle;" class="fa fa-remove"></i></a></div><div class="col-xs-10">'+data.tags[i].nombre+'</div>';
                            $('#addedTags').append(addItem);
                        }*/
                        /* seguimientoComet(data.id1); */
                        $('#addTag').removeClass('hidden');
                        $('#addedTags').removeClass('hidden');
                        $('#filterTag').addClass('hidden');

                        recuperaDataCuenta = false;  
                        recuperaDataProbabilidad = false;                         
                    }	
                    
                    objClicked.on('click');
                    objClicked.css('cursor', 'pointer');

                    return false;
                },
                error:function(data){
                    if(data.error){
                        swal('',data.error,'error');
                    }

                    $('#addTag').addClass('hidden');
                    $('#addedTags').addClass('hidden');
                    $('#filterTag').removeClass('hidden');

                    objClicked.on('click');
                    objClicked.css('cursor', 'pointer');	
                }
            });
        } else {
            if(id==idForm && selected==0){
                $('#pnAdd').slideDown();
            }
        }
    }); /* Fin del on click de la fila del datatable oppotunitiesList */
    
    /* Al momento de seleccionar un tipo de cuenta filtra las cuentas vinculadas al */
    /* tipo de cuenta seleccionado llenando el combobox con las cuentas recuperadas */
    $(document).on('change', '#tipoCuenta', function(event) {
        var id = $(this).val();
        
        if(!recuperaDataCuenta) {
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
        }
        
        return false;
    }); /* Fin del onChange del combobox tipoCuenta */
    
    /* Al momento de seleccionar una etapa de venta se obtiene la probabilidad de ésta */
    /* se llegue a concluir con exito */
    $(document).on('change', '#etapaVenta', function(event) {
        var id = $(this).val();
        
        if(!recuperaDataProbabilidad) {
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
        }
        
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
        
        $('.responsable').append('<div style="margin-top:27px;"><select id="persona-'+numPersonas+'" style="width:100%;margin-top:25px !important;" name="responsable[]" class="input-sm form-control validateSelectP ">'+personas+'</select></div>');
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