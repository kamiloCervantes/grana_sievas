var cronograma = {};

cronograma.initreporte = function(){
    $(document).on('click', '.cronograma-item', cronograma.cargar_actas);
    $(document).on('click', '.ver-actas', cronograma.ver_actas);
}

cronograma.cargar_actas = function(e){
    e.preventDefault();
    var actividad = $(this).data('id');
    var html = '';
    html += '<form>';
    html += '<div class="form-group col-sm-12">';
    html += '<label class="control-label">Nombre</label>';
    html += '<input type="text" class="form-control" id="nombre" name="nombre">';
    html += '</div>';
    html += '<div class="form-group col-sm-12">';
    html += '<label class="control-label">Descripción</label>';
    html += '<textarea class="form-control" id="descripcion" name="descripcion"></textarea>';
    html += '</div>';
    html += '<div class="form-group col-sm-12">';
    html += '<label class="control-label">Soporte</label>';
    html += '<input type="file" id="soporte" name="soporte">';
    html += '</div>';
    html += '</form>';
    
    bootbox.dialog({
        title:"Actas",
        message:html,
        buttons: {            
            accept: {
                label: "Aceptar",
                className: "btn-success",
                callback: function(){
                    var nombre = $('#nombre').val();
                    var descripcion = $('#descripcion').html();
                    var soporte = $('#soporte')[0].files[0];
                    
                    var form = new FormData();
                    form.append('nombre', nombre);
                    form.append('descripcion', descripcion);
                    form.append('soporte', soporte);
                    form.append('actividad', actividad);
                    
                    var jqxhr = $.ajax({
                        url: 'index.php?mod=sievas&controlador=actas&accion=guardar_acta',
                        data: form,
                        processData: false,
                        contentType: false,
                        type: 'POST'
                      });

                   jqxhr.done(function(data){
                       bootbox.alert(data.mensaje);
                   });

                   jqxhr.fail(function(err){
                       bootbox.alert(err.responseText);
                   });
                   
                }
            },
            cancel: {
                label: "Cancelar",
                className: "btn-danger"
            },
        }
   });
}

cronograma.ver_actas = function(){
    var actividad = $(this).data('id');
    var jqxhr = $.get('index.php?mod=sievas&controlador=actas&accion=get_actas', {
        actividad : actividad
    });
    
    jqxhr.done(function(data){
        var html = '';
        html += '<table class="table">';
        html += '<thead>';
        html += '<tr>';        
        html += '<th>Nombre</th>';
        html += '<th>Descripción></th>';
        html += '<th>&nbsp;</th>';
        html += '</tr>';
        html += '</thead>';
        $.each(data, function(index,value){           
            html += '<tr>';
            html += '<td>'+value.nombre+'</td>';
            html += '<td>'+(typeof value.descripcion == 'undefined' ? 'N/A' : value.descripcion)+'</td>';
            html += '<td><a href="'+value.ruta+'" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-eye-open"></i></a></td>';
            html += '</tr>';            
            
        });
        html += '</table>';
        
        bootbox.dialog({
            title:"Actas",
            message:html,
            buttons: {
                cancel: {
                    label: "Aceptar",
                    className: "btn-primary"
                }               
            }
       });
    });
    
    
    
}

cronograma.initcronocomite = function(){
    $(document).on('click', '.eliminar_actividad', function(e){
        e.preventDefault();
       var id = $(this).data('id');
       var self = this;
        bootbox.dialog({
                message: "¿Está seguro que desea eliminar la actividad seleccionada?",
                title: "Eliminar actividad",
                buttons: {
                  si: {
                    label: "Si",
                    className: "btn-default",
                    callback: function(e) {
                        
                        var jqxhr = $.get('index.php?mod=sievas&controlador=cronograma&accion=eliminar_cronograma_comite', {
                            id : id
                        });
                        
                        jqxhr.done(function(){
                            $(self).parent().parent().remove();
                        });
                    }                    

                  },
                  no: {
                    label: "No",
                    className: "btn-default",
                    callback: function(e) {
                         
                    }                    

                  },
                }
              }); 
    });
    
    
    $('.add_actividad').on('click', function(e){
        e.preventDefault();
        $actividad = $($('.actividad_tpl')[0]).clone();
        $actividad.addClass('actividad');
        $actividad.removeClass('hide');        
        $actividad.removeClass('actividad_tpl');  
        
//        $actividad.find('#nro_actividad').html($('.actividad').length + 1); 

    $('.add-itinerario',$actividad).on('click', function(e){
            e.preventDefault(); 
            var html = '';
            html += '<div class="itinerario-item">';
            html += '<div class="form-group col-sm-4" >';
            html += '<label class="control-label">Punto de la agenda</label>';
            html += '<input type="hidden" class="form-control actividad_gen" />';
            html += '</div>';
            html += '<div class="form-group col-sm-4">';
            html += '<label class="control-label">Etapa</label>';
            html += '<input type="hidden" class="form-control etapa" />';
            html += '</div>';
            html += '<div class="form-group col-sm-4">';
            html += '<label class="control-label">Medio</label>';
            html += '<input type="hidden" class="form-control medio" />';
            html += '</div>';
            html += '<div class="form-group col-sm-2">';
            html += '<label class="control-label">Fecha inicia</label>';
            html += '<div class="input-group">';
            html += '<input type="text" class="form-control fecha fecha_inicio" data-date-format="yyyy-mm-dd">';
            html += '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
            html += '</div>';
            html += '</div>';
            html += '<div class="form-group col-sm-2">';
            html += '<label class="control-label">Fecha fin</label>';
            html += '<div class="input-group">';
            html += '<input type="text" class="form-control fecha fecha_fin" data-date-format="yyyy-mm-dd">';
            html += '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
            html += '</div>';
            html += '</div>';
            html += '<div class="form-group col-sm-8">';
            html += '<label class="control-label">Anotaciones</label>';
            html += '<div class="anotaciones summernote_modal" data-title="Anotaciones" style="overflow:auto; min-height: 60px">';
            html += '<input type="text" class="form-control">';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            
            $html = $.parseHTML(html);
            console.log(html);
            
            $('.actividad_gen', $html).select2({
                minimumInputText: 1,
                placeholder: "Seleccione...",
                ajax:{
                    url:'index.php?mod=sievas&controlador=cronograma&accion=get_actividades',
                    dataType: 'json',
                    data: function(term, page){
                        return {
                            q: term,
                            page_limit: 10,
                            agenda : 1
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
                        var url = "index.php?mod=sievas&controlador=cronograma&accion=get_actividad&id="+id;
                        var jqxhr = $.get(url);
                        jqxhr.done(function(data){
                            callback(data);
                        });
                    }
                },
                formatResult: function(d){
                    return d.actividad;
                },
                formatSelection: function(d){
                    return d.actividad;
                },
                dropdownCssClass: "bigdrop",
                escapeMarkup: function (m) { return m; }
            }).on('change', function(){
                var data = $(this).select2('data');
                $(this).closest('.itinerario-item').find('.anotaciones').trigger('reset.summernote_helper');  
                $(this).closest('.itinerario-item').find('.medio').select2('val', '');
                $(this).closest('.itinerario-item').find('.etapa').select2('val', '');
//                console.log(encodeURIComponent(data.anotaciones));
                $(this).closest('.itinerario-item').find('.anotaciones').data('content', data.anotaciones != null ? encodeURIComponent(data.anotaciones):'').trigger('render.summernote_helper');        
                $(this).closest('.itinerario-item').find('.medio').select2('val', data.cod_medio);
                $(this).closest('.itinerario-item').find('.etapa').select2('val', data.cod_etapa);
            });
            
            $('.etapa',$html).select2({
                minimumInputText: 1,
                placeholder: "Seleccione...",
                ajax:{
                    url:'index.php?mod=sievas&controlador=cronograma&accion=get_etapas',
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
                        var url = "index.php?mod=sievas&controlador=cronograma&accion=get_etapa&id="+id;
                        var jqxhr = $.get(url);
                        jqxhr.done(function(data){
                            callback(data);
                        });
                    }
                },
                formatResult: function(d){
                    return d.etapa;
                },
                formatSelection: function(d){
                    return d.etapa;
                },
                dropdownCssClass: "bigdrop",
                escapeMarkup: function (m) { return m; }
            });
            $('.medio',$html).select2({
                minimumInputText: 1,
                placeholder: "Seleccione...",
                ajax:{
                    url:'index.php?mod=sievas&controlador=cronograma&accion=get_medios',
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
                        var url = "index.php?mod=sievas&controlador=cronograma&accion=get_medio&id="+id;
                        var jqxhr = $.get(url);
                        jqxhr.done(function(data){
                            callback(data);
                        });
                    }
                },
                formatResult: function(d){
                    return d.medio;
                },
                formatSelection: function(d){
                    return d.medio;
                },
                dropdownCssClass: "bigdrop",
                escapeMarkup: function (m) { return m; }
            });
            $('.fecha', $html).datepicker();
            $actividad.find('.itinerarios-list').append($html);
          
                    
      
        });
        
    $('.add-responsable',$actividad).popover({            
            content : function(){  
                var html = $(this).parent().parent().parent().parent().parent().find('.responsable-form').clone().removeClass('hide').html();
                return html;                
            },
            placement : "top",
            html : true,
            title : "Agregar responsable"
        }).on('shown.bs.popover', function(){
            var self = this;
            $(this).parent().find('.seleccionar').on('click', function(e){
                e.preventDefault();
                var data = $(self).parent().find('.responsable_select').select2('data');
                var html = '';
                html += '<tr>';
                html += '<td class="responsable-selected" data-responsable-id="'+data.id+'">'+data.nombres+'</td>';
                html += '<td><a href="#" class="remove-responsable"><i class="glyphicon glyphicon-minus-sign pull-right" style="color: #d2322d"></i></a></td>';
                html += '</tr>';
                
                $html = $.parseHTML(html);
                
                $('.remove-responsable', $html).on('click', function(e){
                    e.preventDefault();
                    $(this).parent().parent().remove();
                });
                $(self).parent().parent().parent().parent().find('tbody').append($html);
                $(self).popover('hide');
            });
             $(this).parent().find('.responsable_select').select2({
                minimumInputText: 1,
                placeholder: "Seleccione...",
                ajax:{
                    url:'index.php?mod=sievas&controlador=programas&accion=get_personas',
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
                        var url = "index.php?mod=sievas&controlador=programas&accion=get_persona&id="+id;
                        var jqxhr = $.get(url);
                        jqxhr.done(function(data){
                            callback(data[0]);
                        });
                    }
                },
                formatResult: function(d){
                    return d.nombres;
                },
                formatSelection: function(d){
                    return d.nombres;
                },
                dropdownCssClass: "bigdrop",
                escapeMarkup: function (m) { return m; }
            });
        });
        
    $('.add-invitado',$actividad).popover({            
            content : function(){  
                var html = $(this).parent().parent().parent().parent().parent().find('.invitado-form').clone().removeClass('hide').html();
                return html;                
            },
            placement : "top",
            html : true,
            title : "Invitar"
        }).on('shown.bs.popover', function(){
            var self = this;
            $(this).parent().find('.agregar-invitado').on('click', function(e){
                e.preventDefault();
                var invitado_nombre = $(self).parent().find('.invitado_nombre').val();
                var invitado_email = $(self).parent().find('.invitado_email').val();
                var html = '';
                html += '<tr class="invitados-item">';
                html += '<td>'+invitado_nombre+'</td>';
                html += '<td>'+invitado_email+'</td>';
                html += '<td><a href="#" class="remove-invitado"><i class="glyphicon glyphicon-minus-sign pull-right" style="color: #d2322d"></i></a></td>';
                html += '</tr>';
                
                $html = $.parseHTML(html);
                
                $('.remove-invitado', $html).on('click', function(e){
                    e.preventDefault();
                    $(this).parent().parent().remove();
                });
                $(self).parent().parent().parent().parent().find('tbody').append($html);
                $(self).popover('hide');
            });
             
        });
        
     $('.fecha', $actividad).datepicker();
        $('.actividad_gen', $actividad).select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=cronograma&accion=get_actividades',
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
                var url = "index.php?mod=sievas&controlador=cronograma&accion=get_actividad&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.actividad;
        },
        formatSelection: function(d){
            return d.actividad;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    }).on('change', function(){
        var data = $(this).select2('data');
        $(this).parent().parent().parent().find('.anotaciones').trigger('reset.summernote_helper');  
        $(this).parent().parent().parent().find('.medio').select2('val', '');
        $(this).parent().parent().parent().find('.etapa').select2('val', '');
        console.log(encodeURIComponent(data.anotaciones));
        $(this).parent().parent().parent().find('.anotaciones').data('content',encodeURIComponent(data.anotaciones)).trigger('render.summernote_helper');        
        $(this).parent().parent().parent().find('.medio').select2('val', data.cod_medio);
        $(this).parent().parent().parent().find('.etapa').select2('val', data.cod_etapa);
    });
    $('.medio',$actividad).select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=cronograma&accion=get_medios',
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
                var url = "index.php?mod=sievas&controlador=cronograma&accion=get_medio&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.medio;
        },
        formatSelection: function(d){
            return d.medio;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    $('.etapa',$actividad).select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=cronograma&accion=get_etapas',
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
                var url = "index.php?mod=sievas&controlador=cronograma&accion=get_etapa&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.etapa;
        },
        formatSelection: function(d){
            return d.etapa;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    
          bootbox.dialog({
            message: $actividad,
            title: "Nueva actividad",
            className: 'wide',
            buttons: {
              success: {
                label: "Aceptar",
                className: "btn-success",
                callback: function(e) {
                    cronograma.guardar_actividad_comite(e);
                }
            },
              cancel: {
                label: "Cancelar",
                className: "btn-danger",
                callback: function(e) {
                    
                }
            }
        }
    });
        
        
    });
}

cronograma.initadd = function(){    
    $('.add-more').on('click', function(){
        $actividad = $($('.actividad_tpl')[0]).clone();
        $actividad.addClass('actividad');
        $actividad.removeClass('hide');        
        $actividad.removeClass('actividad_tpl');  
        $actividad.find('#nro_actividad').html($('.actividad').length + 1);
        
        $('.add-itinerario',$actividad).on('click', function(e){
            e.preventDefault(); 
            var html = '';
            html += '<div class="itinerario-item">';
            html += '<div class="form-group col-sm-4" >';
            html += '<label class="control-label">Punto de la agenda</label>';
            html += '<input type="hidden" class="form-control actividad_gen" />';
            html += '</div>';
            html += '<div class="form-group col-sm-4">';
            html += '<label class="control-label">Etapa</label>';
            html += '<input type="hidden" class="form-control etapa" />';
            html += '</div>';
            html += '<div class="form-group col-sm-4">';
            html += '<label class="control-label">Medio</label>';
            html += '<input type="hidden" class="form-control medio" />';
            html += '</div>';
            html += '<div class="form-group col-sm-2">';
            html += '<label class="control-label">Fecha inicia</label>';
            html += '<div class="input-group">';
            html += '<input type="text" class="form-control fecha fecha_inicio" data-date-format="yyyy-mm-dd">';
            html += '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
            html += '</div>';
            html += '</div>';
            html += '<div class="form-group col-sm-2">';
            html += '<label class="control-label">Fecha fin</label>';
            html += '<div class="input-group">';
            html += '<input type="text" class="form-control fecha fecha_fin" data-date-format="yyyy-mm-dd">';
            html += '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
            html += '</div>';
            html += '</div>';
            html += '<div class="form-group col-sm-8">';
            html += '<label class="control-label">Anotaciones</label>';
            html += '<div class="anotaciones summernote_modal" data-title="Anotaciones" style="overflow:auto; min-height: 60px">';
            html += '<input type="text" class="form-control">';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            
            $html = $.parseHTML(html);
            
            $('.actividad_gen', $html).select2({
                minimumInputText: 1,
                placeholder: "Seleccione...",
                ajax:{
                    url:'index.php?mod=sievas&controlador=cronograma&accion=get_actividades',
                    dataType: 'json',
                    data: function(term, page){
                        return {
                            q: term,
                            page_limit: 10,
                            agenda : 1
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
                        var url = "index.php?mod=sievas&controlador=cronograma&accion=get_actividad&id="+id;
                        var jqxhr = $.get(url);
                        jqxhr.done(function(data){
                            callback(data);
                        });
                    }
                },
                formatResult: function(d){
                    return d.actividad;
                },
                formatSelection: function(d){
                    return d.actividad;
                },
                dropdownCssClass: "bigdrop",
                escapeMarkup: function (m) { return m; }
            }).on('change', function(){
                var data = $(this).select2('data');
                $(this).closest('.itinerario-item').find('.anotaciones').trigger('reset.summernote_helper');  
                $(this).closest('.itinerario-item').find('.medio').select2('val', '');
                $(this).closest('.itinerario-item').find('.etapa').select2('val', '');
//                console.log(encodeURIComponent(data.anotaciones));
                $(this).closest('.itinerario-item').find('.anotaciones').data('content', data.anotaciones != null ? encodeURIComponent(data.anotaciones):'').trigger('render.summernote_helper');        
                $(this).closest('.itinerario-item').find('.medio').select2('val', data.cod_medio);
                $(this).closest('.itinerario-item').find('.etapa').select2('val', data.cod_etapa);
            });
            
            $('.etapa',$html).select2({
                minimumInputText: 1,
                placeholder: "Seleccione...",
                ajax:{
                    url:'index.php?mod=sievas&controlador=cronograma&accion=get_etapas',
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
                        var url = "index.php?mod=sievas&controlador=cronograma&accion=get_etapa&id="+id;
                        var jqxhr = $.get(url);
                        jqxhr.done(function(data){
                            callback(data);
                        });
                    }
                },
                formatResult: function(d){
                    return d.etapa;
                },
                formatSelection: function(d){
                    return d.etapa;
                },
                dropdownCssClass: "bigdrop",
                escapeMarkup: function (m) { return m; }
            });
            $('.medio',$html).select2({
                minimumInputText: 1,
                placeholder: "Seleccione...",
                ajax:{
                    url:'index.php?mod=sievas&controlador=cronograma&accion=get_medios',
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
                        var url = "index.php?mod=sievas&controlador=cronograma&accion=get_medio&id="+id;
                        var jqxhr = $.get(url);
                        jqxhr.done(function(data){
                            callback(data);
                        });
                    }
                },
                formatResult: function(d){
                    return d.medio;
                },
                formatSelection: function(d){
                    return d.medio;
                },
                dropdownCssClass: "bigdrop",
                escapeMarkup: function (m) { return m; }
            });
            $('.fecha', $html).datepicker();
            $(this).closest('.panel-body').find('.itinerarios-list').append($html);
          
                    
      
        });
        
        $('.add-responsable',$actividad).popover({            
            content : function(){  
                var html = $(this).parent().parent().parent().parent().parent().find('.responsable-form').clone().removeClass('hide').html();
                return html;                
            },
            placement : "top",
            html : true,
            title : "Agregar responsable"
        }).on('shown.bs.popover', function(){
            var self = this;
            $(this).parent().find('.seleccionar').on('click', function(e){
                e.preventDefault();
                var data = $(self).parent().find('.responsable_select').select2('data');
                var html = '';
                html += '<tr>';
                html += '<td class="responsable-selected" data-responsable-id="'+data.id+'">'+data.nombres+' '+data.primer_apellido+' '+data.segundo_apellido+'</td>';
                html += '<td><a href="#" class="remove-responsable"><i class="glyphicon glyphicon-minus-sign pull-right" style="color: #d2322d"></i></a></td>';
                html += '</tr>';
                
                $html = $.parseHTML(html);
                
                $('.remove-responsable', $html).on('click', function(e){
                    e.preventDefault();
                    $(this).parent().parent().remove();
                });
                $(self).parent().parent().parent().parent().find('tbody').append($html);
                $(self).popover('hide');
            });
             $(this).parent().find('.responsable_select').select2({
                minimumInputText: 1,
                placeholder: "Seleccione...",
                ajax:{
                    url:'index.php?mod=sievas&controlador=programas&accion=get_personas',
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
                        var url = "index.php?mod=sievas&controlador=programas&accion=get_persona&id="+id;
                        var jqxhr = $.get(url);
                        jqxhr.done(function(data){
                            callback(data[0]);
                        });
                    }
                },
                formatResult: function(d){
                    return d.nombres+' '+(d.primer_apellido == null ? '' : d.primer_apellido)+' '+(d.segundo_apellido == null ? '' : d.segundo_apellido);
                },
                formatSelection: function(d){
                    return d.nombres+' '+(d.primer_apellido == null ? '' : d.primer_apellido)+' '+(d.segundo_apellido == null ? '' : d.segundo_apellido);
                },
                dropdownCssClass: "bigdrop",
                escapeMarkup: function (m) { return m; }
            });
        });
        
        
        
        
        $('.add-invitado',$actividad).popover({            
            content : function(){  
                var html = $(this).parent().parent().parent().parent().parent().find('.invitado-form').clone().removeClass('hide').html();
                return html;                
            },
            placement : "top",
            html : true,
            title : "Invitar"
        }).on('shown.bs.popover', function(){
            var self = this;
            $(this).parent().find('.agregar-invitado').on('click', function(e){
                e.preventDefault();
                var invitado_nombre = $(self).parent().find('.invitado_nombre').val();
                var invitado_email = $(self).parent().find('.invitado_email').val();
                var html = '';
                html += '<tr class="invitados-item">';
                html += '<td>'+invitado_nombre+'</td>';
                html += '<td>'+invitado_email+'</td>';
                html += '<td><a href="#" class="remove-invitado"><i class="glyphicon glyphicon-minus-sign pull-right" style="color: #d2322d"></i></a></td>';
                html += '</tr>';
                
                $html = $.parseHTML(html);
                
                $('.remove-invitado', $html).on('click', function(e){
                    e.preventDefault();
                    $(this).parent().parent().remove();
                });
                $(self).parent().parent().parent().parent().find('tbody').append($html);
                $(self).popover('hide');
            });
             
        });
        
        
        $('.fecha', $actividad).datepicker();
        $('.actividad_gen', $actividad).select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=cronograma&accion=get_actividades',
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
                var url = "index.php?mod=sievas&controlador=cronograma&accion=get_actividad&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.actividad;
        },
        formatSelection: function(d){
            return d.actividad;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    }).on('change', function(){
        var data = $(this).select2('data');
        $(this).parent().parent().parent().find('.anotaciones').trigger('reset.summernote_helper');  
        $(this).parent().parent().parent().find('.medio').select2('val', '');
        $(this).parent().parent().parent().find('.etapa').select2('val', '');
        console.log(encodeURIComponent(data.anotaciones));
        $(this).parent().parent().parent().find('.anotaciones').data('content',encodeURIComponent(data.anotaciones)).trigger('render.summernote_helper');        
        $(this).parent().parent().parent().find('.medio').select2('val', data.cod_medio);
        $(this).parent().parent().parent().find('.etapa').select2('val', data.cod_etapa);
    });
    $('.medio',$actividad).select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=cronograma&accion=get_medios',
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
                var url = "index.php?mod=sievas&controlador=cronograma&accion=get_medio&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.medio;
        },
        formatSelection: function(d){
            return d.medio;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    $('.etapa',$actividad).select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=cronograma&accion=get_etapas',
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
                var url = "index.php?mod=sievas&controlador=cronograma&accion=get_etapa&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.etapa;
        },
        formatSelection: function(d){
            return d.etapa;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
        $('.add-more').before($actividad);
    }); 
    
    $('#guardar-form').on('click', cronograma.guardar);
    $(document).on('click', '.eliminar-actividad', cronograma.eliminar);
    cronograma.cargar();
}

cronograma.guardar = function(e){
    e.preventDefault();
    var actividades = [];
    $.each($('.actividad'), function(index,value){
        var actividad = {};
        actividad.fecha_inicio = $(value).find('.fecha_inicio').val();
        actividad.fecha_fin = $(value).find('.fecha_fin').val();
        actividad.actividad_gen = $(value).find('.actividad_gen').select2('data').id;
        actividad.etapa = $(value).find('.etapa').select2('data').id;
        actividad.medio = $(value).find('.medio').select2('data').id;
        actividad.anotaciones = $(value).find('.anotaciones').data('content');
        actividad.responsables = $.map($(value).find('.responsable-selected'), function(val,idx){
            return $(val).data('responsable-id');
        });
        actividad.invitados = $.map($(value).find('.invitados-item'), function(v,i){
            return { nombre : $(v).find(':nth-child(1)').text(), email : $(v).find(':nth-child(2)').text() };
        });
        actividad.itinerario = $.map($(value).find('.itinerario-item'), function(v,i){
            console.log(($(v).find('.anotaciones').data('content')));
            return {
                actividad : $(v).find('.actividad_gen').select2('data').id,
                etapa : $(v).find('.etapa').select2('data').id,
                medio : $(v).find('.medio').select2('data').id,
                fecha_inicio : $(v).find('.fecha_inicio').val(),
                fecha_fin : $(v).find('.fecha_fin').val(),                
//                anotaciones : $(v).find('.anotaciones').data('content')
            }
        });
        actividades.push(actividad);
    });
    
    console.log(JSON.stringify(actividades).length);
    
    var jqxhr = $.post('index.php?mod=sievas&controlador=cronograma&accion=guardar_cronograma', {
        actividades : JSON.stringify(actividades)
    });
    
    jqxhr.done(function(data){
        bootbox.alert(data.mensaje);

    });
        
    jqxhr.fail(function(err){
       if(err.responseText != ''){
           bootbox.alert(err.responseText);
       } 
    });
}

cronograma.guardar_actividad_comite = function(e){
    e.preventDefault();
    var actividades = [];
    $.each($('.actividad'), function(index,value){
        console.log($(value).find('.etapa').select2('data'));
        var actividad = {};
        actividad.fecha_inicio = $(value).find('.fecha_inicio').val();
        actividad.fecha_fin = $(value).find('.fecha_fin').val();
        actividad.actividad_gen = $(value).find('.actividad_gen').select2('data').id;
        actividad.nom_actividad_gen = $(value).find('.actividad_gen').select2('data').actividad;
        actividad.etapa = $(value).find('.etapa').select2('data').id;
        actividad.nom_etapa = $(value).find('.etapa').select2('data').etapa;
        actividad.medio = $(value).find('.medio').select2('data').id;
        actividad.nom_medio = $(value).find('.medio').select2('data').medio;
        actividad.anotaciones = $(value).find('.anotaciones').data('content');
//        actividad.responsables = $.map($(value).find('.responsable-selected'), function(val,idx){
//            return $(val).data('responsable-id');
//        });
//        actividad.invitados = $.map($(value).find('.invitados-item'), function(v,i){
//            return { nombre : $(v).find(':nth-child(1)').text(), email : $(v).find(':nth-child(2)').text() };
//        });
//        actividad.itinerario = $.map($(value).find('.itinerario-item'), function(v,i){
//            console.log(($(v).find('.anotaciones').data('content')));
//            return {
//                actividad : $(v).find('.actividad_gen').select2('data').id,
//                etapa : $(v).find('.etapa').select2('data').id,
//                medio : $(v).find('.medio').select2('data').id,
//                fecha_inicio : $(v).find('.fecha_inicio').val(),
//                fecha_fin : $(v).find('.fecha_fin').val(),                
////                anotaciones : $(v).find('.anotaciones').data('content')
//            }
//        });
        actividades.push(actividad);
    });
    
    console.log(JSON.stringify(actividades).length);
    
    var jqxhr = $.post('index.php?mod=sievas&controlador=cronograma&accion=guardar_cronograma_comite', {
        actividades : JSON.stringify(actividades)
    });
    
    jqxhr.done(function(data){
        var newrow = $('.cronograma_row_tpl tr:first').clone();
        newrow.find('td:nth-child(1)').html($('#cronograma tr').length+'. '+actividades[0].nom_actividad_gen);
        newrow.find('td:nth-child(2)').html(actividades[0].fecha_inicio);
        newrow.find('td:nth-child(3)').html(actividades[0].fecha_fin);
        newrow.find('td:nth-child(4)').html(actividades[0].nom_medio);
        newrow.find('td:nth-child(5)').html(decodeURIComponent(actividades[0].anotaciones));
        newrow.find('.eliminar_actividad').data('id', data.id);
        $('#cronograma').append(newrow);
        

    });
        
    jqxhr.fail(function(err){
       if(err.responseText != ''){
           bootbox.alert(err.responseText);
       } 
    });
}


cronograma.cargar = function(){
    var jqxhr = $.get('index.php?mod=sievas&controlador=cronograma&accion=get_cronograma_evaluacion');
    
    jqxhr.done(function(data){
        $.each(data, function(index,value){
            $('.add-more').trigger('click');
            var actividad_view = $('.actividad:last');
            actividad_view.find('.fecha_inicio').val(value.fecha_inicia);
            actividad_view.find('.fecha_fin').val(value.fecha_fin);
            actividad_view.find('.actividad_gen').select2('val',value.cod_actividad).triggerHandler('change');
            actividad_view.find('.etapa').select2('val',value.cod_etapa).triggerHandler('change');
            actividad_view.find('.medio').select2('val',value.cod_medio).triggerHandler('change');
            actividad_view.find('.anotaciones').data('content',value.anotaciones).trigger('render.summernote_helper');
            actividad_view.find('.eliminar-actividad').data('id',value.id);
            
            $.each(value.invitados, function(idx_invitado, invitado){
                var html = '';
                html += '<tr class="invitados-item">';
                html += '<td>'+invitado.nombres+'</td>';
                html += '<td>'+invitado.email+'</td>';
                html += '<td><a href="#" class="remove-invitado"><i class="glyphicon glyphicon-minus-sign pull-right" style="color: #d2322d"></i></a></td>';
                html += '</tr>';

                $html = $.parseHTML(html);

                $('.remove-invitado', $html).on('click', function(e){
                    e.preventDefault();
                    $(this).parent().parent().remove();
                });
                $(actividad_view).find('.invitados-table tbody').append($html);
            });            
            
            $.each(value.responsables, function(idx_responsable, responsable){
                var html = '';
                html += '<tr>';
                html += '<td class="responsable-selected" data-responsable-id="'+responsable.id+'">'+responsable.nombres+' '+(responsable.primer_apellido == null ? '' : responsable.primer_apellido)+' '+' '+(responsable.segundo_apellido == null ? '' : responsable.segundo_apellido)+'</td>';
                html += '<td><a href="#" class="remove-responsable"><i class="glyphicon glyphicon-minus-sign pull-right" style="color: #d2322d"></i></a></td>';
                html += '</tr>';

                $html = $.parseHTML(html);

                $('.remove-responsable', $html).on('click', function(e){
                    e.preventDefault();
                    $(this).parent().parent().remove();
                });
                $(actividad_view).find('.responsables-table tbody').append($html);
            });            
            
            $.each(value.itinerario, function(idx_itinerario, itinerario){
                var html = '';
                html += '<div class="itinerario-item">';
                html += '<div class="form-group col-sm-4" >';
                html += '<label class="control-label">Punto de la agenda</label>';
                html += '<input type="hidden" class="form-control actividad_gen"/>';
                html += '</div>';
                html += '<div class="form-group col-sm-4">';
                html += '<label class="control-label">Etapa</label>';
                html += '<input type="hidden" class="form-control etapa" />';
                html += '</div>';
                html += '<div class="form-group col-sm-4">';
                html += '<label class="control-label">Medio</label>';
                html += '<input type="hidden" class="form-control medio" />';
                html += '</div>';
                html += '<div class="form-group col-sm-2">';
                html += '<label class="control-label">Fecha inicia</label>';
                html += '<div class="input-group">';
                html += '<input type="text" class="form-control fecha fecha_inicio" data-date-format="yyyy-mm-dd" value="'+itinerario.fecha_inicia+'">';
                html += '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
                html += '</div>';
                html += '</div>';
                html += '<div class="form-group col-sm-2">';
                html += '<label class="control-label">Fecha fin</label>';
                html += '<div class="input-group">';
                html += '<input type="text" class="form-control fecha fecha_fin" data-date-format="yyyy-mm-dd"  value="'+itinerario.fecha_fin+'">';
                html += '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
                html += '</div>';
                html += '</div>';
                html += '<div class="form-group col-sm-8">';
                html += '<label class="control-label">Anotaciones</label>';
                html += '<div class="anotaciones summernote_modal" data-title="Anotaciones" style="overflow:auto; min-height: 60px" data-content="'+itinerario.anotaciones+'">';
                html += '<input type="text" class="form-control">';
                html += '</div>';
                html += '</div>';
                html += '</div>';

                $html = $.parseHTML(html);
                        

                
                $('.etapa',$html).select2({
                    minimumInputText: 1,
                    placeholder: "Seleccione...",
                    ajax:{
                        url:'index.php?mod=sievas&controlador=cronograma&accion=get_etapas',
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
                            var url = "index.php?mod=sievas&controlador=cronograma&accion=get_etapa&id="+id;
                            var jqxhr = $.get(url);
                            jqxhr.done(function(data){
                                callback(data);
                            });
                        }
                    },
                    formatResult: function(d){
                        return d.etapa;
                    },
                    formatSelection: function(d){
                        return d.etapa;
                    },
                    dropdownCssClass: "bigdrop",
                    escapeMarkup: function (m) { return m; }
                }).select2('val',itinerario.cod_etapa);
                $('.medio',$html).select2({
                    minimumInputText: 1,
                    placeholder: "Seleccione...",
                    ajax:{
                        url:'index.php?mod=sievas&controlador=cronograma&accion=get_medios',
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
                            var url = "index.php?mod=sievas&controlador=cronograma&accion=get_medio&id="+id;
                            var jqxhr = $.get(url);
                            jqxhr.done(function(data){
                                callback(data);
                            });
                        }
                    },
                    formatResult: function(d){
                        return d.medio;
                    },
                    formatSelection: function(d){
                        return d.medio;
                    },
                    dropdownCssClass: "bigdrop",
                    escapeMarkup: function (m) { return m; }
                }).select2('val',itinerario.cod_medio);
                $('.fecha', $html).datepicker();

                $('.actividad_gen', $html).select2({
                    minimumInputText: 1,
                    placeholder: "Seleccione...",
                    ajax:{
                        url:'index.php?mod=sievas&controlador=cronograma&accion=get_actividades',
                        dataType: 'json',
                        data: function(term, page){
                            return {
                                q: term,
                                page_limit: 10,
                                agenda : 1
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
                            var url = "index.php?mod=sievas&controlador=cronograma&accion=get_actividad&id="+id;
                            var jqxhr = $.get(url);
                            jqxhr.done(function(data){
                                callback(data);
                            });
                        }
                    },
                    formatResult: function(d){
                        return d.actividad;
                    },
                    formatSelection: function(d){
                        return d.actividad;
                    },
                    dropdownCssClass: "bigdrop",
                    escapeMarkup: function (m) { return m; }
                }).select2('val',itinerario.cod_actividad);
//                        .on('change', function(){
//                    console.log('itinerario');
//                    var data = $(this).select2('data');
//                    console.log(data);
//                    $(this).closest('.itinerario-item').find('.anotaciones').trigger('reset.summernote_helper');  
//                    $(this).closest('.itinerario-item').find('.medio').select2('val', '');
//                    $(this).closest('.itinerario-item').find('.etapa').select2('val', '');
//    //                console.log(encodeURIComponent(data.anotaciones));
//                    $(this).closest('.itinerario-item').find('.anotaciones').data('content', data.anotaciones != null ? encodeURIComponent(data.anotaciones):'').trigger('render.summernote_helper');        
//                    $(this).closest('.itinerario-item').find('.medio').select2('val', data.cod_medio);
//                    $(this).closest('.itinerario-item').find('.etapa').select2('val', data.cod_etapa);
//                });

                
//                $(this).closest('.panel-body').find('.itinerarios-list').append($html);
                $(actividad_view).find('.itinerarios-list').append($html);
            });       
            $('.itinerario-item .anotaciones').trigger('render.summernote_helper');
        });
    });
}

cronograma.eliminar = function(){
    var self = this;
    bootbox.dialog({
        message: "¿Está seguro que desea eliminar la actividad?",
        title: "Eliminar actividad",
        buttons: {
          success: {
            label: "Si",
            className: "btn-success",
            callback: function() {
                var id = $(self).data('id');
                var jqxhr = $.post('index.php?mod=sievas&controlador=cronograma&accion=borrar_actividad', {
                    id : id
                });

                jqxhr.done(function(data){
                    $('.actividad').remove();
                    cronograma.cargar();
                });
            }
          },
          danger: {
            label: "No",
            className: "btn-danger"           
          }         
        }
      });
    
}