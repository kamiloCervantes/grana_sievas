/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var summernote_helper = {};


summernote_helper.init = function(){  
    $.each($('.summernote_modal'), function(index, value){
        if($(value).data('render') && $(value).data('code').length > 0 && $(value).find('input[type=text]').length > 0){
            $(value).replaceWith('<div id="'+$(value).prop('id')+'" class="summernote_modal" data-title="'+$(value).data('title')+'">'+$(value).data('code')+'</div>');
        }
    });
    
    
    $(document).on('click focus','.summernote_modal', function(e){
        var self = e.target;
        var content = '';
        if($(this).find('input[type=text]').length === 0)
            content = $(this).html();

        var mensaje = '';
        mensaje += '<div class="summernote"><textarea></textarea></div>';        
        $mensaje = $.parseHTML(mensaje);        
        $('textarea', $mensaje).summernote({
            height: 150,
            lang: 'es-ES',
            toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
          ]
        });
        var title = ($(this).data('title') !== 'undefined' && $(this).data('title').length > 0) ? $(this).data('title') : 'Editar contenido'
        $('.note-editable', $mensaje).html(content);
        bootbox.dialog({
            title:title,
            message:$mensaje,
            buttons: {
                success: {
                    label: "Aceptar",
                    className: "btn-success",
                    callback: function(){         
                        if($(self).find('input[type=text]').length === 0){
                            if($('textarea').code().length > 0)
                            $(self).replaceWith('<div id="'+$(self).prop('id')+'">'+$('textarea').code()+'</div>');
                        }
                        else{
                            $(self).parent().html($('textarea').code());
                        }                        
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-default",
                    callback: function(){
                        
                    }
                },
            }
        });
    });
}

$(summernote_helper.init);
