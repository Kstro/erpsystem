$(document).ready(function() {
    /////Definición datatable
    var table = $('#accountTypesList').DataTable({
        dom:'ftp',
        "processing": false,
        "serverSide": true,
        "ajax": {
                "url": Routing.generate('admin_account_types_data'),
                "type": 'GET'
        },
        "columns": [
                { "data": "check" },
                { "data": "name" }
                //{ "data": "state" },
                //{ "data": "link" }
        ],
        "order":[1,'asc'],
        "columnDefs": [
                { "orderable": false, "targets": 0 }
                //{ "orderable": false, "targets": 3 }
        ]
    });
    /////Fin definición datatable
    
    $(document).on('click', '#btnSave', function(event) {
        var btn = $(this).button('loading');
        var name=$('#txtName').val();
        var errores = 0; //Contador de errores, para antes de la persistencia

        $('.validateInput').each(function() {
            if (!required($(this))) {
                errores++;
            }
        });

        if (errores==0) {
            var data = {
                            name : name
                        };

            $.ajax({
                data: data,
                url: Routing.generate('admin_register_accounttype'),
                type: 'POST',
                dataType: 'json',
                success: function (response)
                {
                    if(response.exito == 1) {
                        swal('','It has successfully registered!','success');
                        $('#txtName').val('');
                        btn.button('reset');
                        table.ajax.reload();
                        $('.btnAdd').click();
                    } else {
                        swal('','Sorry, an error has occurred!','error');
                        btn.button('reset');
                    }                    
                    
                    return false;
                }
            });
            
            return false;        
        }
        else {
            swal('','Fields in red are required!','error');
            btn.button('reset');
        }                
       
    });     
});