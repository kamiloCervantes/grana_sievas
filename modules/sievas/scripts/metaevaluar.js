var sieva_evaluar = {};

sieva_evaluar.items = [];
sieva_evaluar.plan_mejoramiento = [];

sieva_evaluar.init = function(){
    $('#arbol').jstree({
            "core" : {
              "animation" : 0,
              "multiple": false,
              "check_callback" : true,
              "themes" : { "stripes" : true },
              'data' : {
                    'url' : 'index.php?mod=sievas&controlador=evaluar&accion=get_arbol_rubros',
                    'data' : function (node) {
                      return { 'id' : node.id };
                    }
              }
            },
            "types" : {
                "#" : {
                  "max_children" : 1, 
                  "max_depth" : 4, 
                  "valid_children" : ["root"]
                },
                "root" : {
                  "icon" : "public/img/icon-nodes.png",
                  "valid_children" : ["default"]
                },
                "default" : {
                   "icon" : "public/img/icon-nodes.png",
                  "valid_children" : ["default","file"]
                },
                "file" : {
                  "icon" : "glyphicon glyphicon-file",
                  "valid_children" : []
                }
              }
            ,
            "plugins" : [
              "dnd", "search",
              "state", "types", "wholerow"
            ]
     });
     
     $('#cal').select2({
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
    });
    
      
    $('#referencia').on('click', sieva_evaluar.mostrar_referencia);
    $('#guardar-item').on('click', sieva_evaluar.guardaritem);
    sieva_evaluar.cargardatositem();
    sieva_evaluar.cargar_referencias();
//    $('#plan_mejoramiento').on('click', sieva_evaluar.cargarFormPlanMejoramiento);
    $('#ver-tabla-estadistica').on('click', sieva_evaluar.verTablaEstadistica);
    $(document).on('click', '.referencia-item', sieva_evaluar.cargar_referencia);
      $('.agregar-anexo').on('click', function(e){
            e.preventDefault();
            sieva_evaluar.cargar_popup_anexos();
        });
    $(document).on('click', '.eliminar-anexo', sieva_evaluar.eliminar_anexo);
    $('.dato-rubro').on('click', sieva_evaluar.cargarDatoRubro);
        
    $('.anexos-list').on('recargar.archivos', function(){
        $('.anexos-list table').remove();
        var self = this;        
        var lineamiento_id = $('#arbol').jstree('get_selected')[0];
        console.log(lineamiento_id);
        var jqxhr = $.get('index.php?mod=sievas&controlador=evaluar&accion=getAnexos', {
            lineamiento : lineamiento_id
        });
        
        jqxhr.done(function(data){
             var html = '<table style="width: 100%">';
            $.each(data, function(index,value){               
                html += '<tr>';      
                html += '<td style="padding: 4px">';      
                html += '<a href="'+value.ruta+'" class="btn btn-xs btn-default" target="_blank"><i class="glyphicon glyphicon-file"></i> '+value.nombre+'</a>';      
                html += '</td>';      
                html += '<td style="padding: 4px">';      
                html += value.fecha_creado;      
                html += '</td>';      
                html += '<td style="padding: 4px">';      
                html += '<a href="#" class="btn btn-xs btn-danger eliminar-anexo" data-id="'+value.id+'"><i class="glyphicon glyphicon-trash"></i></a>';      
                html += '</td>';      
                html += '<tr>';    
            });
            html += '</table>';                
            $(self).append(html);
        });
    });    
    
    //buscar
    var to = false;
    $('#buscar-tipo-lineamiento').on('click', function(){
        if(to) { clearTimeout(to); }
        to = setTimeout(function () {
          var v = $('#q').val();
          $('#arbol').jstree(true).search(v);
        }, 250);
    });
    
    $('#cerrar-nodos').on('click', function(){
        $('#arbol').jstree(true).close_all();
    });
    
    $('#abrir-nodos').on('click', function(){
        $('#arbol').jstree(true).open_all();
    });
    
//    $(document).on('guardar_evaluacion', sieva_evaluar.guardaritem);
//    $('#cal').on('change', function(e, data){
//        if(typeof data == 'undefined'){
//            $(document).trigger('guardar_evaluacion');
//        }
//    });
}

sieva_evaluar.cargarnombrerubro = function(){
    var lineamiento_id = $('#arbol').jstree('get_selected','full')[0];
    var rubro = 0;
    if(lineamiento_id.parent > 0){
        //es un item
        var jqxhr = $.get('index.php?mod=sievas&controlador=evaluar&accion=getNombreLineamiento', {
            lineamiento : lineamiento_id.parent
        });
        
        jqxhr.done(function(data){
            $('#nombre-rubro').html(data.num_orden+'. '+data.nom_lineamiento);
        });
    }
    else{
        //es un rubro o la raiz
        if(lineamiento_id.parent !== '#'){
            $('#nombre-rubro').html(lineamiento_id.text);
        }
    }
}

sieva_evaluar.verTablaEstadistica = function(){
    var html = '';
    var $html = null;
    var lineamiento_id = $('#arbol').jstree('get_selected','full')[0];
    var rubro = 0;
    var cargo = $('#formEvaluacion').data('cargo');
    var momento = $('#formEvaluacion').data('momento');
    var bandera = $('#formEvaluacion').data('bandera-ev');
    if(lineamiento_id.parent > 0){
        //es un item
        rubro = lineamiento_id.parent;
    }
    else{
        //es un rubro o la raiz
        if(lineamiento_id.parent !== '#'){
            rubro = lineamiento_id.id;
        }
    }
    
    var jqxhr = $.get('index.php?mod=sievas&controlador=evaluar&accion=getTablaLineamiento', {
        rubro : rubro
    });
    
    jqxhr.done(function(data){
       if(data.length > 0){
            html += '<form>';
            html += '<a href="'+data[0].ruta+'" target="_blank"><div class="alert alert-info" role="alert"><i class="glyphicon glyphicon-file"></i> Haga click aquí para ver la tabla de estadísticas asociada al rubro</div></a>';
            if(cargo == 1 && momento == 1 && bandera == 0){
                html += '<div class="form-group">';
                html += '<label>Actualizar archivo</label>';
                html += '<input type="file" name="file" id="archivo">';       
                html += '</div>';
            }           
            html += '</form>';

            $html = $.parseHTML(html);

            $('#archivo', $html).on('change', function(){
                $(this).append('<p>Cargando archivo...</p>');
                var file = $('#archivo')[0].files[0];
                var formData = new FormData();
                formData.append('archivo', file);
                formData.append('rubro', rubro);
                formData.append('documento_id', data[0].archivo_id)

                var jqxhr = $.ajax({
                    url: 'index.php?mod=sievas&controlador=evaluar&accion=upload_tabla_estadistica',
                    data: formData,           
                    processData: false,
                    contentType: false,
                    type: 'POST'            
                });       

                jqxhr.done(function(data){
                    $('.close').trigger('click');
                    $(document).trigger('guardar_evaluacion');
                });
                
                jqxhr.fail(function(err){ 
                    var error = JSON.parse(err.responseText);
                    bootbox.alert(error.error);         
                });
            });
            
             bootbox.dialog({
                title:"Tabla estadística",
                message:$html,
                buttons: {
                    cancel: {
                        label: "Cancelar",
                        className: "btn-danger"
                    },
                }
           });
       } 
       else{
           if(cargo == 1 && momento == 1 && bandera == 0){
                html += '<form>'; 
                html += '<div class="form-group">';
                html += '<label>Subir archivo</label>';
                html += '<input type="file" name="file" id="archivo">';       
                html += '</div>';
                html += '</form>';

                $html = $.parseHTML(html);

                $('#archivo', $html).on('change', function(){
                    $(this).append('<p>Cargando archivo...</p>');
                    var file = $('#archivo')[0].files[0];
                    var formData = new FormData();
                    formData.append('archivo', file);
                    formData.append('rubro', rubro);

                    var jqxhr = $.ajax({
                        url: 'index.php?mod=sievas&controlador=evaluar&accion=upload_tabla_estadistica',
                        data: formData,           
                        processData: false,
                        contentType: false,
                        type: 'POST'            
                    });       

                    jqxhr.done(function(data){
                        $('.close').trigger('click');
                        $(document).trigger('guardar_evaluacion');
                    });

                    jqxhr.fail(function(err){ 
                        var error = JSON.parse(err.responseText);
                        bootbox.alert(error.error);         
                    });
                });

                 bootbox.dialog({
                    title:"Tabla estadística",
                    message:$html,
                    buttons: {         
                        cancel: {
                            label: "Cancelar",
                            className: "btn-danger"
                        },
                    }
               });
           }
           else{
               bootbox.alert("No hay tablas estadisticas asociadas al rubro seleccionado");
           }
            
       }
    });   
}

sieva_evaluar.eliminar_anexo = function(e){
    e.preventDefault();
    var documento_id = $(this).data('id');
    var jqxhr = $.post('index.php?mod=sievas&controlador=evaluar&accion=eliminarAnexo', {
        documento_id : documento_id
    });
    
    jqxhr.done(function(){
         $('.anexos-list').trigger('recargar.archivos');
         $(document).trigger('guardar_evaluacion');
    });
}

sieva_evaluar.cargarFormPlanMejoramiento = function(e){
    e.preventDefault();
    var html = '';
    html += '<form id="formPlanMejoramiento">';
    html += '<div class="form-group">';
    html += '<label>Titulo</label>';
    html += '<input type="text" class="form-control" id="titulo" name="titulo">';
    html += '</div>';
    html += '<div class="form-group">';
    html += '<label>Subtitulo</label>';
    html += '<input type="text" class="form-control" id="subtitulo" name="subtitulo">';
    html += '</div>';
    html += '<div class="form-group">';
    html += '<label>Presupuesto</label>';
    html += '<input type="text" class="form-control" id="presupuesto" name="presupuesto">';
    html += '</div>';
    html += '<div class="form-group">';
    html += '<label>Fecha cumplimiento</label>';
    html += '<div class="input-group">';    
    html += '<input type="text" class="form-control" id="fecha_cumplimiento" name="fecha_cumplimiento" data-date-format="yyyy-mm-dd">';
    html += '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
    html += '</div>';
    html += '</div>';
    html += '<div class="form-group">';
    html += '<label>Objetivos</label>';
    html += '<textarea class="form-control" id="objetivos" name="objetivos"></textarea>';
    html += '</div>';
    html += '<div class="form-group">';
    html += '<label>Estrategias</label>';
    html += '<textarea class="form-control" id="estrategias" name="estrategias"></textarea>';       
    html += '</div>';
    html += '<div class="form-group">';
    html += '<label>Metas</label>';
    html += '<textarea class="form-control etiquetas" id="metas" name="metas"></textarea>';       
    html += '</div>';
    html += '<br/>';    
    html += '<div class="form-group">';
    html += '<label>Acciones</label>';
    html += '<textarea class="form-control etiquetas" id="acciones" name="acciones"></textarea>';       
    html += '</div>';
    html += '</div>';
    html += '</form>';
    
    $html = $.parseHTML(html); 
    
    $('.etiquetas', $html).textext({ plugins: 'tags' });    
    $('#fecha_cumplimiento', $html).datepicker();
    
    $.each(sieva_evaluar.plan_mejoramiento, function(index,value){
        switch(value.name){
            case 'titulo':
                 $('#titulo', $html).val(value.value);
                break;
            case 'subtitulo':
                $('#subtitulo', $html).val(value.value);
                break;
            case 'presupuesto':
                $('#presupuesto', $html).val(value.value);
                break;
            case 'fecha_cumplimiento':
                $('#fecha_cumplimiento', $html).val(value.value);
                break;
            case 'objetivos':
                $('#objetivos', $html).val(value.value);
                break;
            case 'estrategias':
                $('#estrategias', $html).val(value.value);
                break;
            case 'metas':
                $('#metas', $html).textext()[0].tags().addTags(JSON.parse(value.value));
                break;
            case 'acciones':
                $('#acciones', $html).textext()[0].tags().addTags(JSON.parse(value.value));
                break;
        }
    });

    bootbox.dialog({
            title:"Plan de mejoramiento",
            message:$html,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-success",
                    callback: function(){
                        sieva_evaluar.plan_mejoramiento = $('#formPlanMejoramiento').serializeArray();
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger"
                },
            }
       });

}

sieva_evaluar.cargar_popup_anexos = function(){    
    var html = '';
    html += '<form>'; 
    html += '<div class="form-group">';
    html += '<label>Subir archivo</label>';
    html += '<input type="file" name="file" id="archivo">';       
    html += '</div>';
    html += '</form>';

    $html = $.parseHTML(html); 
    
    $('#archivo', $html).on('change', function(){
        $(this).append('<p>Cargando archivo...</p>');
        var file = $('#archivo')[0].files[0];
        var lineamiento_id = $('#arbol').jstree('get_selected')[0];
        var formData = new FormData();
        formData.append('archivo', file);
        formData.append('lineamiento', lineamiento_id);
        
        var jqxhr = $.ajax({
            url: 'index.php?mod=sievas&controlador=evaluar&accion=upload_anexo',
            data: formData,           
            processData: false,
            contentType: false,
            type: 'POST'            
        });       
        
        jqxhr.done(function(data){
            $('.close').trigger('click');
            $('.anexos-list').trigger('recargar.archivos');
            $(document).trigger('guardar_evaluacion');
        });
        
        jqxhr.fail(function(err){ 
            var error = JSON.parse(err.responseText);
            bootbox.alert(error.error);         
        });
    });
    
    bootbox.dialog({
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


sieva_evaluar.mostrar_referencia = function(){
    var referencia = {};
    referencia.calificacion_item = '9';
    referencia.fortalezas = "Como todas las elecciones, éstas se celebran para que todos los ciudadanos tengan la opurtinadad de elegir a su representante en el gobierno. Cada cuatro años se vuelven a celebrar por si existe algún desacuerdo con el que se encuentra en el poder actualmente tengas la oportunidad de cambiarlo o si en realidad sigues de acuerdo con su mentalidad y también con sus acciones puedas volver a votarlo para que siga en el poder.";
    referencia.debilidades = "Como todas las elecciones, éstas se celebran para que todos los ciudadanos tengan la opurtinadad de elegir a su representante en el gobierno. Cada cuatro años se vuelven a celebrar por si existe algún desacuerdo con el que se encuentra en el poder actualmente tengas la oportunidad de cambiarlo o si en realidad sigues de acuerdo con su mentalidad y también con sus acciones puedas volver a votarlo para que siga en el poder.";
    referencia.plan_mejoramiento = "Como todas las elecciones, éstas se celebran para que todos los ciudadanos tengan la opurtinadad de elegir a su representante en el gobierno. Cada cuatro años se vuelven a celebrar por si existe algún desacuerdo con el que se encuentra en el poder actualmente tengas la oportunidad de cambiarlo o si en realidad sigues de acuerdo con su mentalidad y también con sus acciones puedas volver a votarlo para que siga en el poder.";

    var html = '';
    html += '<p><b>Calificación:</b> '+referencia.calificacion_item+'</p>';
    html += '<p><b>Fortalezas:</b> '+referencia.fortalezas+'</p>';
    html += '<p><b>Debilidades:</b> '+referencia.debilidades+'</p>';
    html += '<p><b>Plan de mejoramiento:</b> '+referencia.plan_mejoramiento+'</p>';
    
    bootbox.dialog({
        title:"Autoevaluación anterior",
        message:html,
        buttons: {
            success: {
                label: "Aceptar",
                className: "btn-success"
            }
        }
    });
}

sieva_evaluar.cargardatositem = function(){
    $('#arbol').on('select_node.jstree', function(e,data){
        $('#formEvaluacion').data('id', '');
//        $('.anexos-list').trigger('recargar.archivos');
//        sieva_evaluar.cargarPlantillaRubro();
        sieva_evaluar.cargarnombrerubro();
        var parent = parseInt(data.node.parent);
        var id = data.node.id;
        var text = data.node.text;
        if(parent == 0){
//            var html = '';
//            html += '<div id="nombre-item" style="color:#990000">'+text+'</div>';
//            html += '<div id="calificacion"></div>';
//            $('#datos-item').html(html);
            
            var jqxhr = $.get('index.php?mod=sievas&controlador=evaluar&accion=get_metaevaluacion_rubro', {
                padre : parent,
                rubro : id
            });
            
            jqxhr.done(function(data){
                if(data.length > 0){
                    data = data[0];
                    $('#formEvaluacion').data('id', data.id);
                    $('#anotacion').data('content', data.anotacion !== null ? data.anotacion : '').trigger('render.summernote_helper');
                    //calificaciones
//                    var bar_class = 'progress-bar-success';
//                    if(data.estadisticas.calificacion_item <= data.gradacion.nivel_bajo*10){
//                        var bar_class = 'progress-bar-danger';
//                    }
//                    if(data.estadisticas.calificacion_item > data.gradacion.nivel_bajo*10 && data.estadisticas.calificacion_item <=data.gradacion.nivel_medio*10){
//                        var bar_class = 'progress-bar-warning';
//                    }
//                    var bar_class_r = 'progress-bar-success';
//                    if(data.estadisticas.calificacion_rubro.valor <= data.gradacion.nivel_bajo){
//                        var bar_class_r = 'progress-bar-danger';
//                    }
//                    if(data.estadisticas.calificacion_rubro.valor > data.gradacion.nivel_bajo && data.estadisticas.calificacion_rubro.valor <=data.gradacion.nivel_medio){
//                        var bar_class_r = 'progress-bar-warning';
//                    }
//                    var html = '';
//                    html += '<div class="row">';
//                    html += '<div class="col-sm-6">';
//                    html += '<p>Calificación ítem</p>';
//                    html += '<div class="progress">';
//                    html += '<div class="progress-bar '+bar_class+'" role="progressbar" aria-valuenow="'+data.estadisticas.calificacion_item+'" aria-valuemin="0" aria-valuemax="100" style="width: '+data.estadisticas.calificacion_item+'%;">';
//                    html += ' '+data.estadisticas.calificacion_item+'%';
//                    html += '</div>';
//                    html += '</div>';
//                    html += '</div>';
//                    html += '<div class="col-sm-6">';
//                    html += '<p>Calificación rubro</p>';
//                    html += '<div class="progress">';
//                    html += '<div class="progress-bar '+bar_class_r+'" role="progressbar" aria-valuenow="'+data.estadisticas.calificacion_rubro.porcentaje+'" aria-valuemin="0" aria-valuemax="100" style="width: '+data.estadisticas.calificacion_rubro.porcentaje+'%;">';
//                    html += ''+data.estadisticas.calificacion_rubro.valor;
//                    html += '</div>';
//                    html += '</div>';
//                    html += '</div>';                    
//                    html += '</div>';
//                    
//                    
//                    html += '';
//                    $('#calificaciones').html(html);
 

//                    $('#plan_mejoramiento').data('content', data.acciones !== null ? data.acciones : '').trigger('render.summernote_helper');
//                    if($('#cal').val() != data.cod_gradacion_escala){
                        $('#cal').val(data.cod_gradacion_escala);
                        $('#cal').trigger('change', { update : true });
//                    }
                    
//                    sieva_evaluar.plan_mejoramiento = data.plan_mejoramiento;
                }   
                else{
//                    $('#fortalezas').trigger('reset.summernote_helper');
//                    $('#debilidades').trigger('reset.summernote_helper');
                    $('#anotacion').trigger('reset.summernote_helper');
                    $('#cal').select2('val', '');
//                    sieva_evaluar.plan_mejoramiento = [];
                }
            });
        }
        else{
           var html = '';
           html += '<div id="nombre-item"></div>';
           html += '<div id="calificacion"></div>';
           $('#datos-item').html(html);
           $('#anotacion').trigger('reset.summernote_helper');
           $('#cal').select2('val', '');
        }
    });
}

sieva_evaluar.guardaritem = function(e){
    e.preventDefault();
//    var cargo = $('#formEvaluacion').data('cargo');
//    var momento = $('#formEvaluacion').data('momento');
//    var bandera = $('#formEvaluacion').data('bandera-ev');
//    if(cargo == 1 && bandera == 0){
        var anotacion = $('#anotacion').data('content');    
        var calificacion = $('#cal').val();    
        var id = $('#formEvaluacion').data('id');   
        var selected = $('#arbol').jstree(true).get_selected(true);
        var lineamiento = selected[0];
        if(lineamiento.parent == 0){
            var jqxhr = $.post('index.php?mod=sievas&controlador=evaluar&accion=guardar_metaevaluacion', {
                calificacion : calificacion,
                anotacion : anotacion,                
                id : id,                
                rubro : lineamiento.id
            });

            jqxhr.done(function(data){
                bootbox.alert("Los datos han sido guardados exitosamente");
                 var selected = $('#arbol').jstree('get_selected')[0]; 
                 var node = $('#arbol').jstree('get_node', selected); 
                 $('#arbol').trigger('select_node.jstree', { node : node, selected : selected });
                 $('#formEvaluacion').data('id', data.id);
            });

            jqxhr.fail(function(err){ 
                var error = JSON.parse(err.responseText);
                bootbox.alert(error.error);         
            });
        }
        else{
            bootbox.alert("Debe seleccionar un rubro");
        }    
//    }
//    else{
//        bootbox.alert("No tiene permisos para guardar los cambios");
//        var selected = $('#arbol').jstree('get_selected')[0]; 
//        var node = $('#arbol').jstree('get_node', selected); 
//        $('#arbol').trigger('select_node.jstree', { node : node, selected : selected });
//    }
    
}

sieva_evaluar.cargar_referencias = function(){
    var jqxhr = $.get('index.php?mod=sievas&controlador=evaluar&accion=get_referencias');
    
    jqxhr.done(function(data){
        $.each(data, function(index,value){
            console.log(value);
            $('#lista-referencias').append('<li><a href="#" data-id="'+value.cod_momento_evaluacion+'" class="referencia-item"> '+value.momento+' '+value.fecha_inicia.substring(0,4)+'</a></li>');
        });
        if(data.length === 0){
            $('#lista-referencias').append('<li><a href="#">No hay referencias</a></li>');
        }
        
    });
}

sieva_evaluar.cargar_referencia = function(){
    var momento_evaluacion = $(this).data('id');
    var lineamiento_id = $('#arbol').jstree('get_selected')[0];
    
     var jqxhr = $.get('index.php?mod=sievas&controlador=evaluar&accion=get_lineamiento', {
        momento_evaluacion : momento_evaluacion,
        id : lineamiento_id
    });
    
    jqxhr.done(function(data){
        var html = '';
        html += '<ul class="nav nav-tabs" role="tablist">';
        html += '<li class="active"><a href="#home" role="tab" data-toggle="tab">Calificación</a></li>';
        html += '</ul>';
        html += '<div class="tab-content">';
        html += '<div class="tab-pane active" id="home">';
        html += '<br/>';
        html += '<form>';
        html += '<div class="form-group">';
        html += '<label>Calificacion</label>';
        html += '<input type="hidden" name="calificacion" class="form-control" id="cal_r" readonly>';
        html += '</div>';
        html += '<div class="form-group">';
        html += '<label>Fortalezas</label>';
        html += '<p id="fortalezas_r"></p>';        
        html += '</div>';
        html += '<div class="form-group">';
        html += '<label>Debilidades</label>';
        html += '<div id="debilidades_r" class="summernote_modal" data-title="Editar debilidades" style="overflow:auto">';
        html += '<input type="text" class="form-control">';
        html += '</div>';
        html += '</div>';
        html += '<div class="form-group">';
        html += '<label>Plan de mejoramiento</label>';
        html += '<div id="plan_mejoramiento_r" class="summernote_modal" data-title="Editar mejoramiento" style="overflow:auto">';
        html += '<input type="text" class="form-control">';
        html += '</div>';
        html += '</div>';
        html += ' </form>'; 
        html += '</div>';
        html += ' <div class="tab-pane" id="profile">';
        html += '<form>';
        html += '<div class="form-group">';
        html += '<br/>';
        html += '<label>Titulo</label>';
        html += '<input type="text" class="form-control" id="titulo_r" name="titulo" readonly>';
        html += '</div>';
        html += '<div class="form-group">';
        html += '<label>Subtitulo</label>';
        html += '<input type="text" class="form-control" id="subtitulo_r" name="subtitulo" readonly>';
        html += '</div>';
        html += '<div class="form-group">';
        html += '<label>Presupuesto</label>';
        html += '<input type="text" class="form-control" id="presupuesto_r" name="presupuesto" readonly>';
        html += '</div>';
        html += '<div class="form-group">';
        html += '<label>Fecha cumplimiento</label>';
        html += '<div class="input-group">';    
        html += '<input type="text" class="form-control" id="fecha_cumplimiento_r" name="fecha_cumplimiento" data-date-format="yyyy-mm-dd" readonly>';
        html += '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
        html += '</div>';
        html += '</div>';
        html += '<div class="form-group">';
        html += '<label>Objetivos</label>';
        html += '<textarea class="form-control" id="objetivos_r" name="objetivos" readonly></textarea>';
        html += '</div>';
        html += '<div class="form-group">';
        html += '<label>Estrategias</label>';
        html += '<textarea class="form-control" id="estrategias_r" name="estrategias" readonly></textarea>';       
        html += '</div>';
        html += '<div class="form-group">';
        html += '<label>Metas</label>';
        html += '<textarea class="form-control etiquetas" id="metas_r" name="metas" readonly></textarea>';       
        html += '</div>';
        html += '<br/>';    
        html += '<div class="form-group">';
        html += '<label>Acciones</label>';
        html += '<textarea class="form-control etiquetas" id="acciones_r" name="acciones" readonly></textarea>';       
        html += '</div>';
        html += '</div>';
        html += '</form>';
        html += '</div>';
        html += '</div>';
        
        
        $html = $.parseHTML(html); 
        if(data.length == 0){
            $('#cal_r', $html).html('No hay datos');
            $('#fortalezas_r', $html).html('No hay datos');
            $('#debilidades_r', $html).html('No hay datos');
            $('#plan_mejoramiento_r', $html).html('No hay datos');
            $('.etiquetas', $html).textext({ plugins: 'tags' }); 
        }
        else{
            $('#cal_r', $html).val(data[0].cod_gradacion_escala);
            $('#fortalezas_r', $html).html((decodeURIComponent(data[0].fortalezas) == 'null' ? 'No hay datos' : decodeURIComponent(data[0].fortalezas)));
            $('#debilidades_r', $html).html(decodeURIComponent(data[0].debilidades) == 'null' ? 'No hay datos' : decodeURIComponent(data[0].debilidades));
            $('#plan_mejoramiento_r', $html).html(decodeURIComponent(data[0].plan_mejoramiento) == 'null' ? 'No hay datos' : decodeURIComponent(data[0].plan_mejoramiento));
            $('.etiquetas', $html).textext({ plugins: 'tags' }); 
        }
        
      
//        console.log(data[0].plan_mejoramiento);
        
//       $.each(data[0].plan_mejoramiento, function(index,value){
//            switch(value.name){
//                case 'titulo':
//                     $('#titulo_r', $html).val(value.value);
//                    break;
//                case 'subtitulo':
//                    $('#subtitulo_r', $html).val(value.value);
//                    break;
//                case 'presupuesto':
//                    $('#presupuesto_r', $html).val(value.value);
//                    break;
//                case 'fecha_cumplimiento':
//                    $('#fecha_cumplimiento_r', $html).val(value.value);
//                    break;
//                case 'objetivos':
//                    $('#objetivos_r', $html).val(value.value);
//                    break;
//                case 'estrategias':
//                    $('#estrategias_r', $html).val(value.value);
//                    break;
//                case 'metas':
//                    $('#metas_r', $html).textext()[0].tags().addTags(JSON.parse(value.value));
//                    break;
//                case 'acciones':
//                    $('#acciones_r', $html).textext()[0].tags().addTags(JSON.parse(value.value));
//                    break;
//            }
//        });
        
        $('#cal_r', $html).select2({
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
            console.log(id);
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
    });
    
    bootbox.dialog({
               title:"Antecedentes",
               message:$html,
               buttons: {
                   success: {
                       label: "Aceptar",
                       className: "btn-primary"
                   } 
               }
          });
       });

}

sieva_evaluar.cargarPlantillaRubro = function(){
    var lineamiento_id = $('#arbol').jstree('get_selected','full')[0];
    var rubro = 0;
    if(lineamiento_id.parent > 0){
        //es un item
        rubro = lineamiento_id.parent;
    }
    else{
        //es un rubro o la raiz
        if(lineamiento_id.parent !== '#'){
            rubro = lineamiento_id.id;
        }
    }
    
    var jqxhr = $.get('index.php?mod=sievas&controlador=evaluar&accion=getPlantillaEstadisticas', {
        rubro : rubro
    });
    
    jqxhr.done(function(data){
        $('#plantilla').prop('href', data[0].ruta);
    });
}

sieva_evaluar.cargarDatoRubro = function(e){
    e.preventDefault();
    var lineamiento_id = $('#arbol').jstree('get_selected','full')[0];
    var campo = $(this).data('campo');
    var rubro = 0;
    if(lineamiento_id.parent > 0){
        //es un item
        rubro = lineamiento_id.parent;
    }
    else{
        //es un rubro o la raiz
        if(lineamiento_id.parent !== '#'){
            rubro = lineamiento_id.id;
        }
    }
    var jqxhr = $.get('index.php?mod=sievas&controlador=evaluar&accion=getDatoRubro', {
        campo : campo,
        rubro : rubro
    });
    
    jqxhr.done(function(data){
        bootbox.alert(data.dato);
    });
    
}


$(sieva_evaluar.init);
