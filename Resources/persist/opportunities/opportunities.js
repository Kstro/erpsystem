$(document).ready(function() {
    var numPersonas = 0;
    
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
        var text = $(this).prop('tagName');
        var id=$(this).parent().children().first().children().attr('id');
        var idForm=$('#txtId').val();
        var selected = 0;
        var objClicked = $(this);
        
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
                                /*// console.log(data);*/
                                $('#txtId1').val(data.id1);
                                $('#txtId2').val(data.id2);
                                $('#dpbTitulo').val(data.titulo);
                                $('#txtName').val(data.nombre);
                                $('#txtApellido').val(data.apellido);
                                $('#txtCompania').val(data.compania);
                                /*// console.log(data.addressArray);*/
                                var numDirecciones = data.addressArray.length;
                                var numTelefonos = data.phoneArray.length;
                                var numCorreos = data.emailArray.length;
                                $('.dpbTipoPersona').val(data.entidad).change().trigger("change");
                                /*// Direcciones*/
                                for (var i = 0; i < numDirecciones; i++) {
                                        /*// console.log(i);*/
                                        /*// console.log(data.addressArray[i]);*/
                                        switch(i){
                                                case 0:
                                                        /*$(".dpbStateFirst").val(data.stateArray[i]).trigger("change");
                                                        $(".dpbCityFirst").val(data.cityArray[i]).trigger("change");
                                                        $('.txtAddressFirst').val(data.addressArray[i]);*/

                                                        /*$(".dpbStateFirst").val(data.stateArray[i]);
                                                        $(".dpbCityFirst").val(data.cityArray[i]);
                                                        $('.txtAddressFirst').val(data.addressArray[i]);*/

                                                        /*****************/
                                                        $('.txtState').val(data.stateArray[i]);
                                                        $('.txtcity').val(data.cityArray[i]);
                                                        $('.txtAddress').val(data.addressArray[i]);
                                                        $('.txtZipCode').val(data.zipCodeArray[i]);

                                                break;
                                                default:
                                                        $('#plusAddress').click();
                                                        $("#state-"+(numAddress)).val(data.stateArray[i]);
                                                        $("#city-"+(numAddress)).val(data.cityArray[i]);
                                                        $('#address-'+(numAddress)).val(data.addressArray[i]);
                                                        $('#zip-'+(numAddress)).val(data.zipCodeArray[i]);
                                                break;
                                        }
                                }
                                /*// Telefonos*/
                                for (var i = 0; i < numTelefonos; i++) {
                                        /*// console.log(i);*/
                                        /*// console.log(data.addressArray[i]);*/
                                        switch(i){
                                                case 0:
                                                        $(".firstPhoneType").val(data.typePhoneArray[i]).trigger("change");

                                                        $('.firstPhoneTxt').val(data.phoneArray[i]);
                                                        $('.firstPhoneExtension').val(data.extPhoneArray[i]);
                                                break;
                                                default:
                                                        $('#plusPhone').click();
                                                        /*//$('#types-'+(numPhones)).val(data.typePhoneArray[i]).change();*/
                                                        $('#types-'+(numPhones)).val(data.typePhoneArray[i]).trigger("change");

                                                        $('#phones-'+(numPhones)).val(data.phoneArray[i]);
                                                        $('#extension-'+(numPhones)).val(data.extPhoneArray[i]);
                                                break;
                                        }
                                }
                                /*// Correos*/
                                for (var i = 0; i < numCorreos; i++) {
                                        /*// console.log(i);*/
                                        /*// console.log(data.addressArray[i]);*/
                                        switch(i){
                                                case 0:
                                                        $('.txtEmailFirst').val(data.emailArray[i]);
                                                break;
                                                default:
                                                        $('#plusEmail').click();
                                                        $('#email-'+(numEmail)).val(data.emailArray[i]);
                                                break;
                                        }
                                }
                                if(data.src!=''){
                                        $('#imgTest').attr('src','../../../photos/accounts/'+data.src);	
                                }
                                else{
                                        $('#imgTest').attr('src','http://placehold.it/250x250');
                                }

                                $('.dpbInteres').val(data.interes).change().trigger("change");
                                $('.dpbEstado').val(data.estado).change().trigger("change");
                                $('.dpbFuente').val(data.fuente).change().trigger("change");
                                /*// $('.dpbCampania').val(data.campania).change().trigger("change");*/


                                $('#pnAdd').show();
                                $('.btnAddPage').addClass('hidden');
                                $('#clientePotencialList').parent().hide();
                                $('#btnBack').removeClass('hidden');
                                $('#btnCancelTop').removeClass('hidden');
                                $('#btnSaveTop').removeClass('hidden');
                                /*seguimiento(data.id1, numPedidos,null);*/
                                seguimientoGeneral(data.id1, numPedidos,null,1);
                                /*cargarTags();*/
                                var addItem = '';
                                for (var i = 0; i < data.tags.length; i++) {
                                    /*console.log(i);*/
                                    addItem='<div class="col-xs-1" style="vertical-align:middle;"><a id="'+data.tags[i].id+'" href="" class="tagDelete"><i style="margin-top:3px;vertical-align:middle;" class="fa fa-remove"></i></a></div><div class="col-xs-10">'+data.tags[i].nombre+'</div>';
                                    $('#addedTags').append(addItem);
                                }
                                /*//seguimientoComet(data.id1);*/
                                $('#addTag').removeClass('hidden');
                                $('#addedTags').removeClass('hidden');
                                $('#filterTag').addClass('hidden');
                        }	
                        objClicked.on('click');
                        objClicked.css('cursor', 'pointer');
                },
                error:function(data){
                        if(data.error){
                                /*// console.log(data.id);*/
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