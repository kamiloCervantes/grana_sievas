var lineamientos = {};

lineamientos.initconjuntos = function(){
     $('#data_tabla').dataTable({
            "oLanguage":{"sUrl": "public/js/dataTables.spanish.txt"},
            "bServerSide": true,
            "sAjaxSource": "index.php?mod=sievas&controlador=lineamientos&accion=get_dt_conjuntos",
            "bProcessing": true,            
            "fnServerParams": function(aoData){
                var fkeys = [];
                
                aoData.push({"name": "sIndexColumn", "value": "id"});
                aoData.push({"name": "sTable", "value": "lineamientos_conjuntos"});

            },
            "aoColumns":[
                {"mData": 0,"sName": "lineamientos_conjuntos.nom_conjunto"
                },                                     
                {
                    "mData":1,
                    "sName":"id",
                    "mRender":function(id){
                        return '<a href="#" class="eliminar-generico" data-id="'+id+'" title="Eliminar conjunto de lineamientos"><i class="glyphicon glyphicon-trash"></i></a>\n\
                        <a href="index.php?mod=sievas&controlador=lineamientos&accion=editar_conjunto&id='+id+'"  title="Editar conjunto de lineamientos"><i class="glyphicon glyphicon-edit"></i></a>\n\
                        <a href="index.php?mod=sievas&controlador=lineamientos&accion=editar_datos_complementarios&id='+id+'"  title="Datos complementarios de lineamientos"><i class="glyphicon glyphicon-th-list"></i></a>\n\
                        <a href="index.php?mod=sievas&controlador=lineamientos&accion=copiar_conjunto&id='+id+'"  title="Copiar conjunto de lineamientos"><i class="glyphicon glyphicon-plus-sign"></i></a>';
                    },
                    "bSortable" : false,                    
                }
            ]   
    });
    $(document).on('click','.eliminar-generico', lineamientos.eliminar_conjunto);
}

lineamientos.eliminar_conjunto = function(){
    var self = this;
    var dialog = bootbox.dialog({
        message: "¿Esta seguro que desea eliminar el conjunto de lineamientos?",
        title: 'Eliminar conjunto de lineamientos',
        buttons: {
            success: {
              label: "Si",
              className: "btn-success",
              callback: function() {
                var id = $(self).data('id');
                var jqxhr = $.post('index.php?mod=sievas&controlador=lineamientos&accion=eliminar_conjunto_lineamientos', {
                    id : id
                });

                jqxhr.done(function(data){
                   bootbox.alert(data.mensaje, function(){
                       location.reload();
                   });
                });

                jqxhr.fail(function(err){
                   bootbox.alert(err.responseText); 
                });
              }
            },
            danger: {
              label: "No",
              className: "btn-danger",
              callback: function() {
                
              }
            }
          }
        });
}

lineamientos.eliminar_lineamiento = function(id){
    var self = this;
    var dialog = bootbox.dialog({
        message: "¿Esta seguro que desea eliminar el lineamiento?",
        title: 'Eliminar lineamiento',
        buttons: {
            success: {
              label: "Si",
              className: "btn-success",
              callback: function() {
                var jqxhr = $.post('index.php?mod=sievas&controlador=lineamientos&accion=eliminar_lineamiento', {
                    id : id
                });

                jqxhr.done(function(data){
                   bootbox.alert(data.mensaje, function(){
                       $('#arbol').jstree('refresh');
                   });
                });

                jqxhr.fail(function(err){
                   bootbox.alert(err.responseText); 
                });
              }
            },
            danger: {
              label: "No",
              className: "btn-danger",
              callback: function() {
                
              }
            }
          }
        });
}


lineamientos.loadPageVar = function(sVar) {
  return decodeURI(window.location.search.replace(new RegExp("^(?:.*[&\\?]" + encodeURI(sVar).replace(/[\.\+\*]/g, "\\$&") + "(?:\\=([^&]*))?)?.*$", "i"), "$1"));
}


lineamientos.initaddconjunto = function(){
    $('#arbol').jstree({
            "core" : {
              "animation" : 0,
              "multiple": false,
              "check_callback" : true,
              'data' : {
                    'url' : 'index.php?mod=sievas&controlador=lineamientos&accion=cargar_arbol_conjunto',
                    'data' : function (node) {
                      return { 
                          'id' : node.id,
                          'conjunto': ($('#conjunto').val() > 0 ? $('#conjunto').val() : lineamientos.loadPageVar('id'))
                      };
                    }
              }
            },
            "types" : {
                "#" : {
                  "max_children" : 1, 
                  "max_depth" : 4, 
                  "valid_children" : ["root"]
                },                
                "default" : {
                   "icon" : "public/img/icon-nodes-gris.png",
                  "valid_children" : ["default","file"]
                },
                "file" : {
                  "icon" : "glyphicon glyphicon-file",
                  "valid_children" : []
                }
              },
            "plugins" : [
              "dnd", "search",
              "state", "types", "wholerow"
            ]
     });

   $('.jstree-draggable').on('mousedown', function(e){
       var lineamiento = $('#lineamiento').val();
//       lineamiento = 'hola';
//       if($('#form-lineamiento').valid()){
            return $.vakata.dnd.start(e, {
                'jstree': true,
                'obj': $(this),
                'nodes': [{
                    id: true,
                    text: lineamiento,
                    lineamiento : lineamiento
                }]
            }, '<div id="jstree-dnd" class="jstree-default"><i class="jstree-icon jstree-er"></i>' + lineamiento + '</div>');
//       }
    });
    
    $(document).on('dnd_stop.vakata', function(e, data){
        if(data.element.className === 'jstree-draggable'){
            $('.error').remove();
            var nom_conjunto = $('#nom_conjunto').val();
            var padre = $(data.event.target).parent().prop('id');
            if(typeof nom_conjunto != 'undefined' && nom_conjunto != ''){
            if(padre > 0){
                var padre_node = $('#arbol').jstree('get_node', padre);
                if(padre_node.parent > 0){
                    padre = padre_node.parent;
                }
                var jqxhr = $.post('index.php?mod=sievas&controlador=lineamientos&accion=guardar_lineamiento', {
                    lineamiento : data.data.nodes[0].lineamiento,
                    conjunto : $('#conjunto').val(),
                    padre : padre,
                    nom_conjunto : $('#nom_conjunto').val()
                });
                
                jqxhr.done(function(data){
                    $('#conjunto').val(data.conjunto);
                    $('#arbol').jstree('refresh');
                });
                
                jqxhr.fail(function(err){
                   if(err.responseText != ''){
                       var error = JSON.parse(err.responseText);
                       bootbox.alert(error.mensaje);
                       $('#conjunto').val(error.conjunto);
                       $('#arbol').jstree('refresh');
                   } 
                });
            }
            else{
                var jqxhr = $.post('index.php?mod=sievas&controlador=lineamientos&accion=guardar_lineamiento', {
                    lineamiento : data.data.nodes[0].lineamiento,
                    conjunto : $('#conjunto').val(),
                    padre : 0,
                    nom_conjunto : $('#nom_conjunto').val()
                });
                
                jqxhr.done(function(data){
                    $('#conjunto').val(data.conjunto);
                    $('#arbol').jstree('refresh');
                });
                
                jqxhr.fail(function(err){
                   if(err.responseText != ''){
                       var error = JSON.parse(err.responseText);
                       bootbox.alert(error.mensaje);
                       $('#conjunto').val(error.conjunto);
                       $('#arbol').jstree('refresh');
                   } 
                });
               }
            }
            else{                
                $('#nom_conjunto').after('<label class="error">Este campo es requerido</label>');
            }
        }
    });
    
    $(document).on('dnd_move.vakata', function(e,node){
        console.log(node.element);
        if($(node.element).hasClass('jstree-draggable')){
            if($(node.event.target).closest('.jstree').length > 0){
                node.helper.find('.jstree-icon').css('background', 'url("public/js/jsTree/themes/default/32px.png") no-repeat transparent -9px -71px');
            }
            else{
                node.helper.find('.jstree-icon').css('background', 'url("public/js/jsTree/themes/default/32px.png") no-repeat transparent -39px -71px');
            }
        }       
    });
    
    $('#arbol').on('select_node.jstree', function(e,data){
        if(data.node.parent > -1){
        $('.form-actions').remove();
        var html = '<br/><div class="form-actions" style="text-align:center">';
        html += '<a href="#" class="btn btn-primary editar-lineamiento" data-id="'+data.node.id+'">Editar</a>&nbsp;';
        html += '<a href="#" class="btn btn-danger eliminar-lineamiento" data-id="'+data.node.id+'">Eliminar</a>&nbsp;';
        html += '<a href="#" class="btn btn-default cancelar-editar-lineamiento">Cancelar</a>';
        html += '</div>';
        $('.jstree-draggable').hide();
        $('#lineamiento').val(data.node.text.substring(data.node.text.lastIndexOf(".")+2));
        $('#lineamiento').after(html);
        }
        else{
            $('.form-actions').remove();
            $('#lineamiento').val('');
            $('.jstree-draggable').show();
        }
    });
    
    $(document).on('click', '.editar-lineamiento', lineamientos.editarlineamiento);
    $(document).on('click', '.eliminar-lineamiento', function(){
       var lineamiento_id = $('#arbol').jstree('get_selected','full')[0];
       if(lineamiento_id.parent > -1){
           lineamientos.eliminar_lineamiento(lineamiento_id.id);
       }
       else{
           bootbox.alert("No se puede eliminar el lineamiento");
       }
    });
    $(document).on('click', '.cancelar-editar-lineamiento', function(e){
        e.preventDefault();
        $('.form-actions').remove();
        $('#lineamiento').val('');
        $('.jstree-draggable').show();
    });
    
      //dnd de nodos dentro del arbol (actualizar padre lineamiento)
    $('#arbol').on('move_node.jstree', function(e, data){
        var padre_viejo = data.old_parent;
        var padre_nuevo = data.parent;
        var id_lineamiento = data.node.id;

        var jqxhr = $.post('index.php?mod=sievas&controlador=lineamientos&accion=editar_lineamiento', {
           padre : padre_viejo > 0 ? padre_viejo : padre_nuevo,
           id : id_lineamiento,
           nom_lineamiento : data.node.text.substring(data.node.text.lastIndexOf('.')+2),
           conjunto : $('#conjunto').val()
        });
        
        jqxhr.done(function(){
            $('#arbol').jstree(true).refresh();
        });

        jqxhr.fail(function(err){
            if(err.responseText !== ''){             
                bootbox.alert(err.responseText);
                 $('#arbol').jstree(true).refresh();
            }
        }); 
    });
    
    $('#salir').on('click', function(){
        var conjunto = $('#conjunto').val();
        var nom_conjunto = $('#nom_conjunto').val();
        
        if(nom_conjunto != ''){
            var jqxhr = $.post('index.php?mod=sievas&controlador=lineamientos&accion=guardar_conjunto', {
                conjunto : conjunto,
                nom_conjunto : nom_conjunto
            });

            jqxhr.done(function(){
                window.location = 'index.php?mod=sievas&controlador=lineamientos&accion=conjuntos_lineamientos';
            });

            jqxhr.fail(function(err){
                if(err.responseText !== ''){
                    bootbox.alert(err.responseText, function(){
                        window.location = 'index.php?mod=sievas&controlador=lineamientos&accion=conjuntos_lineamientos';
                    });                
                }            
            });
        }
        else{
            $('#nom_conjunto').after('<label class="error">Este campo es requerido</label>');
        }
        
    });
}

lineamientos.initeditcomplementarios = function(){
    $('#arbol').jstree({
            "core" : {
              "animation" : 0,
              "multiple": false,
              "check_callback" : true,
              'data' : {
                    'url' : 'index.php?mod=sievas&controlador=lineamientos&accion=cargar_arbol_conjunto',
                    'data' : function (node) {
                      return { 
                          'id' : node.id,
                          'conjunto': ($('#conjunto').val() > 0 ? $('#conjunto').val() : lineamientos.loadPageVar('id'))
                      };
                    }
              }
            },
            "types" : {
                "#" : {
                  "max_children" : 1, 
                  "max_depth" : 4, 
                  "valid_children" : ["root"]
                },                
                "default" : {
                   "icon" : "public/img/icon-nodes-gris.png",
                  "valid_children" : ["default","file"]
                },
                "file" : {
                  "icon" : "glyphicon glyphicon-file",
                  "valid_children" : []
                }
              },
            "plugins" : [
              "search",
              "state", "types", "wholerow"
            ]
     });
     
     $('#arbol').on('select_node.jstree', function(e, data){
         $('#form_rubros').fadeOut();
         $('#form_items').fadeOut();
         $('#indicadores_item').code('');
         $('#documentos_item').code('');
         $('#significado').code('');
         $('#contexto').code('');
         $('#glosario').code('');
         $('#referencias').code('');
         $('#documento_contexto').code('');
         $('#documento_glosario').code('');
         $('#documento_referencias').code('');
         $('#tabla_estadistica').code('');
            if(data.node.parent > 0){
                //items
                $('#form_items').fadeIn();
                var jqxhr = $.get('index.php?mod=sievas&controlador=lineamientos&accion=get_datos_complementarios', {
                    lineamiento : data.node.id,
                    tipo_lineamiento : 'item'
                });
                
                jqxhr.done(function(data){
                    $('#indicadores_item').code(decodeURIComponent(data.indicadores) != 'null' ? decodeURIComponent(data.indicadores) : '');
                    $('#documentos_item').code(decodeURIComponent(data.documentos) != 'null' ? decodeURIComponent(data.documentos) : '');                   
                });
            }
            else{
                //rubros
                $('#form_rubros').fadeIn();
                 var jqxhr = $.get('index.php?mod=sievas&controlador=lineamientos&accion=get_datos_complementarios', {
                    lineamiento : data.node.id,
                    tipo_lineamiento : 'rubro'
                });
                
                jqxhr.done(function(data){                   
                    $('.file-icon').remove();
                    $('#significado').code(decodeURIComponent(data.significado) != 'null' && data != 'null' ? decodeURIComponent(data.significado) : '');
                    $('#contexto').code(decodeURIComponent(data.contexto) != 'null' && data != 'null' ? decodeURIComponent(data.contexto) : '');
                    $('#glosario').code(decodeURIComponent(data.glosario) != 'null' && data != 'null' ? decodeURIComponent(data.glosario) : '');
                    $('#referencias').code(decodeURIComponent(data.referencia) != 'null' && data != 'null' ? decodeURIComponent(data.referencia) : '');
                    if(data.documento_contexto != '' && data.documento_contexto != null  && data != 'null'){
                        $('#documento_contexto').parent().find('label').append('&nbsp;<a href="'+data.documento_contexto+'" target="_blank" class="file-icon btn btn-default btn-xs"><i class="glyphicon glyphicon-file"></a>')
                    }
                    if(data.documento_glosario != '' && data.documento_glosario != null  && data != 'null'){
                        $('#documento_glosario').parent().find('label').append('&nbsp;<a href="'+data.documento_contexto+'" target="_blank" class="file-icon btn btn-default btn-xs"><i class="glyphicon glyphicon-file"></a>')
                    }
                    if(data.documento_referencias != '' && data.documento_referencias != null  && data != 'null'){
                        $('#documento_referencias').parent().find('label').append(' <a href="'+data.documento_contexto+'" target="_blank" class="file-icon btn btn-default btn-xs"><i class="glyphicon glyphicon-file"></a>')
                    }
                    if(data.tabla_estadistica != '' && data.tabla_estadistica != null  && data != 'null'){
                        $('#tabla_estadistica').parent().find('label').append('&nbsp;<a href="'+data.tabla_estadistica+'" target="_blank" class="file-icon btn btn-default btn-xs"><i class="glyphicon glyphicon-file"></a>')
                    }
                });
            }
     });    
 
     
     $('.summernote').summernote({
        height: 250,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
          ]
    });
    
    $('#guardar-datos-rubro').on('click', function(e){
        e.preventDefault();
        var significado = lineamientos.formatInput($('#significado').code());
        var contexto = lineamientos.formatInput($('#contexto').code());
        var glosario = lineamientos.formatInput($('#glosario').code());
        var referencias = lineamientos.formatInput($('#referencias').code());
        var documento_contexto = $('#documento_contexto')[0].files[0];
        var documento_glosario = $('#documento_glosario')[0].files[0];
        var documento_referencias = $('#documento_glosario')[0].files[0];
        var tabla_estadistica = $('#tabla_estadistica')[0].files[0];
        var lineamiento = $('#arbol').jstree('get_selected','full')[0];
        
        var fd = new FormData();    
        fd.append( 'significado', significado );
        fd.append( 'contexto', contexto );
        fd.append( 'glosario', glosario );
        fd.append( 'referencias', referencias );
        fd.append( 'documento_contexto', documento_contexto );
        fd.append( 'documento_glosario', documento_glosario );
        fd.append( 'documento_referencias', documento_referencias );
        fd.append( 'tabla_estadistica', tabla_estadistica );
        fd.append( 'tipo_lineamiento', 'rubro' );
        fd.append( 'lineamiento', lineamiento.id );

        var jqxhr = $.ajax({
          url: 'index.php?mod=sievas&controlador=lineamientos&accion=editar_complementarios_lineamiento',
          data: fd,
          processData: false,
          contentType: false,
          type: 'POST',
        });
        
        jqxhr.done(function(){
           $('#arbol').jstree('refresh'); 
        });
        
        jqxhr.fail(function(err){
           bootbox.alert(err.responseText);
        });
    });
    
    $('#guardar-datos-item').on('click', function(e){
        e.preventDefault();
        var indicadores = encodeURIComponent($('#indicadores_item').code());
        var documentos = encodeURIComponent($('#documentos_item').code());
        var lineamiento = $('#arbol').jstree('get_selected','full')[0];
        
        var jqxhr = $.post('index.php?mod=sievas&controlador=lineamientos&accion=editar_complementarios_lineamiento',{
            indicadores : indicadores,
            documentos : documentos,
            tipo_lineamiento : 'item',
            lineamiento : lineamiento.id
        });
        
        jqxhr.done(function(){
            $('#arbol').jstree('refresh'); 
        });
        
        jqxhr.fail(function(err){
           bootbox.alert(err.responseText);
        });
    });
}

lineamientos.formatInput = function(sInput){
    // 1. remove line breaks / Mso classes
      var stringStripper = /(\n|\r| class=(")?Mso[a-zA-Z]+(")?)/g;
      var output = sInput.replace(stringStripper, ' ');

      // 2. strip Word generated HTML comments
      var commentSripper = new RegExp('<!--(.*?)-->', 'g');
      output = output.replace(commentSripper, '');

      // 3. remove tags leave content if any
      var tagStripper = new RegExp('<(/)*(meta|link|span|\\?xml:|st1:|o:|font)(.*?)>', 'gi');
      output = output.replace(tagStripper, '');

      // 4. Remove everything in between and including tags '<style(.)style(.)>'
      var badTags = ['style', 'script', 'applet', 'embed', 'noframes', 'noscript'];

      for (var i = 0; i < badTags.length; i++) {
        tagStripper = new RegExp('<' + badTags[i] + '.*?' + badTags[i] + '(.*?)>', 'gi');
        output = output.replace(tagStripper, '');
      }

      // 5. remove attributes ' style="..."'
      var badAttributes = ['style', 'start'];
      for (var ii = 0; ii < badAttributes.length; ii++) {
        var attributeStripper = new RegExp(' ' + badAttributes[i] + '="(.*?)"', 'gi');
        output = output.replace(attributeStripper, '');
      }

      return output;
}


lineamientos.editarlineamiento = function(e){
    e.preventDefault();
    var id = $(this).data('id');
    var nom_lineamiento = $('#lineamiento').val();
    var lineamiento_id = $('#arbol').jstree('get_selected','full')[0];
    
    var jqxhr = $.post('index.php?mod=sievas&controlador=lineamientos&accion=editar_lineamiento', {
        id : id,
        nom_lineamiento : nom_lineamiento,
        padre : lineamiento_id.parent,
        conjunto : $('#conjunto').val()
    });
    
    jqxhr.done(function(){
        $('#arbol').jstree('refresh');
    });
    
    jqxhr.fail(function(err){
       bootbox.alert(err.responseText); 
    });
}

