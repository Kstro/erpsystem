$(document).ready(function() {
    var attempts = 0;
    
   $('#frmLogin').submit(function() {
       var capa = document.getElementById('captcha');
                
       $.ajax({
            data: $(this).serialize() + '&attempts=' + attempts,
            url: $(this).attr('action'),
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                if(response.success) {
                    $('.error-auth').addClass('hidden');
                    $('.captcha').removeClass('g-recaptcha');
                    capa.style.display = 'none';
                    
                    var url = Routing.generate('admin_dashboard');
                    window.open(url, "_self");                    
                } else {
                    $('.error-auth').html('<p>' + response.message + '</p>');
                    $('.error-auth').removeClass('hidden');
                    
                    if(response.intentos > 2) {
                        attempts++;
                        capa.style.display = 'block';
                    } else {
                        capa.style.display = 'none';
                    }                    
                    
                    return false;
                }
                
                return false;
            }, 
            error:function(response){
                
                
                return false;
            }
       });
       
       return false;
   }); 
});