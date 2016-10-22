var idioma = 0; 
var step = 0;

$(document).ready(function () {
    // Inicializando los valores para verificar si ha terminado
    // cada paso en la configuracion inicial
    var step1 = 0;
    var step2 = 0; 
    var step3 = 0;
    var empresa = 0;
    var usuario = 0;
    var foto = 0;
    
//    $(document).on('click', '#btnStep1', function(event) {
//        $('#frmEmpresaWizard').submit();
//    });
    
//    $(document).on('click', '.btnWelcome', function(event) {
//        var $active = $('.wizard .nav-tabs li.active');
//        $active.next().removeClass('disabled');
//        nextTab($active);        
//    });
    
    // Al hacer click en Guardar y continuar (Paso 1)
    // Se registra la informacion de la empresa
    $(document).on('click', '#btnStep1', function(event) {
    //$('#frmEmpresaWizard').on('submit',(function(event) {
        var id=$('#idEmpresa');
        var errores = 0; 
        var btn;
        var $active = $('.wizard .nav-tabs li.active');
        
        $('.validateInputCia').each(function() {
            if (!required($(this))) {
                errores++;
            }
        });
        
        if (errores==0) {
            //if(step1 == 0) {
                btn = $(this).button('loading');
                
                $.ajax({
                    data: $('#frmEmpresaWizard').serialize(),
                    url: Routing.generate('admin_wizard_register_step1'),
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        console.log('response');
//                        $('#idEmpresa').val(response.msg.id);
                        //alert(response);
                        
//                        $active.next().removeClass('disabled');
//                        nextTab($active);
//                        btn.button('reset');
                        
//                        return false;
                    },  
                    error:function(response){
                        btn.button('reset');
                        
                        return false;
                    }
               });
               return false;
            //}
        }
        else {
            swal('','Fields in red are required!','error');
            btn.button('reset');
        }
                
        return false;
    }); 
    
    // Al hacer click en Guardar y continuar (Paso 2)
    // Se registra la informacion del usuario administrador del sistema
    $(document).on('click', '#btnStep2', function(event) {
        var id=$('#idUsuario');
        var errores = 0; //Contador de errores, para antes de la persistencia
        var btn;
        
        $('.validateInputUser').each(function() {
            if (!required($(this))) {
                errores++;
            }
        });
        
        if(step2 == 0) {
            //var btn = $(this).button('loading');
        }
    }); 
   
    // Al hacer click en Guardar y continuar (Paso 3)
    // Se registra y almacena el logo de la empresa
    $(document).on('click', '#btnStep3', function(event) {
        var id = $('#idLogoEmpresa');
        var btn;
        
        if(step3 == 0) {
            //var btn = $(this).button('loading');
        }
    }); 
   
    // Al hacer click en Finalizar 
    // Una vez se ha concluido la configuracion inicial del sistema
    // Se redirige al dashboard del sistema, ya autenticado como usuario administrador
    $(document).on('click', '#btnCompleted', function(event) {
        //var btn = $(this).button('loading');
        
    }); 
});

function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}