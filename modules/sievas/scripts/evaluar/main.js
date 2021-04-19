var evaluar = {};




evaluar.cargar_popup_anexos = function(lineamiento){    
    var anexos = $('.listado-anexos[data-lineamiento="'+lineamiento+'"]');
    var html = '';
    html += '<form>'; 
    html += '<div class="form-group">';
    html += '<div id="msg-uploader"></div>';
    html += '<label>Subir archivo</label>';
    html += '<input type="file" name="file" id="archivo">';       
    html += '</div>';
    html += '</form>';
    $html = $.parseHTML(html); 
    
    $('#archivo', $html).on('change', function(){
        $("#msg-uploader").append('<div class="alert alert-success" role="alert"><img src="public/img/ajax.gif">Cargando archivo...</div>');
        var file = $('#archivo')[0].files[0];
        var lineamiento_id = lineamiento;
        var momento = 1;
        var formData = new FormData();
        formData.append('archivo', file);
        formData.append('lineamiento', lineamiento_id);
        formData.append('momento', momento);
        formData.append('nuevo', 1);
        
        var jqxhr = $.ajax({
            url: 'index.php?mod=sievas&controlador=evaluar&accion=upload_anexo',
            data: formData,           
            processData: false,
            contentType: false,
            type: 'POST'            
        });       
        
        jqxhr.done(function(data){
            $('.close').trigger('click');
//            $('.anexos-popover', anexos).trigger('click');
            anexos.parent().find('.anexos-popover').trigger('click');
            var html = $('.anexo-tpl').clone();
            html.removeClass('hide');
            html.find('a').attr('href', data.ruta);            
            html.find('span').append(data.nombre);
            html.find('span').attr('data-documento',data.id);
            anexos.parent().find('.listado-anexos').append(html.html());
//            $('.listado-anexos', anexos).append(html.html());
        });        

        jqxhr.fail(function(err){ 
            var error = JSON.parse(err.responseText);
            bootbox.alert(error.error);         
        });
    });

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
}



evaluar.initreevaluacion = function(){
    //acceso rapido
    
    setInterval(function(){
        $.get('index.php?mod=sievas&controlador=evaluar&accion=renovarsesion');
    },30000);
    
    $('#acceso_rapido').select2().on('change', function(){
        console.log($(this).select2('val'));
        var item = $(this).select2('val');
        $('html, body').animate({
            scrollTop: $('a.acceso_rapido_'+item).offset().top
        }, 1000);
    });
    
    $(window).on('scroll', function(e){
        var scroll = $(window).scrollTop();
        if(scroll > 20){
            $('#btn_up').css('display', 'block');
        }
        else{
            $('#btn_up').css('display', 'none');
        }
    });
    $('#btn_up').on('click', function(){
        $('html, body').animate({
            scrollTop: 0
        }, 1000);
    });
    //barras calificacion    
    $(document).on('click','.subir-anexo', function(e){
        var lineamiento = $(this).data('rubro');
        evaluar.cargar_popup_anexos(lineamiento);
    });
    $(document).on('click','.eliminar-anexo', function(e){
        var lineamiento = $(this).data('rubro');
        e.preventDefault();
        console.log(lineamiento);
        evaluar.eliminar_anexo(lineamiento,e);
    });
    //url de anexos
    $(document).on('click','.insertar-url', function(e){
        e.preventDefault();
        var lineamiento_id = $(this).data('rubro');
        var anexos = $('.listado-anexos[data-lineamiento="'+lineamiento_id+'"]');

        var html = $('.insertar-url-form-tpl').clone();
        html.removeClass('hide');
        html.removeClass('insertar-url-form-tpl');
        html.addClass('insertar-url-form');
        html.find('.url').prop('name', 'url');
        html.find('.nombre').prop('name', 'nombre');
        bootbox.dialog({
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
                 
                  var momento = 1;
                  var jqxhr = $.post('index.php?mod=sievas&controlador=evaluar&accion=guardar_url_anexo', {
                     url : url,
                     nombre : nombre,
                     lineamiento_id : lineamiento_id,
                     momento : momento,
                     nuevo : 1
                  });
                  
                  jqxhr.done(function(data){
                    $('.close').trigger('click');
                    anexos.parent().find('.anexos-popover').trigger('click');
                    var html = $('.anexo-tpl').clone();
                    html.removeClass('hide');
                    html.find('a').attr('href', data.ruta);
                    html.find('span').append(data.nombre);
                    anexos.parent().find('.listado-anexos').append(html.html());
                  });
              }
              else{
                  return false;
              }
              
            }
          }
          
        }
      });

    });
    
    
    $('.anexos-popover').popover({
           title : 'Acciones de anexos',
           content: function(){
                var rubro = $(this).data('rubro');
                var html = $('#anexos-popover-tpl').clone();
                html.removeClass('hide');
                html.find('.subir-anexo').attr('data-rubro', rubro);
                html.find('.insertar-url').attr('data-rubro', rubro);
                return html.html();
           },
           placement: 'bottom',
           html : 'true',
           trigger : 'click',
           container : 'body'
       }).on('shown.bs.popover', function(){
           
       });
    
    $('.rm-anexos-popover').popover({
           title : 'Eliminar anexos',
           content: function(){
                var html = [];
                var lineamiento = $(this).data('rubro');
                html = $.map($(this).parent().find('.listado-anexos a'), function(val,idx){
                    if($(val).find('span').hasClass('label-success'))
                    return '<div>'+$(val).html()+' <a href="#" class="eliminar-anexo" style="color: red" data-rubro="'+lineamiento+'"><i class="glyphicon glyphicon-minus-sign"></i></a>'+'<br/></div>';
                });
                return html.join('');
           },
           placement: 'bottom',
           html : 'true',
           trigger : 'click',
           container : 'body'
       }).on('shown.bs.popover', function(){
           
       });
       
    $('.rubro-popover').popover({
           title : 'Información complementaria del rubro',
           content: function(){
                var rubro = $(this).data('rubro');
                var html = $('#rubro-popover-tpl').clone();
                html.removeClass('hide');
                html.find('.dato-rubro').attr('data-rubro', rubro);
                html.find('.anexos-rubro').attr('data-rubro', rubro);
                return html.html();
           },
           placement: 'bottom',
           html : 'true',
           trigger : 'click',
           container : 'body'
       }).on('shown.bs.popover', function(){
           $('.dato-rubro').on('click', evaluar.cargarDatoRubro);      
           $('.anexos-rubro').on('click', function(e){
               e.preventDefault();
                var jqxhr = $.get('index.php?mod=sievas&controlador=evaluar&accion=getAnexos', {
                    momento : 1,
                    lineamiento : $(this).data('rubro')
                });    

                jqxhr.done(function(data){
                    console.log(data);
                    var html = $('.anexos-tablas-est-tpl').clone();
                    html.removeClass('anexos-tablas-est-tpl');
                    html.removeClass('hide');
                        
                    if(data.length > 0){
                        
                    }
                    else{
                        var msg = $('.anexos-tablas-est-tpl-no-data').clone();
                        msg.removeClass('anexos-tablas-est-tpl-no-data');
                        msg.removeClass('hide');
                        html.find('.tablas-estadisticas-list').parent().append(msg);
                    }
                    bootbox.dialog({
                        message: html,
                        title: "Anexos del rubro",
                        buttons: {
                          success: {
                            label: "Aceptar",
                            className: "btn-success",
                            callback: function() {


                            }
                          }
                        }
                      });
                });  
           });
       });
       
    $('.item-popover').popover({
           title : 'Información complementaria del item',
           content: function(){
                var rubro = $(this).data('rubro');
                var html = $('#item-popover-tpl').clone();
                html.removeClass('hide');
                html.find('.dato-item').attr('data-rubro', rubro);
                return html.html();
           },
           placement: 'bottom',
           html : 'true',
           trigger : 'click',
           container : 'body'
       }).on('shown.bs.popover', function(){
           $('.dato-item').on('click', evaluar.cargarIndicadores);          
       });
    
    var opciones = [
            {id: 0, text: 'Seleccione una opción...'},
            {id: 1, text: 'Mantener evaluación'},
            {id: 2, text: 'Nuevamente evaluar'},
            {id: 3, text: 'Actualizar evaluación'},
        ];
    $('.opinion').select2({
        placeholder: "Seleccione una opción...",
        minimumInputText: 1,
        initSelection: function(element, callback){
            var id = $(element).val();
            if(id > 0){
                callback(opciones[id]);
            }
        },
        data: opciones
    });

    
    
    
    $('.cal').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=evaluar&accion=get_escala_completa',
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    page_limit: 10
                }
            },
            results: function(data, page){
                return {
                    results: data
                }
            }
        },

        initSelection: function(element, callback) {
        // the input tag has a value attribute preloaded that points to a preselected movie's id
        // this function resolves that id attribute to an object that select2 can render
        // using its formatResult renderer - that way the movie name is shown preselected
        var id=$(element).val();   
            if (id!=="") {
                var url = "index.php?mod=sievas&controlador=evaluar&accion=get_escala&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data[0]);
                });
            }
        },

        formatResult: function(d){
            return d.desc_escala;
        },

        formatSelection: function(d){
            return d.desc_escala;
        },

        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }

    }).on('change', function(){
        var self = this;
        $('.current').removeClass('current');
        $(this).parent().parent().addClass('current');
        $(this).trigger('guardar_reevaluacion');
        
        //actualizar barras
        var momento_evaluacion = $('#momento_evaluacion').val();
        var lineamiento_id = $(this).parent().data('lineamiento');

        var jqxhr = $.get('index.php?mod=sievas&controlador=evaluar&accion=get_lineamiento', {
            momento_evaluacion : momento_evaluacion,
            id : lineamiento_id
        });
        
        jqxhr.done(function(data){
           data = data[0];
           $('.promedio-gral').html(data.estadisticas.puntaje);
           $('.promedio-gral').css('width', data.estadisticas.puntaje*10+'%');
           
           var bar_class = 'progress-bar-success';
            if(data.estadisticas.puntaje <= data.gradacion.nivel_bajo){
                var bar_class = 'progress-bar-danger';
            }

            if(data.estadisticas.puntaje > data.gradacion.nivel_bajo && data.estadisticas.puntaje <=data.gradacion.nivel_medio){
                var bar_class = 'progress-bar-warning';
            }

            var bar_class_r = 'progress-bar-success';
            if(data.estadisticas.calificacion_rubro.valor <= data.gradacion.nivel_bajo){
                var bar_class_r = 'progress-bar-danger';
            }

            if(data.estadisticas.calificacion_rubro.valor > data.gradacion.nivel_bajo && data.estadisticas.calificacion_rubro.valor <=data.gradacion.nivel_medio){
                var bar_class_r = 'progress-bar-warning';

            }
            
             $('.promedio-gral').removeClass('progress-bar-success');
             $('.promedio-gral').removeClass('progress-bar-danger');
             $('.promedio-gral').removeClass('progress-bar-warning');
             $('.promedio-gral').addClass(bar_class);
           
           $(self).parent().parent().parent().find('.promedio-rubro').html(data.estadisticas.calificacion_rubro.valor);
           $(self).parent().parent().parent().find('.promedio-rubro').css(data.estadisticas.calificacion_rubro.porcentaje);
           
           $(self).parent().parent().parent().find('.promedio-rubro').removeClass('progress-bar-success');
           $(self).parent().parent().parent().find('.promedio-rubro').removeClass('progress-bar-danger');
           $(self).parent().parent().parent().find('.promedio-rubro').removeClass('progress-bar-warning');
           $(self).parent().parent().parent().find('.promedio-rubro').addClass(bar_class_r);
           var rubro = $(self).data('rubro');
           
           $.each($('.promedio-rubro'), function(index,val){
                if($(val).data('rubro') == rubro){
                    $(val).parent().parent().parent().find('.promedio-rubro').removeClass('progress-bar-success');
                    $(val).parent().parent().parent().find('.promedio-rubro').removeClass('progress-bar-danger');
                    $(val).parent().parent().parent().find('.promedio-rubro').removeClass('progress-bar-warning');
                    $(val).parent().parent().parent().find('.promedio-rubro').addClass(bar_class_r);
                    
                    $(val).parent().parent().parent().find('.promedio-rubro').html(data.estadisticas.calificacion_rubro.valor);
                    $(val).parent().parent().parent().find('.promedio-rubro').css(data.estadisticas.calificacion_rubro.porcentaje);
                }
           });
        });
    });
    
    $('.summernote_modal').on('mouseover', function(){
        $('.current').removeClass('current');
        $(this).parent().parent().addClass('current');
    });
    
    $('.opinion').on('change', function(){
        $('.current').removeClass('current');
        $(this).parent().parent().addClass('current');
        $('.opinion-data', $(this).parent()).remove();
        $('.opinion-preview').remove();
        var opinion = $(this).val();
        switch(opinion){
            case '0':
                bootbox.alert("Debe seleccionar una opción");
                break;
            case '1':                
                $(this).trigger('guardar_reevaluacion');
                break;
            case '2':
                var html = '';
                html += '<div class="summernote_modal opinion-data"';
                html += 'data-success="guardar_reevaluacion" data-title="Reevaluar item" data-content=" "';
                html += 'style="padding: 10px; border: 1px solid #F8E0E6; overflow:auto; max-height:170px;">';
                html += '<input type="text" class="form-control">';
                html += '</div>';
                $(this).parent().append(html);
                break;
            case '3':
                var html = '';
                html += '<div class="summernote_modal opinion-data"';
                html += 'data-success="guardar_reevaluacion" data-title="Reevaluar item" data-content=" "';
                html += 'style="padding: 10px; border: 1px solid #F8E0E6; overflow:auto; max-height:170px;">';
                html += '<input type="text" class="form-control">';
                html += '</div>';
                $(this).parent().append(html);
                break;
        }
    });
    
    $(document).on('guardar_reevaluacion', function(e){       
        var lineamiento = $('.current').find('.opinion').parent().data('lineamiento');
        if(!lineamiento > 0){
            var lineamiento = $('.current').find('.cal').parent().data('lineamiento');
        }
        var fortalezas_data = $('.current').find('.fortalezas_data .opinion-data').data('content');
        var debilidades_data = $('.current').find('.debilidades_data .opinion-data').data('content');
        var planesmejora_data = $('.current').find('.planesmejora_data .opinion-data').data('content');     
        var fortalezas_opcion = $('.current').find('.fortalezas_op').select2('val') > 0 ? $('.current').find('.fortalezas_op').select2('val') : null;
        var debilidades_opcion = $('.current').find('.debilidades_op').select2('val') > 0 ? $('.current').find('.debilidades_op').select2('val') : null;
        var planesmejora_opcion = $('.current').find('.planesmejora_op').select2('val') > 0 ? $('.current').find('.planesmejora_op').select2('val') : null;
        var calificacion_nueva = $('.current').find('.cal').select2('val') > 0 ? $('.current').find('.cal').select2('val') : null;
        
//        console.log(lineamiento);
        
        var jqxhr = $.post('index.php?mod=sievas&controlador=evaluar&accion=guardar_reevaluacion', {
           lineamiento : lineamiento, 
           fortalezas_data : fortalezas_data, 
           debilidades_data : debilidades_data, 
           planesmejora_data : planesmejora_data, 
           fortalezas_opcion : fortalezas_opcion, 
           debilidades_opcion : debilidades_opcion, 
           planesmejora_opcion : planesmejora_opcion, 
           calificacion_nueva : calificacion_nueva 
        });

        jqxhr.done(function(data){
            $('.opinion-preview').remove();
            var opcion = fortalezas_opcion > 0 ? 'fortalezas_data' : debilidades_opcion > 0 ? 'debilidades_data' : planesmejora_opcion > 0 ? 'planesmejora_data' : '';
            var data_preview = fortalezas_opcion > 0 ? data.fortalezas : debilidades_opcion > 0 ? data.debilidades : planesmejora_opcion > 0 ? data.planesmejora : '';
            $("."+opcion).append('<div class="opinion-preview" style="color: blue"><br/>'+decodeURIComponent(data_preview)+'</div>');
            bootbox.alert("Se ha guardado su evaluación");
        });

    });
}

evaluar.cargarDatoRubro = function(e){
    e.preventDefault();
    var rubro = $(this).data('rubro');
    var campo = $(this).data('campo');

	
    var jqxhr = $.get('index.php?mod=sievas&controlador=evaluar&accion=getDatoRubro', {
        campo : campo,
        rubro : rubro
    });    

    jqxhr.done(function(data){
        bootbox.alert(decodeURIComponent(data.dato));
    });    
}

evaluar.cargarIndicadores = function(e){
    e.preventDefault();
     var lineamiento = $(this).data('rubro');
     var jqxhr = $.get('index.php?mod=sievas&controlador=lineamientos&accion=get_datos_complementarios', {
            lineamiento : lineamiento,
            tipo_lineamiento : 'item'
        });    

        jqxhr.done(function(data){
            bootbox.alert(decodeURIComponent(data.indicadores));
        }); 
          
}

evaluar.eliminar_anexo = function(lineamiento,e){
//    console.log($(e.target).parent().parent().find('span').data());
    var documento_id = $(e.target).parent().parent().find('span').data('documento');
    console.log(documento_id);
//    var self = this;
    var anexos = $('.listado-anexos[data-lineamiento="'+lineamiento+'"]');
    
    var jqxhr = $.post('index.php?mod=sievas&controlador=evaluar&accion=eliminarAnexo', {
        documento_id : documento_id
    });    
    
    jqxhr.done(function(){
        $(e.target).parent().parent().remove();
        anexos.parent().find('span[data-documento="'+documento_id+'"]').remove();
        anexos.parent().find('.anexos-popover').trigger('click');
    });
}