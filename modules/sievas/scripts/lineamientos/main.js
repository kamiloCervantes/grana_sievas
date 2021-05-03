var sieva_evaluar = {};



sieva_evaluar.init = function(){

    $('#arbol').jstree({
            "core" : {

              "animation" : 0,

              "multiple": false,

              "check_callback" : true,

              "themes" : { "stripes" : true },

              'data' : {

                    'url' : 'index.php?mod=sievas&controlador=evaluar&accion=get_arbol',

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

    

    $('.etiquetas').textext({ plugins: 'tags' }); 
    
    $('#guardar-item').on('click', sieva_evaluar.guardaritem);
    sieva_evaluar.cargardatositem();
}


sieva_evaluar.cargardatositem = function(){

    $('#arbol').on('select_node.jstree', function(e,data){

        var parent = parseInt(data.node.parent);
        var id = data.node.id;
        var text = data.node.text;
        
        $('#lineamiento').val(text.substring(text.lastIndexOf('.')+2));
        $('#guardar-item').data('id', id);

    });

}



sieva_evaluar.guardaritem = function(e){
    e.preventDefault();
    var lineamiento = $('#lineamiento').val();
    var id = $(this).data('id');
    var jqxhr = $.post('index.php?mod=sievas&controlador=lineamientos&accion=guardar_lineamiento', {
        lineamiento : lineamiento,
        id : id
    });
    
    jqxhr.done(function(data){
        bootbox.alert("Los datos han sido guardados exitosamente");
         var selected = $('#arbol').jstree('get_selected')[0]; 
         var node = $('#arbol').jstree('get_node', selected); 
         $('#arbol').jstree('refresh');
         $('#arbol').trigger('select_node.jstree', { node : node, selected : selected });                 
    });

    jqxhr.fail(function(err){ 
        bootbox.alert(err.responseText);         
    });
  

    

}









