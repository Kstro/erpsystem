var i = 0;
var contador = 0;
var totalVenta = 0;

$(document).ready(function() {
    $("input[name=checktodos]").prop({'checked': false});
    
    // Estableciendo los combobox como Select2
    $('#tipoCuenta').select2();
    $('#cuenta').select2();
    $('#etapaVenta').select2();
    $('#fuente').select2();
    $('#campania').select2();
    $('#sProducto-0').select2();
    $('.firstResponsable').select2();
    
    $('#txtProbability').numeric('.'); 
    
    // Al hacer click en el Botón "Add"
    $(document).on('click', '.btnAddPage', function(event) {
            var id = $('#txtId').val();

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
        $('#pnAdd').hide();
        $('#oppotunitiesList').parent().show();
        $('#btnSaveTop').addClass('hidden');
        $('#btnCancelTop').addClass('hidden');
        $('.btnAddPage').removeClass('hidden');

        return false;	
    });
    
    $('#datetimepicker1').datetimepicker({
        format: 'Y/MM/DD HH:mm',
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
});