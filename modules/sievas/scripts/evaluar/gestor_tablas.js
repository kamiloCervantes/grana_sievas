var gestor_tablas = {};
var socket = io('http://sievas.herokuapp.com');
gestor_tablas.init = function(){

   $('#agregar-data').on('click', function(e){
       console.log("click");
        e.preventDefault();
        var subtabla = $('#subtabla').val();
        var tipo = $(this).data('tipo');
        console.log(tipo);
        var form = null;
        switch(tipo){
            case 1:
                $('.tabla_datos').fadeOut();
                form = $('.tabla_estadistica_r1_tpl_form').clone();
                form.removeClass('hide');
                $('.add-form').hide();
                $('.controls').hide();
                $('.add-form').append(form);
                $('.add-form').fadeIn();
                break;
            case 2:
                console.log('Rubro 2');
                $('.tabla_datos').fadeOut();
                form = $('.tabla_estadistica_r2_tpl_form').clone();
                form.removeClass('hide');
                $('.add-form').hide();
                $('.controls').hide();
                $('.add-form').append(form);
                $('.add-form').fadeIn();
                break;
            case 3:
                console.log('Rubro 2');
                $('.tabla_datos').fadeOut();
                form = $('.tabla_estadistica_r3_tpl_form').clone();
                form.removeClass('hide');
                $('.add-form').hide();
                $('.controls').hide();
                $('.add-form').append(form);
                $('.add-form').fadeIn();
                break;
            case 4:
                console.log('Rubro 2');
                $('.tabla_datos').fadeOut();
                form = $('.tabla_estadistica_r4_tpl_form').clone();
                form.removeClass('hide');
                $('.add-form').hide();
                $('.controls').hide();
                $('.add-form').append(form);
                $('.add-form').fadeIn();
                break;
            case 5:
                console.log('Rubro 2');
                $('.tabla_datos').fadeOut();
                form = $('.tabla_estadistica_r5_tpl_form').clone();
                form.removeClass('hide');
                $('.add-form').hide();
                $('.controls').hide();
                $('.add-form').append(form);
                $('.add-form').fadeIn();
                break;
            case 6:
                console.log('Rubro 2');
                $('.tabla_datos').fadeOut();
                form = $('.tabla_estadistica_r6_tpl_form').clone();
                form.removeClass('hide');
                $('.add-form').hide();
                $('.controls').hide();
                $('.add-form').append(form);
                $('.add-form').fadeIn();
                break;
            case 7:
                console.log('Rubro 2');
                $('.tabla_datos').fadeOut();
                form = $('.tabla_estadistica_r7_tpl_form').clone();
                form.removeClass('hide');
                $('.add-form').hide();
                $('.controls').hide();
                $('.add-form').append(form);
                $('.add-form').fadeIn();
                break;
            case 8:
                console.log('Rubro 2');
                $('.tabla_datos').fadeOut();
                form = $('.tabla_estadistica_r8_tpl_form').clone();
                form.removeClass('hide');
                $('.add-form').hide();
                $('.controls').hide();
                $('.add-form').append(form);
                $('.add-form').fadeIn();
                break;
            case 9:
                console.log('Rubro 2');
                $('.tabla_datos').fadeOut();
                form = $('.tabla_estadistica_r9_tpl_form').clone();
                form.removeClass('hide');
                $('.add-form').hide();
                $('.controls').hide();
                $('.add-form').append(form);
                $('.add-form').fadeIn();
                break;
            case 10:
                console.log(subtabla);
                switch(parseInt(subtabla)){
                    case 1:
                        $('.tabla_datos').fadeOut();
                        form = $('.tabla_estadistica_r10_1_tpl_form').clone();
                        form.removeClass('hide');
                        $('.add-form').hide();
                        $('.controls').hide();
                        $('.add-form').append(form);
                        $('.add-form').fadeIn();
                        break;
                    case 2:
                        $('.tabla_datos').fadeOut();
                        form = $('.tabla_estadistica_r10_2_tpl_form').clone();
                        form.removeClass('hide');
                        $('.add-form').hide();
                        $('.controls').hide();
                        $('.add-form').append(form);
                        $('.add-form').fadeIn();
                        break;
                    case 3:
                        $('.tabla_datos').fadeOut();
                        form = $('.tabla_estadistica_r10_3_tpl_form').clone();
                        form.removeClass('hide');
                        $('.add-form').hide();
                        $('.controls').hide();
                        $('.add-form').append(form);
                        $('.add-form').fadeIn();
                        break;
                }
                console.log('Rubro 2');

                break;
        }
   });

   $(document).on('change', '.pregunta_inicial', function(){
       var option = $(this).val();
       var self = this;
       console.log("hola");
       switch(option){
           case '0':
               bootbox.alert("Debe seleccionar una opción");
               break;
           case '1':
               console.log("form.si");
               var form_si = $('.tabla_estadistica_r10_2_1_tpl_form');
               form_si.removeClass('hide');
               form_si.removeClass('tabla_estadistica_r10_2_1_tpl_form');
               $(self).closest('.panel-body').replaceWith(form_si.find('.panel-body'));
               break;
           case '2':
               console.log("form.no");
               break;
       }
   });

   $(document).on('click', '.cancelar',  function(){

       $(this).parent().parent().parent().parent().remove();
       $('.tabla_datos').fadeIn();
       $('.controls').fadeIn();
   });

   $(document).on('click', '.agregar-docente',  function(e){
       e.preventDefault();
       console.log('hola');
       var panel = $('#panel-profesor-tpl').clone();
       panel.removeClass('hide');
       console.log(panel.html());
//       console.log($('.form-profesores'));
//       panel.removeClass('hide');
//       console.log($('.form-profesores').parent().parent().html());
       console.log($(this).parent().parent().parent().parent().find('.form-profesores'));
       console.log(panel.find('.panel-profesor'));
//       $(this).parent().parent().parent().parent().find('.form-profesores').append("hola");
       $(this).parent().parent().parent().parent().find('.form-profesores').append(panel.find('.panel-profesor'));
//       $('.form-profesores').append(panel.find('.panel-profesor'));
   });


    $(document).on('click', '.guardar-data-profesor',  function(e){
        e.preventDefault();
        
        var self = this;
        var subtabla = $('#subtabla').val();
        var tipo = $(this).data('tipo');
        var rubro = $(this).data('rubro');
        var dato_id = $(this).data('dato') > 0 ? $(this).data('dato') : 0;
        console.log(dato_id);
        var data = [];
        var anio = $(self).parent().parent().parent().parent().find('.anio').find('input').val();

        $.each($(this).parent().parent().parent().parent().find('.form-profesores').find('.panel-profesor'), function(index, value){
             data.push($(value).find('input').map(function(){
                return {
                    campo : $(this).parent().attr('class'),
                    valor : $(this).val()
                }
            }).get());

        });



        var jqxhr = $.post('index.php?mod=sievas&controlador=evaluar&accion=guardar_data_tabla_estadistica_profesores', {
            data : {
                data : data,
                anio : $(self).parent().parent().parent().parent().find('.anio').find('input').val()
            },
            rubro : $(self).data('rubro'),
            dato_id : dato_id
        });

        jqxhr.done(function(datos){
            $(self).parent().parent().parent().parent().remove();
            $('.tabla_datos').fadeIn();
            $('.controls').fadeIn();
            $('.no-data').remove();

            var tabla = $('.tabla_estadistica_r4_tpl').clone();
            tabla.removeClass('hide');
            tabla.removeClass('tabla_estadistica_r4_tpl');
            tabla.find('.panel').addClass('item-tabla');
            tabla.find('.panel').addClass('item-tabla-'+datos.id);
            tabla.find('.anio').html(anio);
            tabla.find('.editar-tabla-datos').attr('data-id', datos.id);
            tabla.find('.editar-tabla-datos').attr('data-tipo', 4);
            tabla.find('.eliminar-tabla-datos').attr('data-id', datos.id);
            tabla.find('.eliminar-tabla-datos').attr('data-tipo', 4);

            console.log(data);

            //por cada docente se ingresa data en la tabla

            $.each(data, function(index, value){
                var tabla_item_profesor = tabla.find('.tabla_estadistica_r4_tpl_item_profesor').clone();
                $.each(value, function(i, valor){
                  tabla_item_profesor.find('.'+valor.campo).html(valor.valor);
                });
//                tabla_item_profesor.find('.anio').html(anio);
                tabla_item_profesor.removeClass('tabla_estadistica_r4_tpl_item_profesor');
                tabla_item_profesor.addClass('tabla_estadistica_r4_item_profesor');
                tabla.find('.panel table:last').after($('<table/>', {class: 'table profesores', html: tabla_item_profesor.find('tr')}));
//                tabla.find('.panel table.profesores').append();
            });
            if(dato_id > 0){
                console.log($('.tabla_datos').find('.item-tabla-'+dato_id)); 
                $('.tabla_datos').find('.item-tabla-'+dato_id).replaceWith(tabla.find('.panel'));
            }
            else{
               console.log(tabla);
                $('.tabla_datos').append(tabla.find('.panel'));
            }
        });



    });

    $(document).on('click', '.guardar-data',  function(){
        var self = this;
        var subtabla = $('#subtabla').val();
        var tipo = $(this).data('tipo');
        var rubro = $(this).data('rubro');
        var dato_id = $(this).data('dato') > 0 ? $(this).data('dato') : 0;
        var data = $(this).parent().parent().parent().parent().find('input').map(function(){
            return {
                campo : $(this).parent().attr('class'),
                valor : $(this).val()
            }
        }).get();

        var jqxhr = $.post('index.php?mod=sievas&controlador=evaluar&accion=guardar_data_tabla_estadistica', {
            data : data,
            rubro : $(this).data('rubro'),
            subtabla : subtabla, 
            dato_id : dato_id
        });

        jqxhr.done(function(datos){
            $(self).parent().parent().parent().parent().remove();
            $('.tabla_datos').fadeIn();
            $('.controls').fadeIn();
            $('.no-data').remove();
            var evaluacion_id = $('#ev_id').val();
            socket.emit('calificacion_evaluacion', { evaluacion : evaluacion_id });

            switch(tipo){
                case 1:
                    var tabla = $('.tabla_estadistica_r1_tpl').clone();
                    tabla.removeClass('hide');
                    tabla.removeClass('tabla_estadistica_r1_tpl');
                    tabla.find('.panel').addClass('item-tabla');
                    tabla.find('.panel').addClass('item-tabla-'+datos.id);
                    tabla.find('.editar-tabla-datos').attr('data-id', datos.id);
                    tabla.find('.editar-tabla-datos').attr('data-tipo', 1);
                    tabla.find('.eliminar-tabla-datos').attr('data-id', datos.id);
                    tabla.find('.eliminar-tabla-datos').attr('data-tipo', 1);
                    $.each(data, function(index, value){
                        tabla.find('.'+value.campo).html(value.valor);
                    });
//                    console.log(dato_id);
                    if(dato_id > 0){
                        $('.tabla_datos').find('.item-tabla-'+dato_id).replaceWith(tabla.find('.panel'));
                    }
                    else{
                        $('.tabla_datos').append(tabla.find('.panel'));
                    }
                    break;
                case 2:
                    var tabla = $('.tabla_estadistica_r2_tpl').clone();
                    tabla.removeClass('hide');
                    tabla.removeClass('tabla_estadistica_r2_tpl');
                    tabla.find('.panel').addClass('item-tabla');
                    tabla.find('.panel').addClass('item-tabla-'+datos.id);
                    tabla.find('.editar-tabla-datos').attr('data-id', datos.id);
                    tabla.find('.editar-tabla-datos').attr('data-tipo', 2);
                    tabla.find('.eliminar-tabla-datos').attr('data-id', datos.id);
                    tabla.find('.eliminar-tabla-datos').attr('data-tipo', 2);
                    $.each(data, function(index, value){
                        tabla.find('.'+value.campo).html(value.valor);
                    });
                     if(dato_id > 0){
                        $('.tabla_datos').find('.item-tabla-'+dato_id).replaceWith(tabla.find('.panel'));
                    }
                    else{
                        $('.tabla_datos').append(tabla.find('.panel'));
                    }
                    break;
                case 3:
                    var tabla = $('.tabla_estadistica_r3_tpl').clone();
                    tabla.removeClass('hide');
                    tabla.removeClass('tabla_estadistica_r3_tpl');
                    tabla.find('.panel').addClass('item-tabla');
                    tabla.find('.panel').addClass('item-tabla-'+datos.id);
                    tabla.find('.editar-tabla-datos').attr('data-id', datos.id);
                    tabla.find('.editar-tabla-datos').attr('data-tipo', 3);
                    tabla.find('.eliminar-tabla-datos').attr('data-id', datos.id);
                    tabla.find('.eliminar-tabla-datos').attr('data-tipo', 3);
                    $.each(data, function(index, value){
                        tabla.find('.'+value.campo).html(value.valor);
                    });
                    if(dato_id > 0){
                        $('.tabla_datos').find('.item-tabla-'+dato_id).replaceWith(tabla.find('.panel'));
                    }
                    else{
                        $('.tabla_datos').append(tabla.find('.panel'));
                    }
                    break;
                case 4:
                    var tabla = $('.tabla_estadistica_r4_tpl').clone();
                    tabla.removeClass('hide');
                    tabla.removeClass('tabla_estadistica_r4_tpl');
                    tabla.find('.panel').addClass('item-tabla');
                    tabla.find('.panel').addClass('item-tabla-'+datos.id);
                    tabla.find('.editar-tabla-datos').attr('data-id', datos.id);
                    tabla.find('.editar-tabla-datos').attr('data-tipo', 4);
                    tabla.find('.eliminar-tabla-datos').attr('data-id', datos.id);
                    tabla.find('.eliminar-tabla-datos').attr('data-tipo', 4);
                    $.each(data, function(index, value){
                        tabla.find('.'+value.campo).html(value.valor);
                    });
                    if(dato_id > 0){
                        $('.tabla_datos').find('.item-tabla-'+dato_id).replaceWith(tabla.find('.panel'));
                    }
                    else{
                        $('.tabla_datos').append(tabla.find('.panel'));
                    }
                    break;
                case 5:
                    var tabla = $('.tabla_estadistica_r5_tpl').clone();
                    tabla.removeClass('hide');
                    tabla.removeClass('tabla_estadistica_r5_tpl');
                    tabla.find('.panel').addClass('item-tabla');
                    tabla.find('.panel').addClass('item-tabla-'+datos.id);
                    tabla.find('.editar-tabla-datos').attr('data-id', datos.id);
                    tabla.find('.editar-tabla-datos').attr('data-tipo', 5);
                    tabla.find('.eliminar-tabla-datos').attr('data-id', datos.id);
                    tabla.find('.eliminar-tabla-datos').attr('data-tipo', 5);
                    $.each(data, function(index, value){
                        tabla.find('.'+value.campo).html(value.valor);
                    });
                    if(dato_id > 0){
                        $('.tabla_datos').find('.item-tabla-'+dato_id).replaceWith(tabla.find('.panel'));
                    }
                    else{
                        $('.tabla_datos').append(tabla.find('.panel'));
                    }
                    break;
                case 6:
                    var tabla = $('.tabla_estadistica_r6_tpl').clone();
                    tabla.removeClass('hide');
                    tabla.removeClass('tabla_estadistica_r6_tpl');
                    tabla.find('.panel').addClass('item-tabla');
                    tabla.find('.panel').addClass('item-tabla-'+datos.id);
                    tabla.find('.editar-tabla-datos').attr('data-id', datos.id);
                    tabla.find('.editar-tabla-datos').attr('data-tipo', 6);
                    tabla.find('.eliminar-tabla-datos').attr('data-id', datos.id);
                    tabla.find('.eliminar-tabla-datos').attr('data-tipo', 6);
                    $.each(data, function(index, value){
                        tabla.find('.'+value.campo).html(value.valor);
                    });
                    if(dato_id > 0){
                        $('.tabla_datos').find('.item-tabla-'+dato_id).replaceWith(tabla.find('.panel'));
                    }
                    else{
                        $('.tabla_datos').append(tabla.find('.panel'));
                    }
                    break;
                case 7:
                     var tabla = $('.tabla_estadistica_r7_tpl').clone();
                    tabla.removeClass('hide');
                    tabla.removeClass('tabla_estadistica_r7_tpl');
                    tabla.find('.panel').addClass('item-tabla');
                    tabla.find('.panel').addClass('item-tabla-'+datos.id);
                    tabla.find('.editar-tabla-datos').attr('data-id', datos.id);
                    tabla.find('.editar-tabla-datos').attr('data-tipo', 7);
                    tabla.find('.eliminar-tabla-datos').attr('data-id', datos.id);
                    tabla.find('.eliminar-tabla-datos').attr('data-tipo', 7);
                    $.each(data, function(index, value){
                        tabla.find('.'+value.campo).html(value.valor);
                    });
                    if(dato_id > 0){
                        $('.tabla_datos').find('.item-tabla-'+dato_id).replaceWith(tabla.find('.panel'));
                    }
                    else{
                        $('.tabla_datos').append(tabla.find('.panel'));
                    }
                    break;
                case 8:
                     var tabla = $('.tabla_estadistica_r8_tpl').clone();
                    tabla.removeClass('hide');
                    tabla.removeClass('tabla_estadistica_r8_tpl');
                    tabla.find('.panel').addClass('item-tabla');
                    tabla.find('.panel').addClass('item-tabla-'+datos.id);
                    tabla.find('.editar-tabla-datos').attr('data-id', datos.id);
                    tabla.find('.editar-tabla-datos').attr('data-tipo', 8);
                    tabla.find('.eliminar-tabla-datos').attr('data-id', datos.id);
                    tabla.find('.eliminar-tabla-datos').attr('data-tipo', 8);
                    $.each(data, function(index, value){
                        tabla.find('.'+value.campo).html(value.valor);
                    });
                    if(dato_id > 0){
                        $('.tabla_datos').find('.item-tabla-'+dato_id).replaceWith(tabla.find('.panel'));
                    }
                    else{
                        $('.tabla_datos').append(tabla.find('.panel'));
                    }
                    break;
                case 9:
                     var tabla = $('.tabla_estadistica_r9_tpl').clone();
                    tabla.removeClass('hide');
                    tabla.removeClass('tabla_estadistica_r9_tpl');
                    tabla.find('.panel').addClass('item-tabla');
                    tabla.find('.panel').addClass('item-tabla-'+datos.id);
                    tabla.find('.editar-tabla-datos').attr('data-id', datos.id);
                    tabla.find('.editar-tabla-datos').attr('data-tipo', 9);
                    tabla.find('.eliminar-tabla-datos').attr('data-id', datos.id);
                    tabla.find('.eliminar-tabla-datos').attr('data-tipo', 9);
                    $.each(data, function(index, value){
                        tabla.find('.'+value.campo).html(value.valor);
                    });
                    if(dato_id > 0){
                        $('.tabla_datos').find('.item-tabla-'+dato_id).replaceWith(tabla.find('.panel'));
                    }
                    else{
                        $('.tabla_datos').append(tabla.find('.panel'));
                    }
                    break;
                case 10:
                    console.log(subtabla);
                     switch(parseInt(subtabla)){
                         case 1:
                            var tabla = $('.tabla_estadistica_r10_1_tpl').clone();
                            tabla.removeClass('hide');
                            tabla.removeClass('tabla_estadistica_r10_1_tpl');
                            tabla.find('.panel').addClass('item-tabla');
                            tabla.find('.panel').addClass('item-tabla-'+datos.id);
                            tabla.find('.editar-tabla-datos').attr('data-id', datos.id);
                            tabla.find('.editar-tabla-datos').attr('data-tipo', 10);
                            tabla.find('.eliminar-tabla-datos').attr('data-id', datos.id);
                            tabla.find('.eliminar-tabla-datos').attr('data-tipo', 10);
                            $.each(data, function(index, value){
                                tabla.find('.'+value.campo).html(value.valor);
                            });
                            if(dato_id > 0){
                                $('.tabla_datos').find('.item-tabla-'+dato_id).replaceWith(tabla.find('.panel'));
                            }
                            else{
                                $('.tabla_datos').append(tabla.find('.panel'));
                            }
                             break;
                         case 2:
                             var tabla = $('.tabla_estadistica_r10_2_tpl').clone();
                            tabla.removeClass('hide');
                            tabla.removeClass('tabla_estadistica_r10_2_tpl');
                            tabla.find('.panel').addClass('item-tabla');
                            tabla.find('.panel').addClass('item-tabla-'+datos.id);
                            tabla.find('.editar-tabla-datos').attr('data-id', datos.id);
                            tabla.find('.editar-tabla-datos').attr('data-tipo', 10);
                            tabla.find('.eliminar-tabla-datos').attr('data-id', datos.id);
                            tabla.find('.eliminar-tabla-datos').attr('data-tipo', 10);
                            $.each(data, function(index, value){
                                tabla.find('.'+value.campo).html(value.valor);
                            });
                            if(dato_id > 0){
                                $('.tabla_datos').find('.item-tabla-'+dato_id).replaceWith(tabla.find('.panel'));
                            }
                            else{
                                $('.tabla_datos').append(tabla.find('.panel'));
                            }
                             break;
                         case 3:
                            var tabla = $('.tabla_estadistica_r10_3_tpl').clone();
                            tabla.removeClass('hide');
                            tabla.removeClass('tabla_estadistica_r10_3_tpl');
                            tabla.find('.panel').addClass('item-tabla');
                            tabla.find('.panel').addClass('item-tabla-'+datos.id);
                            tabla.find('.editar-tabla-datos').attr('data-id', datos.id);
                            tabla.find('.editar-tabla-datos').attr('data-tipo', 10);
                            tabla.find('.eliminar-tabla-datos').attr('data-id', datos.id);
                            tabla.find('.eliminar-tabla-datos').attr('data-tipo', 10);
                            $.each(data, function(index, value){
                                tabla.find('.'+value.campo).html(value.valor);
                            });
                            if(dato_id > 0){
                                $('.tabla_datos').find('.item-tabla-'+dato_id).replaceWith(tabla.find('.panel'));
                            }
                            else{
                                $('.tabla_datos').append(tabla.find('.panel'));
                            }
                             break;
                     }

                    break;
            }
            $('.item-tabla-'+datos.id)[0].scrollIntoView();
        });
    });

    $(document).on('click', '.eliminar-tabla-datos',  function(e){
        var dato_id = $(this).data('id');
        var tipo = $(this).data('tipo');
        var self = this;
        var jqxhr = $.post('index.php?mod=sievas&controlador=evaluar&accion=eliminar_data_tabla_estadistica', {
           dato_id : dato_id,
           tipo: (tipo == 4 ? 1: 0)
        });

        jqxhr.done(function(){
            console.log($('.item-tabla').length);
            if($('.item-tabla').length > 1){
                $(self).closest('.item-tabla').remove();
            }
            else{
                console.log("ultimo dato");
                var el = $('.no-data-tpl').clone();
                $(self).closest('.panel').remove();
                console.log(el.html());
                el.removeClass('hide');
                el.removeClass('no-data-tpl');
                el.addClass('no-data');
                $('.tabla_datos').append(el);
            }

        });
    });

    $(document).on('click', '.editar-tabla-datos',  function(e){
        e.preventDefault();
        console.log("hola");
        var subtabla = $('#subtabla').val();
        var self = this;
        var tipo = $(this).data('tipo');
        var dato_id = $(this).data('id');
        
        var form = null;
        console.log(tipo);
        switch(tipo){
            case 1:
                var tds = $(self).closest('table').find('td');
                $('.tabla_datos').fadeOut();
                form = $('.tabla_estadistica_r1_tpl_form').clone();
                form.removeClass('hide');
                var datos = [];
                $.each($(self).closest('table').find('td'), function(idx,val){
                    if(typeof $(val).attr('class') !== 'undefined'){
                        datos[$(val).attr('class')] = $(val).html();
                        form.find('td.'+$(val).attr('class')).find('input').val($(val).html());
                        console.log($(val).attr('class'));
                        console.log($(val).html());
                    }
                });
                console.log(datos);
                form.find('.guardar-data').attr('data-dato', dato_id);
                $('.add-form').hide();
                $('.controls').hide();
                $('.add-form').append(form);
                $('.add-form').fadeIn();
                break;
            case 2:
                var tds = $(self).closest('table').find('td');
                $('.tabla_datos').fadeOut();
                form = $('.tabla_estadistica_r2_tpl_form').clone();
                form.removeClass('hide');
                var datos = [];
                $.each($(self).closest('table').find('td'), function(idx,val){
                    if(typeof $(val).attr('class') !== 'undefined'){
                        datos[$(val).attr('class')] = $(val).html();
                        form.find('td.'+$(val).attr('class')).find('input').val($(val).html());
                    }
                });
                form.find('.guardar-data').attr('data-dato', dato_id);
                $('.add-form').hide();
                $('.controls').hide();
                $('.add-form').append(form);
                $('.add-form').fadeIn();
                break;
            case 3:
                console.log("hola");
                var tds = $(self).closest('table').find('td');
                $('.tabla_datos').fadeOut();
                form = $('.tabla_estadistica_r3_tpl_form').clone();
                form.removeClass('hide');
                var datos = [];
                $.each($(self).closest('table').find('td'), function(idx,val){
                    if(typeof $(val).attr('class') !== 'undefined'){
                        datos[$(val).attr('class')] = $(val).html();
                        form.find('td.'+$(val).attr('class')).find('input').val($(val).html());
                    }
                });
                form.find('.guardar-data').attr('data-dato', dato_id);
                $('.add-form').hide();
                $('.controls').hide();
                $('.add-form').append(form);
                $('.add-form').fadeIn();
                break;
            case 4:
                var tds = $(self).closest('table').parent().find('table');
                console.log(tds);
                $('.tabla_datos').fadeOut();
                form = $('.tabla_estadistica_r4_tpl_form').clone();
                form.find('.panel-profesor').remove();
                form.removeClass('hide');
                form.find('#panel-profesor-tpl').remove();
                
                var anio = $(tds[0]).find('td.anio').html();
                form.find('td.anio input').val(anio);
                
                
                //extraer datos de tablas html
                //crear formularios por cada profesor
                //llenar datos de cada profesor en forms
               //por cada tabla de class profesor se hacen las 3 operaciones
               
               $.each(tds, function(idx, val){
                    if($(val).hasClass('profesores')){
                        console.log(idx);
                        var tmp = $('.tabla_estadistica_r4_tpl_form .form-profesores .panel-profesor').clone();
                        $.each($(val).find('td'), function(i,v){
                            if(typeof $(v).attr('class') !== 'undefined'){
//                                datos[$(val).attr('class')] = $(val).html();
                                $(tmp).find('td.'+$(v).attr('class')).find('input').val($(v).html());
                            }
                        });
//                        form.append(tmp);
                          form.find('.form-profesores').append(tmp);
                    }
               });
                
//                var datos = [];
//                $.each($(self).closest('table').find('td'), function(idx,val){
//                    if(typeof $(val).attr('class') !== 'undefined'){
//                        datos[$(val).attr('class')] = $(val).html();
//                        form.find('td.'+$(val).attr('class')).find('input').val($(val).html());
//                    }
//                });
                form.find('.guardar-data').attr('data-dato', dato_id);
                form.find('.guardar-data-profesor').attr('data-dato', dato_id);
                $('.add-form').hide();
                $('.controls').hide();
                $('.add-form').append(form);
                $('.add-form').fadeIn();
                break;
            case 5:
                 var tds = $(self).closest('table').find('td');
                $('.tabla_datos').fadeOut();
                form = $('.tabla_estadistica_r5_tpl_form').clone();
                form.removeClass('hide');
                var datos = [];
                $.each($(self).closest('table').find('td'), function(idx,val){
                    if(typeof $(val).attr('class') !== 'undefined'){
                        datos[$(val).attr('class')] = $(val).html();
                        form.find('td.'+$(val).attr('class')).find('input').val($(val).html());
                    }
                });
                form.find('.guardar-data').attr('data-dato', dato_id);
                $('.add-form').hide();
                $('.controls').hide();
                $('.add-form').append(form);
                $('.add-form').fadeIn();
                break;
            case 6:
                 var tds = $(self).closest('table').find('td');
                $('.tabla_datos').fadeOut();
                form = $('.tabla_estadistica_r6_tpl_form').clone();
                form.removeClass('hide');
                var datos = [];
                $.each($(self).closest('table').find('td'), function(idx,val){
                    if(typeof $(val).attr('class') !== 'undefined'){
                        datos[$(val).attr('class')] = $(val).html();
                        form.find('td.'+$(val).attr('class')).find('input').val($(val).html());
                    }
                });
                form.find('.guardar-data').attr('data-dato', dato_id);
                $('.add-form').hide();
                $('.controls').hide();
                $('.add-form').append(form);
                $('.add-form').fadeIn();
                break;
            case 7:
                  var tds = $(self).closest('table').find('td');
                $('.tabla_datos').fadeOut();
                form = $('.tabla_estadistica_r7_tpl_form').clone();
                form.removeClass('hide');
                var datos = [];
                $.each($(self).closest('table').find('td'), function(idx,val){
                    if(typeof $(val).attr('class') !== 'undefined'){
                        datos[$(val).attr('class')] = $(val).html();
                        form.find('td.'+$(val).attr('class')).find('input').val($(val).html());
                    }
                });
                form.find('.guardar-data').attr('data-dato', dato_id);
                $('.add-form').hide();
                $('.controls').hide();
                $('.add-form').append(form);
                $('.add-form').fadeIn();
                break;
            case 8:
                  var tds = $(self).closest('table').find('td');
                $('.tabla_datos').fadeOut();
                form = $('.tabla_estadistica_r8_tpl_form').clone();
                form.removeClass('hide');
                var datos = [];
                $.each($(self).closest('table').find('td'), function(idx,val){
                    if(typeof $(val).attr('class') !== 'undefined'){
                        datos[$(val).attr('class')] = $(val).html();
                        form.find('td.'+$(val).attr('class')).find('input').val($(val).html());
                    }
                });
                form.find('.guardar-data').attr('data-dato', dato_id);
                $('.add-form').hide();
                $('.controls').hide();
                $('.add-form').append(form);
                $('.add-form').fadeIn();
                break;
            case 9:
                  var tds = $(self).closest('table').find('td');
                $('.tabla_datos').fadeOut();
                form = $('.tabla_estadistica_r9_tpl_form').clone();
                form.removeClass('hide');
                var datos = [];
                $.each($(self).closest('table').find('td'), function(idx,val){
                    if(typeof $(val).attr('class') !== 'undefined'){
                        datos[$(val).attr('class')] = $(val).html();
                        form.find('td.'+$(val).attr('class')).find('input').val($(val).html());
                    }
                });
                form.find('.guardar-data').attr('data-dato', dato_id);
                $('.add-form').hide();
                $('.controls').hide();
                $('.add-form').append(form);
                $('.add-form').fadeIn();
                break;
            case 10:
                console.log(subtabla);
                switch(parseInt(subtabla)){
                    case 1:
                          var tds = $(self).closest('table').find('td');
                            $('.tabla_datos').fadeOut();
                            form = $('.tabla_estadistica_r10_1_tpl_form').clone();
                            form.removeClass('hide');
                            var datos = [];
                            $.each($(self).closest('table').find('td'), function(idx,val){
                                if(typeof $(val).attr('class') !== 'undefined'){
                                    datos[$(val).attr('class')] = $(val).html();
                                    form.find('td.'+$(val).attr('class')).find('input').val($(val).html());
                                }
                            });
                            form.find('.guardar-data').attr('data-dato', dato_id);
                            $('.add-form').hide();
                            $('.controls').hide();
                            $('.add-form').append(form);
                            $('.add-form').fadeIn();
                        break;
                    case 2:
                        var tds = $(self).closest('table').find('td');
                            $('.tabla_datos').fadeOut();
                            form = $('.tabla_estadistica_r10_2_1_tpl_form').clone();
                            form.removeClass('hide');
                            var datos = [];
                            $.each($(self).closest('table').find('td'), function(idx,val){
                                if(typeof $(val).attr('class') !== 'undefined'){
                                    datos[$(val).attr('class')] = $(val).html();
                                    form.find('td.'+$(val).attr('class')).find('input').val($(val).html());
                                }
                            });
                            form.find('.guardar-data').attr('data-dato', dato_id);
                            $('.add-form').hide();
                            $('.controls').hide();
                            $('.add-form').append(form);
                            $('.add-form').fadeIn();
                        break;
                    case 3:
                          var tds = $(self).closest('table').find('td');
                            $('.tabla_datos').fadeOut();
                            form = $('.tabla_estadistica_r10_3_tpl_form').clone();
                            form.removeClass('hide');
                            var datos = [];
                            $.each($(self).closest('table').find('td'), function(idx,val){
                                if(typeof $(val).attr('class') !== 'undefined'){
                                    datos[$(val).attr('class')] = $(val).html();
                                    form.find('td.'+$(val).attr('class')).find('input').val($(val).html());
                                }
                            });
                            form.find('.guardar-data').attr('data-dato', dato_id);
                            $('.add-form').hide();
                            $('.controls').hide();
                            $('.add-form').append(form);
                            $('.add-form').fadeIn();
                        break;
                }
  

                break;
        }
        $('.item-tabla-'+dato_id)[0].scrollIntoView();
    });
}


$(gestor_tablas.init);
