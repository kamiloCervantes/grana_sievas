var asociar_lineamientos = {};
var socket = io('http://sievas.herokuapp.com');

asociar_lineamientos.items = [];
asociar_lineamientos.plan_mejoramiento = [];

asociar_lineamientos.init = function(){
    asociar_lineamientos.cargar_asociaciones();
    //asociar items
    
    //eliminar todas las asociaciones de un conjunto de lineamientos
    $('.eliminar_asociaciones').on('click', function(){
        var conjunto1 = $('#conjunto_lineamientos').val();
        var conjunto2 = $('#conjunto_lineamientos2').val();
        if(conjunto1 != conjunto2){           
        bootbox.dialog({
            message: "¿Está seguro que desea eliminar todas las asociaciones entre los conjuntos 1 y 2?",
            title: "Eliminar asociaciones",
            buttons: {
              success: {
                label: "Si",
                className: "btn-success",
                callback: function() {                    
                    var jqxhr = $.post('index.php?mod=sievas&controlador=lineamientos&accion=eliminar_asociacion_lineamientos', {
                        conjunto1: conjunto1,
                        conjunto2: conjunto2,
                        action: 'remove_all'
                    });
                    
                    jqxhr.done(function(){
                        asociar_lineamientos.cargar_asociaciones();
                    });
                    
                }
              },
              cancel: {
                label: "No",
                className: "btn-danger",
                callback: function() {


                }
              }

            }
          });
      } else{
          bootbox.alert("Los conjuntos 1 y 2 deben ser diferentes");
      }
    });
    
    
    //eliminar una asociacion de un conjunto de lineamientos
    $(document).on('click', '.eliminar_asociacion_lineamiento', function(e){
        e.preventDefault();
        var asociaciones = [];
        asociaciones.push($(this).data('id'));
        var self = this;
        bootbox.dialog({
            message: "¿Está seguro que desea eliminar la asociación?",
            title: "Eliminar asociación",
            buttons: {
              success: {
                label: "Si",
                className: "btn-success",
                callback: function() {                    
                    var jqxhr = $.post('index.php?mod=sievas&controlador=lineamientos&accion=eliminar_asociacion_lineamientos', {
                        asociaciones: asociaciones,
                        action: 'remove_el'
                    });
                    
                    jqxhr.done(function(){
                        $(self).parent().parent().remove();
                    });
                    
                }
              },
              cancel: {
                label: "No",
                className: "btn-danger",
                callback: function() {


                }
              }

            }
          });
        
        
         
    });
    
    
    $('#agregar_asoc').on('click', function(e){
        e.preventDefault();
        var arbol_left = $('#arbol').jstree('get_selected')[0];
        var arbol_right = $('#arbol2').jstree('get_selected');
        var conjunto1 = $('#conjunto_lineamientos').val();
        var conjunto2 = $('#conjunto_lineamientos2').val();


        
        var jqxhr = $.post('index.php?mod=sievas&controlador=lineamientos&accion=guardar_asociacion_lineamientos', {
            left: arbol_left,
            right: arbol_right,
            conjunto1: conjunto1,
            conjunto2: conjunto2
        });
        
        jqxhr.done(function(){
            asociar_lineamientos.cargar_asociaciones();
        });
    });
    
    //arboles selectores
    $('#arbol').jstree({
            "core" : {
              "animation" : 0,
              "multiple": false,
              "check_callback" : true,
              "themes" : { "stripes" : true },
              'data' : {
                    'url' : 'index.php?mod=sievas&controlador=lineamientos&accion=cargar_arbol_evaluacion',
                    'data' : function (node) {
	                     return { 
                                 'id' : node.id,
                                 'conjunto' : $('#conjunto_lineamientos').val()
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
              },

            "plugins" : [
              "dnd", "search",
              "state", "types", "wholerow"
            ]
     });
    $('#arbol2').jstree({
            "core" : {
              "animation" : 0,
              "multiple": true,
              "check_callback" : true,
              "themes" : { "stripes" : true },
              'data' : {
                    'url' : 'index.php?mod=sievas&controlador=lineamientos&accion=cargar_arbol_evaluacion',
                    'data' : function (node) {
	                      return { 
                                 'id' : node.id,
                                 'conjunto' : $('#conjunto_lineamientos2').val()
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
              },

            "plugins" : [
              "dnd", "search",
              "state", "types", "wholerow"
            ]
     });
	      
     //selector de conjuntos de lineamientos
     $('#conjunto_lineamientos').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=evaluaciones&accion=get_lineamientos_conjuntos',
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
                var url = "index.php?mod=sievas&controlador=evaluaciones&accion=get_lineamientos_conjunto&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nom_conjunto;
        },
        formatSelection: function(d){
            return d.nom_conjunto;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    $('#conjunto_lineamientos').on('change', function(){
        $('#arbol').jstree('refresh');
        asociar_lineamientos.cargar_asociaciones();
    });
     $('#conjunto_lineamientos2').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=evaluaciones&accion=get_lineamientos_conjuntos',
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
                var url = "index.php?mod=sievas&controlador=evaluaciones&accion=get_lineamientos_conjunto&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nom_conjunto;
        },
        formatSelection: function(d){
            return d.nom_conjunto;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    $('#conjunto_lineamientos2').on('change', function(){
        $('#arbol2').jstree('refresh');
        asociar_lineamientos.cargar_asociaciones();
    });
   
}

asociar_lineamientos.cargar_asociaciones = function(){
    var conjunto1 = $('#conjunto_lineamientos').val();
    var conjunto2 = $('#conjunto_lineamientos2').val();
    var jqxhr = $.get('index.php?mod=sievas&controlador=lineamientos&accion=cargar_asociaciones', {
        conjunto1 : conjunto1,
        conjunto2 : conjunto2
    });
    
    jqxhr.done(function(data){
        var html = '';
        $.each(data, function(index, value){
            html += '<tr>'
            html += '<td>' + value.p1 + value.o1 + ' '+ value.nom1 + '</td>';
            html += '<td>' + value.p2 + value.o2 + ' '+ value.nom2 + '</td>';
            html += '<td><a href="#" data-id="'+ value.id +'" class="eliminar_asociacion_lineamiento"><i class="glyphicon glyphicon-trash"></i></a></td>';
            html += '</tr>';
        });
        $('#asociaciones tbody').html(html);
    });
    
     
}




$(asociar_lineamientos.init);

