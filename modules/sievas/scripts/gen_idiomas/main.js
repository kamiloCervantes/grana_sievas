var gen_idiomas = {};

gen_idiomas.initlistar = function(){
    $('#data_tabla').dataTable({
            "oLanguage":{"sUrl": "public/js/dataTables.spanish.txt"},
            "bServerSide": true,
            "sAjaxSource": "index.php?mod=sievas&controlador=gen_idiomas&accion=get_dt_idiomas",
            "bProcessing": true,            
            "fnServerParams": function(aoData){
                var fkeys = [];
               
                
                aoData.push({"name": "sIndexColumn", "value": "id"});
                aoData.push({"name": "sTable", "value": "gen_idiomas"});
            },
            "aoColumns":[
                {"mData": 0,"sName":"gen_idiomas.nombre"
                    },               
                {
                    "mData":1,
                    "sName":"id",
                    "mRender":function(id){
                        return '<a href="index.php?mod=sievas&controlador=gen_idiomas&accion=editar&id='+id+'" class="editar-generico" data-id="'+id+'"><i class="glyphicon glyphicon-edit"></i></a><a href="#" class="eliminar-generico" data-id="'+id+'"><i class="glyphicon glyphicon-trash"></i></a>';
                    },
                    "bSortable" : false,
                    
                }
            ]    
    });
    
    $(document).on('click', '.eliminar-generico', gen_idiomas.eliminaridioma);
}

gen_idiomas.initagregar = function(){
    $('#guardar-idioma').on('click', function(e){
        e.preventDefault();
        var jqxhr = $.post('index.php?mod=sievas&controlador=gen_idiomas&accion=guardar_idioma', {
            idioma : $('#idioma').val()
        });
        
        jqxhr.done(function(data){
            bootbox.alert(data.mensaje, function(){
                window.location = 'index.php?mod=sievas&controlador=gen_idiomas';
            });
        });
        
        jqxhr.fail(function(err){
            bootbox.alert(err.responseText);
        });
    });
}


gen_idiomas.initeditar = function(){
    $('#guardar-idioma').on('click', function(e){
        e.preventDefault();
        var jqxhr = $.post('index.php?mod=sievas&controlador=gen_idiomas&accion=guardar_idioma', {
            idioma : $('#idioma').val(),
            id : $('#idioma_id').val()
        });
        
        jqxhr.done(function(data){
            bootbox.alert(data.mensaje, function(){
                window.location = 'index.php?mod=sievas&controlador=gen_idiomas';
            });
        });
        
        jqxhr.fail(function(err){
            bootbox.alert(err.responseText);
        });
    });
}

gen_idiomas.eliminaridioma = function(){
    var self = this;
    var dialog = bootbox.dialog({
        message: "¿Esta seguro que desea eliminar el idioma?",
        title: 'Eliminar idioma',
        buttons: {
            success: {
              label: "Si",
              className: "btn-success",
              callback: function() {
                var id = $(self).data('id');
                var jqxhr = $.post('index.php?mod=sievas&controlador=gen_idiomas&accion=eliminar_idioma', {
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
