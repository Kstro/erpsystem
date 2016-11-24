var recuperaDataCuenta = true;
var recuperaDataProbabilidad = true;

var contadorQ = 0;
var k = 0;
    
$(document).ready(function() {
    var numPersonas = 0;
    var numPedidos = 0;
    var numPedidosAjaxEdit=0;
    /* Persist datatable (Save method) */
    //var filesSelectedPrev = document.getElementById("file").files;
    //console.log(document.getElementById("file").files);
    $(document).on('change', '#file', function(event) {
        var filesSelected = document.getElementById("file").files;
        if(filesSelectedPrev[0]=="undefined"){
            filesSelectedPrev = document.getElementById("file").files;
        }
        var fileToLoad = filesSelected[0];
        var fileReader = new FileReader();
        if ((filesSelected[0].size<=4096000)){
            fileReader.onload = function(fileLoadedEvent) {
                srcData = fileLoadedEvent.target.result;
                $('#imgTest').attr('src', srcData);
            };
            var imgBase64 = $('#imgTest').attr('src');
            fileReader.readAsDataURL(fileToLoad);
            $('#imgTest').show();
        }
        else{
            $(this).val('');
            $('#imgTest').attr('src', '');
            $('#imgTest').hide();
            swal('',$('.imageError').html(),'error');
        }
    });
    
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
                        
                        limpiarCampos();
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
        numPedidos = 1;
        
        recuperaDataCuenta = true;
        recuperaDataProbabilidad = true;
        
        $('.btnAddCommentGen').attr('id',1);
        $('#iteracion').val(numPedidos);
        $('#comentarios').show();
        $('#wallmessages').show();
        $('#wallmessages').html('');
        $('#addedTags').html('');//limpiar tags anteriores
        $('#addedFiles').html('');//limpiar archivos anteriores
        
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
        
        if (text=='TD' && id!=idForm && selected==0 && numPedidosAjaxEdit==0) {
            numPedidosAjaxEdit=1;
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
                        $('#pnCotizacion').show();        

                        $('.btnAddPage').addClass('hidden');
                        $('#btnBack').removeClass('hidden');
                        $('#btnCancelTop').removeClass('hidden');
                        $('#btnSaveTop').removeClass('hidden');
                        $('#btnNewQuotation').removeClass('hidden');        
                        
                        if(data.cotizaciones.length > 0) {
                            $('#tbodyQuotes').html(''); 
                        }
                        
                        var tbody = '';
                        
                        /* Mostrando data de las cotizaciones vinculadas a la oportunidad de venta */
                        for (var j = 0; j < data.cotizaciones.length; j++) {   
                            tbody+='<tr id="'+data.cotizaciones[j][1]+'">';
                            tbody+='<td style="text-align: center;" >'+'<div id="' + data.cotizaciones[j][1]+'" style="text-align:left"><input style="z-index:5;" class="chkItemQ" type="checkbox"></div></td>';
                            tbody+='<td style="text-align: center;" >'+data.cotizaciones[j][0]+'</td>';
                            tbody+='<td style="text-align: center;" >'+data.cotizaciones[j][3]+'</td>';
                            tbody+='<td style="text-align: left;" >'+data.cotizaciones[j][2]+'</td>';
                            tbody+='<td style="text-align: left;" >'+data.cotizaciones[j][4]+'</td>';   
                            tbody+='<td style="text-align: center;" >'+data.cotizaciones[j][5]+'</td>';   
                            tbody+='<td style="text-align: right;" >'+(data.cotizaciones[j][6]).toFixed(2)+'</td>';   
                            tbody+='</tr>';
                            
                            $('#tbodyQuotes').append(tbody); 
                            tbody = '';
                        }

                        if(data.cotizaciones.length > 0) {                                                        
                            $('#divQuotes').removeClass('hidden');
                            $('#noQuotes').addClass('hidden');
                        } else {
                            $('#divQuotes').addClass('hidden');
                            $('#noQuotes').removeClass('hidden');
                        } /*  Fin de Data Cotizaciones vinculadas  a la oportunidad de venta  */
                        
                        
                       seguimientoGeneral(data.id, numPedidos, null, 5);
                        
                        var addItem = '';
                        for (var i = 0; i < data.tags.length; i++) {
                            addItem='<div class="col-xs-1" style="vertical-align:middle;"><a id="'+data.tags[i].id+'" href="" class="tagDelete"><i style="margin-top:3px;vertical-align:middle;" class="fa fa-remove"></i></a></div><div class="col-xs-10">'+data.tags[i].nombre+'</div>';
                            $('#addedTags').append(addItem);
                        }

                        for (var i = 0; i < data.docs.length; i++) {
                            if(data.docs[i].estado==1){
                                var addItem='<div class="col-xs-1" style="vertical-align:middle;">';

                                addItem+='<a id="'+data.docs[i].id+'" href="" class="fileDelete">';
                                addItem+='<i style="margin-top:3px;vertical-align:middle;" class="fa fa-remove"></i>';
                                addItem+='</a>';

                                addItem+='</div><div class="col-xs-10">';
                                addItem+='<a target="_blank" href="../../../files/opportunities/';
                                addItem+=data.docs[i].nombre;
                                addItem+='">';

                                addItem+=data.docs[i].nombre;
                                addItem+='</a>';
                                addItem+='</div>';
                                
                                $('#addedFiles').append(addItem);
                            }
                        }

                        $('#addTag').removeClass('hidden');
                        $('#addedTags').removeClass('hidden');
                        $('#addedFiles').removeClass('hidden');
                        $('#filterTag').addClass('hidden');
                        $('#addFile').removeClass('hidden');
                        $('#btnLoadMoreFiles').removeClass('hidden');
                        
                        recuperaDataCuenta = false;  
                        recuperaDataProbabilidad = false;                         
                    }	
                    
                    numPedidosAjaxEdit=0;
                    
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
                    
                    numPedidosAjaxEdit=0;
                    
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
    
    
    /* Al momento que se desea eliminar una o varias oportunidades de venta */
    $(document).on('click', '.btnDelete', function(event) {
        var $btn = $(this).button('loading');
        /* Definición de variables */
        var id=$(this).children().first().children().attr('id');
        var ids=[];
        var table = $('#oppotunitiesList').DataTable();
        
        $('.chkItem').each(function() {
            if ($(this).is(':checked')) {
                ids.push($(this).parent().attr('id'));
            }
        });	
            
        swal({
                title: "",
                text: "Remove selected rows?",
                type: "info",
                showCancelButton: true,
                confirmButtonText: "Remove",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: Routing.generate('admin_oportunities_delete_ajax'),
                        type: 'POST',
                        data: {param1: ids},
                        success:function(data){
                            if(data.error){
                                swal('',data.error,'error');
                            }
                            else{
                                $('#txtId').val(data.id);
                                $('#txtName').val(data.name);
                                $("input[name='hayProductos']").prop({'checked': false});
                                $btn.button('reset');
                                table.ajax.reload();
                                    
                                swal('',data.msg,'success');
                            }

                            $('#pnAdd').slideUp();
                        },
                        error:function(data){
                            if(data.error){
                                /*console.log(data.id);*/
                                swal('',data.error,'error');
                            }
                            
                            $btn.button('reset');
                        }
                    });
                    
                    $('.btnDelete').addClass('hidden');
                    $('.btnAction').addClass('hidden');
                    $('.btnAddPage').removeClass('hidden');
                }
        });
        
        $btn.button('reset');		
    });
    /*/////Fin definición persist data (Delete method)*/
    
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
    
    /* Al momento que se dé click en guardar la información del formulario frmQuotes        */
    /* Para registrar la información ingresada de la cotizaci+on vinculada a la oportunidad */
    $('#frmQuotes').on('submit',(function(event) {
        event.preventDefault();
        /*var table = $('#oppotunitiesList').DataTable();*/
        var errores = 0;
        var $btn;
        var idOportunidad = $("#txtId").val();
        var form = new FormData(this);
        form.append("idOportunidad", idOportunidad);
        
        $btn = $('#btnSaveQuote').button('loading');
        
        /* Verificando si se ha ingresado la información necesaria de la oportunidad */
        $('.validateSelectQ').each(function() {
            if (!requiredSelectP($(this))) {
                $(this).next().children().children().addClass('errorform');
                errores++;
            }
        });        
        $('.validateInputQ').each(function() {
            if (!required($(this))) {
                $(this).addClass('errorform');
                errores++;
            }
        });
        
        /* Si se ha ingresado toda la información necesaria sobre la oportunidad de venta */
        if (errores==0) {
            $.ajax({
                url: Routing.generate('admin_opportunity_quotes_save_ajax'),
                type: "POST",            
                data: form,
                contentType: false,      
                cache: false,            
                processData:false,     
                success: function(data)  
                {
                    /*$("#message").html(data);*/
                    $("#txtIdQuote").val(data.id);                    
                    
                    if(data.msg){
                        swal('',data.msg,'success');
                        
                        if(data.cotizaciones.length > 0) {
                            $('#tbodyQuotes').html(''); 
                        }
                        
                        var tbody = '';
                        
                        /* Mostrando data de las cotizaciones vinculadas a la oportunidad de venta */
                        for (var j = 0; j < data.cotizaciones.length; j++) {   
                            tbody+='<tr id="'+data.cotizaciones[j][1]+'">';
                            tbody+='<td style="text-align: center;" >'+'<div id="' + data.cotizaciones[j][1]+'" style="text-align:left"><input style="z-index:5;" class="chkItemQ" type="checkbox"></div></td>';
                            tbody+='<td style="text-align: center;" >'+data.cotizaciones[j][0]+'</td>';
                            tbody+='<td style="text-align: center;" >'+data.cotizaciones[j][3]+'</td>';
                            tbody+='<td style="text-align: left;" >'+data.cotizaciones[j][2]+'</td>';
                            tbody+='<td style="text-align: left;" >'+data.cotizaciones[j][4]+'</td>';   
                            tbody+='<td style="text-align: center;" >'+data.cotizaciones[j][5]+'</td>';   
                            tbody+='<td style="text-align: right;" >'+(data.cotizaciones[j][6]).toFixed(2)+'</td>';   
                            tbody+='</tr>';
                            
                            $('#tbodyQuotes').append(tbody); 
                            tbody = '';
                        }

                        if(data.cotizaciones.length > 0) {                                                        
                            $('#divQuotes').removeClass('hidden');
                            $('#noQuotes').addClass('hidden');
                        } else {
                            $('#divQuotes').addClass('hidden');
                            $('#noQuotes').removeClass('hidden');
                        } /*  Fin de Data Cotizaciones vinculadas  a la oportunidad de venta  */
                        
                        limpiarCamposDivQuotes();
                        $('#btnSaveTop').removeClass('hidden');
                        $('#btnSave').removeClass('hidden');

                        $('#detalleQuote').addClass('hidden');
                        $('#btnAddQuote').removeClass('hidden');
                        $('#divQuotes').removeClass('hidden');
                        
                        $btn.button('reset');
                    }
                    if(data.error){
                        swal('',data.error,'error');
                        $btn.button('reset');
                    }
                    
                    /*table.ajax.reload();*/
                    $btn.button('reset');
                    
                    $('#btnSaveTop').addClass('hidden');
                    $('#btnCancelTop').addClass('hidden');
                    /*$('.btnNewQuotation').removeClass('hidden');*/
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
    })); /* Fin del submit del formulario frmQuotes */
    
    /* Al momento de hacer click en una cotización de una determinada oportunidad de venta. */
    /* Mostrando a detalle la cotización que se ha seleccionado, para su edición            */
    $(document).on('click', '#tablaQuotes>tbody>tr>td:nth-child(2),#tablaQuotes>tbody>tr>td:nth-child(3),#tablaQuotes>tbody>tr>td:nth-child(4),#tablaQuotes>tbody>tr>td:nth-child(5),#tablaQuotes>tbody>tr>td:nth-child(6)', function(event) {
        var text = $(this).prop('tagName');
        var id = $(this).parent().attr('id');
        var idForm = $('#txtIdQuote').val();
        var selected = 0;
        var objClicked = $(this);
        
        /* Verificando si se ha seleccionado algún checkbox del datatable */
        $('.chkItemQ').each(function() {
            if ($(this).is(':checked')) {
                selected++;
            }
        });
        
        if (text=='TD' && id!=idForm && selected==0) {
            objClicked.off('click');
            objClicked.css('cursor','progress');
            $.ajax({
                url: Routing.generate('admin_quotes_opportunities_retrieve_ajax'),
                type: 'POST',
                data: {param1: id},
                success:function(data){
                    if(data.error){
                        swal('',data.error,'error');
                        id.val(data.id);
                    }
                    else{
                        /* Seteando Id de la cotización en campo de texto oculto */
                        $('#txtIdQuote').val(data.id);

                        /* seteando a la persona que realiza la cotización en su respectivo select */
                        $('#assignedUserQuote').val(data.assignedTo).change().trigger("change");

                        /* seteando el valor del estado de la cotización en su respectivo select */
                        $('#statusQuote').val(data.status).change().trigger("change");

                        /* Seteando fecha de vencimiento de la cotización en su respectivo campo de texto */
                        $('#txtDateExpirationQuote').val(data.validUntil);
                        
                        /* Seteando las condiciones generales de la cotización en su respectivo textarea */
                        $('#conditionssQuote').val(data.gnalConditions);        
                        
                        /* Seteando los productos / servicios vinculados  a la cotización, así como las opciones */
                        /* de los productos / servicios que pueden ser asignados a la cotizacion                 */ 
                        var itemsQ = $('.firstItemQ').html();
                        contadorQ=0;
                        k=0;

                        $('.cantItemQ').html('');
                        $('.itemsQ').html('');
                        $('.priceItemQ').html('');
                        $('.taxItemQ').html('');
                        $('.totalItemQ').html('');

                        for (var j = 0; j < data.items.length; j++) {
                            if(j == 0) {
                                $('.itemsQ').append('<div id="itemQ-' + k + '" ><select id="sItemQ-' + k + '" style="width:100%;" type="text" name="sItemQ[]" class="sItemQ firstItemQ input-sm form-control validateSelectQ">'+itemsQ+'</select></div>');
                                $('.cantItemQ').append('<input id="txtCantItemQ-' + k + '" type="text" name="txtCantItemQ[]" class="cantQ input-sm form-control text-right validateInputQ" min="1">');
                                $('.priceItemQ').append('<input id="txtPriceItemQ-' + k + '" type="text" name="txtPriceItemQ[]" class="priceQ input-sm form-control text-right validateInputOQ" min="1">');
                                $('.taxItemQ').append('<input id="txtTaxItemQ-' + k + '" type="text" name="txtTaxItemQ[]" class="taxQ input-sm form-control text-right validateInputOQ" min="1">');
                                $('.totalItemQ').append('<div id="totalItemQ-' + k + '" style="margin-top: 10px;"><label class="totalQ control-label">'+(data.totalItem[j]).toFixed(2)+'</label></div>');
                            } else {
                                $('.itemsQ').append('<div id="itemQ-' + k + '" style="margin-top:7px;"><select id="sItemQ-' + k + '" style="width:100%;" type="text" name="sItemQ[]" class="sItemQ input-sm form-control validateSelectQ">'+itemsQ+'</select></div>');
                                $('.cantItemQ').append('<input id="txtCantItemQ-' + k + '" type="text" name="txtCantItemQ[]" class="cantQ input-sm form-control text-right validateInputQ" min="1" style="margin-top:5px;">');
                                $('.priceItemQ').append('<input id="txtPriceItemQ-' + k + '" type="text" name="txtPriceItemQ[]" class="priceQ input-sm form-control text-right validateInputOQ" min="1" style="margin-top:5px;">');
                                $('.taxItemQ').append('<input id="txtTaxItemQ-' + k + '" type="text" name="txtTaxItemQ[]" class="taxQ input-sm form-control text-right validateInputOQ" min="1" style="margin-top:5px;">');
                                $('.totalItemQ').append('<div id="totalItemQ-' + k + '" style="margin-top: 10px;"><label class="totalQ control-label">'+(data.totalItem[j]).toFixed(2)+'</label></div>');

                                if(contadorQ > 1) {
                                    $('.removeRowQ').append('<button id="deleteItemQ-' + k + '" class="btn removeItemQ btn-danger" style="margin-top:7px;"><i class="fa fa-remove"></i></button>');            
                                } else {
                                    if(k == 1) {
                                        $('#deleteItemQ-0').removeClass('hidden');
                                        $('#deleteItemQ-0').show();
                                    } else if(contadorQ == 1) {
                                        $('.removeItemQ').each(function( index, value ) { 
                                            $('#' + $(this).attr('id')).removeClass('hidden');
                                        });
                                    }

                                    $('.removeRowQ').append('<button id="deleteItemQ-' + k + '" class="btn removeItemQ btn-danger" style="margin-top:7px;"><i class="fa fa-remove"></i></button>');
                                }
                            }

                            $('.cantQ').numeric('.'); 
                            $('.priceQ').numeric('.'); 
                            $('.taxQ').numeric('.'); 
                            $('#sItemQ-' + k).select2();    

                            $('#sItemQ-' + k).val(data.items[j]).change().trigger("change");
                            $('#txtCantItemQ-' + k).val(data.cantItem[j]);
                            $('#txtPriceItemQ-' + k).val(data.priceItem[j]);
                            $('#txtTaxItemQ-' + k).val(data.tax[j]);                                                        

                            k++;
                            contadorQ++;                                    
                        } 
                                                
                        if(data.items.length > 0) {
                            $('.subTotalVenta').html((data.priceSale).toFixed(2));
                            $('.totalTaxVenta').html((data.taxTotal).toFixed(2));
                            $('.totalVenta').html((data.priceTotal).toFixed(2));
                            
                            contadorQ--;
                        } else {
                            $('.itemsQ').append('<div id="itemQ-' + k + '" ><select id="sItemQ-' + k + '" style="width:100%;" type="text" name="sItemQ[]" class="sItemQ firstItemQ input-sm form-control validateSelectQ">'+itemsQ+'</select></div>');
                            $('.cantItemQ').append('<input id="txtCantItemQ-' + k + '" type="text" name="txtCantItemQ[]" class="cantQ input-sm form-control text-right validateInputQ" min="1">');
                            $('.priceItemQ').append('<input id="txtPriceItemQ-' + k + '" type="text" name="txtPriceItemQ[]" class="priceQ input-sm form-control text-right validateInputQ" min="1">');
                            $('.taxItemQ').append('<input id="txtTaxItemQ-' + k + '" type="text" name="txtTaxItemQ[]" class="taxQ input-sm form-control text-right validateInputQ" min="1">');
                            $('.totalItemQ').append('<div id="totalItemQ-' + k + '" style="margin-top: 10px;"><label class="totalQ control-label">'+(data.totalItem[j]).toFixed(2)+'</label></div>');
                        }
                        /* Fin de seteo de los productos/servicios y sus opciones del select */
                        
                        $('#detalleQuote').removeClass('hidden');
                        $('#btnAddQuote').addClass('hidden');
                        $('#btnNewQuotation').addClass('hidden');
                        $('#btnCancelQuote').removeClass('hidden');  
                        $('#divQuotes').addClass('hidden');
                        
                        $('#btnSaveTop').addClass('hidden');
                        $('#btnSave').addClass('hidden');
                    }	                                        

                    return false;
                },
                error:function(data){
                    if(data.error){
                        swal('',data.error,'error');
                    }                   	
                }
            });    
        } else {
            if(id==idForm && selected==0){
                $('#detalleQuote').addClass('hidden');
                $('#btnAddQuote').removeClass('hidden');
                $('#divQuotes').removeClass('hidden');
            }
        }
    });
    
    $(document).on('click', '#btnAddRowQ', function(event) {
        k++;
        contadorQ++;
        
        /* Obteniendo las opciones del select de los productos / servicios  */
        var itemsQ = $('.firstItemQ').html();
        
        $('.itemsQ').append('<div id="itemQ-' + k + '" style="margin-top:7px;"><select id="sItemQ-' + k + '" style="width:100%;" type="text" name="sItemQ[]" class="sItemQ firstItemQ input-sm form-control validateSelectQ">'+itemsQ+'</select></div>');
        $('.cantItemQ').append('<input id="txtCantItemQ-' + k + '" type="text" name="txtCantItemQ[]" class="cantQ input-sm form-control text-right validateInputQ" min="1" value="1" style="margin-top: 5px;">');
        $('.priceItemQ').append('<input id="txtPriceItemQ-' + k + '" type="text" name="txtPriceItemQ[]" class="priceQ input-sm form-control text-right" validateInputQ" value="0.00" min="0" style="margin-top: 5px;">');
        $('.taxItemQ').append('<input id="txtTaxItemQ-' + k + '" type="text" name="txtTaxItemQ[]" class="taxQ input-sm form-control text-right" validateInputQ" value="0.00" min="0" style="margin-top: 5px;">');
        $('.totalItemQ').append('<div id="totalItemQ-' + k + '" style="margin-top: 10px;"><label class="totalQ control-label">0.00</label></div>');
        
        $('.cantQ').numeric('.'); 
        $('.priceQ').numeric('.'); 
        $('.taxQ').numeric('.'); 
        $('#sItemQ-' + k).select2();              
                
        if(contadorQ > 1) {
            $('.removeRowQ').append('<button id="deleteItemQ-' + k + '" class="btn removeItemQ btn-danger" style="margin-top:7px;"><i class="fa fa-remove"></i></button>');
        } else {
            if(k == 1) {
                $('#deleteItemQ-0').removeClass('hidden');
                $('#deleteItemQ-0').show();
            } else if(contadorQ == 1) {
                $('.removeItemQ').each(function( index, value ) { 
                    $('#' + $(this).attr('id')).removeClass('hidden');
                });
            }
            
            $('.removeRowQ').append('<button id="deleteItemQ-' + k + '" class="btn removeItemQ btn-danger" style="margin-top:7px;"><i class="fa fa-remove"></i></button>');
        }
        
        return false;
    });
    
    /* Al momento de eliminar una fila del detalle de una determinada cotizacion */
    $(document).on('click', '.removeItemQ', function(event) {
        var numDel = $(this).attr('id');
        var numDelArray = numDel.split('-');
        var corr = $(this).attr('id');
        var corrArray = corr.split('-');
        var totalItem = parseFloat($('#totalItemQ-' + corrArray[1]).children().html());
        
        var tax = parseFloat($('#txtTaxItemQ-' + corrArray[1]).val())/100;
        var price = parseFloat($('#txtPriceItemQ-' + corrArray[1]).val());
        var quantity = parseFloat($('#txtCantItemQ-' + corrArray[1]).val());
        
        var totalVenta = parseFloat($('.totalVenta').html());   
        var totalTax = parseFloat($('.totalTaxVenta').html());   
        var totalTaxNvo = totalTax - (quantity * price * tax);   
        
        contadorQ--;
        $('#txtCantItemQ-' + numDelArray[1]).remove();
        $('#itemQ-' + numDelArray[1]).remove();
        $('#txtPriceItemQ-' + numDelArray[1]).remove();
        $('#txtTaxItemQ-' + numDelArray[1]).remove();
        $('#deleteItemQ-' + numDelArray[1]).remove();
        $('#totalItemQ-' + numDelArray[1]).remove();
        
        $('.subTotalVenta').html(((totalVenta - totalItem) - totalTaxNvo).toFixed(2));  
        $('.totalVenta').html(((totalVenta - totalItem)- (quantity * price * tax)).toFixed(2));  
        $('.totalTaxVenta').html((totalTaxNvo).toFixed(2));                            
                
        if(contadorQ == 0) {
            $('.removeItemQ').each(function( index, value ) { 
               $('#' + $(this).attr('id')).addClass('hidden');
            });
        }
        
        return false;
    });
    
    /* Al momento que se desea eliminar una o varias oportunidades de venta */
    $(document).on('click', '.btnDeleteQuotation', function(event) {
        var $btn = $(this).button('loading');
        /* Definición de variables */
        var id = $(this).parent().attr('id');
        var ids=[];
        var idOportunidad = $("#txtId").val();
        /*var table = $('#oppotunitiesList').DataTable();*/
        
        $('.chkItemQ').each(function() {
            if ($(this).is(':checked')) {
                ids.push($(this).parent().attr('id'));
            }
        });	
            
        swal({
                title: "",
                text: "Remove selected rows?",
                type: "info",
                showCancelButton: true,
                confirmButtonText: "Remove",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: Routing.generate('admin_quotes_delete_ajax'),
                        type: 'POST',
                        data: {param1: ids, param2: idOportunidad},
                        success:function(data){
                            if(data.error){
                                swal('',data.error,'error');
                            }
                            else{
                                $('#txtIdQuote').val('');
                                /*$('#txtName').val(data.name);*/
                                $("input[name='hayProductosQ']").prop({'checked': false});
                                
                                if(data.cotizaciones.length > 0) {
                                    $('#tbodyQuotes').html(''); 
                                }

                                var tbody = '';

                                /* Mostrando data de las cotizaciones vinculadas a la oportunidad de venta */
                                for (var j = 0; j < data.cotizaciones.length; j++) {   
                                    tbody+='<tr id="'+data.cotizaciones[j][1]+'">';
                                    tbody+='<td style="text-align: center;" >'+'<div id="' + data.cotizaciones[j][1]+'" style="text-align:left"><input style="z-index:5;" class="chkItemQ" type="checkbox"></div></td>';
                                    tbody+='<td style="text-align: center;" >'+data.cotizaciones[j][0]+'</td>';
                                    tbody+='<td style="text-align: center;" >'+data.cotizaciones[j][3]+'</td>';
                                    tbody+='<td style="text-align: left;" >'+data.cotizaciones[j][2]+'</td>';
                                    tbody+='<td style="text-align: left;" >'+data.cotizaciones[j][4]+'</td>';   
                                    tbody+='<td style="text-align: center;" >'+data.cotizaciones[j][5]+'</td>';   
                                    tbody+='<td style="text-align: right;" >'+(data.cotizaciones[j][6]).toFixed(2)+'</td>';   
                                    tbody+='</tr>';

                                    $('#tbodyQuotes').append(tbody); 
                                    tbody = '';
                                }

                                if(data.cotizaciones.length > 0) {                                                        
                                    $('#divQuotes').removeClass('hidden');
                                    $('#noQuotes').addClass('hidden');
                                } else {
                                    $('#divQuotes').addClass('hidden');
                                    $('#noQuotes').removeClass('hidden');
                                } /*  Fin de Data Cotizaciones vinculadas  a la oportunidad de venta  */
                                
                                $('.btnNewQuotation').removeClass('hidden');
                                $('.btnDeleteQuotation').addClass('hidden');
                                
                                $btn.button('reset');
                                swal('',data.msg,'success');
                            }

                            /*$('#pnAdd').slideUp();*/
                        },
                        error:function(data){
                            if(data.error){
                                /*console.log(data.id);*/
                                swal('',data.error,'error');
                            }
                            
                            $btn.button('reset');
                        }
                    });
                    
                    $('.btnDeleteQuotation').addClass('hidden');
                    /*$('.btnAction').addClass('hidden');*/
                    $('.btnNewQuotation').removeClass('hidden');
                }
        });
        
        $btn.button('reset');		
    });
    /*/////Fin definición persist data (Delete method)*/
});

/* Función que pone el formulario de registro de cotizaciones en blanco*/
function limpiarCamposDivQuotes() {
    contadorQ=0;
    k=0;
    
    /* Obteniendo las opciones del select de los productos / servicios  */
    var itemsQ = $('.firstItemQ').html();
    
    /* Limpiando los campos de texto */
    $('#txtIdQuote').val('');
    $('#conditionssQuote').val('');
    $('#txtDateExpirationQuote').val('');

    /*$('#txtAddComment').val('');*/
    
    /* Limpiando los select */
    $('#assignedUserQuote').val('0').change().trigger("change");
    $('#statusQuote').val('0').change().trigger("change");

    /* Removiendo la clase errorform de los campos de texto */
    $('.validateInput').each(function(index, el) {
        $(this).removeClass('errorform');
    });
    
    /* Removiendo la clase errorform de los select */
    $('.validateSelectP').each(function(index, el) {
        $(this).next().children().children().removeClass('errorform');
    });

    /* Removiendo la clase */
    $('#btnNewQuotation').removeClass('hidden');
    $('#btnAddQuote').removeClass('hidden');
    
    /* Reseteando el bloque de productos/Servicios */
    $('.cantItemQ').html('');
    $('.itemsQ').html('');
    $('.priceItemQ').html('');
    $('.taxItemQ').html('');
    $('.totalItemQ').html('');
    $('.removeRowQ').html('');
           
    $('.removeRowQ').append('<button id="deleteItemQ-' + k + '" class="btn removeItemQ btn-danger hidden"><i class="fa fa-remove"></i></button>');
    $('.itemsQ').append('<div id="itemQ-' + k + '" ><select id="sItemQ-' + k + '" style="width:100%;" type="text" name="sItemQ[]" class="sItemQ firstItemQ input-sm form-control validateSelectQ">'+itemsQ+'</select></div>');
    $('.cantItemQ').append('<input id="txtCantItemQ-' + k + '" type="text" name="txtCantItemQ[]" class="cantQ input-sm form-control text-right validateInputQ" min="1" value="1">');
    $('.priceItemQ').append('<input id="txtPriceItemQ-' + k + '" type="text" name="txtPriceItemQ[]" class="priceQ input-sm form-control text-right validateInputQ" min="1" value="0.00">');
    $('.taxItemQ').append('<input id="txtTaxItemQ-' + k + '" type="text" name="txtTaxItemQ[]" class="taxQ input-sm form-control text-right validateInputQ" min="1" value="0.00">');
    $('.totalItemQ').append('<div id="totalItemQ-' + k + '" style="margin-top: 10px;"><label class="totalQ control-label">0.00</label></div>');
    
    $('.cantQ').numeric('.'); 
    $('.priceQ').numeric('.'); 
    $('.taxQ').numeric('.'); 
    $('#sItemQ-' + k).select2();    
    
    $('.subTotalVenta').html('0.00');
    $('.totalTaxVenta').html('0.00');
    $('.totalVenta').html('0.00');

    
    /* Agregando la clase hidden*/
    /*$('.btnDelete').addClass('hidden');*/
    $('#btnCancelQuote').addClass('hidden');  
    
} /* Fin de Función que pone el formulario de registro de cotizaciones en blanco */