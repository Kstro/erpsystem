$(document).ready(function() {
    $("input[name=checktodos]").prop({'checked': false});
    
    $(document).on('click', '#btnCancel', function(event) {
        $('#txtId').val('');
        $('#txtName').val('');
        $('#pnAdd').slideToggle();
    });
    
    $(document).on('click', '#btnSave', function(event) {
        var btn = $(this).button('loading');
        var id=$('#txtId');
        var name=$('#txtName');
        var table = $('#originSourceList').DataTable();
        var errores = 0; 
/*        //Contador de errores, para antes de la persistencia*/

        $('.validateInput').each(function() {
            console.log($(this).val());
            
            if (!required($(this))) {
                errores++;
            }
        });

        if (errores==0) {
            var data = {
                            id: id.val(),
                            name : name.val()
                        };

            $.ajax({
                data: data,
                url: Routing.generate('admin_register_origin_source'),
                type: 'POST',
                dataType: 'json',
                success: function (response)
                {
                    console.log(response.msg.error);
                    
                    if(!response.msg.error){
                        swal('', response.msg.msg,'success');
                        $('#txtId').val('');
                        $('#txtName').val('');
                        
                        table.ajax.reload();
                        $('#pnAdd').slideToggle();
                    } else {
                        swal('', response.msg.error, 'error');
                        /*btn.button('reset');*/
                    }                    
                    
                    btn.button('reset');
                    return false;
                },
                error:function(response){
                    if(response.msg.error){
                        swal('', response.msg.error, 'error');
                    }
                    btn.button('reset');
                }
            });
            
            return false;        
        }
        else {
            swal('','Fields in red are required!','error');
            btn.button('reset');
        }                
       
    });     
    
    /*/////Persist datatable (Edit method)*/
    $(document).on('click', '#originSourceList>tbody>tr>td:nth-child(2)', function(event) {
        /*/////Definición de variables*/
        var text = $(this).prop('tagName');
        var id=$(this).parent().children().first().children().attr('id');
        var idForm=$('#txtId').val();
        var selected = 0;
        
        /*//Cambiar nombre del panel heading para Modify*/
        $('.pnHeadingLabelAdd').addClass('hidden');
        $('.pnHeadingLabelEdit').removeClass('hidden');
        
        $('.chkItem').each(function() {
            if ($(this).is(':checked')) {
                selected++;
            }
        });	
        
        if (text=='TD' && id!=idForm && selected==0) {
            $.ajax({
                url: Routing.generate('admin_retrieve_originsource'),
                type: 'POST',
                data: {id: id},
                success:function(data){
                    if(data.error){
                        swal('',data.error,'error');
                        id.val(data.id);
                    }
                    else{
                        $('#txtId').val(data.id);
                        $('#txtName').val(data.name);
                        $('#pnAdd').slideDown();
                    }					

                },
                error:function(data){
                    if(data.error){
                        swal('',data.error,'error');
                    }
                }
            });
        } 
        else {
            if(id==idForm && selected==0){
                $('#pnAdd').slideDown();
            }
        }					
    });
    /*/////Fin definición persist data (Edit method)*/
    
    $(document).on('click', '.chkItem', function(event) {
        var contador = 0;
        var totalchk = $('.chkItem').length;
        
        $('#txtId').val('');
        $('#txtName').val('');
        $('#pnAdd').slideUp();
        
        if ($(this).is(':checked')) {
            $('.btAdd').addClass('hidden');
            $('.btDelete').removeClass('hidden');
            
            $('.chkItem').each( function() {			
                if ($(this).is(':checked')) {
                    contador++;
                }
            });
            
            if(contador == totalchk){
                $("input[name=checktodos]").prop({'checked': true});
            }
        } else {
            $("input[name=checktodos]").prop({'checked': false});
            
            $('.chkItem').each( function() {			
                if ($(this).is(':checked')) {
                    contador++;
                }
            });
            
            if(contador == 0){
                $('.btAdd').removeClass('hidden');
                $('.btDelete').addClass('hidden');
            }
        }
    });
    
    /*/////Persist datatable (Delete method)*/
    $(document).on('click', '.btDelete', function(event) {
        var btn = $(this).button('loading');
        
        swal({
            text: "Really remove origin source?",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#1D234D",
            confirmButtonText: "Acept",
            cancelButtonText: "Cancel"
        }).then(function(isConfirm) {
            if (isConfirm) {
                console.log('if');
                /*/////Definición de variables*/
                var id=$(this).children().first().children().attr('id');
                var ids=[];
                var table = $('#originSourceList').DataTable();
                $('.chkItem').each(function() {
                    if ($(this).is(':checked')) {
                            ids.push($(this).parent().attr('id'));
                    }
                });	
                /*//console.log(ids);*/

                $.ajax({
                    url: Routing.generate('admin_delete_origin_source'),
                    type: 'POST',
                    data: {param1: ids},
                    success:function(data){
                        if(data.error){
                            swal('',data.error,'error');
                        }
                        else{
                            $('#txtId').val(data.id);
                            $('#txtName').val(data.name);
                            $("input[name=checktodos]").prop({'checked': false});
                            
                            /*//swal('', 'It has been successfully removed', 'success');*/
                            swal('', data.msg,'success');
                            btn.button('reset');
                            table.ajax.reload();
                        }

                        $('#pnAdd').slideUp();
                    },
                    error:function(data){
                        if(data.error){
                            swal('',data.error,'error');
                            btn.button('reset');
                        }
                    }
                });

                
            } else {
                console.log('else');
                btn.button('reset');
            }
        });
    });
    /*/////Fin definición persist data (Delete method)*/
});
