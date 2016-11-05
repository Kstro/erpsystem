$(document).ready(function() {
    $('#contactos').select2({
                 ajax: {
                        url: function () {
                                return Routing.generate('busqueda_contacto_select_info')+'?param1='+$('#txtId2').val();
                            },  
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
                                                obj.id = obj.id;
                                                obj.text = obj.nombre;

                                                return obj;
                                            });

                                            return {
                                                results: select2Data,
//                                                
                                            };
                                        },
                        cache: true
                      },
                      escapeMarkup: function (markup) { return markup; },
                      minimumInputLength: 1,
                      templateResult: formatRepo,
                    });
            
            
    });
        
        function formatRepo (data) {
                if(data.nombre){
                    var markup = "<div class='select2-result-repository clearfix'>" +
                                 "<div class='select2-result-repository__meta'>" +
                                 "<div class='select2-result-repository__title'>" + data.nombre+"</div>" +
                                 "</div></div>";
                } else {
                    var markup = "";
                }
                return markup;
            }

        function formatRepoSelection (data) {
            if(data.nombre){
                return data.nombre;
            } else {
                return "Realice una búsqueda (Ctrl+g)"; 
            }    
        }