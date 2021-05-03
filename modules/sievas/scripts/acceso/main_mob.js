var acceso = {};

acceso.init = function(){
    
    
    if(acceso.loadPageVar('evaluacion') > 0){
        acceso.cargarselect('index.php?mod=auth&controlador=usuarios&accion=get_roles','idrol','id','#rol');
        acceso.cargarselect('index.php?mod=sievas&controlador=evaluaciones&accion=get_datos_evaluacion','pais','cod_pais','#pais', {
            evaluacion : acceso.loadPageVar('evaluacion')
        });
        acceso.cargarselect('index.php?mod=sievas&controlador=evaluaciones&accion=get_datos_evaluacion','tipo_evaluado','cod_tipo_evaluado','#tipo_evaluado', {
            evaluacion : acceso.loadPageVar('evaluacion')
        });
        acceso.cargarselect('index.php?mod=sievas&controlador=evaluaciones&accion=get_datos_evaluacion','evaluado','cod_evaluado','#evaluado', {
            evaluacion : acceso.loadPageVar('evaluacion')
        });
        acceso.cargarselect('index.php?mod=sievas&controlador=evaluaciones&accion=get_datos_evaluacion','etiqueta','evaluacion','#evaluacion', {
            evaluacion : acceso.loadPageVar('evaluacion')
        });
        
    }
    else{
        acceso.cargarselect('index.php?mod=sievas&controlador=gen_paises&accion=get_paises','nom_pais','id','#pais');
        acceso.cargarselect('index.php?mod=auth&controlador=usuarios&accion=get_roles','idrol','id','#rol');
        acceso.cargarselect('index.php?mod=sievas&controlador=evaluaciones&accion=get_niveles_autoevaluacion','nivel','id','#tipo_evaluado');

//        $('#evaluado').select2({ width : '50%'});
//        $('#evaluacion').select2({ width : '100%'});
        
        $('#tipo_evaluado').on('change', function(){
        var tipo_evaluado = $(this).val();
        console.log(tipo_evaluado);
        switch(parseInt(tipo_evaluado)){
            case 1:
                acceso.cargarselect('index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluados','nom_institucion','id','#evaluado', {
                    tipo_evaluado : $('#tipo_evaluado').val(),
                    pais : $('#pais').val()
                });
                break;
            case 2:
                acceso.cargarselect('index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluados','programa','id','#evaluado', {
                    tipo_evaluado : $('#tipo_evaluado').val(),
                    pais : $('#pais').val()
                });
                break;
            case 3:
                acceso.cargarselect('index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluados','nombres','id','#evaluado', {
                    tipo_evaluado : $('#tipo_evaluado').val(),
                    pais : $('#pais').val()
                });
                break;
             case 6:
                acceso.cargarselect('index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluados','programa','id','#evaluado', {
                    tipo_evaluado : $('#tipo_evaluado').val(),
                    pais : $('#pais').val()
                });
                break;
            }

        });
        $('#pais').on('change', function(){
            var tipo_evaluado = $('#tipo_evaluado').val();
            switch(parseInt(tipo_evaluado)){
                case 1:
                    acceso.cargarselect('index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluados','nom_institucion','id','#evaluado', {
                        tipo_evaluado : $('#tipo_evaluado').val(),
                        pais : $('#pais').val()
                    });
                    break;
                case 2:
                    acceso.cargarselect('index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluados','programa','id','#evaluado', {
                        tipo_evaluado : $('#tipo_evaluado').val(),
                        pais : $('#pais').val()
                    });
                    break;
            }

        });
        $('#evaluado').on('change', function(){
            acceso.cargarselect('index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluaciones_evaluado','etiqueta','id','#evaluacion',{
                tipo_evaluado : $('#tipo_evaluado').val(),
                evaluado : $('#evaluado').val(),
                rol: $('#rol').val()
            });
        });
        $('#rol').on('change', function(){
            var rol = $(this).val();
            console.log(rol);
            if(parseInt(rol) == 6){
                $('#pais').closest('td').hide();
                $('#tipo_evaluado').closest('td').hide();
                $('#evaluado').closest('td').hide();
                $('#evaluacion').closest('td').hide();
            }
            else{
                acceso.cargarselect('index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluaciones_evaluado','etiqueta','id','#evaluacion',{
                    tipo_evaluado : $('#tipo_evaluado').val(),
                    evaluado : $('#evaluado').val(),
                    rol: $('#rol').val()
                });
            }
            
        });
        
    }
    
    $('#continuar').on('click', acceso.continuar);
}

acceso.continuar = function(e){
    e.preventDefault();
    var form = $('#formLoginData').serialize();
    var jqxhr = $.get('index.php?mod=auth&controlador=usuarios&accion=continuar', form);
    
    jqxhr.done(function(){
        window.location = 'index.php?mod=sievas&controlador=index_sieva&accion=index';
    });
    
    jqxhr.fail(function(err){
        if(err.responseText !== ''){
             alert(err.responseText);
        }
        else{
            window.location = 'index.php?mod=auth&controlador=usuarios&accion=logout';
        }
    });
}

acceso.cargarselect = function(url,visible,option,elemento,params){
    var jqxhr = $.ajax({
        url : url,
        data : params,
        type : 'GET',
        async: 'false'
    });
    
    jqxhr.done(function(data){
        $(elemento+' option').remove();
        if(data.length > 0){            
            $.each(data, function(index,value){
               $(elemento).append('<option value="'+value[option]+'">'+value[visible]+'</option>'); 
            });
//            $(elemento).select2({ width: '100%' });
            $(elemento).triggerHandler('change');
        }
        else{
            $(elemento).append('<option value="0">No hay resultados</option>'); 
//            $(elemento).select2('val', 0);            
            $(elemento).val(0);            
        }
    });
}

acceso.loadPageVar = function(sVar) {
  return decodeURI(window.location.search.replace(new RegExp("^(?:.*[&\\?]" + encodeURI(sVar).replace(/[\.\+\*]/g, "\\$&") + "(?:\\=([^&]*))?)?.*$", "i"), "$1"));
}

$(acceso.init);

