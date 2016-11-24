/// Funcion que realiza acciones al hacer click en un checkbox 
function seleccionarChk(){
    /////Definición de variables
    var contador = 0;
    var totalchk = $('.chkItem').length;
    
    //Si el checkbox se ha seleccionado
    if ($(this).is(':checked')) {
        $('.btAdd').addClass('hidden');
        $('.btDelete').removeClass('hidden');

        //Se obtiene el numero de checkboxes que se ha seleccionado
        $('.chkItem').each( function() {			
            if ($(this).is(':checked')) {
                contador++;
            }
        });
        
        //Si se han seleccionado todos los checkboxes
        if(contador == totalchk){
            $("input[name=checktodos]").prop({'checked': true});
        }
    //Si el checkbox se ha deseleccionado
    } else {
        $("input[name=checktodos]").prop({'checked': false});
        
        //Se obtiene el numero de checkboxes que se ha seleccionado
        $('.chkItem').each( function() {			
            if ($(this).is(':checked')) {
                contador++;
            }
        });
        
        //Si no se ha seleccionado ningun los checkbox
        if(contador == 0){
            $('.btAdd').removeClass('hidden');
            $('.btDelete').addClass('hidden');
        }
    }
}

/// Funcion que seleciona y deselecciona todos los checkboxes de la tabla
function seleccionarTodo() {	
    /////Definición de variables
    var id=$(this).children().first().children().attr('id');
    
    //$('#txtId').val('');
    //$('#txtName').val('');
    $('#pnAdd').slideUp();
    
    //Se recorren todos los checkboxes
    $('input[type=checkbox]').each( function() {	
        //Si se ha seleccionado el checkbox con nombre checktodos
        if($("input[name=checktodos]:checked").length == 1){
            $('.btAdd').addClass('hidden');
            $('.btDelete').removeClass('hidden');
            $(this).prop({'checked': true});
        //Si se ha deseleccionado el checkbox con nombre checktodos
        } else {
           $('.btAdd').removeClass('hidden');
            $('.btDelete').addClass('hidden');
            $(this).prop({'checked': false}); 
        }
    });	       
}

/* Funcion que seleciona y deselecciona todos los checkboxes de la tabla de cotizaciones en la oportunidad */
function seleccionarTodoQ() {
    $('#detalleQuote').addClass('hidden');
    $('#divQuotes').removeClass('hidden');    
    
    //Se recorren todos los checkboxes
    /*$('input[type=checkbox]').each( function() {*/
    $('.chkItemQ').each( function() {    
        //Si se ha seleccionado el checkbox con nombre checktodos
        if($("input[name=checktodosQ]:checked").length == 1){
            $('.btnNewQuotation').addClass('hidden');
            $('.btnDeleteQuotation').removeClass('hidden');
            $(this).prop({'checked': true});
        //Si se ha deseleccionado el checkbox con nombre checktodos
        } else {
            $('.btnNewQuotation').removeClass('hidden');
            $('.btnDeleteQuotation').addClass('hidden');
            $(this).prop({'checked': false}); 
        }
    });	
}

var flag = 1;

function cambiaTexto(dataEn, dataEs, idioma) {
    //console.log(idioma);
    
    if(idioma == 0) {    
        if(flag == 0) {
            var data = [[{"id": 1, "text":"" + dataEn.englishLanguage + ""}, {"id": 2, "text":"" + dataEn.spanishLanguage + ""}]];
            
            $('.encabezadoWizard').html(dataEn.encabezadoEn);
            $('.wellcomeWiz').html(dataEn.step1En);
            $('.start-wizard').html('<button id="btnWelcome" type="button" class="btn btn-success btn-sm next-step">' + dataEn.btnWelcomeEn + '</button>');
            $('.textoBienvenida').html(dataEn.textoBienvenida);
            $('.languageWizardLabel').html(dataEn.language);
            
            $('.idioma').select2('destroy');
            $('.idioma').html('');
            
            $('.idioma').append('<option value="1">' + dataEn.englishLanguage + '</option>');
            $('.idioma').append('<option value="2">' + dataEn.spanishLanguage + '</option>');
            $(".idioma").select2(); 

            flag = 1;     
        } else if(flag == 1) {
            var data = [[{"id": 1, "text":"" + dataEs.englishLanguageEs + ""}, {"id": 2, "text":"" + dataEs.spanishLanguageEs + ""}]];
            
            $('.encabezadoWizard').html(dataEs.encabezadoEs);
            $('.wellcomeWiz').html(dataEs.step1Es);
            $('.start-wizard').html('<button id="btnWelcome" type="button" class="btn btn-success btn-sm next-step">' + dataEs.btnWelcomeEs + '</button>');
            $('.textoBienvenida').html(dataEs.textoBienvenidaEs);
            $('.languageWizardLabel').html(dataEs.languageEs);
            
            $('.idioma').select2('destroy');
            $('.idioma').html('');
            
            $('.idioma').append('<option value="1">' + dataEs.englishLanguageEs + '</option>');
            $('.idioma').append('<option value="2">' + dataEs.spanishLanguageEs + '</option>');
            $(".idioma").select2(); 

            flag = 0;        
        }
    } else if(idioma == 1) {    
        // Tab bienvenida
        $('.encabezadoWizard').html(dataEn.encabezadoEn);
        $('.wellcomeWiz').html(dataEn.step1En);
        $('.start-wizard').html('<button id="btnWelcome" type="button" class="btn btn-success btn-sm next-step">' + dataEn.btnWelcomeEn + '</button>');
        $('.textoBienvenida').html(dataEn.textoBienvenida);
        $('.languageWizardLabel').html(dataEn.language);
        $(".idioma").select2('destroy'); 
        $('select[name="language"]').find('option[value="1"]').html(dataEn.englishLanguage);
        $('select[name="language"]').find('option[value="2"]').html(dataEn.spanishLanguage);
        $(".idioma").select2(); 
        
        // Tab Paso 1
        $('.companyNameWizard').html(dataEn.companyNameWizard);
        $('.companyIndustryWizard').html(dataEn.companyIndustryWizard);
        $('.companyAddressWizard').html(dataEn.companyAddressWizard);
        $('.companyStateWizard').html(dataEn.companyStateWizard);
        $('.companyCityWizard').html(dataEn.companyCityWizard);
        $('.wellcomeWiz').html(dataEn.phoneWizard);
        $('.extensionWizard').html(dataEn.extensionWizard);
        $('.emailWizard').html(dataEn.emailWizard);
        
        // Tab Paso 2
        
        
    } else {
        // Tab bienvenida
        $('.encabezadoWizard').html(dataEs.encabezadoEs);
        $('.wellcomeWiz').html(dataEs.step1Es);
        $('.start-wizard').html('<button id="btnWelcome" type="button" class="btn btn-success btn-sm next-step">' + dataEs.btnWelcomeEs + '</button>');
        $('.textoBienvenida').html(dataEs.textoBienvenidaEs);
        $('.languageWizardLabel').html(dataEs.languageEs);
        $(".idioma").select2('destroy'); 
        $('select[name="language"]').find('option[value="1"]').html(dataEs.englishLanguageEs);
        $('select[name="language"]').find('option[value="2"]').html(dataEs.spanishLanguageEs);
        $(".idioma").select2(); 
        
        // Tab Paso 1
        $('.companyNameWizard').html(dataEs.companyNameWizardEs);
        $('.companyIndustryWizard').html(dataEs.companyIndustryWizardEs);
        $('.companyAddressWizard').html(dataEs.companyAddressWizardEs);
        $('.companyStateWizard').html(dataEs.companyStateWizardEs);
        $('.companyCityWizard').html(dataEs.companyCityWizardEs);
        $('.phoneWizard').html(dataEs.phoneWizardEs);
        $('.extensionWizard').html(dataEs.extensionWizardEs);
        $('.emailWizard').html(dataEs.emailWizardEs);
        
        // Tab Paso 2
        
    }
}

