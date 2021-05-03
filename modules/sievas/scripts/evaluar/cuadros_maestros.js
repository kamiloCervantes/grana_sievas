var cuadros_maestros = {};

cuadros_maestros.init = function(){
    $(document).on('change', 'input[type=file]', function(e){
        var file = $(this)[0].files[0];
        var id = $(this).closest('form').data('id');
        var form = new FormData();
        form.append("file", file);
        form.append("id", id);
        
        var jqxhr = $.ajax({
                url: 'index.php?mod=sievas&controlador=evaluar&accion=guardar_cuadro_maestro',
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
            });
            
        jqxhr.done(function(data){
           if(id > 0){
               $('.form-cuadro').data('id', data.id);
               $('.form-cuadro').find('a').prop('href', data.ruta);
               $('.form-cuadro').find('a').html('<i class="glyphicon glyphicon-file"></i> '+data.nombre);
           } 
           else{
               var form_html = $('.form_tpl').clone();
               form_html.removeClass('form_tpl');
               form_html.removeClass('hide');
               form_html.data('id', data.id);
               form_html.find('a').prop('href', data.ruta);
               form_html.find('a').html('<i class="glyphicon glyphicon-file"></i> '+data.nombre);
               $('form.form-cuadro').replaceWith(form_html);
           }
        });
    });
}

$(cuadros_maestros.init);