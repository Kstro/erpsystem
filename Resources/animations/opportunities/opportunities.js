var i = 0;
var contador = 0;
var totalVenta = 0;

$(document).ready(function() {
    $('#tipoCuenta').select2();
    $('#cuenta').select2();
    $('#etapaVenta').select2();
    $('#fuente').select2();
    $('#campania').select2();
    $('#sProducto-0').select2();
    $('.firstResponsable').select2();
    
    $('#txtProbability').numeric('.'); 
    
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
            $('#productos').removeClass('hidden');	
        } else {
            $('#productos').addClass('hidden');
        }
    });
    
    $(document).on('click', '#btnAddRow', function(event) {
        i++;
        contador++;

        $('.producto').append('<div id="producto-' + i + '" style="margin-top:6px;"><select id="sProducto-' + i + '" style="width:100%;" type="text" name="sProducto[]" class="sProducto input-sm form-control validateSelectP"></select></div>');
        $('.cantidad').append('<input id="txtCantidad-' + i + '" type="text" name="cantidad[]" class="cant input-sm form-control text-center validateInput" value="1" min="1" style="margin-top:5px;">');
        
                
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
        
        $('.cant').numeric('.'); 
        $('.price').numeric('.'); 
        $('#sTalla-' + i).select2();
        $('#sProducto-' + i).select2({
            ajax: {
                url: Routing.generate('busqueda_producto_data'),
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, 
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    var select2Data = $.map(data.data, function (obj) {
                        obj.id = obj.objid;
                        obj.text = obj.nombre;


                        if(obj.disponible == 0) {
                            obj.disabled = true;
                        } 

                        return obj;
                    });

                    return {
                        results: select2Data
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, 
            minimumInputLength: 1,
            templateResult: formatRepoProducto, 
            templateSelection: formatRepoSelectionProducto,
            formatInputTooShort: function () {
                return "Enter 1 Character";
            }
        });                
    });
});