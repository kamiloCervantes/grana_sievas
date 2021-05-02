var avances = {};

avances.initvalidaravance = function(){
    $(document).on('click', '.editar_revision', function(e){
        e.preventDefault();
        var self = this;
        var id = $(this).data('id');
        var form = $('.add_revision_tpl').clone();
        form.removeClass('hide');
        form.removeClass('add_revision_tpl');
        form.addClass("form_edit_revision");
        
       var jqxhr = $.get('index.php?mod=sievas&controlador=avances&accion=get_revision_avance', {
            id : id
        });
        
        jqxhr.done(function(data){
            form.find('textarea').val(data.comentarios);
            form.find('input[type=checkbox]').prop('checked', data.validez > 0);
            
        bootbox.dialog({
            message: form,
            title: "Editar revisión",
            buttons: {
              success: {
                label: "Aceptar",
                className: "btn-success",
                callback: function(e) {
                    var validez = form.find('.validar').prop('checked');
                    var comentario = form.find('.comentario').val();
                    var cod_lineamiento = form.find('.cod_lineamiento').val();
                    var cod_momento_evaluacion = form.find('.cod_momento_evaluacion').val();
//                    var cod_gradacion = form.find('.cod_gradacion').val();
//                    var fortalezas = $('#fortalezas').html();
//                    var debilidades = $('#debilidades').html();
//                    var plan_mejoramiento = $('#plan_mejoramiento').html();
//                    var anexos = $('#anexos').html();
                    
                    var valid = true;

                    
                    if(comentario == ''){
                        valid = false;
                    }
                    
                    if(valid){
                       var jqxhr = $.post('index.php?mod=sievas&controlador=avances&accion=guardar_revision_avance', {
                           validez : validez,
                           comentario : comentario,
                           cod_lineamiento : cod_lineamiento,
                           cod_momento_evaluacion : cod_momento_evaluacion,
                           revision_id : id
//                           cod_gradacion : cod_gradacion,
//                           fortalezas : fortalezas,
//                           debilidades : debilidades,
//                           plan_mejoramiento : plan_mejoramiento,
//                           anexos : anexos
                       }) ;
                       
                       jqxhr.done(function(data){
                           var tr = $(self).parent().parent();
                           tr.find('td:nth-child(3)').html(data.comentarios);
                           tr.find('td:nth-child(4)').html(data.validez > 0 ? 'SI':'NO');
                       });
                       
                       jqxhr.fail(function(){
                          e.preventDefault(); 
                       });
                    }
                    else{
                        e.preventDefault();
                    }
                }                    
                  
              },
              cancel: {
                label: "Cancelar",
                className: "btn-danger",
                callback: function() {


                }
              }

            }
          });
            
        });
        
       
    });
    
    
    $(document).on('click', '.eliminar_revision', function(e){
        e.preventDefault();
        var self = this;
        var id = $(this).data('id');
        bootbox.dialog({
                message: "¿Está seguro que desea eliminar la revisión seleccionada?",
                title: "Eliminar revisión",
                buttons: {
                  si: {
                    label: "Si",
                    className: "btn-default",
                    callback: function(e) {
                        
                        var jqxhr = $.get('index.php?mod=sievas&controlador=avances&accion=eliminar_revision_avance', {
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
    
    $(document).on('click', '.evaluacion_item', function(e){
        e.preventDefault();
        
        var id = $(this).data('id');
        var jqxhr = $.get('index.php?mod=sievas&controlador=avances&accion=get_revision_avance', {
            id : id
        });
        
        jqxhr.done(function(data){   
            var html = "";
            html += '<table class="table">';
            html += '<thead>';
            html += '<tr><td colspan="2">Datos actuales del lineamiento seleccionado</td></tr>';
            html += '</thead>';
            html += '<tr>';
            html += '<td>Fortalezas</td>';
            html += '<td>'+(data.fortalezas == null ? 'N/A' : data.fortalezas)+'</td>';
            html += '</tr>';
            html += '<tr>';
            html += '<td>Debilidades</td>';
            html += '<td>'+(data.debilidades == null ? 'N/A' : data.debilidades)+'</td>';
            html += '</tr>';
            html += '<tr>';
            html += '<td>Plan de mejoramiento</td>';
            html += '<td>'+(data.plan_mejoramiento == null ? 'N/A' : data.plan_mejoramiento)+'</td>';
            html += '</tr>';
            html += '<tr>';
            html += '<td>Calificación</td>';
            html += '<td>'+(data.calificacion == null ? 'N/A' : data.calificacion)+'</td>';
            html += '</tr>';
            html += '<tr>';
            html += '<td>Anexos</td>';
            html += '<td>'+(data.anexos == null ? 'N/A' : data.anexos)+'</td>';
            html += '</tr>';
            html += '</table>';



            bootbox.dialog({
                message: html,
                title: "Ver estado de evaluación",
                buttons: {
                  success: {
                    label: "Aceptar",
                    className: "btn-success",
                    callback: function(e) {

                    }                    

                  }
                }
              });

        });
        
        
        
    });
    
    
    $('#add_revision').on('click', function(e){
        e.preventDefault();
        var form = $('.add_revision_tpl').clone();
        form.removeClass('hide');
        form.removeClass('add_revision_tpl');
        form.addClass("form_revision");
        bootbox.dialog({
            message: form,
            title: "Agregar revisión",
            buttons: {
              success: {
                label: "Aceptar",
                className: "btn-success",
                callback: function(e) {
                    var validez = form.find('.validar').prop('checked');
                    var comentario = form.find('.comentario').val();
                    var cod_lineamiento = form.find('.cod_lineamiento').val();
                    var cod_momento_evaluacion = form.find('.cod_momento_evaluacion').val();
                    var cod_gradacion = form.find('.cod_gradacion').val();
                    var tipo_revision = form.find('.tipo_revision').val();
                    var fortalezas = $('#fortalezas').html();
                    var debilidades = $('#debilidades').html();
                    var plan_mejoramiento = $('#plan_mejoramiento').html();
                    var anexos = $('#anexos').html();
                    
                    var valid = true;
                    
                    console.log(cod_gradacion);
                    
                    if(comentario == ''){
                        valid = false;
                    }
                    
                    if(valid){
                       var jqxhr = $.post('index.php?mod=sievas&controlador=avances&accion=guardar_revision_avance', {
                           validez : validez,
                           comentario : comentario,
                           cod_lineamiento : cod_lineamiento,
                           cod_momento_evaluacion : cod_momento_evaluacion,
                           cod_gradacion : cod_gradacion,
                           fortalezas : fortalezas,
                           debilidades : debilidades,
                           plan_mejoramiento : plan_mejoramiento,
                           anexos : anexos,
                           tipo_revision : tipo_revision
                       }) ;
                       
                       jqxhr.done(function(data){
                           var html = '<tr>';
                           html += '<td>'+(parseInt(data.indice)+1)+'</td>';
                           html += '<td>'+data.fecha+'</td>';
                           html += '<td>'+data.comentarios+'</td>';
                           html += '<td>'+(data.validez > 0 ? 'SI':'NO')+'</td>';
                           html += '<td>'+data.revisor+'</td>';
                           html += '<td>';
                           html +='<a href="#" class="btn btn-xs btn-default evaluacion_item" data-id="'+data.revision_id+'" title="Ver evaluacion de ítem"><i class="glyphicon glyphicon-search"></i></a>';
                           html +='<a href="#" class="btn btn-xs btn-default editar_revision" data-id="'+data.revision_id+'" title="Editar revisión"><i class="glyphicon glyphicon-edit"></i></a>';
                           html +='<a href="#" class="btn btn-xs btn-default eliminar_revision" data-id="'+data.revision_id+'" title="Eliminar revisión"><i class="glyphicon glyphicon-trash"></i></a>';
                           html +='</td>';
                           html += '</tr>';
                           $('#revisiones tr:first').after(html);
                       });
                       
                       jqxhr.fail(function(){
                          e.preventDefault(); 
                       });
                    }
                    else{
                        e.preventDefault();
                    }
                }                    
                  
              },
              cancel: {
                label: "Cancelar",
                className: "btn-danger",
                callback: function() {


                }
              }

            }
          });
    });
}

avances.initmonitor = function(){
    $('#pais').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=gen_paises&accion=get_paises',
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
                var url = "index.php?mod=sievas&controlador=gen_paises&accion=get_pais&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nom_pais;
        },
        formatSelection: function(d){
            return d.nom_pais;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    }).on('change', function(){
        $('#institucion').triggerHandler('change');
        console.log($('#pais').select2('val'));
    });
    $('#institucion').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=programas&accion=get_instituciones',
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    page_limit: 10,
                    pais: $('#pais').select2('val')
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
                var url = "index.php?mod=sievas&controlador=programas&accion=get_institucion&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nom_institucion;
        },
        formatSelection: function(d){
            return d.nom_institucion;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    $('#programa').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=usuarios&accion=get_programas',
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    page_limit: 10,
                    institucion: $('#institucion').select2('val')
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
                var url = "index.php?mod=sievas&controlador=usuarios&accion=get_programa&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.programa;
        },
        formatSelection: function(d){
            return d.programa;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    $('#evaluacion').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluaciones',
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    page_limit: 10,
                    programa: $('#programa').select2('val')
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
                var url = "index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluacion&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.etiqueta;
        },
        formatSelection: function(d){
            return d.etiqueta;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $("#reporte_avance").on('submit', function(e){
        e.preventDefault();
        if($('#evaluacion').select2('val') > 0){
            window.location = 'index.php?mod=sievas&controlador=avances&accion=avances_evaluacion&evaluacion='+$('#evaluacion').select2('val');
        }
        else{
            console.log("no hay evaluacion");
        }
        
    });
}

avances.initvalidacion = function(){
    $('#pais').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=gen_paises&accion=get_paises',
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
                var url = "index.php?mod=sievas&controlador=gen_paises&accion=get_pais&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nom_pais;
        },
        formatSelection: function(d){
            return d.nom_pais;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    }).on('change', function(){
        $('#institucion').triggerHandler('change');
        console.log($('#pais').select2('val'));
    });
    $('#institucion').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=programas&accion=get_instituciones',
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    page_limit: 10,
                    pais: $('#pais').select2('val')
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
                var url = "index.php?mod=sievas&controlador=programas&accion=get_institucion&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nom_institucion;
        },
        formatSelection: function(d){
            return d.nom_institucion;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    $('#programa').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=usuarios&accion=get_programas',
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    page_limit: 10,
                    institucion: $('#institucion').select2('val')
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
                var url = "index.php?mod=sievas&controlador=usuarios&accion=get_programa&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.programa;
        },
        formatSelection: function(d){
            return d.programa;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    $('#evaluacion').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluaciones',
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    page_limit: 10,
                    programa: $('#programa').select2('val')
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
                var url = "index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluacion&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.etiqueta;
        },
        formatSelection: function(d){
            return d.etiqueta;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $("#reporte_avance").on('submit', function(e){
        e.preventDefault();
        if($('#evaluacion').select2('val') > 0){
            window.location = 'index.php?mod=sievas&controlador=avances&accion=resultados_evaluacion&evaluacion='+$('#evaluacion').select2('val');
        }
        else{
            console.log("no hay evaluacion");
        }
        
    });
}

avances.graficar_grafindicametro_general = function(data){
     var d = data;
     var radars = [];
    $.each($('.grafindicametro-general'), function(idx,val){
        var tmp_id = 'radar_gen_'+idx;
        
        $(val).prop('id', tmp_id);
        RGraph.reset(document.getElementById(tmp_id));
        console.log(d.calificaciones_generales);
         $(val).data('datos', typeof d.calificaciones_generales !== 'undefined' && d.calificaciones_generales.length > 0 ? d.calificaciones_generales.join(',') : 0);
        console.log($(val).data('datos'));
        if($(val).data('datos') == '0'){
            $(val).data('datos', '0,0,0,0,0,0,0,0,0,0');
        }
        var dr = $(val).data('datos').split(',');
        var tmp2 = [];
        $.each(dr, function(index,value){
            tmp2[index] = Math.round(value,1);
        });

        var r = new RGraph.Radar(tmp_id, [10,10,10,10,10,10,10,10,10,10], tmp2 ,[3,3,3,3,3,3,3,3,3,3],[6,6,6,6,6,6,6,6,6,6])
                .set('labels', ['1','2','3','4','5','6','7','8','9','10'])
                .set('colors', ['rgba(133,235,106,0.5)','rgba(70,111,213,0.5)', 'rgba(200,0,0,0.0)','rgba(55,0,0,0.0)'])
                .set('axes.color', 'rgba(0,0,0,0)')
                .set('text.size', '10')
                .set('labels.axes', 'N')
                .set('labels.axes.boxed', false)
                .set('labels.axes.boxed.zero', false)
                .set('labels.offset', 20)
                .set('accumulative', false)
                .set('background.circles.poly', true)
                .set('strokestyle', ['#2dd700','rgba(0,0,0,0)', 'red', 'yellow'])
                .set('radius', 150)
                .set({
                    contextmenu: [
                        ['Get PNG', RGraph.showPNG],
                        null,
                        ['Cancel', function () {}]
                    ]
                })
                .draw();
        radars.push(r);
    });    
}

avances.graficar_grafindicametros_consolidados = function(data){
    
    var radars = [];
    var d = data;
    
    $.each($('.grafindicametro-rubros-interna'), function(idx,val){
        RGraph.reset($(val)[0]);

        if($(val).hasClass('interna')){
            var tmp_id = 'radar_int_'+idx;
            $(val).data('datos', d.calificaciones_interna[idx]);
        }
        else{
            var tmp_id = 'radar_ext_'+idx;
            $(val).data('datos', d.calificaciones_externa[idx]);
        }
        
        $(val).prop('id', tmp_id);
        

        if($(val).data('datos') == '0'){
            $(val).data('datos', '0,0,0,0,0,0,0,0,0,0');
        }
        var dr = $(val).data('datos').split(',');
        var tmp2 = [];
        $.each(dr, function(index,value){
            tmp2[index] = Math.round(value);
        });

        var r = new RGraph.Radar(tmp_id, [10,10,10,10,10,10,10,10,10,10], tmp2 ,[3,3,3,3,3,3,3,3,3,3],[6,6,6,6,6,6,6,6,6,6])
                .set('labels', ['1','2','3','4','5','6','7','8','9','10'])
                .set('colors', ['rgba(133,235,106,0.5)','rgba(70,111,213,0.5)', 'rgba(200,0,0,0.0)','rgba(55,0,0,0.0)'])
                .set('axes.color', 'rgba(0,0,0,0)')
                .set('text.size', '10')
                .set('labels.axes', 'N')
                .set('labels.axes.boxed', false)
                .set('labels.axes.boxed.zero', false)
                .set('labels.offset', 20)
                .set('accumulative', false)
                .set('background.circles.poly', true)
                .set('strokestyle', ['#2dd700','rgba(0,0,0,0)', 'red', 'yellow'])
                .set('radius', 150)
                .set({
                    contextmenu: [
                        ['Get PNG', RGraph.showPNG],
                        null,
                        ['Cancel', function () {}]
                    ]
                })
                .draw();
        radars.push(r);
    });    
    $.each($('.grafindicametro-rubros-externa'), function(idx,val){
        RGraph.reset($(val)[0]);

        if($(val).hasClass('interna')){
            var tmp_id = 'radar_int_'+idx;
            $(val).data('datos', d.calificaciones_interna[idx]);
        }
        else{
            var tmp_id = 'radar_ext_'+idx;
            $(val).data('datos', d.calificaciones_externa[idx]);
        }
        
        $(val).prop('id', tmp_id);
        

        if($(val).data('datos') == '0'){
            $(val).data('datos', '0,0,0,0,0,0,0,0,0,0');
        }
        var dr = $(val).data('datos').split(',');
        var tmp2 = [];
        $.each(dr, function(index,value){
            tmp2[index] = Math.round(value);
        });

        var r = new RGraph.Radar(tmp_id, [10,10,10,10,10,10,10,10,10,10], tmp2 ,[3,3,3,3,3,3,3,3,3,3],[6,6,6,6,6,6,6,6,6,6])
                .set('labels', ['1','2','3','4','5','6','7','8','9','10'])
                .set('colors', ['rgba(133,235,106,0.5)','rgba(70,111,213,0.5)', 'rgba(200,0,0,0.0)','rgba(55,0,0,0.0)'])
                .set('axes.color', 'rgba(0,0,0,0)')
                .set('text.size', '10')
                .set('labels.axes', 'N')
                .set('labels.axes.boxed', false)
                .set('labels.axes.boxed.zero', false)
                .set('labels.offset', 20)
                .set('accumulative', false)
                .set('background.circles.poly', true)
                .set('strokestyle', ['#2dd700','rgba(0,0,0,0)', 'red', 'yellow'])
                .set('radius', 150)
                .set({
                    contextmenu: [
                        ['Get PNG', RGraph.showPNG],
                        null,
                        ['Cancel', function () {}]
                    ]
                })
                .draw();
        radars.push(r);
    });    
    $.each($('.grafindicametro-general-interna'), function(idx,val){
        RGraph.reset($(val)[0]);

        if($(val).hasClass('interna')){
            var tmp_id = 'radar_gen_int_'+idx;
            $(val).data('datos', d.calificaciones_generales[1]);
        }
        else{
            var tmp_id = 'radar_gen_ext_'+idx;
            $(val).data('datos', d.calificaciones_generales[2]);
        }
        
        $(val).prop('id', tmp_id);
        

        if($(val).data('datos') == '0'){
            $(val).data('datos', '0,0,0,0,0,0,0,0,0,0');
        }
        var dr = $(val).data('datos').split(',');
        var tmp2 = [];
        $.each(dr, function(index,value){
            tmp2[index] = Math.round(value);
        });

        var r = new RGraph.Radar(tmp_id, [10,10,10,10,10,10,10,10,10,10], tmp2 ,[3,3,3,3,3,3,3,3,3,3],[6,6,6,6,6,6,6,6,6,6])
                .set('labels', ['1','2','3','4','5','6','7','8','9','10'])
                .set('colors', ['rgba(133,235,106,0.5)','rgba(70,111,213,0.5)', 'rgba(200,0,0,0.0)','rgba(55,0,0,0.0)'])
                .set('axes.color', 'rgba(0,0,0,0)')
                .set('text.size', '10')
                .set('labels.axes', 'N')
                .set('labels.axes.boxed', false)
                .set('labels.axes.boxed.zero', false)
                .set('labels.offset', 20)
                .set('accumulative', false)
                .set('background.circles.poly', true)
                .set('strokestyle', ['#2dd700','rgba(0,0,0,0)', 'red', 'yellow'])
                .set('radius', 150)
                .set({
                    contextmenu: [
                        ['Get PNG', RGraph.showPNG],
                        null,
                        ['Cancel', function () {}]
                    ]
                })
                .draw();
        radars.push(r);
    });    
    $.each($('.grafindicametro-general-externa'), function(idx,val){
        RGraph.reset($(val)[0]);

        if($(val).hasClass('interna')){
            var tmp_id = 'radar_gen_int_'+idx;
            $(val).data('datos', d.calificaciones_generales[1]);
        }
        else{
            var tmp_id = 'radar_gen_ext_'+idx;
            $(val).data('datos', d.calificaciones_generales[2]);
        }
        
        $(val).prop('id', tmp_id);
        

        if($(val).data('datos') == '0'){
            $(val).data('datos', '0,0,0,0,0,0,0,0,0,0');
        }
        var dr = $(val).data('datos').split(',');
        var tmp2 = [];
        $.each(dr, function(index,value){
            tmp2[index] = Math.round(value);
        });

        var r = new RGraph.Radar(tmp_id, [10,10,10,10,10,10,10,10,10,10], tmp2 ,[3,3,3,3,3,3,3,3,3,3],[6,6,6,6,6,6,6,6,6,6])
                .set('labels', ['1','2','3','4','5','6','7','8','9','10'])
                .set('colors', ['rgba(133,235,106,0.5)','rgba(70,111,213,0.5)', 'rgba(200,0,0,0.0)','rgba(55,0,0,0.0)'])
                .set('axes.color', 'rgba(0,0,0,0)')
                .set('text.size', '10')
                .set('labels.axes', 'N')
                .set('labels.axes.boxed', false)
                .set('labels.axes.boxed.zero', false)
                .set('labels.offset', 20)
                .set('accumulative', false)
                .set('background.circles.poly', true)
                .set('strokestyle', ['#2dd700','rgba(0,0,0,0)', 'red', 'yellow'])
                .set('radius', 150)
                .set({
                    contextmenu: [
                        ['Get PNG', RGraph.showPNG],
                        null,
                        ['Cancel', function () {}]
                    ]
                })
                .draw();
        radars.push(r);
    });    
//    avances.graficar_grafindicametro_general(data);
}

avances.initcalificacionesevaluaciongraficasconsolidados = function(){
    RGraph.AJAX.getJSON('index.php?mod=sievas&controlador=avances&accion=get_data_graficas_consolidados', avances.graficar_grafindicametros_consolidados);
    setInterval(function(){
        RGraph.AJAX.getJSON('index.php?mod=sievas&controlador=avances&accion=get_data_graficas_consolidados', avances.graficar_grafindicametros_consolidados);
    }, 2000);
}

avances.initcalificacionesevaluaciongraficas = function(){
    var momento = $('#tipo_momento').val();
    RGraph.AJAX.getJSON('index.php?mod=sievas&controlador=avances&accion=get_data_grafindicametro&momento='+momento, avances.graficar_grafindicametros);
    
    setInterval(function(){
        var momento = $('#tipo_momento').val();
        RGraph.AJAX.getJSON('index.php?mod=sievas&controlador=avances&accion=get_data_grafindicametro&momento='+momento, avances.graficar_grafindicametros);
    }, 2000);   
 
    
    $('#tipo_momento').on('change', function(){
        window.location = "index.php?mod=sievas&controlador=avances&accion=calificaciones_evaluacion_graficas&momento="+$(this).val();
    });
    
    var momento = avances.loadPageVar('momento');
    if(typeof momento !== 'undefined'){
        $('#tipo_momento option[value='+momento+']').prop('selected', 'selected');
    }
    $('#rubro').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=avances&accion=get_rubros',
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
                var url = "index.php?mod=sievas&controlador=avances&accion=get_rubro&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nom_lineamiento;
        },
        formatSelection: function(d){
            return d.nom_lineamiento;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $('#rubro').on('change', function(){
        var jqxhr = $.get('index.php?mod=sievas&controlador=avances&accion=get_calificaciones_rubro', {
            rubro : $(this).val(),
            momento : avances.loadPageVar('momento')
        });
        
        jqxhr.done(function(data){
            if(typeof(data.calificaciones) == 'undefined' || data.calificaciones.length == 0){
                data.calificaciones = [0,0,0,0,0,0,0,0,0,0];
            }
            else{
                data.calificaciones = $.map(data.calificaciones, function(val,idx){               
                    return parseInt(val);                    
                });
//                data.calificaciones = [4,5,4,5,4,5,4,5,4,5];
//                data.calificaciones = [6,7,6,7,6,7,6,7,6,7];
//                data.calificaciones = [8,9,8,9,8,9,8,9,8,9];
//                data.calificaciones = [9,10,9,10,10,9,9,9,9,10];
            }
            
            RGraph.reset(document.getElementById('cvs'));
            
            radar = new RGraph.Radar('cvs', [10,10,10,10,10,10,10,10,10,10], data.calificaciones ,[3,3,3,3,3,3,3,3,3,3],[6,6,6,6,6,6,6,6,6,6])
                .set('labels', ['1','2','3','4','5','6','7','8','9','10'])
                .set('colors', ['rgba(133,235,106,0.5)','rgba(70,111,213,0.5)', 'rgba(200,0,0,0.0)','rgba(55,0,0,0.0)'])
                .set('axes.color', 'rgba(0,0,0,0)')
                .set('text.size', '10')
                .set('labels.axes', 'N')
                .set('labels.axes.boxed', false)
                .set('labels.axes.boxed.zero', false)
                .set('labels.offset', 20)
                .set('accumulative', false)
                .set('background.circles.poly', true)
                .set('strokestyle', ['#2dd700','rgba(0,0,0,0)', 'red', 'yellow'])
                .set('radius', 150)
                .set({
                    contextmenu: [
                        ['Get PNG', RGraph.showPNG],
                        null,
                        ['Cancel', function () {}]
                    ]
                })
                .draw();
            
            data.items = $.map(data.items, function(val,i){
                return '<p>'+val+'</p>';
            });           

            $('#items').html('<br/><label class="control-label">Ítems</label><br/>'+data.items.join(''));
            
           
        });
    });
    
    
    $.each($('[id^="bar_"]'), function(index,value){
        if(typeof $(this).data('datos') !== 'undefined'){
            var d = $(this).data('datos')
            if(typeof $(this).data('datos') == 'number'){
                d = $(this).data('datos').toString();
            }
            
            var data = d.split(',');
            data = $.map(data, function(val,index){
                   return parseInt(val);
            });

            var bar = new RGraph.Bar({
                   id: $(this).prop('id'),
                   data: data,
                   options: {
                       labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
                       gutter: {
                           left: 35
                       }
                   }
               }).draw();
        }
        
    });
    $.each($('[id^="pie_"]'), function(index,value){
        if(typeof $(this).data('datos') !== 'undefined'){
            var d = $(this).data('datos')
            if(typeof $(this).data('datos') == 'number'){
                d = $(this).data('datos').toString();
            }
            
            var data = d.split(',');
            data = $.map(data, function(val,index){
                   return parseInt(val);
            });
         var pie = new RGraph.Pie({
                    id: $(this).prop('id'),
                    data: data,
                    options: {
                        tooltips: ['1','2','3','4','5','6','7','8','9','10'],
                        labels: ['1','2','3','4','5','6','7','8','9','10']
                    }
                }).draw();
            }
    });
    
     
}

avances.graficar_grafindicametros = function(data){
    
    var radars = [];
    var d = data;
    
    $.each($('.grafindicametro-rubros'), function(idx,val){
        RGraph.reset($(val)[0]);
        var tmp_id = 'radar_'+idx;
        
        $(val).prop('id', tmp_id);
//        console.log(idx);
        $(val).data('datos', idx < 10 && typeof d.rubros[idx] !== 'undefined' && d.rubros[idx].length > 0 ? d.rubros[idx].join(',') : d.rubros[idx]);

        if($(val).data('datos') == '0'){
            $(val).data('datos', '0,0,0,0,0,0,0,0,0,0');
        }
        var dr = $(val).data('datos').split(',');
        var tmp2 = [];
        $.each(dr, function(index,value){
            tmp2[index] = Math.round(value);
        });
//        console.log(tmp2); 
        
//        console.log(data);
        var r = new RGraph.Radar(tmp_id, [10,10,10,10,10,10,10,10,10,10], tmp2 ,[3,3,3,3,3,3,3,3,3,3],[6,6,6,6,6,6,6,6,6,6])
                .set('labels', ['1','2','3','4','5','6','7','8','9','10'])
                .set('colors', ['rgba(133,235,106,0.5)','rgba(70,111,213,0.5)', 'rgba(200,0,0,0.0)','rgba(55,0,0,0.0)'])
                .set('axes.color', 'rgba(0,0,0,0)')
                .set('text.size', '10')
                .set('labels.axes', 'N')
                .set('labels.axes.boxed', false)
                .set('labels.axes.boxed.zero', false)
                .set('labels.offset', 20)
                .set('accumulative', false)
                .set('background.circles.poly', true)
                .set('strokestyle', ['#2dd700','rgba(0,0,0,0)', 'red', 'yellow'])
                .set('radius', 150)
                .set({
                    contextmenu: [
                        ['Get PNG', RGraph.showPNG],
                        null,
                        ['Cancel', function () {}]
                    ]
                })
                .draw();
        radars.push(r);
    });    
    
    $.each($('.promedio-rubro'), function(idx,val){
        $(val).html(d.calificaciones_generales[idx]);
    });
    avances.graficar_grafindicametro_general(data);
}

avances.initreporteevaluacion = function(){
    $('.grafindicametro-rubros').each(function(idx, el){
        el = $(el);
        //crear radar con data de el
        RGraph.reset(document.getElementById('cvs'));
        var datos = el.data('datos').split(',');
        if(datos.length > 5){
            radar = new RGraph.Radar('cvs', [10,10,10,10,10,10,10,10,10,10], datos ,[3,3,3,3,3,3,3,3,3,3],[6,6,6,6,6,6,6,6,6,6])
                .set('labels', ['1','2','3','4','5','6','7','8','9','10'])
                .set('colors', ['rgba(133,235,106,0.5)','rgba(70,111,213,0.5)', 'rgba(200,0,0,0.0)','rgba(55,0,0,0.0)'])
                .set('axes.color', 'rgba(0,0,0,0)')
                .set('text.size', '10')
                .set('labels.axes', 'N')
                .set('labels.axes.boxed', false)
                .set('labels.axes.boxed.zero', false)
                .set('labels.offset', 20)
                .set('accumulative', false)
                .set('background.circles.poly', true)
                .set('strokestyle', ['#2dd700','rgba(0,0,0,0)', 'red', 'yellow'])
                .set('radius', 150)
                .set({
                    contextmenu: [
                        ['Get PNG', RGraph.showPNG],
                        null,
                        ['Cancel', function () {}]
                    ]
                })
                .draw();
            //generar png
            el.attr('src', $('#cvs')[0].toDataURL());
        }
        else{
            el.replaceWith('<p>No hay suficientes datos para generar el grafindicametro</p>');
        }
    });

    console.log($('#cvs')[0].toDataURL());
    $('#cvs2').attr('src', $('#cvs')[0].toDataURL());

    $('.guardarword').on('click', function(e){
        e.preventDefault();
        $('.word_version').wordExport();
    });
    
    $('.imprimir').on('click', function(e){
        e.preventDefault();
        window.print(); 
    });
    
    $(document).on('click', '.visualizador', function(){
        var content = $(this).data('content');
        bootbox.dialog({
                title:"Ver datos",
                message:decodeURIComponent(content),
                buttons: {
                    cancel: {
                        label: "Aceptar",
                        className: "btn-primary"
                    },
                }
        }); 
    });
    
    var momento = avances.loadPageVar('momento');
    if(typeof momento !== 'undefined'){
        $('#tipo_momento option[value='+momento+']').prop('selected', 'selected');
    }
    
    $('#tipo_momento').on('change', function(){
       if($(this).data('evaluacion') > 0){
           window.location = "index.php?mod=sievas&controlador=avances&accion=resultados_evaluacion&evaluacion="+$(this).data('evaluacion')+"&momento="+$(this).val();
       }
       else{
           window.location = "index.php?mod=sievas&controlador=avances&accion=resultados_evaluacion&momento="+$(this).val();
       }
       
    });
}

avances.initreporteevaluacionred = function(){
    $(document).on('click', '.visualizador', function(){
        var content = $(this).data('content');
        bootbox.dialog({
                title:"Ver datos",
                message:decodeURIComponent(content),
                buttons: {
                    cancel: {
                        label: "Aceptar",
                        className: "btn-primary"
                    },
                }
        }); 
    });
    
    var momento = avances.loadPageVar('momento');
    if(typeof momento !== 'undefined'){
        $('#tipo_momento option[value='+momento+']').prop('selected', 'selected');
    }
    
    $('#tipo_momento').on('change', function(){
       window.location = "index.php?mod=sievas&controlador=avances&accion=resultados_evaluacion_red&momento="+$(this).val();
    });
    
    $('.anexos').on('click', function(e){
      e.preventDefault();
      var lineamiento = $(this).data('lineamiento');
      var evaluacion = $(this).data('evaluacion');
      var self = this;
      var jqxhr = $.get('index.php?mod=sievas&controlador=avances&accion=get_anexos_evaluacion', {
          lineamiento : lineamiento,
          evaluacion : evaluacion
      });
      
      jqxhr.done(function(data){
          var html = '';
          $.each(data, function(index,value){
              html += '<a href="'+value.ruta+'" target="_blank"><span class="label label-primary">';
              html += '<i class="glyphicon glyphicon-file"></i> '+value.nombre+'</span></a><br/>';
          });
          $(self).addClass('hide');
          $(self).parent().find('.anexos-list').html(html);
      });

    });
}

avances.cargardatosavance = function(){
    var jqxhr = $.get('', function(){
        
    });
    
    jqxhr.done(function(data){
        
    })
    
    
    $('.resultados td').each(function(i,v){
        var jqxhr = $.get('');
        
        jqxhr.done(function(data){
            var tipo = $(v).data('tipo');
            switch(tipo){
                case 1:
                    console.log(v);
                    break;
            }
        });
       
        
    });
}

avances.initavanceevaluacion = function(){
    avances.cargardatosavance();
    var momento = avances.loadPageVar('momento');
    if(typeof momento !== 'undefined'){
        $('#tipo_momento option[value='+momento+']').prop('selected', 'selected');
    }
    
    $('#tipo_momento').on('change', function(){
       if($(this).data('evaluacion') > 0){
           window.location = "index.php?mod=sievas&controlador=avances&accion=avances_evaluacion&evaluacion="+$(this).data('evaluacion')+"&momento="+$(this).val();
       }
       else{
           window.location = "index.php?mod=sievas&controlador=avances&accion=avances_evaluacion&momento="+$(this).val();
       }
       
    });
}

avances.initavancereevaluacion = function(){
    avances.cargardatosavance();
    var momento = avances.loadPageVar('momento');
    if(typeof momento !== 'undefined'){
        $('#tipo_momento option[value='+momento+']').prop('selected', 'selected');
    }
    
    $('#tipo_momento').on('change', function(){
       if($(this).data('evaluacion') > 0){
           window.location = "index.php?mod=sievas&controlador=avances&accion=avances_reevaluacion&evaluacion="+$(this).data('evaluacion')+"&momento="+$(this).val();
       }
       else{
           window.location = "index.php?mod=sievas&controlador=avances&accion=avances_reevaluacion&momento="+$(this).val();
       }
       
    });
}

avances.loadPageVar = function(sVar) {
  return decodeURI(window.location.search.replace(new RegExp("^(?:.*[&\\?]" + encodeURI(sVar).replace(/[\.\+\*]/g, "\\$&") + "(?:\\=([^&]*))?)?.*$", "i"), "$1"));
}
