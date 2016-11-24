var i = 0;
var contador = 0;
var totalVenta = 0;

$(document).ready(function() {
    limpiarCampos();
    
    // Estableciendo los combobox como Select2
    $('#tipoCuenta').select2();
    $('#cuenta').select2();
    $('#etapaVenta').select2();
    $('#fuente').select2();
    $('#campania').select2();
    $('#sProducto-0').select2();
    $('.firstResponsable').select2();
    
    $('#assignedUserQuote').select2();
    $('#statusQuote').select2();
    $('#sItemQ-0').select2();
    
    $('#txtProbability').numeric('.'); 
    
    // Al hacer click en el Botón "Add"
    $(document).on('click', '.btnAddPage', function(event) {
            var id = $('#txtId').val();
            
            $('#comentarios').hide();
            $('#wallmessages').hide();

            $('#btnLoadMore').hide();
            $('#addTag').addClass('hidden');
            $('#addFile').addClass('hidden');
            $('#addedTags').addClass('hidden');
            $('#filterTag').addClass('hidden');
            
            recuperaDataCuenta = false;
            recuperaDataProbabilidad = false;

            /*//Cambiar nombre del panel heading para add (Inserción)*/
            $('.pnHeadingLabelAdd').removeClass('hidden');
            $('.pnHeadingLabelEdit').addClass('hidden');
            if (id!='') {
                limpiarCampos();
                $('#btnBack').removeClass('hidden');
                $('#btnCancelTop').removeClass('hidden');
                $('#btnSaveTop').removeClass('hidden');
                $(this).addClass('hidden');
            }
            else{
                $('#pnAdd').show();
                $('#oppotunitiesList').parent().hide();
            }

            $('#btnSaveTop').removeClass('hidden');
            $('#btnCancelTop').removeClass('hidden');
            $('.btnAddPage').addClass('hidden');
            $('#txtName').focus();
    });
    
    $(document).on('click', '#btnCancel,#btnBack,#btnCancelTop', function(event) {
        recuperaDataCuenta = true;
        recuperaDataProbabilidad = true;
        
        $('#pnAdd').hide();
        $('#oppotunitiesList').parent().show();
        $('#btnSaveTop').addClass('hidden');
        $('#btnCancelTop').addClass('hidden');
        $('.btnAddPage').removeClass('hidden');
        limpiarCampos();

        return false;	
    });
    
    $('#datetimepicker1').datetimepicker({
        format: 'Y/MM/DD HH:mm',
        allowInputToggle:true,
        ignoreReadonly:true,
        // minDate: Date(),
    });
    
    $('#datetimepicker2').datetimepicker({
        format: 'Y/MM/DD',
        allowInputToggle:true,
        ignoreReadonly:true,
        // minDate: Date(),
    });
    
    $("input[name='hayProductos']").change(function(){
        if ($(this).is(':checked')) {
            $('.sProducto').addClass('validateSelectP');
            $('.cant').addClass('validateInput');
            $('#productos').removeClass('hidden');	
        } else {
            $('.sProducto').removeClass('validateSelectP');
            $('.cant').removeClass('validateInput');
            $('#productos').addClass('hidden');
        }
    });
    
    $(document).on('click', '#btnAddRow', function(event) {
        i++;
        contador++;
        var productos = $('.firstProduct').html();

        $('.producto').append('<div id="producto-' + i + '" style="margin-top:6px;"><select id="sProducto-' + i + '" style="width:100%;" type="text" name="sProducto[]" class="sProducto input-sm form-control validateSelectP">'+productos+'</select></div>');
        $('.cantidad').append('<input id="txtCantidad-' + i + '" type="text" name="cantidad[]" class="cant input-sm form-control text-right validateInput" value="1" min="1" style="margin-top:5px;">');
        $('.cant').numeric('.'); 
        $('#sProducto-' + i).select2();                
                
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
        
        return false;
    });
    
    $(document).on('click', '.removeProd', function(event) {
        var numDel = $(this).attr('id');
        var numDelArray = numDel.split('-');
        
        contador--;
        console.log(contador);
        $('#txtCantidad-' + numDelArray[1]).remove();
        $('#producto-' + numDelArray[1]).remove();
        $('#deleteProd-' + numDelArray[1]).remove();
                
        if(contador == 0) {
            $('.removeProd').each(function( index, value ) { 
               $('#' + $(this).attr('id')).addClass('hidden');
            });
        }
        
        return false;
    });
    
    $(document).on('click', '.chkItem', function(event) {
        var count = 0;
        var totalchk = $('.chkItem').length;        
        
        if ($(this).is(':checked')) {
            $('.btnAddPage').addClass('hidden');
            $('.btnDelete').removeClass('hidden');
            
            $('.chkItem').each( function() {			
                if ($(this).is(':checked')) {
                    count++;
                }
            });
            
            if(count == totalchk){
                $("input[name=checktodos]").prop({'checked': true});
            }
        } else {
            $("input[name=checktodos]").prop({'checked': false});
            
            $('.chkItem').each( function() {			
                if ($(this).is(':checked')) {
                    count++;
                }
            });
            
            if(count == 0){
                $('.btnAddPage').removeClass('hidden');
                $('.btnDelete').addClass('hidden');
            }
        }
    });
    
    $(document).on('click', '.chkItemQ', function(event) {
        var count = 0;
        var totalchk = $('.chkItemQ').length;        
        
        if ($(this).is(':checked')) {
            $('.btnNewQuotation').addClass('hidden');
            $('.btnDeleteQuotation').removeClass('hidden');
            
            $('.chkItemQ').each( function() {			
                if ($(this).is(':checked')) {
                    count++;
                }
            });
            
            if(count == totalchk){
                $("input[name=checktodosQ]").prop({'checked': true});
            }
        } else {
            $("input[name=checktodosQ]").prop({'checked': false});
            
            $('.chkItemQ').each( function() {			
                if ($(this).is(':checked')) {
                    count++;
                }
            });
            
            if(count == 0){
                $('.btnNewQuotation').removeClass('hidden');
                $('.btnDeleteQuotation').addClass('hidden');
            }
        }
    });
    
    /* Al hacer click en el Botón para registrar una nueva cotización */
    /* desde la gestión de oportunidades de venta                     */
    $(document).on('click', '.btnNewQuotation', function(event) {
            var id = $('#txtIdQuote').val();
            recuperaDataCuenta = false;
            recuperaDataProbabilidad = false;

            if (id!='') {
                limpiarCamposDivQuotes();
                /*$('#btnBack').removeClass('hidden');
                $('#btnCancelTop').removeClass('hidden');
                $('#btnSaveTop').removeClass('hidden');*/
                $(this).addClass('hidden');
            }
            else{
                $('#detalleQuote').removeClass('hidden');
                $('#divQuotes').addClass('hidden');
            }

            $('#btnAddQuote').addClass('hidden');
            $('#btnNewQuotation').addClass('hidden');
            $('#btnCancelQuote').removeClass('hidden');  
            
            $('#btnSaveTop').addClass('hidden');
            $('#btnSave').addClass('hidden');
            /*$('#txtName').focus();*/
    });
    
    $(document).on('input', '.cantQ', function(event) {
        var corr = $(this).attr('id');
        var corrArray = corr.split('-');
        var venta = 0;
        var taxTotal = 0;
        var cantidad = $(this).val();
        var precio = $('#txtPriceItemQ-' + corrArray[1]).val();
        var tax = $('#txtTaxItemQ-' + corrArray[1]).val();
        var totalProducto = parseFloat(precio) * parseFloat(cantidad);
        
        if(!isNaN(totalProducto)) {
            $('#totalItemQ-' + corrArray[1]).html('<label class="totalQ control-label">' + (totalProducto).toFixed(2) + '</label>');
        } 
        
        $('.totalQ').each(function( index, value ) { 
            if(!isNaN(parseFloat($(this).html()))) {
                venta+=parseFloat($(this).html()); 
            }
        });
        
        $('.taxQ').each(function( index, value ) { 
            var id = $(this).attr('id');
            var idArray = id.split('-');
            var cantQ = $('#txtCantItemQ-' + idArray[1]).val();
            var priceQ = $('#txtPriceItemQ-' + idArray[1]).val();
            var taxQ = $(this).val();
            
            taxTotal+= parseFloat(cantQ) * parseFloat(priceQ) * (parseFloat(taxQ)/100);
        });

        $('.subTotalVenta').html((venta).toFixed(2));   
        
        if(!isNaN(taxTotal)) {
            $('.totalTaxVenta').html((taxTotal).toFixed(2));    
        }
        
        if(!isNaN((venta + taxTotal))) {
            $('.totalVenta').html((venta + taxTotal).toFixed(2));    
        }    
    });
    
    $(document).on('input', '.priceQ', function(event) {
        var corr = $(this).attr('id');
        var corrArray = corr.split('-');
        var venta = 0;
        var taxTotal = 0;
        var precio = $(this).val();
        var cantidad = $('#txtCantItemQ-' + corrArray[1]).val();
        var totalProducto = parseFloat(precio) * parseFloat(cantidad);
        
        if(!isNaN(totalProducto)) {
            $('#totalItemQ-' + corrArray[1]).html('<label class="totalQ control-label">' + (totalProducto).toFixed(2) + '</label>');
        } 
        
        $('.totalQ').each(function( index, value ) { 
            if(!isNaN(parseFloat($(this).html()))) {
                venta+=parseFloat($(this).html()); 
            }
        });

        $('.taxQ').each(function( index, value ) { 
            var id = $(this).attr('id');
            var idArray = id.split('-');
            var cantQ = $('#txtCantItemQ-' + idArray[1]).val();
            var priceQ = $('#txtPriceItemQ-' + idArray[1]).val();
            var taxQ = $(this).val();
            
            taxTotal+= parseFloat(cantQ) * parseFloat(priceQ) * (parseFloat(taxQ)/100);
        });

        $('.subTotalVenta').html((venta).toFixed(2));    
        
        if(!isNaN(taxTotal)) {
            $('.totalTaxVenta').html((taxTotal).toFixed(2));    
        }

        if(!isNaN((venta + taxTotal))) {
            $('.totalVenta').html((venta + taxTotal).toFixed(2));    
        }
    });
    
    $(document).on('input', '.taxQ', function(event) {
        var corr = $(this).attr('id');
        var corrArray = corr.split('-');
        var venta = 0;
        var taxTotal = 0;
        var cantidad = $('#txtCantItemQ-' + corrArray[1]).val();
        var precio = $('#txtPriceItemQ-' + corrArray[1]).val();
        var totalProducto = parseFloat(precio) * parseFloat(cantidad);
        var tax = $(this).val();
        var valor = "";
        
        if(parseFloat($(this).val()) > 100){
            for (var i = 1; i< tax.length-3; i++) {
                valor+=tax.charAt(i - 1);                                                
            }

            $(this).val(valor + '.00');
            swal('', $('#errorPorcTax').html(), 'error');	
        } else {
            if(!isNaN(totalProducto)) {
                $('#totalItemQ-' + corrArray[1]).html('<label class="totalQ control-label">' + (totalProducto).toFixed(2) + '</label>');
            } 

            $('.totalQ').each(function( index, value ) { 
                if(!isNaN(parseFloat($(this).html()))) {
                    venta+=parseFloat($(this).html()); 
                }
            });

            $('.taxQ').each(function( index, value ) { 
                var id = $(this).attr('id');
                var idArray = id.split('-');
                var cantQ = $('#txtCantItemQ-' + idArray[1]).val();
                var priceQ = $('#txtPriceItemQ-' + idArray[1]).val();
                var taxQ = $(this).val();

                taxTotal+= parseFloat(cantQ) * parseFloat(priceQ) * (parseFloat(taxQ)/100);
            });

            $('.subTotalVenta').html((venta).toFixed(2));    
            
            if(!isNaN(taxTotal)) {
                $('.totalTaxVenta').html((taxTotal).toFixed(2));    
            }

            if(!isNaN((venta + taxTotal))) {
                $('.totalVenta').html((venta + taxTotal).toFixed(2));    
            } 
        }
    });
    
    $(document).on('click', '#btnCancelQuote', function(event) {
        /*recuperaDataCuenta = true;
        recuperaDataProbabilidad = true;*/
        
        $('#btnSaveTop').removeClass('hidden');
        $('#btnSave').removeClass('hidden');
        
        $('#detalleQuote').addClass('hidden');
        $('#btnAddQuote').removeClass('hidden');
        $('#divQuotes').removeClass('hidden');    
        limpiarCamposDivQuotes();

        return false;	
    });
});

/* Función que pone el formulario en blanco*/
function limpiarCampos() {
    contador=0;
    i=0;
    
    /* Obteniendo las opciones del select de usuario asignado   */
    var personas = $('.firstResponsable').html();

    /* Obteniendo el contenido del label*/
    var assignedUserOportunidad =$('#assignedUserOportunidad').html();
    
    /* Obteniendo las opciones del select de los productos   */
    var productos = $('.firstProduct').html();
    
    /* Limpiando los campos de texto */
    $('#txtId').val('');
    $('#txtName').val('');
    $('#txtProbability').val('');
    $('#descripcion').val('');
    $('#txtFechaCierre').val('');

    /*$('#txtAddComment').val('');*/
    
    /* Limpiando los select */
    $('#tipoCuenta').val('0').change().trigger("change");
    $('#etapaVenta').val('0').change().trigger("change");
    $('#fuente').val('0').change().trigger("change");
    $('#campania').val('0').change().trigger("change");

    /* Removiendo la clase errorform de los campos de texto */
    $('.validateInput').each(function(index, el) {
        $(this).removeClass('errorform');
    });
    
    /* Removiendo la clase errorform de los select */
    $('.validateSelectP').each(function(index, el) {
        $(this).next().children().children().removeClass('errorform');
    });

    /* Reseteando el bloque de usuarios asignados */
    $('.responsable').html('');
    $('.addUser').html('');
    $('.responsable').append('<label id="assignedUserOportunidad">'+assignedUserOportunidad+'</label>');
    $('.responsable').append('<select id="persona-0" style="width:100%;" name="responsable[]" class="input-sm form-control validateSelectP dpbResponsable firstResponsable">'+personas+'</select>');
    $('#persona-0').select2(); 
    $('.addUser').append('<button id="plusUser" style="margin-top:25px;" class="btn btn-success"><i class="fa fa-plus"></i></button>');        

    /* Estableciendo el checbox como false*/
    $("input[name='hayProductos']").prop('checked', false);
    
    
    /* Removiendo la clase*/
    $('.btnAddPage').removeClass('hidden');
    $('#campania').removeClass('validateSelectP');
    
    
    /* Reseteando el bloque de productos/Servicios */
    $('.sProducto').removeClass('validateSelectP');
    $('.cant').removeClass('validateInput');
    $('#productos').addClass('hidden');
    
    $('.producto').html('');
    $('.cantidad').html('');
    $('.removeRow').html('');
           
    $('.producto').append('<div id="producto-' + i + '" ><select id="sProducto-' + i + '" style="width:100%;" type="text" name="sProducto[]" class="sProducto firstProduct input-sm form-control validateSelectP">'+productos+'</select></div>');
    $('.cantidad').append('<input id="txtCantidad-' + i + '" type="text" name="cantidad[]" class="cant input-sm form-control text-right validateInput" value="1" min="1">');        
    $('.removeRow').append('<button id="deleteProd-' + i + '" class="btn removeProd btn-danger hidden"><i class="fa fa-remove"></i></button>');        
    
    $('.cant').numeric('.'); 
    $('#sProducto-' + i).select2();        


    /* Ocultando el panel con las cotizaciones vinculadas a la oportunidad de venta */
    $('#pnCotizacion').hide();
    
    /* Agregando la clase hidden*/
    $('#divCampania').addClass('hidden');
    $('#divCuentaOportunidad').addClass('hidden');
    $('.btnDelete').addClass('hidden');
    $('.btnDelete').addClass('hidden');
    $('#btnBack').addClass('hidden');
    $('#btnCancelTop').addClass('hidden');
    $('#btnSaveTop').addClass('hidden');
    $('#btnNewQuotation').addClass('hidden');
    
    /* Limpiar campos  de la sección de cotizaciones */
    limpiarCamposDivQuotes();
    $('#detalleQuote').addClass('hidden');
    $('#btnNewQuotation').addClass('hidden');
    
} /* Fin de Función que pone el formulario en blanco*/
