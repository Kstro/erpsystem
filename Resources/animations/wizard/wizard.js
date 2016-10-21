$(document).ready(function () {
    // Select2
    
    // Step 2
    $('#industria').select2();
    $('#addressDepartamentoCia').select2();
    $('#addressCityCia').select2();
    $('#phoneTypeCia').select2();
    
    // Step 3
    $('#addressDepartamentoUser').select2();
    $('#addressCityUser').select2();
    $('#phoneTypeUser').select2();
    $('#titleWizard').select2();
    $('#gender').select2();
    
    //Initialize tooltips
    $('.nav-tabs > li a[title]').tooltip();
    
    //Wizard
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        var $target = $(e.target);
        
        if ($target.parent().hasClass('disabled')) {
            return false;
        }
    });
    
    $("#btnWelcome").click(function (e) {        
        var $active = $('.wizard .nav-tabs li.active');
        $active.next().removeClass('disabled');
        nextTab($active);
        
    });
    
    $(".prev-step").click(function (e) {
        var $active = $('.wizard .nav-tabs li.active');
        prevTab($active);

    });
});

function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}


//according menu
$(document).ready(function() {
    // Contadores para agregar o eliminar telefono, email y direccion para empresa y usuario administrador
    var numPhonesCia = 0;
    var numEmailCia = 0;
    var numAddressCia = 0;
    var numPhonesUser = 0;
    var numEmailUser = 0;
    var numAddressUser = 0;

    $('.txtPhoneCia').each(function(index, el) {
            numPhonesCia++;
    });
    $('.txtEmailCia').each(function(index, el) {
            numEmailCia++;
    });
    $('.txtAddressCia').each(function(index, el) {
            numAddressCia++;
    });
    
    $('.txtPhoneUser').each(function(index, el) {
            numPhonesUser++;
    });
    $('.txtEmailUser').each(function(index, el) {
            numEmailUser++;
    });
    $('.txtAddressUser').each(function(index, el) {
            numAddressUser++;
    });
    // Fin de contadores para agregar o eliminar telefono, email y direccion para empresa y usuario administrador
    
    // Agregar/remover telefonos Empresa
    $(document).on('click', '#plusPhoneCia', function(event) {
        numPhonesCia++;
        var optionsPhoneType = $('.firstPhoneType').html();
        $('.phonesType').append('<div style="margin-top:27px;"><select id="types-'+numPhonesCia+'" style="width:100%;margin-top:25px !important;" name="phoneTypeCia[]" class="input-sm form-control validateInput dpbTipoPhone">'+optionsPhoneType+'</select></div>');
        $('.phonesText').append('<input id="phones-'+numPhonesCia+'" style="margin-top:25px;" type="text" name="phoneCia[]" class="input-sm form-control validateInput txtPhone">');
        $('.phonesExtension').append('<input id="extension-'+numPhonesCia+'" style="margin-top:25px;" type="text" name="phoneExtCia[]" class="input-sm form-control txtExtension">');
        $('.addPhone').append('<button id="deletePhone-'+numPhonesCia+'" style="margin-top:27px;" class="btn removePhoneCia btn-danger"><i class="fa fa-remove"></i></button>');
        $('#types-'+numPhonesCia).select2();
        return false;
    });
    
    $(document).on('click', '.removePhoneCia', function(event) {
        var numDel = $(this).attr('id');
        numDelArray= numDel.split('-');
        $('#types-'+numDelArray[1]).parent().remove();
        $('#phones-'+numDelArray[1]).remove();
        $('#extension-'+numDelArray[1]).remove();
        $('#deletePhone-'+numDelArray[1]).remove();
        return false;
    });
    // Fin de agregar/remover telefonos Empresa
    
    // Agregar/remover email Empresa
    $(document).on('click', '#plusEmailCia', function(event) {
        numEmailCia++;

        $('.emailText').append('<input id="email-'+numEmailCia+'" type="text" name="emailCia[]" style="margin-top:25px ;" class="input-sm form-control validateInput txtEmail">');
        $('.addEmail').append('<button id="deleteEmail-'+numEmailCia+'" style="margin-top:27px;" class="btn removeEmailCia btn-danger"><i class="fa fa-remove"></i></button>');
        return false;
    });
    $(document).on('click', '.removeEmailCia', function(event) {
        var numDel = $(this).attr('id');
        numDelArray= numDel.split('-');
        $('#email-'+numDelArray[1]).remove();
        $('#deleteEmail-'+numDelArray[1]).remove();

        return false;
    });
    // Fin de agregar/remover email Empresa
    
    // Agregar/remover direccion Empresa
    $(document).on('click', '#plusAddressCompanyCia', function(event) {
        numAddressCia++;
        var optionsCity = $('.dpbCityFirst').html();
        var optionsState = $('.dpbStateFirst').html();
        $('.address').append('<input style="margin-top:25px ;" id="address-'+numAddressCia+'" type="text" name="addressCia[]" class="input-sm form-control validateInput txtAddress">');
        $('.city').append('<div style="margin-top:27px;"><select style="margin-top:25px; width:100%;" id="city-'+numAddressCia+'" name="addressCityCia[]" class="input-sm form-control dpbCity">'+optionsCity+' </select></div>');
        $('.state').append('<div style="margin-top:27px;"><select style="margin-top:25px; width:100%;" id="state-'+numAddressCia+'" name="addressDepartamentoCia[]" class="input-sm form-control dpbState">'+optionsState+' </select></div>');
        $('.addAddress').append('<button id="deleteAddress-'+numAddressCia+'" style="margin-top:25px;" class="btn removeAddressCia btn-danger"><i class="fa fa-remove"></i></button>');
        $('#city-'+numAddressCia).select2();
        $('#state-'+numAddressCia).select2();
        return false;
    });
    $(document).on('click', '.removeAddressCia', function(event) {
        var numDel = $(this).attr('id');
        numDelArray= numDel.split('-');
        $('#address-'+numDelArray[1]).remove();
        $('#city-'+numDelArray[1]).parent().remove();
        $('#state-'+numDelArray[1]).parent().remove();
        $(this).remove();
        return false;
    });
    // Fin de agregar/remover direccion Empresa
 
    // Agregar/remover telefonos Usuario administrador
    $(document).on('click', '#plusPhoneUser', function(event) {
        numPhonesCia++;
        var optionsPhoneType = $('.firstPhoneType').html();
        $('.phonesType').append('<div style="margin-top:27px;"><select id="types-'+numPhonesUser+'" style="width:100%;margin-top:25px !important;" name="phoneType[]" class="input-sm form-control validateInput dpbTipoPhone">'+optionsPhoneType+'</select></div>');
        $('.phonesText').append('<input id="phones-'+numPhonesUser+'" style="margin-top:25px;" type="text" name="phone[]" class="input-sm form-control validateInput txtPhone">');
        $('.phonesExtension').append('<input id="extension-'+numPhonesUser+'" style="margin-top:25px;" type="text" name="phoneExt[]" class="input-sm form-control txtExtension">');
        $('.addPhone').append('<button id="deletePhone-'+numPhonesUser+'" style="margin-top:27px;" class="btn removePhoneUser btn-danger"><i class="fa fa-remove"></i></button>');
        $('#types-'+numPhonesUser).select2();
        return false;
    });
    
    $(document).on('click', '.removePhoneUser', function(event) {
        var numDel = $(this).attr('id');
        numDelArray= numDel.split('-');
        $('#types-'+numDelArray[1]).parent().remove();
        $('#phones-'+numDelArray[1]).remove();
        $('#extension-'+numDelArray[1]).remove();
        $('#deletePhone-'+numDelArray[1]).remove();
        return false;
    });
    // Fin de agregar/remover telefonos Usuario administrador
    
    // Agregar/remover email Usuario administrador
    $(document).on('click', '#plusEmailUser', function(event) {
        numEmailUser++;

        $('.emailText').append('<input id="email-'+numEmailUser+'" type="text" name="email[]" style="margin-top:25px ;" class="input-sm form-control validateInput txtEmail">');
        $('.addEmail').append('<button id="deleteEmail-'+numEmailUser+'" style="margin-top:27px;" class="btn removeEmailUser btn-danger"><i class="fa fa-remove"></i></button>');
        return false;
    });
    $(document).on('click', '.removeEmailUser', function(event) {
        var numDel = $(this).attr('id');
        numDelArray= numDel.split('-');
        $('#email-'+numDelArray[1]).remove();
        $('#deleteEmail-'+numDelArray[1]).remove();

        return false;
    });
    // Fin de agregar/remover email Usuario administrador
    
    // Agregar/remover direccion Usuario administrador
    $(document).on('click', '#plusAddressUser', function(event) {
        numAddressUser++;
        var optionsCity = $('.dpbCityFirst').html();
        var optionsState = $('.dpbStateFirst').html();
        $('.address').append('<input style="margin-top:25px ;" id="address-'+numAddressUser+'" type="text" name="address[]" class="input-sm form-control validateInput txtAddress">');
        $('.city').append('<div style="margin-top:27px;"><select style="margin-top:25px; width:100%;" id="city-'+numAddressUser+'" name="addressCity[]" class="input-sm form-control dpbCity">'+optionsCity+' </select></div>');
        $('.state').append('<div style="margin-top:27px;"><select style="margin-top:25px; width:100%;" id="state-'+numAddressUser+'" name="addressDepartamento[]" class="input-sm form-control dpbState">'+optionsState+' </select></div>');
        $('.addAddress').append('<button id="deleteAddress-'+numAddressUser+'" style="margin-top:25px;" class="btn removeAddressUser btn-danger"><i class="fa fa-remove"></i></button>');
        $('#city-'+numAddressUser).select2();
        $('#state-'+numAddressUser).select2();
        return false;
    });
    $(document).on('click', '.removeAddressUser', function(event) {
        var numDel = $(this).attr('id');
        numDelArray= numDel.split('-');
        $('#address-'+numDelArray[1]).remove();
        $('#city-'+numDelArray[1]).parent().remove();
        $('#state-'+numDelArray[1]).parent().remove();
        $(this).remove();
        return false;
    });
    // Fin de agregar/remover direccion Usuario administrador
    
    //Add Inactive Class To All Accordion Headers
    $('.accordion-header').toggleClass('inactive-header');	
    //Set The Accordion Content Width
    var contentwidth = $('.accordion-header').width();
    $('.accordion-content').css({});

    //Open The First Accordion Section When Page Loads
    $('.accordion-header').first().toggleClass('active-header').toggleClass('inactive-header');
    $('.accordion-content').first().slideDown().toggleClass('open-content');

    // The Accordion Effect
    $('.accordion-header').click(function () {
        if($(this).is('.inactive-header')) {
            $('.active-header').toggleClass('active-header').toggleClass('inactive-header').next().slideToggle().toggleClass('open-content');
            $(this).toggleClass('active-header').toggleClass('inactive-header');
            $(this).next().slideToggle().toggleClass('open-content');
        } else {
            $(this).toggleClass('active-header').toggleClass('inactive-header');
            $(this).next().slideToggle().toggleClass('open-content');
        }
    });

    return false;
});