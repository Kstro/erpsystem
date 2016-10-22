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
    
    
});