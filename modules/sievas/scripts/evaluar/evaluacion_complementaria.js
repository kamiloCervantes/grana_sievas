var evaluacion_complementaria = {};


evaluacion_complementaria.init = function(){
    $('.summernote').summernote({
        height: 400,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
//            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
          ]
    });
    
    $('.agregar-anexo').on('click', function(e){
            e.preventDefault();
            evaluacion_complementaria.cargar_popup_anexos();
        });
        
    $(document).on('click', '.eliminar-anexo', evaluacion_complementaria.eliminar_anexo);
    
    //url de anexos
    $('.insertar-url').on('click', function(e){
        e.preventDefault();
        var evaluacion_complementaria_id = $('#evaluacion_complementaria_id').val();
        if(evaluacion_complementaria_id > 0){
            var html = $('.insertar-url-form-tpl').clone();
            html.removeClass('hide');
            html.removeClass('insertar-url-form-tpl');
            html.addClass('insertar-url-form');
            html.find('.url').prop('name', 'url');
            html.find('.nombre').prop('name', 'nombre');
            var d = bootbox.dialog({
            message: html,
            title: "Insertar URL",
            buttons: {
              success: {
                label: "Aceptar",
                className: "btn-success",
                callback: function() {
                  html.find('form').validate({
                      rules: {
                          url: {
                              required: true
                          },
                          nombre: {
                              required: true
                          }
                      }
                  });

                  if(html.find('form').valid()){
                      var url = html.find('.url').val();
                      var nombre = html.find('.nombre').val();
                      var jqxhr = $.post('index.php?mod=sievas&controlador=evaluar&accion=guardar_url_anexo_c', {
                         url : url,
                         nombre : nombre,
                         evaluacion_complementaria_id : evaluacion_complementaria_id
                      });

                      jqxhr.done(function(data){
                          var count = $('#anexos tr').size() + 1;                
                
                        var html = '';
                        html += '<tr>';
                        html += '<td style="padding: 4px">'+count+'</td>';
                        html += '<td style="padding: 4px"><a target="_blank" class="btn btn-xs btn-default" href="'+data.ruta+'"><i class="glyphicon glyphicon-file"></i> '+data.nombre+'</a></td>';
                        html += '<td style="padding: 4px"><a data-id="'+data.id+'" class="btn btn-xs btn-danger eliminar-anexo" href="#"><i class="glyphicon glyphicon-trash"></i></a></td>';
                        html += '</tr>';

                        $('#anexos tbody').append(html);
                        d.modal('hide');   
//                         $('.close').trigger('click');
//                         $(document).trigger('guardar_evaluacion'); 
                      });
                  }
                  else{
                      return false;
                  }

                }
              }

            }
          });

        }
        else{
            bootbox.alert("Debe guardar la evaluación complementaria");  
        }
        
    });
}

evaluacion_complementaria.eliminar_anexo = function(e){
    e.preventDefault();
    var documento_id = $(this).data('id');
    var jqxhr = $.post('index.php?mod=sievas&controlador=evaluar&accion=eliminarAnexo', {
        documento_id : documento_id
    });    

    jqxhr.done(function(){
//         $('.anexos-list').trigger('recargar.archivos');
//         $('.tablas-list').trigger('recargar.archivos');
//         $(document).trigger('guardar_evaluacion');
    });
}

evaluacion_complementaria.cargar_popup_anexos = function(){    
    var evaluacion_complementaria_id = $('#evaluacion_complementaria_id').val();
    if(evaluacion_complementaria_id > 0){
         var html = '';
        html += '<form>'; 
        html += '<div class="form-group">';
        html += '<div id="msg-uploader"></div>';
        html += '<label>Subir archivo</label>';
        html += '<input type="file" name="file" id="archivo">';       
        html += '</div>';
        html += '</form>';
        $html = $.parseHTML(html); 
        
        var d = bootbox.dialog({
            title:"Adjuntar anexo",
            message:$html,
            buttons: {            
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger"
                }
            }
        });

        $('#archivo', $html).on('change', function(){
            $("#msg-uploader").append('<div class="alert alert-success" role="alert"><img src="public/img/ajax.gif">Cargando archivo...</div>');
            var file = $('#archivo')[0].files[0];
    //        var lineamiento_id = $('#arbol').jstree('get_selected')[0];

            var formData = new FormData();
            formData.append('archivo', file);
            formData.append('evaluacion_complementaria_id', evaluacion_complementaria_id);
    //        formData.append('momento', momento);

            var jqxhr = $.ajax({
                url: 'index.php?mod=sievas&controlador=evaluar&accion=upload_anexo_c',
                data: formData,           
                processData: false,
                contentType: false,
                type: 'POST'            
            });       

            jqxhr.done(function(data){
                var count = $('#anexos tr').size() + 1;                
                
                var html = '';
                html += '<tr>';
                html += '<td style="padding: 4px">'+count+'</td>';
                html += '<td style="padding: 4px"><a target="_blank" class="btn btn-xs btn-default" href="'+data.ruta+'"><i class="glyphicon glyphicon-file"></i> '+data.nombre+'</a></td>';
                html += '<td style="padding: 4px"><a data-id="'+data.id+'" class="btn btn-xs btn-danger eliminar-anexo" href="#"><i class="glyphicon glyphicon-trash"></i></a></td>';
                html += '</tr>';
                
                $('#anexos tbody').append(html);
                d.modal('hide');            
                            
                            
                        
            });        

            jqxhr.fail(function(err){ 
                var error = JSON.parse(err.responseText);
                bootbox.alert(error.error);         
            });
        });

        
    }
    else{
         bootbox.alert("Debe guardar la evaluación complementaria");  
    }
   
}

evaluacion_complementaria.eliminar_anexo = function(e){
    e.preventDefault();
    var self = this;
    var documento_id = $(this).data('id');
    var jqxhr = $.post('index.php?mod=sievas&controlador=evaluar&accion=eliminarAnexoC', {
        documento_id : documento_id
    });    

    jqxhr.done(function(){
         $(self).parent().parent().remove();
    });
}


$(evaluacion_complementaria.init);