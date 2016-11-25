$(document).ready(function() {
    //limpiarCampos();
    
    $('#assignedUserQuote').select2();
    $('#statusQuote').select2();
    $('#sItemQ-0').select2();
    
    // Al hacer click en el Botón "Add"
    $(document).on('click', '.btnAddPage', function(event) {
        var id = $('#txtId').val();
       
//        $('#comentarios').hide();
//        $('#wallmessages').hide();
//
//        $('#btnLoadMore').hide();
//        $('#addTag').addClass('hidden');
//        $('#addFile').addClass('hidden');
//        $('#addedTags').addClass('hidden');
//        $('#filterTag').addClass('hidden');
        
        /*//Cambiar nombre del panel heading para add (Inserción)*/
        $('.pnHeadingLabelAdd').removeClass('hidden');
        $('.pnHeadingLabelEdit').addClass('hidden');
        //console.log($('#quotesList').parent().html());
        console.log(id);
        if (id!='') {
            //limpiarCampos();
            $('#btnBack').removeClass('hidden');
            $('#btnCancelTop').removeClass('hidden');
            $('#btnSaveTop').removeClass('hidden');
            $(this).addClass('hidden');
        }
        else{
            $('#pnAdd').show();
            $('#quotesList').parent().hide();
        }

        $('#btnSaveTop').removeClass('hidden');
        $('#btnCancelTop').removeClass('hidden');
        $('.btnAddPage').addClass('hidden');
    });
    
    $(document).on('click', '#btnCancel,#btnBack,#btnCancelTop', function(event) {
        recuperaDataCuenta = true;
        recuperaDataProbabilidad = true;
        
        $('#pnAdd').hide();
        $('#quotesList').parent().show();
        $('#btnSaveTop').addClass('hidden');
        $('#btnCancelTop').addClass('hidden');
        $('.btnAddPage').removeClass('hidden');
        
//        $('#filterTag').removeClass('hidden');
//	  $('#addTag').addClass('hidden');
//	  $('#addedTags').addClass('hidden');
//	  $('#addedFiles').addClass('hidden');
//        $('#addFile').addClass('hidden');
//        $('#btnLoadMoreFiles').addClass('hidden');
        
        //limpiarCampos();

        return false;	
    });
    
    $('#datetimepicker2').datetimepicker({
        format: 'Y/MM/DD',
        allowInputToggle:true,
        ignoreReadonly:true,
        // minDate: Date(),
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
});