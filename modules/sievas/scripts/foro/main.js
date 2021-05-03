var foro = {};

foro.initresponder = function(){
     $('.summernote').summernote({
        height: 400,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
          ]
    });
    
    $('#add-comentario').on('click', function(e){
        e.preventDefault();
        var comentario = encodeURIComponent($('#comentario').code());
        var tema = foro.loadPageVar('tema');
        var jqxhr = $.post('index.php?mod=sievas&controlador=foro&accion=guardar_comentario',{
            comentario : comentario,
            tema : tema
        });
        
        jqxhr.done(function(data){
            bootbox.alert(data.mensaje, function(){
                window.location = 'index.php?mod=sievas&controlador=foro&accion=ver_tema&id='+tema+'#end';
            });
        });
    });
}

foro.initdiscusion = function(){
    $(document).on('click', '.tema', function(){
        console.log("hola");
        var tema = $(this).data('id');
        var momento = foro.loadPageVar('momento');
        window.location = 'index.php?mod=sievas&controlador=foro&accion=ver_tema&id='+tema+(momento > 0 ? '&momento='+momento : '');
    });
}

foro.inittema = function(){
    $(document).on('click', '.eliminar-comentario', foro.eliminar_comentario);
    $(document).on('click', '.eliminar-tema', foro.eliminar_tema);
}

foro.eliminar_comentario = function(e){
    e.preventDefault();
    var comentario_id = $(this).data('id');
    var self = this;
    var jqxhr = $.post('index.php?mod=sievas&controlador=foro&accion=eliminar_comentario', {
        comentario_id : comentario_id
    });
    
    jqxhr.done(function(data){
        $(self).closest('.comentario').remove();
    });
    
    jqxhr.fail(function(err){
        bootbox.alert(err.responseText);
    });
}

foro.eliminar_tema = function(e){
    e.preventDefault();
    var tema_id = $(this).data('id');
    var jqxhr = $.post('index.php?mod=sievas&controlador=foro&accion=eliminar_tema', {
        id : tema_id
    });
    
    jqxhr.done(function(data){
        bootbox.alert(data.msg, function(){
            window.location = 'index.php?mod=sievas&controlador=foro&accion=index';
        });
    });
    
    jqxhr.fail(function(err){
        bootbox.alert(err.responseText);
    });
}

foro.initagregar = function(){
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
    
    $('#add-tema').on('click', foro.agregartema);
    
}, 

foro.agregartema = function(e){
    e.preventDefault();
    var tema = $('#tema').val();
    var comentario = $.trim(encodeURIComponent($('#comentario').code()));
    var momento = foro.loadPageVar('momento');
    
    var jqxhr = $.post('index.php?mod=sievas&controlador=foro&accion=guardar_tema', {
        tema : tema,
        comentario : comentario,
        cod_momento : momento
    });
    
    jqxhr.done(function(data){
        bootbox.alert(data.mensaje, function(){
            window.location = 'index.php?mod=sievas&controlador=foro';
        });
    });
}

foro.loadPageVar = function(sVar) {
  return decodeURI(window.location.search.replace(new RegExp("^(?:.*[&\\?]" + encodeURI(sVar).replace(/[\.\+\*]/g, "\\$&") + "(?:\\=([^&]*))?)?.*$", "i"), "$1"));
}