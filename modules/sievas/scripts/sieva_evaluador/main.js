var sieva_evaluador = {};

sieva_evaluador.oTable = $('#data_tabla');

sieva_evaluador.initlistar = function(){
    sieva_evaluador.oTable.dataTable({
            "oLanguage":{"sUrl": "public/js/dataTables.spanish.txt"},
            "bServerSide": true,
            "sAjaxSource": "index.php?mod=sievas&controlador=usuarios&accion=get_dt_evaluadores",
            "bProcessing": true,            
            "fnServerParams": function(aoData){
                var fkeys = [];
                fkeys.push(
                    {nombre : 'gen_persona',fkey : 'cod_persona'}
                );
                
                aoData.push({"name": "sIndexColumn", "value": "id"});
                aoData.push({"name": "sTable", "value": "evaluador"});
                aoData.push({"name": "fKeys", "value": JSON.stringify(fkeys)});
            },
            "aoColumns":[
                {"mData": 0,"sName":"gen_persona.nombres"
                    },
                {"mData": 1,"sName":"gen_persona.email",
                   },              
                {
                    "mData":2,
                    "sName":"id",
                    "mRender":function(id){
                        return '<a href="index.php?mod=sievas&controlador=usuarios&accion=editar_evaluador&id='+id+'"><i class="glyphicon glyphicon-edit"></i></a><a href="#" class="eliminar-generico" data-id="'+id+'"><i class="glyphicon glyphicon-trash"></i></a>';
                    },
                    "bSortable" : false,
                    
                }
            ]    
    });
    $(document).on('click', '.eliminar-generico', sieva_evaluador.eliminarevaluador);
}

sieva_evaluador.eliminarevaluador = function(){
    var self = this;
    var dialog = bootbox.dialog({
        message: "¿Esta seguro que desea eliminar el evaluador?",
        title: 'Eliminar evaluador',
        buttons: {
            success: {
              label: "Si",
              className: "btn-success",
              callback: function() {
                var id = $(self).data('id');
                var jqxhr = $.post('index.php?mod=sievas&controlador=usuarios&accion=eliminar_evaluador', {
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
